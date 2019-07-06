<?php


namespace Xigen\BackInStock\Model;

use Xigen\BackInStock\Model\ResourceModel\Interest\CollectionFactory as InterestCollectionFactory;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Xigen\BackInStock\Api\Data\InterestInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Xigen\BackInStock\Api\InterestRepositoryInterface;
use Magento\Framework\Reflection\DataObjectProcessor;
use Xigen\BackInStock\Model\ResourceModel\Interest as ResourceInterest;
use Magento\Framework\Api\DataObjectHelper;
use Xigen\BackInStock\Api\Data\InterestSearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;

/**
 * InterestRepository class
 */
class InterestRepository implements InterestRepositoryInterface
{
    /**
     * @var ExtensibleDataObjectConverter
     */
    protected $extensibleDataObjectConverter;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var InterestSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var InterestCollectionFactory
     */
    protected $interestCollectionFactory;

    /**
     * @var JoinProcessorInterface
     */
    protected $extensionAttributesJoinProcessor;

    /**
     * @var InterestFactory
     */
    protected $interestFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var InterestInterfaceFactory
     */
    protected $dataInterestFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var \Xigen\BackInStock\Model\ResourceModel\Interest
     */
    protected $resource;

    /**
     * @param ResourceInterest $resource
     * @param InterestFactory $interestFactory
     * @param InterestInterfaceFactory $dataInterestFactory
     * @param InterestCollectionFactory $interestCollectionFactory
     * @param InterestSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceInterest $resource,
        InterestFactory $interestFactory,
        InterestInterfaceFactory $dataInterestFactory,
        InterestCollectionFactory $interestCollectionFactory,
        InterestSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->interestFactory = $interestFactory;
        $this->interestCollectionFactory = $interestCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataInterestFactory = $dataInterestFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Xigen\BackInStock\Api\Data\InterestInterface $interest
    ) {
        /* if (empty($interest->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $interest->setStoreId($storeId);
        } */
        
        $interestData = $this->extensibleDataObjectConverter->toNestedArray(
            $interest,
            [],
            \Xigen\BackInStock\Api\Data\InterestInterface::class
        );
        
        $interestModel = $this->interestFactory->create()->setData($interestData);
        
        try {
            $this->resource->save($interestModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the interest: %1',
                $exception->getMessage()
            ));
        }
        return $interestModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($interestId)
    {
        $interest = $this->interestFactory->create();
        $this->resource->load($interest, $interestId);
        if (!$interest->getId()) {
            throw new NoSuchEntityException(__('Interest with id "%1" does not exist.', $interestId));
        }
        return $interest->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->interestCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Xigen\BackInStock\Api\Data\InterestInterface::class
        );
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Xigen\BackInStock\Api\Data\InterestInterface $interest
    ) {
        try {
            $interestModel = $this->interestFactory->create();
            $this->resource->load($interestModel, $interest->getInterestId());
            $this->resource->delete($interestModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Interest: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($interestId)
    {
        return $this->delete($this->getById($interestId));
    }
}
