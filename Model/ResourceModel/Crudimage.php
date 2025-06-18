<?php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Model\ResourceModel;

class Crudimage extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct(): void
    {
        $this->_init('kiwicommerce_testimonials', 'testimonial_id');
    }
}