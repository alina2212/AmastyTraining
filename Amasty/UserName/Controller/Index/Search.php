<?php

namespace Amasty\UserName\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class Search extends \Magento\Framework\App\Action\Action
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    public function __construct(
        CollectionFactory $collectionFactory,
        JsonFactory $resultJsonFactory,
        Context $context
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        return parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $sku = $this->getRequest()->getParam('searchValue');
        $productCollection = $this->collectionFactory->create();
        $productCollection->addFieldToFilter('sku', ['like' => "%$sku%"])->setPageSize(5);
        $data = [];

        foreach ($productCollection->getItems() as $item) {
            $data[] = ['sku' => $item->getSku(), 'name' => $item->getName()];
        }

        $result->setData($data);

        return $result;
    }
}
