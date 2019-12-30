<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Plugin\Magento\Catalog\Api;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerExtensionInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use SuttonSilver\PriceLists\Model\PriceListData;

class ProductRepositoryInterface
{

    protected $priceListData;
    public function __construct(PriceListData $priceListData)
    {
        $this->priceListData = $priceListData;
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

        $price = $this->priceListData->getProductPrice($result->getId());

        $extension = $this->getExtensionAttributes($result);
        $extension->setCustomPrice($price);
        $extension->setOriginalPrice($result->getPrice());

        $result->setExtensionAttributes($extension);

        return $result;
    }

    public function afterGetList(
        CustomerRepositoryInterface $subject,
        \Magento\Customer\Api\Data\CustomerSearchResultsInterface $searchCriteria
    ) : \Magento\Customer\Api\Data\CustomerSearchResultsInterface {
        $products = [];
        foreach ($searchCriteria->getItems() as $entity) {
            $extensionAttributes = $this->getExtensionAttributes($entity);
            $price = $this->priceListData($entity->getId());
            if ($price > 0) {
                $extensionAttributes->setCustomPrice($price);
                $extensionAttributes->setOriginalPrice($entity->getPrice());
            }

            $entity->setExtensionAttributes($extensionAttributes);

            $products[] = $entity;
        }
        $searchCriteria->setItems($products);
        return $searchCriteria;
    }


    /**
     * Get a CustomerExtensionInterface object, creating it if it is not yet created
     *
     * @param ProductInterface $customer
     * @return \Magento\Catalog\Api\Data\ProductExtensionInterface|null
     */
    private function getExtensionAttributes(ProductInterface $customer)
    {
        $extensionAttributes = $customer->getExtensionAttributes();
        if (!$extensionAttributes) {
            $extensionAttributes = $this->extensionFactory->create();
            $customer->setExtensionAttributes($extensionAttributes);
        }

        return $extensionAttributes;
    }
}
