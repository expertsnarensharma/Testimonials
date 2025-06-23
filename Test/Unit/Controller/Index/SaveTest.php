<?php
// filepath: app/code/KiwiCommerce/Testimonials/Test/Unit/Controller/Index/SaveTest.php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Test\Unit\Controller\Index;

use KiwiCommerce\Testimonials\Controller\Index\Save;
use PHPUnit\Framework\TestCase;
use Magento\Framework\App\Action\Context;
use KiwiCommerce\Testimonials\Model\CrudimageFactory;
use KiwiCommerce\Testimonials\Model\Crudimage;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\MediaStorage\Model\File\Uploader;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Image\Adapter\AbstractAdapter;
use Magento\Framework\Filesystem;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Directory\ReadInterface;

class SaveTest extends TestCase
{
    private $contextMock;
    private $crudimageFactoryMock;
    private $crudimageMock;
    private $uploaderFactoryMock;
    private $uploaderMock;
    private $adapterFactoryMock;
    private $adapterMock;
    private $filesystemMock;
    private $requestMock;
    private $messageManagerMock;
    private $resultRedirectFactoryMock;
    private $resultRedirectMock;
    private $mediaDirectoryMock;

    protected function setUp(): void
    {
        $this->contextMock = $this->createMock(Context::class);
        $this->crudimageFactoryMock = $this->createMock(CrudimageFactory::class);
        $this->crudimageMock = $this->createMock(Crudimage::class);
        $this->uploaderFactoryMock = $this->createMock(UploaderFactory::class);
        $this->uploaderMock = $this->createMock(Uploader::class);
        $this->adapterFactoryMock = $this->createMock(AdapterFactory::class);
        $this->adapterMock = $this->createMock(AbstractAdapter::class);
        $this->filesystemMock = $this->createMock(Filesystem::class);
        $this->requestMock = $this->createMock(RequestInterface::class);
        $this->messageManagerMock = $this->createMock(ManagerInterface::class);
        $this->resultRedirectFactoryMock = $this->createMock(RedirectFactory::class);
        $this->resultRedirectMock = $this->createMock(Redirect::class);
        $this->mediaDirectoryMock = $this->createMock(ReadInterface::class);

        $this->contextMock->method('getRequest')->willReturn($this->requestMock);
        $this->contextMock->method('getMessageManager')->willReturn($this->messageManagerMock);
        $this->contextMock->method('getResultRedirectFactory')->willReturn($this->resultRedirectFactoryMock);
        $this->resultRedirectFactoryMock->method('create')->willReturn($this->resultRedirectMock);
    }

    private function getSaveInstance(): Save
    {
        $instance = new Save(
            $this->contextMock,
            $this->crudimageFactoryMock,
            $this->uploaderFactoryMock,
            $this->adapterFactoryMock,
            $this->filesystemMock
        );
        // Set resultRedirectFactory property if needed
        $reflection = new \ReflectionProperty($instance, 'resultRedirectFactory');
        $reflection->setAccessible(true);
        $reflection->setValue($instance, $this->resultRedirectFactoryMock);
        return $instance;
    }

    public function testExecuteWithoutImageUploadAndSuccessSave()
    {
        $params = [
            'company_name' => 'Acme',
            'name' => 'John Doe',
            'message' => 'Test',
            'post' => 'CEO',
            'profile_pic' => null,
            'status' => 1
        ];

        $this->requestMock->expects($this->once())
            ->method('getParams')
            ->willReturn($params);

        $this->crudimageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->crudimageMock);

        $this->crudimageMock->expects($this->once())
            ->method('setData')
            ->with($params);

        $this->crudimageMock->expects($this->once())
            ->method('save');

        $this->messageManagerMock->expects($this->once())
            ->method('addSuccessMessage')
            ->with(__('You saved the data.'));

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('crudimage')
            ->willReturnSelf();

        $result = $this->getSaveInstance()->execute();
        $this->assertSame($this->resultRedirectMock, $result);
    }

    public function testExecuteSaveThrowsException()
    {
        $params = [
            'company_name' => 'Acme',
            'name' => 'John Doe',
            'message' => 'Test',
            'post' => 'CEO',
            'profile_pic' => null,
            'status' => 1
        ];

        $this->requestMock->expects($this->once())
            ->method('getParams')
            ->willReturn($params);

        $this->crudimageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->crudimageMock);

        $this->crudimageMock->expects($this->once())
            ->method('setData')
            ->with($params);

        $this->crudimageMock->expects($this->once())
            ->method('save')
            ->willThrowException(new \Exception('Save error'));

        $this->messageManagerMock->expects($this->once())
            ->method('addErrorMessage')
            ->with(__('Data was not saved. Error: %1', 'Save error'));

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('crudimage')
            ->willReturnSelf();

        $result = $this->getSaveInstance()->execute();
        $this->assertSame($this->resultRedirectMock, $result);
    }

    public function testExecuteWithImageUploadSuccess()
    {
        $params = [
            'company_name' => 'Acme',
            'name' => 'John Doe',
            'message' => 'Test',
            'post' => 'CEO',
            'profile_pic' => null,
            'status' => 1
        ];

        // Simulate $_FILES superglobal
        $_FILES['image'] = [
            'name' => 'test.jpg'
        ];

        $this->requestMock->expects($this->once())
            ->method('getParams')
            ->willReturn($params);

        $this->uploaderFactoryMock->expects($this->once())
            ->method('create')
            ->with(['fileId' => 'image'])
            ->willReturn($this->uploaderMock);

        $this->uploaderMock->expects($this->once())->method('setAllowedExtensions')->with(['jpg', 'jpeg', 'gif', 'png'])->willReturnSelf();
        $this->adapterFactoryMock->expects($this->once())->method('create')->willReturn($this->adapterMock);
        $this->uploaderMock->expects($this->once())->method('addValidateCallback')->with('custom_image_upload', $this->adapterMock, 'validateUploadFile')->willReturnSelf();
        $this->uploaderMock->expects($this->once())->method('setAllowRenameFiles')->with(true)->willReturnSelf();
        $this->uploaderMock->expects($this->once())->method('setFilesDispersion')->with(true)->willReturnSelf();

        $this->filesystemMock->expects($this->once())
            ->method('getDirectoryRead')
            ->with(DirectoryList::MEDIA)
            ->willReturn($this->mediaDirectoryMock);

        $this->mediaDirectoryMock->expects($this->once())
            ->method('getAbsolutePath')
            ->with('kiwicommerce/testimonials')
            ->willReturn('/tmp/kiwicommerce/testimonials');

        $this->uploaderMock->expects($this->once())
            ->method('save')
            ->with('/tmp/kiwicommerce/testimonials')
            ->willReturn(['file' => '/test.jpg']);

        $this->crudimageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->crudimageMock);

        $this->crudimageMock->expects($this->once())
            ->method('setData')
            ->with($this->callback(function ($data) {
                return isset($data['image']) && $data['image'] === 'kiwicommerce/testimonials/test.jpg';
            }));

        $this->crudimageMock->expects($this->once())
            ->method('save');

        $this->messageManagerMock->expects($this->once())
            ->method('addSuccessMessage')
            ->with(__('You saved the data.'));

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('crudimage')
            ->willReturnSelf();

        $result = $this->getSaveInstance()->execute();
        $this->assertSame($this->resultRedirectMock, $result);

        unset($_FILES['image']);
    }
}