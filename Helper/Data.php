<?php


namespace Xigen\BackInStock\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Data helper class
 */
class Data extends AbstractHelper
{
    const CUSTOMER_HAS_NOTIFIED = 1;
    const CUSTOMER_NOT_NOTIFIED = 0;
    const CUSTOMER_FAIL_NOTIFIED = 2;

    const XML_PATH_EMAIL_TEMPLATE = 'backinstock/email/template';
    const XML_PATH_EMAIL_IDENTITY = 'backinstock/email/email_identity';
    const XML_PATH_EMAIL_SENDER_NAME = 'trans_email/ident_general/name';
    const XML_PATH_EMAIL_SENDER_EMAIL = 'trans_email/ident_general/email';

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepositoryInterface;

    /**
     * @var \Xigen\BackInStock\Model\InterestFactory
     */
    protected $interestFactory;

    /**
     * @var \Magento\CatalogInventory\Api\StockStateInterface
     */
    protected $stockItem;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepositoryInterface
     * @param \Xigen\BackInStock\Model\InterestFactory $interestFactory
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockItem
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Escaper $escaper
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepositoryInterface,
        \Xigen\BackInStock\Model\InterestFactory $interestFactory,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockItem,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Escaper $escaper
    ) {
        $this->logger = $logger;
        $this->productRepositoryInterface = $productRepositoryInterface;
        $this->interestFactory = $interestFactory;
        $this->stockItem = $stockItem;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->escaper = $escaper;
        parent::__construct($context);
    }

    /**
     * Get product by Id
     * @param int $productId
     * @param bool $editMode
     * @param int $storeId
     * @param bool $forceReload
     * @return \Magento\Catalog\Model\Data\Product
     */
    public function getProductById($productId, $editMode = false, $storeId = null, $forceReload = false)
    {
        try {
            return $this->productRepositoryInterface->getById($productId, $editMode, $storeId, $forceReload);
        } catch (\Exception $e) {
            $this->logger->critical($e);
            return false;
        }
    }

    /**
     * Run notify interests process
     * @return void
     */
    public function notifyInterests()
    {
        $collection = $this->interestFactory
            ->create()
            ->getCollection()
            ->addFieldToFilter('has_notified', ['neq' => self::CUSTOMER_HAS_NOTIFIED]);

        $collection->setPageSize(100);
        $pages = $collection->getLastPageNumber();
        $currentPage = 1;
        do {
            $collection->setCurPage($currentPage);
            $collection->load();
            foreach ($collection as $check) {
                $product = $this->getProductById($check->getProductId());
                $save = false;
                if ($product && $product->getId()) {
                    $stock = $this->stockItem->getStockItem($product->getId());
                    if ($stock && (int) $stock->getQty() > 0) {
                        try {
                            $this->sendTransactionalEmail([
                                'firstname' => $check->getName(),
                                'lastname' => $check->getLastname(),
                                'email' => $check->getEmail(),
                                'productname' => $product->getName(),
                                'productlink' => $product->getProductUrl(),
                                'store' => $check->getStoreId()
                            ]);
                            $check->setHasNotified(self::CUSTOMER_HAS_NOTIFIED);
                            $save = true;
                        } catch (Exception $e) {
                            $this->logger->critical($e);
                            $check->setHasNotified(self::CUSTOMER_FAIL_NOTIFIED);
                            $save = true;
                        }
                    }
                } else {
                    $check->setHasNotified(self::CUSTOMER_FAIL_NOTIFIED);
                    $save = true;
                }

                if ($save) {
                    try {
                        $check->save();
                    } catch (Exception $e) {
                        $this->logger->critical($e);
                    }
                }
            }
            $currentPage++;
            //clear collection and free memory
            $collection->clear();
        } while ($currentPage <= $pages);
    }

    /**
     * Send transactional email
     * @param array $vars
     * @return void
     */
    public function sendTransactionalEmail($vars = [])
    {
        $email = $vars['email'] ?? null;
        $storeId = $vars['store'] ?? 1;

        if (empty($vars) || !$email) {
            return;
        }

        $this->inlineTranslation->suspend();
        try {
            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($vars);

            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

            $this->transportBuilder->setTemplateIdentifier(
                $this->scopeConfig->getValue(
                    self::XML_PATH_EMAIL_TEMPLATE,
                    $storeScope
                )
            )->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $storeId,
                ]
            )->setTemplateVars(
                [
                    'firstname' => $vars['firstname'] ?? null,
                    'lastname' => $vars['lastname'] ?? null,
                    'email' => $email,
                    'productname' => $vars['productname'] ?? null,
                    'productlink' => $vars['productlink'] ?? null
                ]
            )->setFrom(
                [
                    'email' => $this->scopeConfig->getValue(
                        self::XML_PATH_EMAIL_SENDER_EMAIL,
                        $storeScope
                    ),
                    'name' => $this->scopeConfig->getValue(
                        self::XML_PATH_EMAIL_SENDER_NAME,
                        $storeScope
                    ),
                ]
            )->addTo(
                $this->escaper->escapeHtml($vars['email']),
                $this->escaper->escapeHtml($vars['firstname'])
            );

            $transport = $this->transportBuilder->getTransport();
            $transport->sendMessage();

            $this->inlineTranslation->resume();

            return true;
        } catch (Exception $e) {
            $this->logger->critical($e);
        }
        return false;
    }
}
