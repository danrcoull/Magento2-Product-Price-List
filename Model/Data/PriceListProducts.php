<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Model\Data;

use Magento\Framework\Api\AbstractExtensibleObject;
use SuttonSilver\PriceLists\Api\Data\PriceListProductsInterface;

/**
 * Class PriceListProducts
 * @package SuttonSilver\PriceLists\Model\Data
 */
class PriceListProducts extends AbstractExtensibleObject implements PriceListProductsInterface
{

    /**
     * Get pricelistproducts_id
     * @return string|null
     */
    public function getPricelistproductsId()
    {
        return $this->_get(self::PRICELISTPRODUCTS_ID);
    }

    /**
     * Set pricelistproducts_id
     * @param string $pricelistproductsId
     * @return \SuttonSilver\PriceLists\Api\Data\PriceListProductsInterface
     */
    public function setPricelistproductsId($pricelistproductsId)
    {
        return $this->setData(self::PRICELISTPRODUCTS_ID, $pricelistproductsId);
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
     * @return \SuttonSilver\PriceLists\Api\Data\PriceListProductsInterface
     */
    public function setPriceListId($priceListId)
    {
        return $this->setData(self::PRICE_LIST_ID, $priceListId);
    }

    /**
     * Get price_list_product_price
     * @return string|null
     */
    public function getPriceListProductPrice()
    {
        return $this->_get(self::PRICE_LIST_PRODUCT_PRICE);
    }

    /**
     * Set price_list_product_price
     * @param string $priceListProductPrice
     * @return \SuttonSilver\PriceLists\Api\Data\PriceListProductsInterface
     */
    public function setPriceListProductPrice($priceListProductPrice)
    {
        return $this->setData(self::PRICE_LIST_PRODUCT_PRICE, $priceListProductPrice);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \SuttonSilver\PriceLists\Api\Data\PriceListProductsExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \SuttonSilver\PriceLists\Api\Data\PriceListProductsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \SuttonSilver\PriceLists\Api\Data\PriceListProductsExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get price_list_product_id
     * @return string|null
     */
    public function getPriceListProductId()
    {
        return $this->_get(self::PRICE_LIST_PRODUCT_ID);
    }

    /**
     * Set price_list_product_id
     * @param string $priceListProductId
     * @return \SuttonSilver\PriceLists\Api\Data\PriceListProductsInterface
     */
    public function setPriceListProductId($priceListProductId)
    {
        return $this->setData(self::PRICE_LIST_PRODUCT_ID, $priceListProductId);
    }

    /**
     * @inheridoc
     */
    public function getPriceListProductRuleType()
    {
        return $this->_get(self::PRICE_LIST_PRODUCT_RULE_TYPE);
    }

    /**
     * @inheridoc
     */
    public function setPriceListProductRuleType($priceListProductRuleType)
    {
        return $this->setData(self::PRICE_LIST_PRODUCT_RULE_TYPE, $priceListProductRuleType);
    }

}
