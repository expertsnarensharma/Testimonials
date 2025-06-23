<?php
// filepath: app/code/KiwiCommerce/Testimonials/Test/Unit/Controller/Index/ListActionTest.php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Test\Unit\Controller\Index;

use KiwiCommerce\Testimonials\Controller\Index\ListAction;
use PHPUnit\Framework\TestCase;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ViewInterface;

class ListActionTest extends TestCase
{
    private $contextMock;
    private $viewMock;

    protected function setUp(): void
    {
        $this->contextMock = $this->createMock(Context::class);
        $this->viewMock = $this->createMock(ViewInterface::class);
    }

    public function testExecuteLoadsAndRendersLayout()
    {
        $controller = $this->getMockBuilder(ListAction::class)
            ->setConstructorArgs([$this->contextMock])
            ->onlyMethods([])
            ->getMock();

        // Set the protected _view property
        $reflection = new \ReflectionProperty($controller, '_view');
        $reflection->setAccessible(true);
        $reflection->setValue($controller, $this->viewMock);

        $this->viewMock->expects($this->once())->method('loadLayout');
        $this->viewMock->expects($this->once())->method('getLayout')->willReturnSelf();
        $this->viewMock->expects($this->once())->method('initMessages');
        $this->viewMock->expects($this->once())->method('renderLayout');

        $controller->execute();
    }
}