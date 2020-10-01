<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Api\Data;

interface PriceListProductsInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const PRICE_LIST_ID = 'price_list_id';
    const PRICELISTPRODUCTS_ID = 'pricelistproducts_id';
    const PRICE_LIST_PRODUCT_ID = 'price_list_product_id';
    const PRICE_LIST_PRODUCT_PRICE = 'price_list_product_price';
    const PRICE_LIST_PRODUCT_RULE_TYPE= 'price_list_product_rule_type';

    /**
     * Get pricelistproducts_id
     * @return string|null
     */
    public function getPricelistproductsId();

    /**
     * Set pricelistproducts_id
     * @param string $pricelistproductsId
     * @return \SuttonSilver\PriceLists\Api\Data\PriceListProductsInterface
     */
    public function setPricelistproductsId($pricelistproductsId);

    /**
     * Get price_list_product_price
     * @return string|null
     */
    public function getPriceListProductPrice();

    /**
     * Set price_list_product_price
     * @param string $priceListProductPrice
     * @return \SuttonSilver\PriceLists\Api\Data\PriceListProductsInterface
     */
    public function setPriceListProductPrice($priceListProductPrice);

    /**
     * Get price_list_id
     * @return string|null
     */
    public function getPriceListId();

    /**
     * Set price_list_id
     * @param string $priceListId
     * @return \SuttonSilver\PriceLists\Api\Data\PriceListProductsInterface
     */
    public function setPriceListId($priceListId);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \SuttonSilver\PriceLists\Api\Data\PriceListProductsExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \SuttonSilver\PriceLists\Api\Data\PriceListProductsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \SuttonSilver\PriceLists\Api\Data\PriceListProductsExtensionInterface $extensionAttributes
    );

    /**
     * Get price_list_product_id
     * @return string|null
     */
    public function getPriceListProductId();

    /**
     * Set price_list_product_id
     * @param string $priceListProductId
     * @return \SuttonSilver\PriceLists\Api\Data\PriceListProductsInterface
     */
    public function setPriceListProductId($priceListProductId);

    /**
     * Get price_list_product_rule_type
     * @return string|null
     */
    public function getPriceListProductRuleType();

    /**
     * Set price_list_product_rule_type
     * @param string $priceListProductId
     * @return \SuttonSilver\PriceLists\Api\Data\PriceListProductsInterface
     */
    public function setPriceListProductRuleType($priceListProductRuleType);
}
