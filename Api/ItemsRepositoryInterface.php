<?php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Api;

use KiwiCommerce\Testimonials\Api\Data\ItemInterface;

interface ItemsRepositoryInterface
{
    /**
     * Get item by ID
     *
     * @param int $id
     * @return \KiwiCommerce\Testimonials\Api\Data\ItemInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $id): ItemInterface;
}