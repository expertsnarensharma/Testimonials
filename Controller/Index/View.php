<?php

declare(strict_types=1);

namespace Biren\Crudimage\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Exception\NotFoundException;
use Biren\Crudimage\Model\CrudimageFactory;

class View extends Action implements HttpGetActionInterface
{
    protected CrudimageFactory $crudimageFactory;

    public function __construct(
        Context $context,
        CrudimageFactory $crudimageFactory
    ) {
        $this->crudimageFactory = $crudimageFactory;
        parent::__construct($context);
    }

    public function execute(): void
    {
        $id = (int)$this->getRequest()->getParam('id');
        $model = $this->crudimageFactory->create()->load($id);

        if (!$model->getId() || (int)$model->getStatus() !== 1) {
            throw new NotFoundException(__('Parameter is incorrect.'));
        }

        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
    }
}