<?php

declare(strict_types=1);

namespace Biren\Crudimage\Model;

use Magento\Framework\Model\AbstractModel;

class Crudimage extends AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct(): void
    {
        $this->_init(\Biren\Crudimage\Model\ResourceModel\Crudimage::class);
    }
}