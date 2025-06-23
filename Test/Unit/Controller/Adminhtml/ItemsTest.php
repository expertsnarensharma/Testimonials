<?php
// filepath: app/code/KiwiCommerce/Testimonials/Test/Unit/Controller/Adminhtml/ItemsTest.php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Test\Unit\Controller\Adminhtml;

use KiwiCommerce\Testimonials\Controller\Adminhtml\Items;
use PHPUnit\Framework\TestCase;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File;

class ItemsTest extends TestCase
{
    private $contextMock;
    private $resultForwardFactoryMock;
    private $resultPageFactoryMock;
    private $directoryListMock;
    private $uploaderFactoryMock;
    private $adapterFactoryMock;
    private $filesystemMock;
    private $fileMock;

    protected function setUp(): void
    {
        $this->contextMock = $this->createMock(Context::class);
        $this->resultForwardFactoryMock = $this->createMock(ForwardFactory::class);
        $this->resultPageFactoryMock = $this->createMock(PageFactory::class);
        $this->directoryListMock = $this->createMock(DirectoryList::class);
        $this->uploaderFactoryMock = $this->createMock(UploaderFactory::class);
        $this->adapterFactoryMock = $this->createMock(AdapterFactory::class);
        $this->filesystemMock = $this->createMock(Filesystem::class);
        $this->fileMock = $this->createMock(File::class);
    }

    public function testConstructorSetsProperties()
    {
        $items = $this->getMockForAbstractClass(
            Items::class,
            [
                $this->contextMock,
                $this->resultForwardFactoryMock,
                $this->resultPageFactoryMock,
                $this->directoryListMock,
                $this->uploaderFactoryMock,
                $this->adapterFactoryMock,
                $this->filesystemMock,
                $this->fileMock
            ]
        );

        $this->assertSame($this->resultForwardFactoryMock, $items->resultForwardFactory);
        $this->assertSame($this->resultPageFactoryMock, $items->resultPageFactory);
        $this->assertSame($this->directoryListMock, $items->directoryList);
        $this->assertSame($this->uploaderFactoryMock, $items->uploaderFactory);
        $this->assertSame($this->adapterFactoryMock, $items->adapterFactory);
        $this->assertSame($this->filesystemMock, $items->filesystem);
        $this->assertSame($this->fileMock, $items->file);
    }

    public function testIsAllowedReturnsTrueWhenAuthorized()
    {
        $items = $this->getMockForAbstractClass(
            Items::class,
            [
                $this->contextMock,
                $this->resultForwardFactoryMock,
                $this->resultPageFactoryMock,
                $this->directoryListMock,
                $this->uploaderFactoryMock,
                $this->adapterFactoryMock,
                $this->filesystemMock,
                $this->fileMock
            ]
        );

        $authorizationMock = $this->getMockBuilder(\Magento\Framework\AuthorizationInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['isAllowed'])
            ->getMock();
        $authorizationMock->expects($this->once())
            ->method('isAllowed')
            ->with('KiwiCommerce_Testimonials::items')
            ->willReturn(true);

        $reflection = new \ReflectionProperty($items, '_authorization');
        $reflection->setAccessible(true);
        $reflection->setValue($items, $authorizationMock);

        $this->assertTrue($items->_isAllowed());
    }
}