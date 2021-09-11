<?php
/*
 * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\DocsCustomer\Controller\Customer;

use GhostUnicorns\DocsCustomer\Model\Config;
use Magento\Customer\Controller\AbstractAccount;
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;

class Index extends AbstractAccount implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param Context $context
     * @param Config $config
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        Config $config,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
        $this->config = $config;
    }

    /**
     * @inheirtDoc
     */
    public function execute()
    {
        if ($this->config->isEnabledCustomerFeSection()) {
            return $this->resultPageFactory->create();
        } else {
            $this->messageManager->addErrorMessage(__('Customer not allowed to visit this section'));
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setPath('customer/account/');
            return $resultRedirect;
        }
    }
}
