<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Model\Data;

use SuttonSilver\PriceLists\Api\Data\PriceListCustomersInterface;

class PriceListCustomers extends \Magento\Framework\Api\AbstractExtensibleObject implements PriceListCustomersInterface
{

    /**
     * Get pricelistcustomers_id
     * @return string|null
     */
    public function getPricelistcustomersId()
    {
        return $this->_get(self::PRICELISTCUSTOMERS_ID);
    }

    /**
     * Set pricelistcustomers_id
     * @param string $pricelistcustomersId
     * @return \SuttonSilver\PriceLists\Api\Data\PriceListCustomersInterface
     */
    public function setPricelistcustomersId($pricelistcustomersId)
    {
        return $this->setData(self::PRICELISTCUSTOMERS_ID, $pricelistcustomersId);
    }

    /**
     * Get price_list_id
     * @return string|null
     */
    public function getPriceListId()
    {
        return $this->_get(self::PRICE_LIST_ID);
    }

    /**
     * Set price_list_id
     * @param string $priceListId
     * @return \SuttonSilver\PriceLists\Api\Data\PriceListCustomersInterface
     */
    public function setPriceListId($priceListId)
    {
        return $this->setData(self::PRICE_LIST_ID, $priceListId);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \SuttonSilver\PriceLists\Api\Data\PriceListCustomersExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \SuttonSilver\PriceLists\Api\Data\PriceListCustomersExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \SuttonSilver\PriceLists\Api\Data\PriceListCustomersExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get price_list_customer_id
     * @return string|null
     */
    public function getPriceListCustomerId()
    {
        return $this->_get(self::PRICE_LIST_CUSTOMER_ID);
    }

    /**
     * Set price_list_customer_id
     * @param string $priceListCustomerId
     * @return \SuttonSilver\PriceLists\Api\Data\PriceListCustomersInterface
     */
    public function setPriceListCustomerId($priceListCustomerId)
    {
        return $this->setData(self::PRICE_LIST_CUSTOMER_ID, $priceListCustomerId);
    }
}
