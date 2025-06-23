<?php
// filepath: app/code/KiwiCommerce/Testimonials/Test/Unit/Controller/Adminhtml/Items/NewActionTest.php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Test\Unit\Controller\Adminhtml\Items;

use KiwiCommerce\Testimonials\Controller\Adminhtml\Items\NewAction;
use PHPUnit\Framework\TestCase;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Backend\Model\View\Result\Forward;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File;

class NewActionTest extends TestCase
{
    private $contextMock;
    private $resultForwardFactoryMock;
    private $resultForwardMock;
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
        $this->resultForwardMock = $this->createMock(Forward::class);
        $this->resultPageFactoryMock = $this->createMock(PageFactory::class);
        $this->directoryListMock = $this->createMock(DirectoryList::class);
        $this->uploaderFactoryMock = $this->createMock(UploaderFactory::class);
        $this->adapterFactoryMock = $this->createMock(AdapterFactory::class);
        $this->filesystemMock = $this->createMock(Filesystem::class);
        $this->fileMock = $this->createMock(File::class);
    }

    public function testExecuteForwardsToEdit()
    {
        $this->resultForwardFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->resultForwardMock);

        $this->resultForwardMock->expects($this->once())
            ->method('forward')
            ->with('edit')
            ->willReturnSelf();

        $controller = new NewAction(
            $this->contextMock,
            $this->resultForwardFactoryMock,
            $this->resultPageFactoryMock,
            $this->directoryListMock,
            $this->uploaderFactoryMock,
            $this->adapterFactoryMock,
            $this->filesystemMock,
            $this->fileMock
        );

        $result = $controller->execute();
        $this->assertSame($this->resultForwardMock, $result);
    }
}