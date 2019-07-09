<?php


namespace Xigen\BackInStock\Model\Data;

use Xigen\BackInStock\Api\Data\InterestInterface;

/**
 * Interest class
 */
class Interest extends \Magento\Framework\Api\AbstractExtensibleObject implements InterestInterface
{

    /**
     * Get interest_id
     * @return string|null
     */
    public function getInterestId()
    {
        return $this->_get(self::INTEREST_ID);
    }

    /**
     * Set interest_id
     * @param string $interestId
     * @return \Xigen\BackInStock\Api\Data\InterestInterface
     */
    public function setInterestId($interestId)
    {
        return $this->setData(self::INTEREST_ID, $interestId);
    }

    /**
     * Get product_id
     * @return string|null
     */
    public function getProductId()
    {
        return $this->_get(self::PRODUCT_ID);
    }

    /**
     * Set product_id
     * @param string $productId
     * @return \Xigen\BackInStock\Api\Data\InterestInterface
     */
    public function setProductId($productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Xigen\BackInStock\Api\Data\InterestExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Xigen\BackInStock\Api\Data\InterestExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Xigen\BackInStock\Api\Data\InterestExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get email
     * @return string|null
     */
    public function getEmail()
    {
        return $this->_get(self::EMAIL);
    }

    /**
     * Set email
     * @param string $email
     * @return \Xigen\BackInStock\Api\Data\InterestInterface
     */
    public function setEmail($email)
    {
        return $this->setData(self::EMAIL, $email);
    }

    /**
     * Get name
     * @return string|null
     */
    public function getName()
    {
        return $this->_get(self::NAME);
    }

    /**
     * Set name
     * @param string $name
     * @return \Xigen\BackInStock\Api\Data\InterestInterface
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Get product_name
     * @return string|null
     */
    public function getProductName()
    {
        return $this->_get(self::PRODUCT_NAME);
    }

    /**
     * Set product_name
     * @param string $productName
     * @return \Xigen\BackInStock\Api\Data\InterestInterface
     */
    public function setProductName($productName)
    {
        return $this->setData(self::PRODUCT_NAME, $productName);
    }

    /**
     * Get lastname
     * @return string|null
     */
    public function getLastname()
    {
        return $this->_get(self::LASTNAME);
    }

    /**
     * Set lastname
     * @param string $lastname
     * @return \Xigen\BackInStock\Api\Data\InterestInterface
     */
    public function setLastname($lastname)
    {
        return $this->setData(self::LASTNAME, $lastname);
    }

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt()
    {
        return $this->_get(self::CREATED_AT);
    }

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Xigen\BackInStock\Api\Data\InterestInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get updated_at
     * @return string|null
     */
    public function getUpdatedAt()
    {
        return $this->_get(self::UPDATED_AT);
    }

    /**
     * Set updated_at
     * @param string $updatedAt
     * @return \Xigen\BackInStock\Api\Data\InterestInterface
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * Get has_notified
     * @return string|null
     */
    public function getHasNotified()
    {
        return $this->_get(self::HAS_NOTIFIED);
    }

    /**
     * Set has_notified
     * @param string $hasNotified
     * @return \Xigen\BackInStock\Api\Data\InterestInterface
     */
    public function setHasNotified($hasNotified)
    {
        return $this->setData(self::HAS_NOTIFIED, $hasNotified);
    }
}
