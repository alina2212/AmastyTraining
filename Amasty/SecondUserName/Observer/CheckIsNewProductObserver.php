<?php

namespace Amasty\SecondUserName\Observer;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class CheckIsNewProductObserver implements ObserverInterface
{
    const ONE_QTY = 1;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    public function __construct(
        CheckoutSession $checkoutSession,
        ScopeConfigInterface $scopeInterface,
        ProductRepositoryInterface $productRepository
    ) {
        $this->scopeConfig = $scopeInterface;
        $this->checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $sku = $observer->getSku();
        $forSku = $this->scopeConfig->getValue('secondusername_config/general/for_sku', ScopeInterface::SCOPE_STORE);
        $promoSku = $this->scopeConfig->getValue('secondusername_config/general/promo_sku', ScopeInterface::SCOPE_STORE);
        $forSku = explode(',', $forSku);

        if (in_array($sku, $forSku)) {
            $product = $this->productRepository->get($promoSku);
            $quote = $this->checkoutSession->getQuote();
            $quote->addProduct($product, self::ONE_QTY);
            $quote->save();
        }

        return $this;
    }
}
