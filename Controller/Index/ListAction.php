<?php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;

class ListAction extends Action implements HttpGetActionInterface
{
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    public function execute(): void
    {
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
    }
}