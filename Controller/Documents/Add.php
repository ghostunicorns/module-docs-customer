<?php
/*
 * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\DocsCustomer\Controller\Documents;

use Exception;
use GhostUnicorns\Docs\Model\SetDocWithTmpFile;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Result\PageFactory;

class Add extends Action
{
    const ENTITYTYPE = 'customer';

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * @var UiComponentFactory
     */
    protected $factory;

    /**
     * @var SetDocWithTmpFile
     */
    protected $setDocWithTmpFile;

    /**
     * @param Context $context
     * @param SetDocWithTmpFile $setDocWithTmpFile
     * @param PageFactory $pageFactory
     * @param UiComponentFactory $factory
     * @param Session $customerSession
     */
    public function __construct(
        Context $context,
        SetDocWithTmpFile $setDocWithTmpFile,
        PageFactory $pageFactory,
        UiComponentFactory $factory,
        Session $customerSession
    ) {
        $this->pageFactory = $pageFactory;
        $this->factory = $factory;
        $this->customerSession = $customerSession;
        $this->setDocWithTmpFile = $setDocWithTmpFile;
        return parent::__construct($context);
    }

    /**
     * @inheirtDoc
     */
    public function execute()
    {
        try {
            if ($this->customerSession->isLoggedIn()) {
                $customerId = (string)$this->customerSession->getCustomerId();
                $file = $this->getRequest()->getFiles('upload_file');

                if (!array_key_exists('name', $file) || $file['name'] === '') {
                    $this->messageManager->addErrorMessage(__('Something went wrong during document upload!'));
                    return $this->errorRedirect();
                }

                $this->setDocWithTmpFile->execute(self::ENTITYTYPE, $customerId, $file);
                $this->messageManager->addSuccessMessage(__('Document added!'));
                $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                $resultRedirect->setPath('docscustomer/customer/');
                return $resultRedirect;
            }
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setPath('customer/account/login');
            return $resultRedirect;
        } catch (Exception $exception) {
            $this->messageManager->addErrorMessage(__('Something went wrong during document upload!'));
            return $this->errorRedirect();
        }
    }

    /**
     * @return ResultInterface
     */
    private function errorRedirect(): ResultInterface
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('docscustomer/customer/');
        return $resultRedirect;
    }
}
