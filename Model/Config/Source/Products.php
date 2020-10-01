<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Model\Config\Source;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Serialize\SerializerInterface;

/**
 * Class Products
 * @package SuttonSilver\PriceLists\Model\Config\Source
 */
class Products implements \Magento\Framework\Data\OptionSourceInterface
{
    private $_productCollection;
    protected $serializer;
    protected $cache;
    protected $dataObjectFactory;

    public function __construct(
        CollectionFactory $productCollection,
        DataObjectFactory $dataObjectFactory,
        CacheInterface $cache,
        SerializerInterface $serializer
    ) {
        $this->_productCollection = $productCollection;
        $this->dataObjectFactory = $dataObjectFactory;
        $this->cache = $cache;
        $this->serializer = $serializer;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array
     */
    public function toOptionArray()
    {
        $data = $this->cache->load('product_options');

        if ($data == null) {
            $options = [];
            $collection = $this->_productCollection->create()->addAttributeToSelect('*');
            $collection->setPageSize(50)
                ->setCurPage(1);

            foreach ($collection as $product) {
                $options[$product->getId()] = [
                    'value' => $product->getId(),
                    'label' => $product->getName(),
                    'is_active' => $product->getStatus(),
                    'path' => $product->getSku(),
                    'optgroup' => false
                ];
            }

            $data = $this->serializer->serialize($options);
            $this->cache->save($data, 'product_options', [], 3600);
            $data = $this->cache->load('product_options');
        }

        return $this->serializer->unserialize($data);
    }
}
