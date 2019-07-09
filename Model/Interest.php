<?php


namespace Xigen\BackInStock\Model;

use Xigen\BackInStock\Api\Data\InterestInterface;
use Magento\Framework\Api\DataObjectHelper;
use Xigen\BackInStock\Api\Data\InterestInterfaceFactory;

/**
 * Interest class
 */
class Interest extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var InterestInterfaceFactory
     */
    protected $interestDataFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $dateTime;

    /**
     * @var \Xigen\BackInStock\Helper\Data
     */
    protected $helper;

    /**
     * @var string
     */
    protected $_eventPrefix = 'xigen_backinstock_interest';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param InterestInterfaceFactory $interestDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Xigen\BackInStock\Model\ResourceModel\Interest $resource
     * @param \Xigen\BackInStock\Model\ResourceModel\Interest\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        InterestInterfaceFactory $interestDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Xigen\BackInStock\Model\ResourceModel\Interest $resource,
        \Xigen\BackInStock\Model\ResourceModel\Interest\Collection $resourceCollection,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Xigen\BackInStock\Helper\Data $helper,
        array $data = []
    ) {
        $this->interestDataFactory = $interestDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dateTime = $dateTime;
        $this->helper = $helper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Before save
     */
    public function beforeSave()
    {
        $this->setUpdatedAt($this->dateTime->gmtDate());
        if ($this->isObjectNew()) {
            $this->setCreatedAt($this->dateTime->gmtDate());
        }

        $product = $this->helper->getProductById($this->getData('product_id'));
        if ($product && $product->getId()) {
            $this->setData('product_name', $product->getName());
        } else {
            $this->setData('product_name', 'Product not found');
        }

        return parent::beforeSave();
    }

    /**
     * Retrieve interest model with interest data
     * @return InterestInterface
     */
    public function getDataModel()
    {
        $interestData = $this->getData();
        
        $interestDataObject = $this->interestDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $interestDataObject,
            $interestData,
            InterestInterface::class
        );
        
        return $interestDataObject;
    }
}
