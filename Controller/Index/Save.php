<?php

declare(strict_types=1);

namespace Biren\Crudimage\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Biren\Crudimage\Model\CrudimageFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Controller\Result\Redirect;

class Save extends Action implements HttpPostActionInterface
{
    protected CrudimageFactory $crudimageFactory;
    protected UploaderFactory $uploaderFactory;
    protected AdapterFactory $adapterFactory;
    protected Filesystem $filesystem;

    public function __construct(
        Context $context,
        CrudimageFactory $crudimageFactory,
        UploaderFactory $uploaderFactory,
        AdapterFactory $adapterFactory,
        Filesystem $filesystem
    ) {
        $this->crudimageFactory = $crudimageFactory;
        $this->uploaderFactory = $uploaderFactory;
        $this->adapterFactory = $adapterFactory;
        $this->filesystem = $filesystem;
        parent::__construct($context);
    }

    public function execute(): Redirect
    {
        $data = $this->getRequest()->getParams();
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] !== '') {
            try {
                $uploader = $this->uploaderFactory->create(['fileId' => 'image']);
                $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                $imageAdapter = $this->adapterFactory->create();
                $uploader->addValidateCallback('custom_image_upload', $imageAdapter, 'validateUploadFile');
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);
                $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
                $destinationPath = $mediaDirectory->getAbsolutePath('biren/crudimage');
                $result = $uploader->save($destinationPath);
                if (!$result) {
                    throw new LocalizedException(
                        __('File cannot be saved to path: %1', $destinationPath)
                    );
                }
                $imagePath = 'biren/crudimage' . $result['file'];
                $data['image'] = $imagePath;
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Image upload failed: %1', $e->getMessage()));
            }
        }

        $crudimage = $this->crudimageFactory->create();
        $crudimage->setData($data);
        try {
            $crudimage->save();
            $this->messageManager->addSuccessMessage(__('You saved the data.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Data was not saved. Error: %1', $e->getMessage()));
        }

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('crudimage');
        return $resultRedirect;
    }
}