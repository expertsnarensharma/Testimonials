<?php
// filepath: app/code/KiwiCommerce/Testimonials/Test/Unit/Controller/Adminhtml/Items/SaveTest.php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Test\Unit\Controller\Adminhtml\Items;

use KiwiCommerce\Testimonials\Controller\Adminhtml\Items\Save;
use PHPUnit\Framework\TestCase;
use Magento\Backend\App\Action\Context;
use KiwiCommerce\Testimonials\Model\CrudimageFactory;
use KiwiCommerce\Testimonials\Model\Crudimage;
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
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Request\Http as HttpRequest;

if (!function_exists('__')) {
    function __($string)
    {
        return $string;
    }
}

class SaveTest extends TestCase
{
    private $contextMock;
    private $resultFactoryMock;
    private $resultRedirectMock;
    private $crudimageFactoryMock;
    private $crudimageMock;
    private $uploaderFactoryMock;
    private $adapterFactoryMock;
    private $filesystemMock;
    private $directoryListMock;
    private $fileMock;
    private $backendSessionMock;
    private $loggerMock;
    private $resultForwardFactoryMock;
    private $resultPageFactoryMock;
    private $messageManagerMock;
    private $requestMock;

    protected function setUp(): void
    {
        $this->contextMock = $this->createMock(Context::class);
        $this->resultFactoryMock = $this->createMock(ResultFactory::class);
        $this->resultRedirectMock = $this->createMock(Redirect::class);
        $this->crudimageFactoryMock = $this->createMock(CrudimageFactory::class);
        $this->crudimageMock = $this->createMock(Crudimage::class);
        $this->uploaderFactoryMock = $this->createMock(UploaderFactory::class);
        $this->adapterFactoryMock = $this->createMock(AdapterFactory::class);
        $this->filesystemMock = $this->createMock(Filesystem::class);
        $this->directoryListMock = $this->createMock(DirectoryList::class);
        $this->fileMock = $this->createMock(File::class);
        $this->backendSessionMock = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->addMethods(['setPageData'])
            ->getMock();
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->resultForwardFactoryMock = $this->createMock(ForwardFactory::class);
        $this->resultPageFactoryMock = $this->createMock(PageFactory::class);
        $this->messageManagerMock = $this->createMock(ManagerInterface::class);
        $this->requestMock = $this->createMock(HttpRequest::class);

        $this->contextMock->method('getMessageManager')->willReturn($this->messageManagerMock);
        $this->contextMock->method('getRequest')->willReturn($this->requestMock);
    }

    private function getSaveInstance(): Save
    {
        $instance = new Save(
            $this->contextMock,
            $this->resultForwardFactoryMock,
            $this->resultPageFactoryMock,
            $this->directoryListMock,
            $this->uploaderFactoryMock,
            $this->adapterFactoryMock,
            $this->filesystemMock,
            $this->fileMock,
            $this->crudimageFactoryMock,
            $this->backendSessionMock,
            $this->loggerMock
        );

        $reflection = new \ReflectionProperty($instance, 'resultFactory');
        $reflection->setAccessible(true);
        $reflection->setValue($instance, $this->resultFactoryMock);
        return $instance;
    }

    public function testExecuteNoPostData()
    {
        $this->resultFactoryMock->expects($this->once())
            ->method('create')
            ->with(ResultFactory::TYPE_REDIRECT)
            ->willReturn($this->resultRedirectMock);

        $this->requestMock->expects($this->once())
            ->method('getPostValue')
            ->willReturn(null);

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('kiwicommerce_testimonials/*/')
            ->willReturnSelf();

        $controller = $this->getSaveInstance();
        $result = $controller->execute();
        $this->assertSame($this->resultRedirectMock, $result);
    }

    public function testExecuteSuccess()
    {

        $uploaderMock = $this->createMock(\Magento\MediaStorage\Model\File\Uploader::class);

        $this->uploaderFactoryMock->expects($this->once())
            ->method('create')
            ->with(['fileId' => 'profile_pic'])
            ->willReturn($uploaderMock);

        $uploaderMock->expects($this->once())
            ->method('save')
            ->willReturn(['file' => '/test.jpg']);


        $postData = [
            'company_name' => 'Acme',
            'name' => 'John Doe',
            'message' => 'Test',
            'post' => 'CEO',
            'status' => 1
        ];

        $_FILES['profile_pic'] = ['name' => '', 'type' => '', 'tmp_name' => '', 'error' => 4, 'size' => 0];

        $this->resultFactoryMock->expects($this->once())
            ->method('create')
            ->with(ResultFactory::TYPE_REDIRECT)
            ->willReturn($this->resultRedirectMock);

        $this->requestMock->expects($this->any())
            ->method('getPostValue')
            ->willReturn($postData);

        $this->crudimageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->crudimageMock);

        $this->crudimageMock->method('getId')->willReturn(123);

        $this->requestMock->expects($this->any())
            ->method('getParam')
            ->willReturnCallback(function ($key, $default = null) {
                if ($key === 'id') {
                    return null;
                }
                if ($key === 'back') {
                    return false;
                }
                return $default;
            });

