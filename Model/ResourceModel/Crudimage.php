<?php

declare(strict_types=1);

namespace Biren\Crudimage\Model\ResourceModel;

class Crudimage extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct(): void
    {
        $this->_init('biren_crudimage', 'crudimage_id'); // "biren_crudimage" is table name, "crudimage_id" is the primary key
    }
}