<?php

namespace SuttonSilver\PriceLists\Model;

use Magento\Customer\Model\SessionFactory as Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Store\Model\ScopeInterface;
use SuttonSilver\PriceLists\Api\Data\PriceListProductsInterface;
use SuttonSilver\PriceLists\Model\ResourceModel\PriceListCustomers\CollectionFactory as PriceCustomersCollection;
use SuttonSilver\PriceLists\Model\ResourceModel\PriceListProducts\CollectionFactory as PriceProductsCollection;

/**
 * Class PriceListData
 * @package SuttonSilver\PriceLists\Model
 */
class PriceListData extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var Session
     */
    protected $session;
    /**
     * @var PriceCustomersCollection
     */
    protected $priceListCustomersCollection;
    /**
     * @var PriceProductsCollection
     */
    protected $priceListProductsCollection;


    const XML_PATH_PRICELISTS = 'price_lists/';

    /**
     * @var ScopeConfigInterface|ScopeInterface
     */
    private $scopeConfig;

    /**
     * PriceListData constructor.
     * @param Context $context
     * @param ScopeInterface $scopeConfig
     * @param Registry $registry
     * @param Session $customerSession
     * @param PriceCustomersCollection $priceListCustomersCollection
     * @param PriceProductsCollection $priceListProductsCollection
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        Registry $registry,
        Session $customerSession,
        PriceCustomersCollection $priceListCustomersCollection,
        PriceProductsCollection $priceListProductsCollection
    ) {
        $this->session = $customerSession;
        $this->priceListCustomersCollection = $priceListCustomersCollection;
        $this->priceListProductsCollection = $priceListProductsCollection;
        $this->scopeConfig = $scopeConfig;

        parent::__construct($context, $registry, null, null);
    }

    /**
     * @return array|null
     */
    public function getCustomerProductIds():? array
    {
        /**
         * @var $productIds array
         */
        $productIds =[];
        $listIds = $this->getLists();
        $products = $this->priceListProductsCollection->create()
            ->addFieldToFilter('price_list_id', ['in'=>$listIds]);
        /**
         * @var PriceListProductsInterface $product
         */
        foreach ($products as $product) {
            $productIds[] = $product->getPriceListProductId();
        }
        /** Make sure the array only holds the product id once */
        return array_unique($productIds);
    }

    /**
     * return if logged in
     * @return bool
     */
    public function isLoggedInId()
    {
        return $this->session->create()->getCustomer() == null ? false : true;
    }

    /**
     * @return array|null
     */
    public function getLists():? array
    {
        $cid = $this->session->create()->getCustomer()->getId();
        $customerOnLists = $this->priceListCustomersCollection->create()
            ->addFieldToFilter('price_list_customer_id', $cid);

        $listIds = [];
        foreach ($customerOnLists as $list) {
            $listIds[] = $list->getPriceListId();
        }

        return $listIds;
    }
    /**
     * @param $productId
     * @return float
     */
    public function getProductPrice($productId):? float
    {
        $listIds = $this->getLists();

        $prices = $this->priceListProductsCollection->create()
            ->addFieldToFilter('price_list_product_id', $productId)
            ->addFieldToFilter('price_list_id', ['in'=>$listIds]);

        $price = 0;
        foreach ($prices as $price) {
            $newPrice = $price->getPriceListProductPrice();
            // Only update the price if the new price is less
            // this to form of using the cheapest for final price.
            if ($newPrice < $price) {
                $price = $newPrice;
            }
        }

        return $price;
    }

    /**
     * @param $field
     * @param null $storeId
     * @return mixed
     */
    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param $code the system config code to loads
     * @param null $storeId the store id if required
     * @return mixed return the config data
     */
    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_PRICELISTS . 'general/' . $code, $storeId);
    }
}
