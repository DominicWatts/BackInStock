<?php


namespace Xigen\BackInStock\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * InterestRepositoryInterface interface
 */
interface InterestRepositoryInterface
{

    /**
     * Save Interest
     * @param \Xigen\BackInStock\Api\Data\InterestInterface $interest
     * @return \Xigen\BackInStock\Api\Data\InterestInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Xigen\BackInStock\Api\Data\InterestInterface $interest
    );

    /**
     * Retrieve Interest
     * @param string $interestId
     * @return \Xigen\BackInStock\Api\Data\InterestInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($interestId);

    /**
     * Retrieve Interest matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Xigen\BackInStock\Api\Data\InterestSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Interest
     * @param \Xigen\BackInStock\Api\Data\InterestInterface $interest
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Xigen\BackInStock\Api\Data\InterestInterface $interest
    );

    /**
     * Delete Interest by ID
     * @param string $interestId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($interestId);
}
