<?php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Controller\Adminhtml\Items;

use Magento\Backend\App\Action\Context;
use KiwiCommerce\Testimonials\Model\CrudimageFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Image\AdapterFactory;
use Magento\Backend\Model\Session;
use Psr\Log\LoggerInterface;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\View\Result\PageFactory;

class Save extends \KiwiCommerce\Testimonials\Controller\Adminhtml\Items
{
    protected CrudimageFactory $crudimageFactory;
    protected UploaderFactory $uploaderFactory;
    protected AdapterFactory $adapterFactory;
    protected Filesystem $filesystem;
    protected DirectoryList $directoryList;
    protected File $file;
    protected Session $backendSession;
    protected LoggerInterface $logger;

    public function __construct(
        Context $context,
        ForwardFactory $resultForwardFactory,
        PageFactory $resultPageFactory,
        DirectoryList $directoryList,
        UploaderFactory $uploaderFactory,
        AdapterFactory $adapterFactory,
        Filesystem $filesystem,
        File $file,
        CrudimageFactory $crudimageFactory,
        Session $backendSession,
        LoggerInterface $logger
    ) {
        $this->crudimageFactory = $crudimageFactory;
        $this->uploaderFactory = $uploaderFactory;
        $this->adapterFactory = $adapterFactory;
        $this->filesystem = $filesystem;
        $this->directoryList = $directoryList;
        $this->file = $file;
        $this->backendSession = $backendSession;
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
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        if ($this->getRequest()->getPostValue()) {
            $data = $this->getRequest()->getPostValue();

            try {
                $model = $this->crudimageFactory->create();

                // Handle image upload
                if (isset($_FILES['profile_pic']['name']) && $_FILES['profile_pic']['name'] !== '') {
                    try {
                        $uploader = $this->uploaderFactory->create(['fileId' => 'profile_pic']);
                        $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                        $imageAdapter = $this->adapterFactory->create();
                        $uploader->addValidateCallback('custom_image_upload', $imageAdapter, 'validateUploadFile');
                        $uploader->setAllowRenameFiles(true);
                        $uploader->setFilesDispersion(true);
                        $mediaDirectory = $this->filesystem->getDirectoryRead($this->directoryList::MEDIA);
                        $destinationPath = $mediaDirectory->getAbsolutePath('kiwicommerce/testimonials');
                        $result = $uploader->save($destinationPath);
                        if (!$result) {
                            throw new LocalizedException(
                                __('File cannot be saved to path: %1', $destinationPath)
                            );
                        }
                        $imagePath = 'kiwicommerce/testimonials' . $result['file'];
                        $data['profile_pic'] = $imagePath;

                        
                    } catch (\Exception $e) {
                        throw new LocalizedException(__('Image upload failed: %1', $e->getMessage()));
                    }
                }

                // Handle image deletion
                if (isset($data['profile_pic']['delete']) && $data['profile_pic']['delete'] == 1) {
                    $mediaDirectory = $this->filesystem->getDirectoryRead($this->directoryList::MEDIA)->getAbsolutePath();
                    $file = $data['profile_pic']['value'];
                    $imgPath = $mediaDirectory . $file;
                    if ($this->file->isExists($imgPath)) {
                        $this->file->deleteFile($imgPath);
                    }
                    $data['profile_pic'] = null;
                }

                if (isset($data['profile_pic']['value'])) {
                    $data['profile_pic'] = $data['profile_pic']['value'];
                }

                $id = $this->getRequest()->getParam('id');
                if ($id) {
                    $model->load($id);
                    if ($id != $model->getId()) {
                        throw new LocalizedException(__('The wrong item is specified.'));
                    }
                }

                $model->setData($data);
                $this->backendSession->setPageData($model->getData());
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the item.'));
                $this->backendSession->setPageData(false);

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('kiwicommerce_testimonials/*/edit', ['id' => $model->getId()]);
                }
                return $resultRedirect->setPath('kiwicommerce_testimonials/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $id = (int)$this->getRequest()->getParam('id');
                if (!empty($id)) {
                    return $resultRedirect->setPath('kiwicommerce_testimonials/*/edit', ['id' => $id]);
                } else {
                    return $resultRedirect->setPath('kiwicommerce_testimonials/*/new');
                }
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('Something went wrong while saving the item data. Please review the error log.')
                );
                $this->logger->critical($e);
                $this->backendSession->setPageData($data);
                return $resultRedirect->setPath('kiwicommerce_testimonials/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            }
        }
        return $resultRedirect->setPath('kiwicommerce_testimonials/*/');
    }
}