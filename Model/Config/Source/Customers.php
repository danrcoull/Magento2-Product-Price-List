<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Model\Config\Source;

use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Serialize\SerializerInterface;

/**
 * Class Customers
 * @package SuttonSilver\PriceLists\Model\Config\Source
 */
class Customers implements \Magento\Framework\Data\OptionSourceInterface
{
    private $_customerCollection;

    /**
     * @var array
     */
    protected $customerTree;
    protected $serializer;
    protected $cache;
    protected $dataObjectFactory;

    /**
     * Customers constructor.
     * @param CollectionFactory $customerCollection
     * @param DataObjectFactory $dataObjectFactory
     * @param CacheInterface $cache
     * @param SerializerInterface|null $serializer
     */
    public function __construct(
        CollectionFactory $customerCollection,
        DataObjectFactory $dataObjectFactory,
        CacheInterface $cache,
        SerializerInterface $serializer
    ) {
        $this->_customerCollection = $customerCollection;
        $this->dataObjectFactory = $dataObjectFactory;
        $this->cache = $cache;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->getCustomerTree();
    }

    /**
     * Retrieve categories tree
     *
     * @return array
     */
    protected function getCustomerTree()
    {
        $data = $this->cache->load('customer_collection');
        if ($data == null) {
            $collection = $this->_customerCollection->create();
            $collection->addNameToSelect();
            $collection->setPageSize(50)
                ->setCurPage(1);
            foreach ($collection as $customer) {
                $customerId = $customer->getId();
                if (!isset($this->customerTree[$customerId])) {
                    $this->customerTree[$customerId] = [
                        'value' => $customerId,
                        'label' => $customer->getName(),
                        'is_active' => $customer->getStatus(),
                        'path' => $customerId,
                        'optgroup' => false
                    ];
                }
            }


            $data = $this->serializer->serialize($this->customerTree);
            $this->cache->save($data, 'customer_collection', [], 3600);
            $data = $this->cache->load('customer_collection');
        }

        return $this->serializer->unserialize($data);
    }
}
