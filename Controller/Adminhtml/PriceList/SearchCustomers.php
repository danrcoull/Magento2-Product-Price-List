<?php
/**
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

namespace SuttonSilver\PriceLists\Controller\Adminhtml\PriceList;

use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\LocalizedException;

class SearchCustomers extends \Magento\Backend\App\Action
{

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    private $customerCollection;
    private $jsonResultFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $jsonResultFactory,
        CollectionFactory $customerCollection
    ) {
        $this->jsonResultFactory = $jsonResultFactory;
        $this->customerCollection = $customerCollection;
        parent::__construct($context);
    }

    public function execute()
    {

        $searchKey = $this->getRequest()->getParam('searchKey');
        $pageNum = (int)$this->getRequest()->getParam('page');
        $limit = (int)$this->getRequest()->getParam('limit');

        $collection = $this->customerCollection->create()->addNameToSelect();
        if($searchKey != "") {
            try {
                $collection->addAttributeToFilter('firstname', ['like' => $searchKey . '%']);
            } catch (LocalizedException $e) {
            }
        }
        $collection->setPageSize($limit)
            ->setCurPage($pageNum);

        $collection->getSelect()->group('entity_id');
        $totalValues = $collection->getSize();
        $customerById = [];
        /** @var  CustomerInterface $product */
        foreach ($collection as $customer) {
            $customerId = $customer->getId();
            $customerById[$customerId] = [
                'value' => $customerId,
                'label' => $customer->getName(),
                'is_active' => true,
                'path' => $customerId,
                'optgroup' => false
            ];
        }
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonResultFactory->create();
        return $resultJson->setData([
            'options' => $customerById,
            'total' => empty($customerById) ? 0 : $totalValues
        ]);

    }
}
