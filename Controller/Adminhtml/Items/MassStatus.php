<?php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Controller\Adminhtml\Items;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use KiwiCommerce\Testimonials\Model\ResourceModel\Crudimage\CollectionFactory;
use Magento\Backend\Model\View\Result\Redirect;

class MassStatus extends \Magento\Backend\App\Action
{
    protected Filter $filter;
    protected CollectionFactory $collectionFactory;

    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute(): Redirect
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();
        $status = (int)$this->getRequest()->getParam('status');

        foreach ($collection as $record) {
            $record->setStatus($status)->save();
        }

        $statusLabel = $status === 1 ? __('enabled') : __('disabled');
        $this->messageManager->addSuccessMessage(
            __('A total of %1 record(s) have been %2.', $collectionSize, $statusLabel)
        );

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}