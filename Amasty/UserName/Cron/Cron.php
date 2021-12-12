<?php

namespace Amasty\UserName\Cron;

use Amasty\UserName\Model\ResourceModel\Foo as FooResourceModel;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Amasty\UserName\Model\FooFactory;
use Magento\Framework\Mail\Template\FactoryInterface as TemplateFactory;

class Cron
{
    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var FooFactory
     */
    private $fooFactory;

    /**
     * @var FooResourceModel
     */
    private $fooResourceModel;

    /**
     * @var TemplateFactory
     */
    private $templateFactory;

    public function __construct(
        Context $context,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        FooFactory $fooFactory,
        FooResourceModel $fooResourceModel,
        TemplateFactory $templateFactory
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->fooFactory = $fooFactory;
        $this->fooResourceModel = $fooResourceModel;
        $this->templateFactory = $templateFactory;
    }

    public function execute()
    {
        $emailTemplate = $this->scopeConfig->getValue('beautiful_config/general/email_template');
        $email = $this->scopeConfig->getValue('beautiful_config/general/email');
        $fromEmail = 'admin@admin.com';
        $fromName = 'Admin';
        $foo = $this->fooFactory->create();
        $item = $foo->getCollection()->getLastItem();

        $templateVars = [
            'qty' => $item->getQty()
        ];
        $storeId = $this->storeManager->getStore()->getId();
        $from = ['email' => $fromEmail, 'name' => $fromName];
        $this->inlineTranslation->suspend();
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $templateOptions = [
            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
            'store' => $storeId
        ];
        $transport = $this->transportBuilder->setTemplateIdentifier($emailTemplate, $storeScope)
            ->setTemplateOptions($templateOptions)
            ->setTemplateVars($templateVars)
            ->setFrom($from)
            ->addTo($email)
            ->getTransport();
        $transport->sendMessage();
        $template = $this->templateFactory->get($emailTemplate);
        $template->setVars($templateVars)
            ->setOptions($templateOptions);
        $emailBody = $template->processTemplate();
        $item->setData('email_body', $emailBody);
        $this->inlineTranslation->resume();

        return $this;
    }
}
