<?php

namespace Xigen\BackInStock\Controller\Index;

/**
 * Submit controller class
 */
class Submit extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Constructor
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Psr\Log\LoggerInterface $logger,
        \Xigen\BackInStock\Model\InterestFactory $interestFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->logger = $logger;
        $this->interestFactory = $interestFactory;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Execute view action
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $request = $this->getRequest();
            
            $name = $request->getPostValue('name');
            $email = $request->getPostValue('email');
            $productId = $request->getPostValue('productId');

            $this->validateInput($name, $email, $productId);

            $parts = explode(" ", $name);
            $lastname = array_pop($parts);
            $firstname = implode(" ", $parts);
            
            $model = $this->interestFactory->create();
            $model->setEmail($email);
            $model->setName($firstname);
            $model->setLastname($lastname);
            $model->setProductId($productId);
            $model->setStoreId($this->getStoreId());
            $model->save();

            return $this->jsonResponse([
                'success' => true,
                'message' => '<strong>' . __("Thank you. We will contact you if the product comes back in stock.") . '</strong>'
            ]);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => '<strong>' . __($e->getMessage()) . '</strong>'
            ]);
        } catch (\Exception $e) {
            $this->logger->critical($e);
            return $this->jsonResponse([
                'success' => false,
                'message' => '<strong>' . __($e->getMessage()) . '</strong>'
            ]);
        }
    }

    /**
     * Create json response
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function jsonResponse($response = '')
    {
        return $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($response)
        );
    }

    /**
     * Validate input
     * @param string $name
     * @param string $email
     * @param int $productId
     * @return void
     */
    public function validateInput($name = null, $email = null, $productId = null)
    {
        $error = false;

        if (!\Zend_Validate::is(trim($name), 'NotEmpty')) {
            $error = true;
        }
        if (!\Zend_Validate::is(trim($email), 'NotEmpty')) {
            $error = true;
        }
        if (!\Zend_Validate::is(trim($email), 'EmailAddress')) {
            $error = true;
        }
        if (!\Zend_Validate::is(trim($productId), 'NotEmpty')) {
            $error = true;
        }

        if (!$name || !$email || !$productId || $error) {
            throw new \Exception(__("Problem with submitted data"));
        }
    }

    /**
     * Get store identifier
     * @return  int
     */
    public function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }
}
