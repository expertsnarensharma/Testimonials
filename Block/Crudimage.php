<?php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Block;

/**
 * Crudimage content block
 */
class Crudimage extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context
    ) {
        parent::__construct($context);
    }

    protected function _prepareLayout(): \Magento\Framework\View\Element\Template
    {
        $this->pageConfig->getTitle()->set(__('KiwiCommerce Crudimage Module'));
        return parent::_prepareLayout();
    }
}