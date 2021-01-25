<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Plugin\Magento\Catalog\Api;

use Magento\Catalog\Api\Data\ProductExtensionFactory;
use Magento\Catalog\Api\Data\ProductExtensionInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\Data\ProductSearchResultsInterface;
use SuttonSilver\PriceLists\Model\PriceListData;

class ProductRepositoryInterface
{
    /**
     * @var PriceListData
     */
    protected $priceListData;

    /**
     * Order Extension Attributes Factory
     *
     * @var ProductExtensionFactory
     */
    protected $extensionFactory;

    public function __construct(PriceListData $priceListData, ProductExtensionFactory $extensionFactory)
    {
        $this->priceListData = $priceListData;
        $this->extensionFactory = $extensionFactory;
    }

    public function afterGetById(
        \Magento\Catalog\Api\ProductRepositoryInterface $subject,
        $result
    ) {
        return $this->afterGet($subject, $result);
    }

    public function afterGet(
        \Magento\Catalog\Api\ProductRepositoryInterface $subject,
        $result
    ) {
        if (!$this->priceListData->getGeneralConfig('enable')) {
            return $result;
        }

        $price = $this->priceListData->getProductPrice($result->getId(), $result->getPrice(), $appliedPriceList);

        if (!$appliedPriceList) {
            return $result;
        }

        $extension = $this->getExtensionAttributes($result);
        $extension->setCustomPrice($price);
        $extension->setOriginalPrice($result->getPrice());

        $result->setExtensionAttributes($extension);

        return $result;
    }

    /**
     * Get a ProductExtensionInterface object, creating it if it is not yet created
     *
     * @param ProductInterface $customer
     * @return ProductExtensionInterface|null
     */
    private
    function getExtensionAttributes(
        ProductInterface $customer
    ) {
        $extensionAttributes = $customer->getExtensionAttributes();
        if (!$extensionAttributes) {
            $extensionAttributes = $this->extensionFactory->create();
            $customer->setExtensionAttributes($extensionAttributes);
        }

        return $extensionAttributes;
    }
}
