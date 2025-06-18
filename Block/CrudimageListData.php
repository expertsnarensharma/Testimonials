<?php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Block;

use Magento\Framework\View\Element\Template\Context;
use KiwiCommerce\Testimonials\Model\CrudimageFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\View\Element\Template;

/**
 * Crudimage List block
 */
class CrudimageListData extends Template
{
    /**
     * @var CrudimageFactory
     */
    protected CrudimageFactory $_crudimage;

    public function __construct(
        Context $context,
        CrudimageFactory $crudimage,
        array $data = []
    ) {
        $this->_crudimage = $crudimage;
        parent::__construct($context, $data);
    }

    /**
     * @return $this
     */
    protected function _prepareLayout(): self
    {
        $this->pageConfig->getTitle()->set(__('KiwiCommerce Crudimage Module List Page'));

        $collection = $this->getCrudimageCollection();
        if ($collection) {
            $pager = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'kiwicommerce.crudimage.pager'
            )->setAvailableLimit([5 => 5, 10 => 10, 15 => 15])
             ->setShowPerPage(true)
             ->setCollection($collection);
            $this->setChild('pager', $pager);
            $collection->load();
        }
        return parent::_prepareLayout();
    }

    /**
     * @return AbstractDb|null
     */
    public function getCrudimageCollection(): ?AbstractDb
    {
        $page = (int)($this->getRequest()->getParam('p') ?? 1);
        $pageSize = (int)($this->getRequest()->getParam('limit') ?? 5);

        $crudimage = $this->_crudimage->create();
        $collection = $crudimage->getCollection();
        $collection->addFieldToFilter('status', '1');
        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);

        return $collection;
    }

    /**
     * @return string
     */
    public function getPagerHtml(): string
    {
        return $this->getChildHtml('pager');
    }
}