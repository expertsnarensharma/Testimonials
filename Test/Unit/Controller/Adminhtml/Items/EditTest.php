<?php
// filepath: app/code/KiwiCommerce/Testimonials/Test/Unit/Controller/Adminhtml/Items/EditTest.php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Test\Unit\Controller\Adminhtml\Items;

use KiwiCommerce\Testimonials\Controller\Adminhtml\Items\Edit;
use PHPUnit\Framework\TestCase;
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
use Magento\Backend\Model\Session;
use Magento\Framework\View\Result\Page;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Controller\Result\Redirect;
use KiwiCommerce\Testimonials\Model\Crudimage;

class EditTest extends TestCase
{
    private $contextMock;
    private $dataPersistorMock;
    private $resultForwardFactoryMock;
    private $resultPageFactoryMock;
    private $directoryListMock;
    private $uploaderFactoryMock;
    private $adapterFactoryMock;
    private $filesystemMock;
    private $fileMock;
    private $dateFilterMock;
    private $filterManagerMock;
    private $fileFactoryMock;
    private $crudimageFactoryMock;
    private $coreRegistryMock;
    private $backendSessionMock;
    private $requestMock;
    private $messageManagerMock;
    private $resultRedirectFactoryMock;
    private $resultRedirectMock;
    private $crudimageMock;
    private $pageMock;

    protected function setUp(): void
    {
        $this->contextMock = $this->createMock(Context::class);
        $this->dataPersistorMock = $this->createMock(DataPersistorInterface::class);
        $this->resultForwardFactoryMock = $this->createMock(ForwardFactory::class);
        $this->resultPageFactoryMock = $this->createMock(PageFactory::class);
        $this->directoryListMock = $this->createMock(DirectoryList::class);
        $this->uploaderFactoryMock = $this->createMock(UploaderFactory::class);
        $this->adapterFactoryMock = $this->createMock(AdapterFactory::class);
        $this->filesystemMock = $this->createMock(Filesystem::class);
        $this->fileMock = $this->createMock(File::class);
        $this->dateFilterMock = $this->createMock(Date::class);
        $this->filterManagerMock = $this->createMock(FilterManager::class);
        $this->fileFactoryMock = $this->createMock(FileFactory::class);
        $this->crudimageFactoryMock = $this->createMock(CrudimageFactory::class);
        $this->coreRegistryMock = $this->createMock(Registry::class);
        $this->backendSessionMock = $this->createMock(Session::class);
        $this->requestMock = $this->createMock(\Magento\Framework\App\RequestInterface::class);
        $this->messageManagerMock = $this->createMock(ManagerInterface::class);
        $this->resultRedirectFactoryMock = $this->createMock(RedirectFactory::class);
        $this->resultRedirectMock = $this->createMock(Redirect::class);
        $this->crudimageMock = $this->createMock(Crudimage::class);
        $this->pageMock = $this->createMock(Page::class);

        $this->contextMock->method('getRequest')->willReturn($this->requestMock);
        $this->contextMock->method('getMessageManager')->willReturn($this->messageManagerMock);
        $this->contextMock->method('getResultRedirectFactory')->willReturn($this->resultRedirectFactoryMock);
        $this->resultRedirectFactoryMock->method('create')->willReturn($this->resultRedirectMock);
    }

    private function getEditInstance(): Edit
    {
        return new Edit(
            $this->contextMock,
            $this->dataPersistorMock,
            $this->resultForwardFactoryMock,
            $this->resultPageFactoryMock,
            $this->directoryListMock,
            $this->uploaderFactoryMock,
            $this->adapterFactoryMock,
            $this->filesystemMock,
            $this->fileMock,
            $this->dateFilterMock,
            $this->filterManagerMock,
            $this->fileFactoryMock,
            $this->crudimageFactoryMock,
            $this->coreRegistryMock,
            $this->backendSessionMock
        );
    }

    public function testExecuteWithExistingIdAndModelFound()
    {
        $this->requestMock->method('getParam')->with('id')->willReturn(5);

        $this->crudimageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->crudimageMock);

        $this->crudimageMock->expects($this->once())
            ->method('load')->with(5)->willReturnSelf();
        $this->crudimageMock->expects($this->once())
            ->method('getId')->willReturn(5);

        $this->backendSessionMock->expects($this->once())
            ->method('getPageData')->with(true)->willReturn([]);

        $this->coreRegistryMock->expects($this->once())
            ->method('register')
            ->with('current_kiwicommerce_testimonials_items', $this->crudimageMock);

        $this->resultPageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->pageMock);

        $controller = $this->getEditInstance();
        $result = $controller->execute();
        $this->assertSame($this->pageMock, $result);
    }

    public function testExecuteWithExistingIdAndModelNotFound()
    {
        $this->requestMock->method('getParam')->with('id')->willReturn(7);

        $this->crudimageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->crudimageMock);

        $this->crudimageMock->expects($this->once())
            ->method('load')->with(7)->willReturnSelf();
        $this->crudimageMock->expects($this->once())
            ->method('getId')->willReturn(null);

        $this->messageManagerMock->expects($this->once())
            ->method('addErrorMessage')
            ->with(__('This item no longer exists.'));

        $this->resultRedirectFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->resultRedirectMock);

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('kiwicommerce_testimonials/*')
            ->willReturnSelf();

        $controller = $this->getEditInstance();
        $result = $controller->execute();
        $this->assertSame($this->resultRedirectMock, $result);
    }

    public function testExecuteWithNoId()
    {
        $this->requestMock->method('getParam')->with('id')->willReturn(0);

        $this->crudimageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->crudimageMock);

        $this->backendSessionMock->expects($this->once())
            ->method('getPageData')->with(true)->willReturn(['foo' => 'bar']);

        $this->crudimageMock->expects($this->once())
            ->method('addData')
            ->with(['foo' => 'bar']);

        $this->coreRegistryMock->expects($this->once())
            ->method('register')
            ->with('current_kiwicommerce_testimonials_items', $this->crudimageMock);

        $this->resultPageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->pageMock);

        $controller = $this->getEditInstance();
        $result = $controller->execute();
        $this->assertSame($this->pageMock, $result);
    }
}