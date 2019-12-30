<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Api\Data;

interface PriceListInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const DESCRIPTION = 'description';
    const NAME = 'name';
    const PRICELIST_ID = 'pricelist_id';

    /**
     * Get pricelist_id
     * @return string|null
     */
    public function getPricelistId();

    /**
     * Set pricelist_id
     * @param string $pricelistId
     * @return \SuttonSilver\PriceLists\Api\Data\PriceListInterface
     */
    public function setPricelistId($pricelistId);

    /**
     * Get name
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     * @param string $name
     * @return \SuttonSilver\PriceLists\Api\Data\PriceListInterface
     */
    public function setName($name);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \SuttonSilver\PriceLists\Api\Data\PriceListExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \SuttonSilver\PriceLists\Api\Data\PriceListExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \SuttonSilver\PriceLists\Api\Data\PriceListExtensionInterface $extensionAttributes
    );

    /**
     * Get description
     * @return string|null
     */
    public function getDescription();

    /**
     * Set description
     * @param string $description
     * @return \SuttonSilver\PriceLists\Api\Data\PriceListInterface
     */
    public function setDescription($description);
}
