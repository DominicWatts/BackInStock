<?php


namespace Xigen\BackInStock\Controller\Adminhtml\Interest;

use Magento\Framework\Exception\LocalizedException;

/**
 * Save class
 */
class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var \Xigen\BackInStock\Model\InterestFactory
     */
    protected $interestFactory;

    /**
     * Save constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param \Xigen\BackInStock\Model\InterestFactory $interestFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Xigen\BackInStock\Model\InterestFactory $interestFactory
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->interestFactory = $interestFactory;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('interest_id');
        
            $model = $this->interestFactory
                ->create()
                ->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Interest no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
        
            $model->setData($data);
        
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Interest.'));
                $this->dataPersistor->clear('xigen_backinstock_interest');
        
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['interest_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Interest.'));
            }
        
            $this->dataPersistor->set('xigen_backinstock_interest', $data);
            return $resultRedirect->setPath('*/*/edit', ['interest_id' => $id]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
