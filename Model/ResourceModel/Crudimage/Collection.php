<?php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Model\ResourceModel\Crudimage;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'crudimage_id';

    /**
     * Define model & resource model
     */
    protected function _construct(): void
    {
        $this->_init(
            \KiwiCommerce\Testimonials\Model\Crudimage::class,
            \KiwiCommerce\Testimonials\Model\ResourceModel\Crudimage::class
        );
    }
}