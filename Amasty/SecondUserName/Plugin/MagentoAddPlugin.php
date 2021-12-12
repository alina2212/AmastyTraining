<?php

namespace Amasty\SecondUserName\Plugin;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Event\ManagerInterface as EventManager;
use \Amasty\UserName\Model\FooFactory;
Use Amasty\UserName\Model\ResourceModel\Foo as FooResourceModel;

class MagentoAddPlugin
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * @var FooFactory
     */
    private $fooFactory;

    /**
     * @var FooResourceModel
     */
    private $fooResourceModel;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        EventManager $eventManager,
        FooFactory $fooFactory,
        FooResourceModel $fooResourceModel
    ) {
        $this->productRepository = $productRepository;
        $this->eventManager = $eventManager;
        $this->fooFactory = $fooFactory;
        $this->fooResourceModel = $fooResourceModel;
    }

    public function beforeExecute($subject)
    {
        $sku = $subject->getRequest()->getParam('sku');
        $product = $this->productRepository->get($sku);
        $subject->getRequest()->setParam('product', $product->getId());
    }

    public function afterExecute($subject, $result)
    {
        if ($subject->getRequest()->getParam('sku') != null ) {
            $productId = $subject->getRequest()->getParam('product');
            $sku = $subject->getRequest()->getParam('sku');
            $qty = $subject->getRequest()->getParam('qty');
            $this->eventManager->dispatch('amasty_username', ['sku' => $sku]);

            $foo = $this->fooFactory->create();
            $qtyItems = $foo->getCollection()->addFieldToFilter('sku', ['eq' => $sku])->getItems();

            if ($qtyItems == 0) {
                $foo->setData('sku', $sku);
                $foo->setData('qty', $qty);
                $foo->save();
            } else {
                $items = $foo->getCollection()->addFieldToFilter('sku', ['eq' => $sku])->getItems();
                $itemQty = 0;

                foreach ($items as $item) {
                    $itemQty += $item->getQty();
                }

                if ($qty > $itemQty) {
                    $foo->setData('sku', $sku);
                    $foo->setData('qty', $qty);
                    $foo->save();
                } else {
                    $foo->setData('sku', $sku);
                    $foo->setData('qty', $qty + $itemQty);
                    $foo->save();
                }
            }
        }

        return $result;
    }
}
