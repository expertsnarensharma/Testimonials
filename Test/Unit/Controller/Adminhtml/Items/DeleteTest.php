<?php
// filepath: app/code/KiwiCommerce/Testimonials/Test/Unit/Controller/Adminhtml/Items/DeleteTest.php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Test\Unit\Controller\Adminhtml\Items;

use KiwiCommerce\Testimonials\Controller\Adminhtml\Items\Delete;
use PHPUnit\Framework\TestCase;
use Magento\Backend\App\Action\Context;
use KiwiCommerce\Testimonials\Model\CrudimageFactory;
use KiwiCommerce\Testimonials\Model\Crudimage;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Psr\Log\LoggerInterface;
use Magento\Framework\Exception\LocalizedException;

class DeleteTest extends TestCase
{
    private $contextMock;
    private $crudimageFactoryMock;
    private $crudimageMock;
    private $loggerMock;
    private $resultRedirectFactoryMock;
    private $resultRedirectMock;
    private $messageManagerMock;
    private $requestMock;
    private $resultForwardFactoryMock;
    private $resultPageFactoryMock;
    private $directoryListMock;
    private $uploaderFactoryMock;
    private $adapterFactoryMock;
    private $filesystemMock;
    private $fileMock;

    protected function setUp(): void
    {
        $this->crudimageFactoryMock = $this->createMock(CrudimageFactory::class);
        $this->crudimageMock = $this->createMock(Crudimage::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->resultRedirectFactoryMock = $this->createMock(RedirectFactory::class);
        $this->resultRedirectMock = $this->createMock(Redirect::class);
        $this->messageManagerMock = $this->createMock(ManagerInterface::class);
        $this->requestMock = $this->createMock(RequestInterface::class);
        $this->resultForwardFactoryMock = $this->createMock(ForwardFactory::class);
        $this->resultPageFactoryMock = $this->createMock(PageFactory::class);
        $this->directoryListMock = $this->createMock(DirectoryList::class);
        $this->uploaderFactoryMock = $this->createMock(UploaderFactory::class);
        $this->adapterFactoryMock = $this->createMock(AdapterFactory::class);
        $this->filesystemMock = $this->createMock(Filesystem::class);
        $this->fileMock = $this->createMock(File::class);

        $this->contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getResultRedirectFactory', 'getMessageManager', 'getRequest'])
            ->getMock();

        $this->contextMock->method('getResultRedirectFactory')->willReturn($this->resultRedirectFactoryMock);
        $this->contextMock->method('getMessageManager')->willReturn($this->messageManagerMock);
        $this->contextMock->method('getRequest')->willReturn($this->requestMock);

        $this->resultRedirectFactoryMock->method('create')->willReturn($this->resultRedirectMock);
    }

    public function getDeleteInstance(): Delete
    {
        return new Delete(
            $this->contextMock,
            $this->crudimageFactoryMock,
            $this->loggerMock,
            $this->resultForwardFactoryMock,
            $this->resultPageFactoryMock,
            $this->directoryListMock,
            $this->uploaderFactoryMock,
            $this->adapterFactoryMock,
            $this->filesystemMock,
            $this->fileMock
        );
    }

    public function testExecuteDeletesItemSuccessfully()
    {
        $this->requestMock->method('getParam')->with('id')->willReturn(5);

        $this->crudimageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->crudimageMock);

        $this->crudimageMock->expects($this->once())
            ->method('load')
            ->with(5)
            ->willReturnSelf();

        $this->crudimageMock->expects($this->once())
            ->method('delete')
            ->willReturnSelf();

        $this->messageManagerMock->expects($this->once())
            ->method('addSuccessMessage')
            ->with(__('You deleted the item.'));

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/')
            ->willReturnSelf();

        $controller = $this->getDeleteInstance();
        $result = $controller->execute();
        $this->assertSame($this->resultRedirectMock, $result);
    }

    public function testExecuteHandlesLocalizedException()
    {
        $this->requestMock->method('getParam')->with('id')->willReturn(7);

        $this->crudimageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->crudimageMock);

        $this->crudimageMock->expects($this->once())
            ->method('load')
            ->with(7)
            ->willReturnSelf();

        $this->crudimageMock->expects($this->once())
            ->method('delete')
            ->willThrowException(new LocalizedException(__('Delete error')));

        $this->messageManagerMock->expects($this->once())
            ->method('addErrorMessage')
            ->with('Delete error');

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/')
            ->willReturnSelf();

        $controller = $this->getDeleteInstance();
        $result = $controller->execute();
        $this->assertSame($this->resultRedirectMock, $result);
    }

    public function testExecuteHandlesGenericException()
    {
        $this->requestMock->method('getParam')->with('id')->willReturn(9);

        $this->crudimageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->crudimageMock);

        $this->crudimageMock->expects($this->once())
            ->method('load')
            ->with(9)
            ->willReturnSelf();

        $this->crudimageMock->expects($this->once())
            ->method('delete')
            ->willThrowException(new \Exception('Some error'));

        $this->messageManagerMock->expects($this->once())
            ->method('addErrorMessage')
            ->with(__("We can't delete item right now. Please review the log and try again."));

        $this->loggerMock->expects($this->once())
            ->method('critical')
            ->with($this->isInstanceOf(\Exception::class));

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/edit', ['id' => 9])
            ->willReturnSelf();

        $controller = $this->getDeleteInstance();
        $result = $controller->execute();
        $this->assertSame($this->resultRedirectMock, $result);
    }

    public function testExecuteHandlesMissingId()
    {
        $this->requestMock->method('getParam')->with('id')->willReturn(null);

        $this->messageManagerMock->expects($this->once())
            ->method('addErrorMessage')
            ->with(__("We can't find an item to delete."));

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/')
            ->willReturnSelf();

        $controller = $this->getDeleteInstance();
        $result = $controller->execute();
        $this->assertSame($this->resultRedirectMock, $result);
    }
}