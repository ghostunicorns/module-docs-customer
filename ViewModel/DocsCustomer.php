<?php
/*
 * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\DocsCustomer\ViewModel;

use GhostUnicorns\DocsCustomer\Model\Config;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class DocsCustomer implements ArgumentInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * @return bool
     */
    public function showUploadButton(): bool
    {
        return $this->config->isEnabledCustomerUploadFileFeSection();
    }

    /**
     * @return bool
     */
    public function showFrontendSection(): bool
    {
        return $this->config->isEnabledCustomerFeSection();
    }
}
