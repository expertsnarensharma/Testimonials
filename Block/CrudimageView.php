<?php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Block;

use Magento\Framework\View\Element\Template\Context;
use KiwiCommerce\Testimonials\Model\CrudimageFactory;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\View\Element\Template;

/**
 * Crudimage View block
 */
class CrudimageView extends Template
{
    /**
     * @var CrudimageFactory
     */
    protected CrudimageFactory $_crudimage;

    /**
     * @var FilterProvider
     */
    protected FilterProvider $_filterProvider;

    public function __construct(
        Context $context,
        CrudimageFactory $crudimage,
        FilterProvider $filterProvider,
        array $data = []
    ) {
        $this->_crudimage = $crudimage;
        $this->_filterProvider = $filterProvider;
        parent::__construct($context, $data);
    }

    /**
     * @return $this
     */
    protected function _prepareLayout(): self
    {
        $this->pageConfig->getTitle()->set(__('KiwiCommerce Testimonial Module View Page'));
        return parent::_prepareLayout();
    }

    /**
     * @return \KiwiCommerce\Testimonials\Model\Crudimage|false
     */
    public function getSingleData(): \KiwiCommerce\Testimonials\Model\Crudimage|false
    {
        $id = (int) $this->getRequest()->getParam('id');
        $crudimage = $this->_crudimage->create();
        $singleData = $crudimage->load($id);
        if ($singleData->getTestimonialId() && (int)$singleData->getStatus() === 1) {
            return $singleData;
        }
        return false;
    }
}