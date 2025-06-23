<?php
// filepath: app/code/KiwiCommerce/Testimonials/Test/Unit/Block/Adminhtml/Items/Edit/TabsTest.php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Test\Unit\Block\Adminhtml\Items\Edit;

use KiwiCommerce\Testimonials\Block\Adminhtml\Items\Edit\Tabs;
use PHPUnit\Framework\TestCase;
use Magento\Backend\Block\Template\Context;

class TabsTest extends TestCase
{
    private $contextMock;

    protected function setUp(): void
    {
        $this->contextMock = $this->createMock(Context::class);
    }

    public function testConstructSetsProperties()
    {
        $block = $this->getMockBuilder(Tabs::class)
            ->setConstructorArgs([$this->contextMock, []])
            ->onlyMethods(['setId', 'setDestElementId', 'setTitle'])
            ->getMock();

        $block->expects($this->once())->method('setId')->with('kiwicommerce_testimonials_items_edit_tabs')->willReturnSelf();
        $block->expects($this->once())->method('setDestElementId')->with('edit_form')->willReturnSelf();
        $block->expects($this->once())->method('setTitle')->with(__('Item'))->willReturnSelf();

        $reflection = new \ReflectionMethod($block, '_construct');
        $reflection->setAccessible(true);
        $reflection->invoke($block);
    }
}