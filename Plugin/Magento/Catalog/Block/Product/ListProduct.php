<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Plugin\Magento\Catalog\Block\Product;

use SuttonSilver\PriceLists\Model\PriceListData;

class ListProduct
{

    /**
     * @var PriceListData
     */
    protected $priceListData;
    /**
     * @var \Magento\Catalog\Model\Layer\Resolver
     */
    private $layerResolver;
    /**
     * @var \Magento\Catalog\Model\Layer\Resolver
     */
    private $layerResolver;
    /**
     * @var \Magento\Customer\Model\SessionFactory
     */
    private $customerSession;

    public function __construct(PriceListData $priceListData)
    {
        $this->priceListData = $priceListData;
    }

    /**
     * @param $subject
     * @param $result
     * @return mixed
     */
    public function afterGetProductCollection(
        $subject,
        $result
    ) {
        if ($this->priceListData->getGeneralConfig('restrict_product_lists') &&
            $this->priceListData->getGeneralConfig('enable')
        ) {
            $productIds = $this->priceListData->getCustomerProductIds();
            $ids = $result->getAllIds();

            $result->addFieldToFilter('entity_id', ['in' => array_intersect($productIds, $ids)]);
        }
        return $result;
    }

    public function getCurrentCategory()
    {
        return $this->layerResolver->get()->getCurrentCategory();
    }
}
