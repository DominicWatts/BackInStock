<?php


namespace Xigen\BackInStock\Model\ResourceModel\Interest;

/**
 * Collection class
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'interest_id';

    /**
     * Define resource model
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Xigen\BackInStock\Model\Interest::class,
            \Xigen\BackInStock\Model\ResourceModel\Interest::class
        );
    }
}
