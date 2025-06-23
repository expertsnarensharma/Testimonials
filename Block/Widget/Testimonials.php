<?php
namespace KiwiCommerce\Testimonials\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use KiwiCommerce\Testimonials\Model\ResourceModel\Crudimage\CollectionFactory;

class Testimonials extends Template implements BlockInterface
{
    protected $_template = 'widget/testimonials.phtml';

    protected $collectionFactory;

    public function __construct(
        Template\Context $context,
        CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $data);
    }

    public function getTitle()
    {
        return $this->getData('title');
    }

    public function getTestimonials()
    {
        $limit = (int) $this->getData('limit') ?: 5;

        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('status', 1)
                   ->setOrder('created_at', 'DESC')
                   ->setPageSize($limit);

        return $collection;
    }
}
