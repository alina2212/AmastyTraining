<?php

namespace Amasty\SecondUserName\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\ScopeInterface;

class CheckIsNewProductObserver implements ObserverInterface
{
    protected $scopeConfig;

    public function __construct(
        ScopeInterface$scopeInterface,
    ) {
        $this->scopeConfig = $scopeInterface;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $sku = $observer->getSku();
        $forSku = $this->scopeConfig->getValue('secondusername_config/general/for_sku', ScopeInterface::SCOPE_STORE);
        $promoSku = $this->scopeConfig->getValue('secondusername_config/general/promo_sku', ScopeInterface::SCOPE_STORE);
        $forSku = explode(',', $forSku);

        /*if (in_array($sku, $forSku)) {

        }*/

        return $this;
    }
}
