<?php
/*
 * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\DocsCustomer\Model;

use GhostUnicorns\Docs\Api\ConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config implements ConfigInterface
{
    /**
     * @var string
     */
    const DOCS_GENERAL_ENABLED = 'docs/customer_docs/enabled';

    /**
     * @var string
     */
    const DOCS_CUSTOMER_DOCS_ENABLED_CUSTOMER_FE_SECTION = 'docs/customer_docs/enabled_customer_fe_section';

    /**
     * @var string
     */
    const DOCS_CUSTOMER_DOCS_ENABLED_UPLOAD_FILE_FE_SECTION_CUSTOMER = 'docs/customer_docs/enabled_upload_file_fe_section_customer';

    /**
     * @var string
     */
    const DOCS_CUSTOMER_DOCS_UPLOAD_ALLOWED_EXTENSION = 'docs/customer_docs/upload_extension';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @inheirtDoc
     */
    public function isEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::DOCS_GENERAL_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     */
    public function isEnabledCustomerFeSection(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::DOCS_CUSTOMER_DOCS_ENABLED_CUSTOMER_FE_SECTION,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     */
    public function isEnabledCustomerUploadFileFeSection(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::DOCS_CUSTOMER_DOCS_ENABLED_UPLOAD_FILE_FE_SECTION_CUSTOMER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @inheirtDoc
     */
    public function getAllowedExtension(): string
    {
        return $this->scopeConfig->getValue(
            self::DOCS_CUSTOMER_DOCS_UPLOAD_ALLOWED_EXTENSION,
            ScopeInterface::SCOPE_STORE
        );
    }
}
