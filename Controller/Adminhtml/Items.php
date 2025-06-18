<?php

declare(strict_types=1);

namespace Biren\Crudimage\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File;

/**
 * Items controller
 */
abstract class Items extends Action
{
    protected ForwardFactory $resultForwardFactory;
    protected PageFactory $resultPageFactory;
    protected UploaderFactory $uploaderFactory;
    protected AdapterFactory $adapterFactory;
    protected Filesystem $filesystem;
    protected DirectoryList $directoryList;
    protected File $file;

    public function __construct(
        Context $context,
        ForwardFactory $resultForwardFactory,
        PageFactory $resultPageFactory,
        DirectoryList $directoryList,
        UploaderFactory $uploaderFactory,
        AdapterFactory $adapterFactory,
        Filesystem $filesystem,
        File $file
    ) {
        parent::__construct($context);
        $this->resultForwardFactory = $resultForwardFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->directoryList = $directoryList;
        $this->uploaderFactory = $uploaderFactory;
        $this->adapterFactory = $adapterFactory;
        $this->filesystem = $filesystem;
        $this->file = $file;
    }

    protected function _initAction(): static
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('Biren_Crudimage::items')->_addBreadcrumb(__('Items'), __('Items'));
        return $this;
    }

    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('Biren_Crudimage::items');
    }
}