<?php

namespace Amasty\UserName\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http;
use Magento\CatalogInventory\Api\StockStateInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Controller\ResultFactory;

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

    /**
     * @var StockStateInterface
     */
    protected $stockState;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        CheckoutSession $checkoutSession,
        ProductRepositoryInterface $productRepository,
        Http $request,
        StockStateInterface $stockState,
        ManagerInterface $messageManager
    ) {
        $this->productRepository = $productRepository;
        $this->checkoutSession = $checkoutSession;
        $this->scopeConfig = $scopeConfig;
        $this->request = $request;
        $this->stockState = $stockState;
        $this->messageManager = $messageManager;
        return parent::__construct($context);
    }

    public function execute()
    {
        $sku = $this->request->getParam('sku');
        $qty = $this->request->getParam('qty');
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());

        try {
            $product = $this->productRepository->get($sku);
        } catch (\Exception $exception) {
            $this->messageManager->addError($exception->getMessage());
            return $resultRedirect;
        }

        $quote = $this->checkoutSession->getQuote();

        if(!$quote->getId()){
            $quote->save();
        }

        $productId = $product->getId();
        $availebleQty = $this->getStockQty($productId, 0);

        if ($qty > $availebleQty) {
           $this->messageManager->addError(__("Not qty"));
           return $resultRedirect;
        }

        if ($product->getTypeId() != \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE) {
            $this->messageManager->addError(__("Product is not a simple"));
            return $resultRedirect;
        }

        try {
            $quote->addProduct($product, $qty);
            $quote->collectTotals();
            $quote->save();
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage('Quote not saved or Product not added');
        }
        $this->messageManager->addSuccessMessage('Product added in quote');

        return $resultRedirect;
    }

    public function getStockQty($productId, $websiteId = null)
    {
        return $this->stockState->getStockQty($productId, $websiteId);
    }
}
