<?php
// filepath: app/code/KiwiCommerce/Testimonials/Test/Unit/Controller/Index/IndexTest.php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Test\Unit\Controller\Index;

use KiwiCommerce\Testimonials\Controller\Index\Index;
use PHPUnit\Framework\TestCase;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ViewInterface;

class IndexTest extends TestCase
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
        $controller = $this->getMockBuilder(Index::class)
            ->setConstructorArgs([$this->contextMock])
            ->onlyMethods(['_view'])
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