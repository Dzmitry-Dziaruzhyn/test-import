<?php

declare(strict_types=1);

namespace Test\Import\Model\Profile;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Api\GroupManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Magento\Framework\Filesystem\Driver\File;

class JSON extends AbstractProfile implements Profile
{
    /**
     * @var JsonSerializer
     */
    private $jsonSerializer;

    /**
     * @var File
     */
    private $file;

    /**
     * @param JsonSerializer $jsonSerializer
     * @param CustomerInterfaceFactory $customerFactory
     * @param StoreManagerInterface $storeManager
     * @param GroupManagementInterface $groupManagement
     * @param CustomerRepositoryInterface $customerRepository
     * @param File $file
     */
    public function __construct(
        JsonSerializer $jsonSerializer,
        CustomerInterfaceFactory $customerFactory,
        StoreManagerInterface $storeManager,
        GroupManagementInterface $groupManagement,
        CustomerRepositoryInterface $customerRepository,
        File $file
    ) {
        $this->jsonSerializer = $jsonSerializer;
        $this->file = $file;
        parent::__construct($customerFactory, $storeManager, $groupManagement, $customerRepository);
    }

    /**
     * @inheritDoc
     */
    protected function prepareData(string $source)
    {
        $fileContent = $this->file->fileGetContents($source);
        $jsonData = $this->jsonSerializer->unserialize($fileContent);
        foreach ($jsonData as $data) {
            yield $data;
        }
    }

    /**
     * @inheritDoc
     */
    protected function addCustomerData(CustomerInterface $customer, array $row)
    {
        return $customer->setFirstname($row['fname'])
            ->setLastname($row['lname'])
            ->setEmail($row['emailaddress']);
    }
}
