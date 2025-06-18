<?php

declare(strict_types=1);

namespace Biren\Crudimage\Controller\Adminhtml\Items;

use Magento\Backend\App\Action\Context;
use Biren\Crudimage\Model\CrudimageFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;
use Magento\Backend\Model\View\Result\ForwardFactory;

class Delete extends \Biren\Crudimage\Controller\Adminhtml\Items
{
    protected CrudimageFactory $crudimageFactory;
    protected LoggerInterface $logger;

    public function __construct(
        Context $context,
        CrudimageFactory $crudimageFactory,
        LoggerInterface $logger,
        ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\Image\AdapterFactory $adapterFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Filesystem\Driver\File $file
    ) {
        $this->crudimageFactory = $crudimageFactory;
        $this->logger = $logger;
        parent::__construct(
            $context,
            $resultForwardFactory,
            $resultPageFactory,
            $directoryList,
            $uploaderFactory,
            $adapterFactory,
            $filesystem,
            $file
        );
    }

    public function execute(): Redirect
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = (int)$this->getRequest()->getParam('id');
        if ($id) {
            try {
                $model = $this->crudimageFactory->create();
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('You deleted the item.'));
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    __("We can't delete item right now. Please review the log and try again.")
                );
                $this->logger->critical($e);
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__("We can't find an item to delete."));
        return $resultRedirect->setPath('*/*/');
    }
}