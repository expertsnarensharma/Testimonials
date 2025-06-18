<?php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Block\Adminhtml\Items\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct(): void
    {
        parent::_construct();
        $this->setId('kiwicommerce_testimonials_items_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Item'));
    }
}