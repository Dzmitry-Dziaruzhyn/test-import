<?php

declare(strict_types=1);

namespace Test\Import\Model\Profile;

interface Profile
{
    /**
     * Executing profile import
     *
     * @param string $source
     * @return array
     */
    public function import(string $source): array;
}
