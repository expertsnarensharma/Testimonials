<?php

declare(strict_types=1);

namespace Biren\Crudimage\Api;

use Biren\Crudimage\Api\Data\ItemInterface;

interface ItemsRepositoryInterface
{
    /**
     * Get item by ID
     *
     * @param int $id
     * @return \Biren\Crudimage\Api\Data\ItemInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $id): ItemInterface;
}