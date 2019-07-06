<?php


namespace Xigen\BackInStock\Api\Data;

/**
 * InterestSearchResultsInterface interface
 */
interface InterestSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Interest list.
     * @return \Xigen\BackInStock\Api\Data\InterestInterface[]
     */
    public function getItems();

    /**
     * Set product_id list.
     * @param \Xigen\BackInStock\Api\Data\InterestInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
