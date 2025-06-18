<?php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Controller\Adminhtml\Items;

class Index extends \KiwiCommerce\Testimonials\Controller\Adminhtml\Items
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
        $resultPage->setActiveMenu('KiwiCommerce_Testimonials::test');
        $resultPage->getConfig()->getTitle()->prepend(__('Test Items'));
        $resultPage->addBreadcrumb(__('Test'), __('Test'));
        $resultPage->addBreadcrumb(__('Items'), __('Items'));
        return $resultPage;
    }
}