        $this->crudimageMock->expects($this->once())
            ->method('setData')
            ->with($postData);

        $this->crudimageMock->expects($this->once())
            ->method('save');

        $this->messageManagerMock->expects($this->once())
            ->method('addSuccessMessage')
            ->with(__('You saved the item.'));

        $this->backendSessionMock->expects($this->atLeastOnce())
            ->method('setPageData')
            ->with($postData);

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with($this->logicalOr(
                $this->equalTo('kiwicommerce_testimonials/*/edit'),
                $this->equalTo('kiwicommerce_testimonials/*/')
            ))
            ->willReturnSelf();

        $controller = $this->getSaveInstance();
        $result = $controller->execute();
        $this->assertSame($this->resultRedirectMock, $result);
    }

    public function testExecuteLocalizedException()
    {
        $postData = [
            'company_name' => 'Acme',
            'name' => 'John Doe',
            'message' => 'Test',
            'post' => 'CEO',
            'profile_pic' => null,
            'status' => 1
        ];

        $_FILES['profile_pic'] = ['name' => '', 'type' => '', 'tmp_name' => '', 'error' => 4, 'size' => 0];

        $this->resultFactoryMock->expects($this->once())
            ->method('create')
            ->with(ResultFactory::TYPE_REDIRECT)
            ->willReturn($this->resultRedirectMock);

        $this->requestMock->expects($this->any())
            ->method('getPostValue')
            ->willReturn($postData);

        $this->crudimageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->crudimageMock);

        $this->requestMock->expects($this->atLeastOnce())
            ->method('getParam')
            ->willReturnCallback(function ($key, $default = null) {
                if ($key === 'id') {
                    return null;
                }
                return $default;
            });

        $this->crudimageMock->expects($this->once())
            ->method('setData')
            ->with($postData);

        $this->backendSessionMock->expects($this->atLeastOnce())
            ->method('setPageData')
            ->with($postData);

        $this->crudimageMock->expects($this->once())
            ->method('save')
            ->willThrowException(new LocalizedException(__('Save error')));

        $this->messageManagerMock->expects($this->once())
            ->method('addErrorMessage')
            ->with(__('Something went wrong while saving the item data. Please review the error log.'));

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('kiwicommerce_testimonials/*/new')
            ->willReturnSelf();

        $controller = $this->getSaveInstance();
        $result = $controller->execute();
        $this->assertSame($this->resultRedirectMock, $result);
    }

    public function testExecuteGenericException()
    {
        $postData = [
            'company_name' => 'Acme',
            'name' => 'John Doe',
            'message' => 'Test',
            'post' => 'CEO',
            'profile_pic' => null,
            'status' => 1
        ];

        $_FILES['profile_pic'] = ['name' => '', 'type' => '', 'tmp_name' => '', 'error' => 4, 'size' => 0];

        $this->resultFactoryMock->expects($this->once())
            ->method('create')
            ->with(ResultFactory::TYPE_REDIRECT)
            ->willReturn($this->resultRedirectMock);

        $this->requestMock->expects($this->any())
            ->method('getPostValue')
            ->willReturn($postData);

        $this->crudimageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->crudimageMock);

        $this->requestMock->expects($this->atLeastOnce())
            ->method('getParam')
            ->willReturnCallback(function ($key, $default = null) {
                if ($key === 'id') {
                    return 10;
                }
                return $default;
            });

        $this->crudimageMock->expects($this->any())
            ->method('setData');

        $this->backendSessionMock->expects($this->atLeastOnce())
            ->method('setPageData')
            ->with($postData);

        $this->crudimageMock->expects($this->once())
            ->method('save')
            ->willThrowException(new \Exception('Some error'));

        $this->messageManagerMock->expects($this->once())
            ->method('addErrorMessage')
            ->with(__('Something went wrong while saving the item data. Please review the error log.'));

        $this->loggerMock->expects($this->once())
            ->method('critical')
            ->with($this->isInstanceOf(\Exception::class));

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('kiwicommerce_testimonials/*/edit', ['id' => 10])
            ->willReturnSelf();

        $controller = $this->getSaveInstance();
        $result = $controller->execute();
        $this->assertSame($this->resultRedirectMock, $result);
    }

    public function testExecuteWithImageUploadFailure()
{
    $postData = [
        'company_name' => 'Acme',
        'name' => 'John Doe',
        'message' => 'Test',
        'post' => 'CEO',
        'status' => 1
    ];

    $_FILES['profile_pic'] = ['name' => 'test.jpg', 'type' => 'image/jpeg', 'tmp_name' => '/tmp/php123', 'error' => 0, 'size' => 123];

    $this->resultFactoryMock->expects($this->once())
        ->method('create')
        ->with(ResultFactory::TYPE_REDIRECT)
        ->willReturn($this->resultRedirectMock);

    $this->requestMock->expects($this->any())
        ->method('getPostValue')
        ->willReturn($postData);

    $this->crudimageFactoryMock->expects($this->once())
        ->method('create')
        ->willReturn($this->crudimageMock);

    $uploaderMock = $this->getMockBuilder(\Magento\MediaStorage\Model\File\Uploader::class)
        ->disableOriginalConstructor()
        ->getMock();

    $this->uploaderFactoryMock->expects($this->once())
        ->method('create')
        ->willReturn($uploaderMock);

    $adapterMock = $this->getMockBuilder(\Magento\Framework\Image\Adapter\AdapterInterface::class)
        ->getMock();

    $this->adapterFactoryMock->expects($this->once())
        ->method('create')
        ->willReturn($adapterMock);

    $uploaderMock->expects($this->once())
        ->method('setAllowedExtensions')
        ->with(['jpg', 'jpeg', 'gif', 'png'])
        ->willReturnSelf();

    $uploaderMock->expects($this->once())
        ->method('addValidateCallback')
        ->with('custom_image_upload', $adapterMock, 'validateUploadFile')
        ->willReturnSelf();

    $uploaderMock->expects($this->once())
        ->method('setAllowRenameFiles')
        ->with(true)
        ->willReturnSelf();

    $uploaderMock->expects($this->once())
        ->method('setFilesDispersion')
        ->with(true)
        ->willReturnSelf();

    $mediaDirectoryMock = $this->getMockBuilder(\Magento\Framework\Filesystem\Directory\ReadInterface::class)
        ->disableOriginalConstructor()
        ->getMock();

    $this->filesystemMock->expects($this->once())
        ->method('getDirectoryRead')
        ->willReturn($mediaDirectoryMock);

    $mediaDirectoryMock->expects($this->once())
        ->method('getAbsolutePath')
        ->with('kiwicommerce/testimonials')
        ->willReturn('/tmp/kiwicommerce/testimonials');

    $uploaderMock->expects($this->once())
        ->method('save')
        ->willThrowException(new \Exception('Upload failed'));

    $this->messageManagerMock->expects($this->once())
        ->method('addErrorMessage')
        ->with('Image upload failed: Upload failed');

    $this->requestMock->expects($this->atLeastOnce())
        ->method('getParam')
        ->willReturn(null);

    $this->resultRedirectMock->expects($this->once())
        ->method('setPath')
        ->with('kiwicommerce_testimonials/*/new')
        ->willReturnSelf();

    $controller = $this->getSaveInstance();
    $result = $controller->execute();
    $this->assertSame($this->resultRedirectMock, $result);
}

