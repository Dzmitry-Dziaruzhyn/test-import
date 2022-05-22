<?php

declare(strict_types=1);

namespace Test\Import\Model\Profile;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Api\GroupManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;

abstract class AbstractProfile implements Profile
{
    /**
     * @var CustomerInterfaceFactory
     */
    private $customerFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var GroupManagementInterface
     */
    private $groupManagement;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @param CustomerInterfaceFactory $customerFactory
     * @param StoreManagerInterface $storeManager
     * @param GroupManagementInterface $groupManagement
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        CustomerInterfaceFactory $customerFactory,
        StoreManagerInterface $storeManager,
        GroupManagementInterface $groupManagement,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->customerFactory = $customerFactory;
        $this->storeManager = $storeManager;
        $this->groupManagement = $groupManagement;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @inheritDoc
     */
    public function import(string $source): array
    {
        $data = $this->prepareData($source);
        $errors = [];
        foreach ($data as $row) {
            try {
                $customer = $this->customerFactory->create();
                $customer->setGroupId($this->groupManagement->getDefaultGroup()->getId())
                    ->setWebsiteId($this->storeManager->getDefaultStoreView()->getWebsiteId());
                $customer = $this->addCustomerData($customer, $row);
                $this->customerRepository->save($customer);
            } catch (\Exception $exception) {
                $errors[] = $exception->getMessage();
            }
        }

        return $errors;
    }

    /**
     * Prepare data from source
     *
     * @param string $source
     * @return mixed
     */
    abstract protected function prepareData(string $source);

    /**
     * Add customer data
     *
     * @param CustomerInterface $customer
     * @param array $row
     * @return mixed
     */
    abstract protected function addCustomerData(CustomerInterface $customer, array $row);
}
