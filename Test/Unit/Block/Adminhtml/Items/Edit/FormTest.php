<?php
// filepath: app/code/KiwiCommerce/Testimonials/Test/Unit/Block/Adminhtml/Items/Edit/FormTest.php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Test\Unit\Block\Adminhtml\Items\Edit;

use KiwiCommerce\Testimonials\Block\Adminhtml\Items\Edit\Form;
use PHPUnit\Framework\TestCase;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Data\Form as MagentoForm;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;

class FormTest extends TestCase
{
    private $contextMock;
    private $registryMock;
    private $formFactoryMock;
    private $formMock;

    protected function setUp(): void
    {
        $this->contextMock = $this->createMock(Context::class);
        $this->registryMock = $this->createMock(Registry::class);
        $this->formFactoryMock = $this->createMock(FormFactory::class);
        $this->formMock = $this->createMock(MagentoForm::class);
    }

    public function testConstructSetsIdAndTitle()
    {
        $block = $this->getMockBuilder(Form::class)
            ->setConstructorArgs([
                $this->contextMock,
                $this->registryMock,
                $this->formFactoryMock
            ])
            ->onlyMethods(['setId', 'setTitle'])
            ->getMock();

        $block->expects($this->once())->method('setId')->with('crudimage_items_form')->willReturnSelf();
        $block->expects($this->once())->method('setTitle')->with(__('Item Information'))->willReturnSelf();

        $reflection = new \ReflectionMethod($block, '_construct');
        $reflection->setAccessible(true);
        $reflection->invoke($block);
    }

    public function testPrepareFormSetsFormAndReturnsParent()
    {
        $this->formFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->formMock);

        $this->formMock->expects($this->once())
            ->method('setUseContainer')
            ->with(true);

        $block = $this->getMockBuilder(Form::class)
            ->setConstructorArgs([
                $this->contextMock,
                $this->registryMock,
                $this->formFactoryMock
            ])
            ->onlyMethods(['setForm', 'getUrl', 'parent::_prepareForm'])
            ->getMock();

        $block->expects($this->once())->method('setForm')->with($this->formMock)->willReturnSelf();
        $block->expects($this->once())->method('getUrl')->with('kiwicommerce_testimonials/items/save')->willReturn('some_url');
        $block->expects($this->once())->method('parent::_prepareForm')->willReturnSelf();

        $reflection = new \ReflectionMethod($block, '_prepareForm');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($block);

        $this->assertInstanceOf(Form::class, $result);
    }
}