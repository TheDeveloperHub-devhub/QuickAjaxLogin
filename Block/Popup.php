<?php

declare(strict_types=1);

namespace DeveloperHub\QuickAjaxLogin\Block;

use Magento\Customer\Model\AccountManagement;
use Magento\Customer\Model\SessionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Popup extends Template
{
    /**
     * @var SessionFactory
     */
    private $sessionFactory;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param Context $context
     * @param SessionFactory $sessionFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
        Context $context,
        SessionFactory $sessionFactory,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->sessionFactory = $sessionFactory;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return boolean
     */
    public function isLoggedIn() : bool
    {
        $customerSession = $this->sessionFactory->create();
        return (bool) $customerSession->isLoggedIn();
    }

    /**
     * @return string
     */
    public function getMinimumPasswordLength() : string
    {
        return $this->scopeConfig->getValue(AccountManagement::XML_PATH_MINIMUM_PASSWORD_LENGTH) ?? '';
    }

    /**
     * @return string
     */
    public function getRequiredCharacterClassesNumber() : string
    {
        return $this->scopeConfig->getValue(AccountManagement::XML_PATH_REQUIRED_CHARACTER_CLASSES_NUMBER) ?? '';
    }
}
