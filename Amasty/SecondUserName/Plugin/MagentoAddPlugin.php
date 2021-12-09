<?php

namespace Amasty\SecondUserName\Plugin;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Event\ManagerInterface as EventManager;

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

    public function __construct(
        ProductRepositoryInterface $productRepository,
        EventManager $eventManager
    ) {
        $this->productRepository = $productRepository;
        $this->eventManager = $eventManager;
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
            $sku = $subject->getRequest()->getParam('sku');
            $this->eventManager->dispatch('amasty_username', ['sku' => $sku]);
        }

        return $result;
    }
}
