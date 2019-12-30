<?php

namespace SuttonSilver\PriceLists\Model;

use Magento\Customer\Model\SessionFactory as Session;
use SuttonSilver\PriceLists\Model\ResourceModel\PriceListCustomers\CollectionFactory;

class PriceListData extends \Magento\Framework\Model\AbstractModel
{
    protected $session;
    protected $priceListCustomersCollection;
    protected $priceListProductsCollection;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        Session $customerSession,
        CollectionFactory $priceListCustomersCollection,
        \SuttonSilver\PriceLists\Model\ResourceModel\PriceListProducts\CollectionFactory $priceListProductsCollection
    ) {
        $this->session = $customerSession;
        $this->priceListCustomersCollection = $priceListCustomersCollection;
        $this->priceListProductsCollection = $priceListProductsCollection;
        parent::__construct($context, $registry, null, null);
    }

    public function getProductPrice($productId)
    {
        $cid = $this->session->create()->getCustomer()->getId();

        $customerOnLists = $this->priceListCustomersCollection->create()
            ->addFieldToFilter('price_list_customer_id', $cid);

        $listIds = [];
        foreach ($customerOnLists as $list) {
            $listIds[] = $list->getPriceListId();
        }

        $prices = $this->priceListProductsCollection->create()
            ->addFieldToFilter('price_list_product_id', $productId)
            ->addFieldToFilter('price_list_id', ['in'=>$listIds]);

        $price = 0;
        foreach ($prices as $price) {
            $price = $price->getPriceListProductPrice();
        }

        return $price;
    }
}
