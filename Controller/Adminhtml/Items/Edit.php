<?php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Controller\Adminhtml\Items;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use Magento\Framework\Filter\FilterManager;
use Magento\Framework\App\Response\Http\FileFactory;
use KiwiCommerce\Testimonials\Model\CrudimageFactory;
use Magento\Framework\Registry;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Backend\Model\Session;
use Magento\Framework\View\Result\Page;

class Edit extends \KiwiCommerce\Testimonials\Controller\Adminhtml\Items
{
    protected DataPersistorInterface $dataPersistor;
    protected CrudimageFactory $crudimageFactory;
    protected Registry $coreRegistry;
    protected Session $backendSession;
    protected PageFactory $resultPageFactory;

    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        ForwardFactory $resultForwardFactory,
        PageFactory $resultPageFactory,
        DirectoryList $directoryList,
        UploaderFactory $uploaderFactory,
        AdapterFactory $adapterFactory,
        Filesystem $filesystem,
        File $file,
        Date $dateFilter,
        FilterManager $filterManager,
        FileFactory $fileFactory,
        CrudimageFactory $crudimageFactory,
        Registry $coreRegistry,
        Session $backendSession
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->crudimageFactory = $crudimageFactory;
        $this->coreRegistry = $coreRegistry;
        $this->backendSession = $backendSession;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct(
            $context,
            $resultForwardFactory,
            $resultPageFactory,
            $directoryList,
            $uploaderFactory,
            $adapterFactory,
            $filesystem,
            $file,
            $dateFilter,
            $filterManager,
            $fileFactory
        );
    }

    public function execute(): Page|\Magento\Framework\Controller\Result\Redirect
    {
        $id = (int)$this->getRequest()->getParam('id');
        $model = $this->crudimageFactory->create();

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This item no longer exists.'));
                /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('kiwicommerce_testimonials/*');
            }
        }

        // Set entered data if there was an error when saving
        $data = $this->backendSession->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }

        // Register model for use in blocks
        $this->coreRegistry->register('current_kiwicommerce_testimonials_items', $model);

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        return $resultPage;
    }
}