<?php

namespace Amasty\UserName\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http;

class Cart extends Action
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var Http
     */
    private $request;

    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        CheckoutSession $checkoutSession,
        ProductRepositoryInterface $productRepository,
        Http $request
    ) {
        $this->productRepository = $productRepository;
        $this->checkoutSession = $checkoutSession;
        $this->scopeConfig = $scopeConfig;
        $this->request = $request;
        return parent::__construct($context);
    }

    public function execute()
    {
        $sku = $this->request->getParam('sku');
        $qty = $this->request->getParam('qty');
        $product = $this->productRepository->get($sku);
        $quote = $this->checkoutSession->getQuote();

        if(!$quote->getId()){
             $quote->save();
        }

        $quote->addProduct($product, $qty);
        $quote->save();
    }
}
