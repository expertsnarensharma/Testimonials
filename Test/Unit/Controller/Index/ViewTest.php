<?php
// filepath: app/code/KiwiCommerce/Testimonials/Test/Unit/Controller/Index/ViewTest.php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Test\Unit\Controller\Index;

use KiwiCommerce\Testimonials\Controller\Index\View;
use PHPUnit\Framework\TestCase;
use Magento\Framework\App\Action\Context;
use KiwiCommerce\Testimonials\Model\CrudimageFactory;
use KiwiCommerce\Testimonials\Model\Crudimage;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\App\ViewInterface;

class ViewTest extends TestCase
{
    private $contextMock;
    private $crudimageFactoryMock;
    private $crudimageMock;
    private $requestMock;
    private $viewMock;

    protected function setUp(): void
    {
        $this->contextMock = $this->createMock(Context::class);
        $this->crudimageFactoryMock = $this->createMock(CrudimageFactory::class);
        $this->crudimageMock = $this->createMock(Crudimage::class);
        $this->requestMock = $this->createMock(RequestInterface::class);
        $this->viewMock = $this->createMock(ViewInterface::class);

        $this->contextMock->method('getRequest')->willReturn($this->requestMock);
    }

    private function getViewInstance(): View
    {
        $instance = new View($this->contextMock, $this->crudimageFactoryMock);
        // Set the protected _view property
        $reflection = new \ReflectionProperty($instance, '_view');
        $reflection->setAccessible(true);
        $reflection->setValue($instance, $this->viewMock);
        return $instance;
    }

    public function testExecuteRendersLayoutWhenModelIsValid()
    {
        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('id')
            ->willReturn(5);

        $this->crudimageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->crudimageMock);

        $this->crudimageMock->expects($this->once())
            ->method('load')
            ->with(5)
            ->willReturnSelf();

        $this->crudimageMock->expects($this->once())
            ->method('getId')
            ->willReturn(5);

        $this->crudimageMock->expects($this->once())
            ->method('getStatus')
            ->willReturn(1);

        $this->viewMock->expects($this->once())->method('loadLayout');
        $this->viewMock->expects($this->once())->method('getLayout')->willReturnSelf();
        $this->viewMock->expects($this->once())->method('initMessages');
        $this->viewMock->expects($this->once())->method('renderLayout');

        $controller = $this->getViewInstance();
        $controller->execute();
    }

    public function testExecuteThrowsNotFoundExceptionIfModelNotFound()
    {
        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('id')
            ->willReturn(7);

        $this->crudimageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->crudimageMock);

        $this->crudimageMock->expects($this->once())
            ->method('load')
            ->with(7)
            ->willReturnSelf();

        $this->crudimageMock->expects($this->once())
            ->method('getId')
            ->willReturn(null);

        $controller = $this->getViewInstance();

        $this->expectException(NotFoundException::class);
        $controller->execute();
    }

    public function testExecuteThrowsNotFoundExceptionIfStatusIsNot1()
    {
        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('id')
            ->willReturn(8);

        $this->crudimageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->crudimageMock);

        $this->crudimageMock->expects($this->once())
            ->method('load')
            ->with(8)
            ->willReturnSelf();

        $this->crudimageMock->expects($this->once())
            ->method('getId')
            ->willReturn(8);

        $this->crudimageMock->expects($this->once())
            ->method('getStatus')
            ->willReturn(0);

        $controller = $this->getViewInstance();

        $this->expectException(NotFoundException::class);
        $controller->execute();
    }
}