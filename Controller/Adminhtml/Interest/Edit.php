<?php


namespace Xigen\BackInStock\Controller\Adminhtml\Interest;

/**
 * Edit class
 */
class Edit extends \Xigen\BackInStock\Controller\Adminhtml\Interest
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Xigen\BackInStock\Model\InterestFactory
     */
    protected $interestFactory;

    /**
     * Edit constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Xigen\BackInStock\Model\InterestFactory $interestFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Xigen\BackInStock\Model\InterestFactory $interestFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->interestFactory = $interestFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('interest_id');
        $model = $this->interestFactory->create();
        
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Product Interest no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('xigen_backinstock_interest', $model);
        
        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Interest') : __('New Interest'),
            $id ? __('Edit Interest') : __('New Interest')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Interests'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Interest %1', $model->getId()) : __('New Interest'));
        return $resultPage;
    }
}
