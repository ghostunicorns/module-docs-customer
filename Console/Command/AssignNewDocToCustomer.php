<?php
/*
 * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\DocsCustomer\Console\Command;

use Exception;
use GhostUnicorns\Docs\Model\DocsManager;
use GhostUnicorns\Docs\Model\SetAreaCode;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Filesystem\DirectoryList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AssignNewDocToCustomer extends Command
{
    const ARGUMENT_NAME = 'filename';

    const ARGUMENT_NAME1 = 'filepath';

    const ARGUMENT_NAME2 = 'customer_email';

    const ENTITY_TYPE = 'customer';

    /**
     * @var DocsManager
     */
    private $docsManager;

    /**
     * @var DirectoryList
     */
    private $directoryList;

    /**
     * @var SetAreaCode
     */
    private $areaCode;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('customer:assign:doc');
        $this->setDescription('Assign new doc to specific customer, check it inside var/docs/customer folder');
        $this->addArgument(
            self::ARGUMENT_NAME,
            null,
            'filename',
            InputOption::VALUE_REQUIRED
        );
        $this->addArgument(
            self::ARGUMENT_NAME1,
            null,
            'filepath',
            InputOption::VALUE_REQUIRED
        );
        $this->addArgument(
            self::ARGUMENT_NAME2,
            null,
            'customer-email',
            InputOption::VALUE_REQUIRED
        );

        parent::configure();
    }

    /**
     * @param CustomerRepositoryInterface $customerRepository
     * @param DocsManager $docsManager
     * @param DirectoryList $directoryList
     * @param SetAreaCode $areaCode
     * @param string|null $name
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        DocsManager $docsManager,
        DirectoryList $directoryList,
        SetAreaCode $areaCode,
        string $name = null
    ) {
        parent::__construct($name);
        $this->docsManager = $docsManager;
        $this->directoryList = $directoryList;
        $this->areaCode = $areaCode;
        $this->customerRepository = $customerRepository;
    }

    /**
     * Command utile solo al debug
     * testbase è il nome del file sta sulla cartella var
     * @inheirtDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->areaCode->execute('adminhtml');
            $fileName = $input->getArgument(self::ARGUMENT_NAME);
            $filePath = $input->getArgument(self::ARGUMENT_NAME1);
            $customerEmail = $input->getArgument(self::ARGUMENT_NAME2);
            $completePath = $this->directoryList->getPath('var') . DIRECTORY_SEPARATOR . $filePath;
            $customer = $this->customerRepository->get($customerEmail);
            $customerId = (string)$customer->getId();
            $content = file_get_contents($completePath);

            $this->docsManager->setNewDoc($fileName, $content, self::ENTITY_TYPE, $customerId);
            $output->writeln('<info>Docs added!</info>');
        } catch (Exception $exception) {
            $output->writeln('<error>Something went wrong during docs add, check logs!</error>');
        }
    }
}
