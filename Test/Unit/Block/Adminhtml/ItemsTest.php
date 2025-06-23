<?php
// filepath: app/code/KiwiCommerce/Testimonials/Test/Unit/Block/Adminhtml/ItemsTest.php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Test\Unit\Block\Adminhtml;

use KiwiCommerce\Testimonials\Block\Adminhtml\Items;
use PHPUnit\Framework\TestCase;
use Magento\Backend\Block\Widget\Context;

class ItemsTest extends TestCase
{
    private $contextMock;

    protected function setUp(): void
    {
        $this->contextMock = $this->createMock(Context::class);
    }

    public function testConstructSetsProperties()
    {
        $block = $this->getMockBuilder(Items::class)
            ->setConstructorArgs([$this->contextMock, []])
            ->onlyMethods(['setTemplate'])
            ->getMock();

        $reflection = new \ReflectionMethod($block, '_construct');
        $reflection->setAccessible(true);
        $reflection->invoke($block);

        $this->assertEquals('items', $block->_controller);
        $this->assertEquals(__('Items'), $block->_headerText);
        $this->assertEquals(__('Add New Item'), $block->_addButtonLabel);
    }
}