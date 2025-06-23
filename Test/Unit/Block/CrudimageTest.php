<?php
// filepath: app/code/KiwiCommerce/Testimonials/Test/Unit/Block/CrudimageTest.php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Test\Unit\Block;

use KiwiCommerce\Testimonials\Block\Crudimage;
use PHPUnit\Framework\TestCase;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Page\Config as PageConfig;

class CrudimageTest extends TestCase
{
    private $contextMock;
    private $pageConfigMock;

    protected function setUp(): void
    {
        $this->pageConfigMock = $this->createMock(PageConfig::class);

        $this->contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getPageConfig'])
            ->getMock();

        $this->contextMock->method('getPageConfig')->willReturn($this->pageConfigMock);
    }

    public function testPrepareLayoutSetsPageTitle()
    {
        $this->pageConfigMock->expects($this->once())
            ->method('getTitle')
            ->willReturnSelf();

        $this->pageConfigMock->expects($this->once())
            ->method('set')
            ->with(__('KiwiCommerce Testimonial Module'));

        $block = new Crudimage($this->contextMock);

        $result = $block->_prepareLayout();
        $this->assertInstanceOf(Crudimage::class, $result);
    }
}