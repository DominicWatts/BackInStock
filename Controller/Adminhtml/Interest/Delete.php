<?php


namespace Xigen\BackInStock\Controller\Adminhtml\Interest;

/**
 * Delete class
 */
class Delete extends \Xigen\BackInStock\Controller\Adminhtml\Interest
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
     * Delete constructor.
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
     * Delete action
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('interest_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->interestFactory->create();
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Interest.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['interest_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Interest to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
