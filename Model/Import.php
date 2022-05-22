<?php

declare(strict_types=1);

namespace Test\Import\Model;

use Magento\Framework\Exception\NotFoundException;

class Import
{
    /**
     * @var array
     */
    private $profiles;

    /**
     * @param array $profiles
     */
    public function __construct(
        array $profiles
    ) {
        $this->profiles = $profiles;
    }

    /**
     * @param string $profile
     * @param string $source
     * @return array
     * @throws NotFoundException
     */
    public function execute(string $profile, string $source): array
    {
        if (empty($this->profiles[$profile])) {
            throw new NotFoundException(__('Profile not found!'));
        }

        return $this->profiles[$profile]->import($source);
    }
}
