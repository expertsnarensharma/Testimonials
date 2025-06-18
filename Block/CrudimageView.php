<?php

declare(strict_types=1);

namespace Biren\Crudimage\Block;

use Magento\Framework\View\Element\Template\Context;
use Biren\Crudimage\Model\CrudimageFactory;
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
        $this->pageConfig->getTitle()->set(__('Biren Crudimage Module View Page'));
        return parent::_prepareLayout();
    }

    /**
     * @return \Biren\Crudimage\Model\Crudimage|false
     */
    public function getSingleData(): \Biren\Crudimage\Model\Crudimage|false
    {
        $id = (int) $this->getRequest()->getParam('id');
        $crudimage = $this->_crudimage->create();
        $singleData = $crudimage->load($id);
        if ($singleData->getCrudimageId() && (int)$singleData->getStatus() === 1) {
            return $singleData;
        }
        return false;
    }
}