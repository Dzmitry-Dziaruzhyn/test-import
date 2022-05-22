<?php

declare(strict_types=1);

namespace Test\Import\Model\Profile;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\File\Csv as MagentoCsv;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Api\GroupManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;

class CSV extends AbstractProfile implements Profile
{
    /**
     * @var MagentoCsv
     */
    private $magentoCsv;

    /**
     * @param MagentoCsv $magentoCsv
     * @param CustomerInterfaceFactory $customerFactory
     * @param StoreManagerInterface $storeManager
     * @param GroupManagementInterface $groupManagement
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        MagentoCsv $magentoCsv,
        CustomerInterfaceFactory $customerFactory,
        StoreManagerInterface $storeManager,
        GroupManagementInterface $groupManagement,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->magentoCsv = $magentoCsv;
        parent::__construct($customerFactory, $storeManager, $groupManagement, $customerRepository);
    }

    /**
     * @inheritDoc
     */
    protected function prepareData(string $source)
    {
        $csvData = $this->magentoCsv->getData($source);
        foreach ($csvData as $row => $data) {
            if ($row > 0) {
                yield $data;
            }
        }
    }

    /**
     * @inheritDoc
     */
    protected function addCustomerData(CustomerInterface $customer, array $row)
    {
        return $customer->setFirstname($row[0])
            ->setLastname($row[1])
            ->setEmail($row[2]);
    }
}
