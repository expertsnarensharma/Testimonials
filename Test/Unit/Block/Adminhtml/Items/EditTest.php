<?php
// filepath: app/code/KiwiCommerce/Testimonials/Test/Unit/Block/Adminhtml/Items/EditTest.php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Test\Unit\Block\Adminhtml\Items;

use KiwiCommerce\Testimonials\Block\Adminhtml\Items\Edit;
use PHPUnit\Framework\TestCase;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;
use Magento\Framework\Phrase;

class EditTest extends TestCase
{
    private $contextMock;
    private $registryMock;

    protected function setUp(): void
    {
        $this->contextMock = $this->createMock(Context::class);
        $this->registryMock = $this->createMock(Registry::class);
    }

    public function testConstructSetsCoreRegistry()
    {
        $block = new Edit($this->contextMock, $this->registryMock, []);
        $reflection = new \ReflectionProperty($block, '_coreRegistry');
        $reflection->setAccessible(true);
        $this->assertSame($this->registryMock, $reflection->getValue($block));
    }

    public function testGetHeaderTextReturnsEditText()
    {
        $itemMock = $this->getMockBuilder(\Magento\Framework\DataObject::class)
            ->onlyMethods(['getId', 'getName'])
            ->getMock();
        $itemMock->method('getId')->willReturn(1);
        $itemMock->method('getName')->willReturn('Test Name');

        $this->registryMock->method('registry')
            ->with('current_kiwicommerce_testimonials_items')
            ->willReturn($itemMock);

        $block = $this->getMockBuilder(Edit::class)
            ->setConstructorArgs([$this->contextMock, $this->registryMock, []])
            ->onlyMethods(['escapeHtml'])
            ->getMock();

        $block->expects($this->once())
            ->method('escapeHtml')
            ->with('Test Name')
            ->willReturn('Test Name');

        $result = $block->getHeaderText();
        $this->assertInstanceOf(Phrase::class, $result);
        $this->assertStringContainsString("Edit Item", (string)$result);
    }

    public function testGetHeaderTextReturnsNewItemText()
    {
        $this->registryMock->method('registry')
            ->with('current_kiwicommerce_testimonials_items')
            ->willReturn(null);

        $block = new Edit($this->contextMock, $this->registryMock, []);
        $result = $block->getHeaderText();
        $this->assertInstanceOf(Phrase::class, $result);
        $this->assertEquals('New Item', (string)$result);
    }
}