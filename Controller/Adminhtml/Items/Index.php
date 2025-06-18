<?php

declare(strict_types=1);

namespace Biren\Crudimage\Controller\Adminhtml\Items;

class Index extends \Biren\Crudimage\Controller\Adminhtml\Items
{
    /**
     * Items list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute(): \Magento\Backend\Model\View\Result\Page
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Biren_Crudimage::test');
        $resultPage->getConfig()->getTitle()->prepend(__('Test Items'));
        $resultPage->addBreadcrumb(__('Test'), __('Test'));
        $resultPage->addBreadcrumb(__('Items'), __('Items'));
        return $resultPage;
    }
}