public function testExecuteWithImageDelete()
{
    $postData = [
        'company_name' => 'Acme',
        'name' => 'John Doe',
        'message' => 'Test',
        'post' => 'CEO',
        'profile_pic' => [
            'delete' => 1,
            'value' => 'kiwicommerce/testimonials/test.jpg'
        ],
        'status' => 1
    ];

    $_FILES['profile_pic'] = ['name' => '', 'type' => '', 'tmp_name' => '', 'error' => 4, 'size' => 0];

    $this->resultFactoryMock->expects($this->once())
        ->method('create')
        ->with(ResultFactory::TYPE_REDIRECT)
        ->willReturn($this->resultRedirectMock);

    $this->requestMock->expects($this->any())
        ->method('getPostValue')
        ->willReturn($postData);

    $this->crudimageFactoryMock->expects($this->once())
        ->method('create')
        ->willReturn($this->crudimageMock);

    $mediaDirectoryMock = $this->getMockBuilder(\Magento\Framework\Filesystem\Directory\ReadInterface::class)
        ->disableOriginalConstructor()
        ->getMock();

    $this->filesystemMock->expects($this->once())
        ->method('getDirectoryRead')
        ->with($this->anything())
        ->willReturn($mediaDirectoryMock);

    $mediaDirectoryMock->expects($this->once())
        ->method('getAbsolutePath')
        ->willReturn('/tmp/');

    $this->fileMock->expects($this->once())
        ->method('isExists')
        ->with('/tmp/kiwicommerce/testimonials/test.jpg')
        ->willReturn(true);

    $this->fileMock->expects($this->once())
        ->method('deleteFile')
        ->with('/tmp/kiwicommerce/testimonials/test.jpg');

    $this->crudimageMock->expects($this->once())
        ->method('setData')
        ->with($this->callback(function ($data) {
            return $data['profile_pic'] === null;
        }));

    $this->crudimageMock->expects($this->once())
        ->method('save');

    $this->messageManagerMock->expects($this->once())
        ->method('addSuccessMessage')
        ->with('You saved the item.');

    $this->backendSessionMock->expects($this->atLeastOnce())
        ->method('setPageData');

    $this->requestMock->expects($this->any())
        ->method('getParam')
        ->willReturn(null);

    $this->resultRedirectMock->expects($this->once())
        ->method('setPath')
        ->with('kiwicommerce_testimonials/*/')
        ->willReturnSelf();

    $controller = $this->getSaveInstance();
    $result = $controller->execute();
    $this->assertSame($this->resultRedirectMock, $result);
}
}




