<?php
namespace Xigen\BackInStock\Controller\Adminhtml\Interest;

/**
 * MassNotify Controller.
 */
class MassNotify extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Xigen_BackInStock::top_level';

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    private $filter;

    /**
     * @var \Xigen\BackInStock\Model\ResourceModel\Interest\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \Xigen\BackInStock\Model\InterestFactory
     */
    private $interestFactory;

    /**
     * MassStatus constructor
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Xigen\BackInStock\Model\ResourceModel\Interest\CollectionFactory $collectionFactory
     * @param \Xigen\BackInStock\Model\InterestFactory $interestFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Xigen\BackInStock\Model\ResourceModel\Interest\CollectionFactory $collectionFactory,
        \Xigen\BackInStock\Model\InterestFactory $interestFactory
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->interestFactory = $interestFactory;
        parent::__construct($context);
    }
    /**
     * Execute action.
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        $ids = $this->getRequest()->getPost('selected');
        $status = $this->getRequest()->getParam('status');
        if ($ids) {
            $collection = $this->interestFactory->create()
                ->getCollection()
                ->addFieldToFilter('interest_id', ['in' => $ids]);
            $collectionSize = $collection->getSize();
            $updatedItems = 0;
            foreach ($collection as $item) {
                try {
                    $item->setStatus($status);
                    $item->save();
                    $updatedItems++;
                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                }
            }
            if ($updatedItems != 0) {
                if ($collectionSize != $updatedItems) {
                    $this->messageManager->addErrorMessage(
                        __('Failed to update %1 interest item(s).', $collectionSize - $updatedItems)
                    );
                }
                $this->messageManager->addSuccessMessage(
                    __('A total of %1 interest item(s) have been updated.', $updatedItems)
                );
            }
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
