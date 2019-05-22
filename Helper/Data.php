<?php

namespace Hero\DisableEditUsers\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    /**
     * @var \Magento\Framework\App\Http\Context
     */
    private $httpContext;

    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->_customerSession = $customerSession;
        $this->scopeConfig = $scopeConfig;

    }

    /**
     * check can edit user
     * @return bool
     */
    public function canEdit()
    {
        $currentCustomer = $this->_customerSession->getCustomer();
        if(!$currentCustomer) {

            return false;
        }

        $groupID = $currentCustomer->getGroupId();

        if(!$groupID) {

            return false;
        }

        //if value is no
        $dac = $this->scopeConfig->getValue('customer/online_customers/dac', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        if(!$dac) {

            return true;
        }

        $groups = $this->scopeConfig->getValue('customer/online_customers/groups', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        if(!$groups) {

            return false;
        }
        $groups = explode(',', $groups);


        return !in_array($groupID, $groups);

    }
}