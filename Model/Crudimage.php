<?php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Model;

use Magento\Framework\Model\AbstractModel;

class Crudimage extends AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct(): void
    {
        $this->_init(\KiwiCommerce\Testimonials\Model\ResourceModel\Crudimage::class);
    }
}