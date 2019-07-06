<?php


namespace Xigen\BackInStock\Api\Data;

/**
 * InterestInterface interface
 */
interface InterestInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    const UPDATED_AT = 'updated_at';
    const PRODUCT_ID = 'product_id';
    const EMAIL = 'email';
    const LASTNAME = 'lastname';
    const NAME = 'name';
    const PRODUCT_NAME = 'product_name';
    const INTEREST_ID = 'interest_id';
    const CREATED_AT = 'created_at';
    const HAS_NOTIFIED = 'has_notified';

    /**
     * Get interest_id
     * @return string|null
     */
    public function getInterestId();

    /**
     * Set interest_id
     * @param string $interestId
     * @return \Xigen\BackInStock\Api\Data\InterestInterface
     */
    public function setInterestId($interestId);

    /**
     * Get product_id
     * @return string|null
     */
    public function getProductId();

    /**
     * Set product_id
     * @param string $productId
     * @return \Xigen\BackInStock\Api\Data\InterestInterface
     */
    public function setProductId($productId);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Xigen\BackInStock\Api\Data\InterestExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Xigen\BackInStock\Api\Data\InterestExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Xigen\BackInStock\Api\Data\InterestExtensionInterface $extensionAttributes
    );

    /**
     * Get email
     * @return string|null
     */
    public function getEmail();

    /**
     * Set email
     * @param string $email
     * @return \Xigen\BackInStock\Api\Data\InterestInterface
     */
    public function setEmail($email);

    /**
     * Get name
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     * @param string $name
     * @return \Xigen\BackInStock\Api\Data\InterestInterface
     */
    public function setName($name);

    /**
     * Get product_name
     * @return string|null
     */
    public function getProductName();

    /**
     * Set product_name
     * @param string $productName
     * @return \Xigen\BackInStock\Api\Data\InterestInterface
     */
    public function setProductName($productName);

    /**
     * Get lastname
     * @return string|null
     */
    public function getLastname();

    /**
     * Set lastname
     * @param string $lastname
     * @return \Xigen\BackInStock\Api\Data\InterestInterface
     */
    public function setLastname($lastname);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Xigen\BackInStock\Api\Data\InterestInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get updated_at
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated_at
     * @param string $updatedAt
     * @return \Xigen\BackInStock\Api\Data\InterestInterface
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Get has_notified
     * @return string|null
     */
    public function getHasNotified();

    /**
     * Set has_notified
     * @param string $hasNotified
     * @return \Xigen\BackInStock\Api\Data\InterestInterface
     */
    public function setHasNotified($hasNotified);
}
