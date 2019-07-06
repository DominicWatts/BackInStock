<?php


namespace Xigen\BackInStock\Model\ResourceModel;

/**
 * Interest class
 */
class Interest extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     * @return void
     */
    protected function _construct()
    {
        $this->_init('xigen_backinstock_interest', 'interest_id');
    }
}
