<?php
// filepath: app/code/KiwiCommerce/Testimonials/Test/Unit/Block/CrudimageViewTest.php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Test\Unit\Block;

use KiwiCommerce\Testimonials\Block\CrudimageView;
use PHPUnit\Framework\TestCase;
use Magento\Framework\View\Element\Template\Context;
use KiwiCommerce\Testimonials\Model\CrudimageFactory;
use KiwiCommerce\Testimonials\Model\Crudimage;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Page\Config as PageConfig;
use Magento\Framework\View\Page\Title as PageTitle;

class CrudimageViewTest extends TestCase
{
    private $contextMock;
    private $crudimageFactoryMock;
    private $crudimageMock;
    private $filterProviderMock;
    private $requestMock;
    private $pageConfigMock;
    private $pageTitleMock;

    protected function setUp(): void
    {
        $this->crudimageFactoryMock = $this->createMock(CrudimageFactory::class);
        $this->crudimageMock = $this->createMock(Crudimage::class);
        $this->filterProviderMock = $this->createMock(FilterProvider::class);

        $this->requestMock = $this->createMock(RequestInterface::class);

        $this->pageTitleMock = $this->getMockBuilder(PageTitle::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['set'])
            ->getMock();

        $this->pageConfigMock = $this->getMockBuilder(PageConfig::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getTitle'])
            ->getMock();
        $this->pageConfigMock->method('getTitle')->willReturn($this->pageTitleMock);

        $this->contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getRequest', 'getPageConfig'])
            ->getMock();
        $this->contextMock->method('getRequest')->willReturn($this->requestMock);
        $this->contextMock->method('getPageConfig')->willReturn($this->pageConfigMock);
    }

    public function testPrepareLayoutSetsPageTitle()
    {
        $this->pageTitleMock->expects($this->once())
            ->method('set')
            ->with(__('KiwiCommerce Testimonial Module View Page'));

        $block = new CrudimageView(
            $this->contextMock,
            $this->crudimageFactoryMock,
            $this->filterProviderMock
        );

        $result = $block->_prepareLayout();
        $this->assertInstanceOf(CrudimageView::class, $result);
    }

    public function testGetSingleDataReturnsData()
    {
        $this->requestMock->method('getParam')->with('id')->willReturn(5);

        $this->crudimageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->crudimageMock);

        $this->crudimageMock->expects($this->once())
            ->method('load')
            ->with(5)
            ->willReturnSelf();

        $this->crudimageMock->expects($this->once())
            ->method('getTestimonialId')
            ->willReturn(5);

        $this->crudimageMock->expects($this->once())
            ->method('getStatus')
            ->willReturn(1);

        $block = new CrudimageView(
            $this->contextMock,
            $this->crudimageFactoryMock,
            $this->filterProviderMock
        );

        $result = $block->getSingleData();
        $this->assertSame($this->crudimageMock, $result);
    }

    public function testGetSingleDataReturnsFalseIfNoId()
    {
        $this->requestMock->method('getParam')->with('id')->willReturn(0);

        $this->crudimageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->crudimageMock);

        $this->crudimageMock->expects($this->once())
            ->method('load')
            ->with(0)
            ->willReturnSelf();

        $this->crudimageMock->expects($this->once())
            ->method('getTestimonialId')
            ->willReturn(null);

        $block = new CrudimageView(
            $this->contextMock,
            $this->crudimageFactoryMock,
            $this->filterProviderMock
        );

        $result = $block->getSingleData();
        $this->assertFalse($result);
    }

    public function testGetSingleDataReturnsFalseIfStatusIsNot1()
    {
        $this->requestMock->method('getParam')->with('id')->willReturn(7);

        $this->crudimageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->crudimageMock);

        $this->crudimageMock->expects($this->once())
            ->method('load')
            ->with(7)
            ->willReturnSelf();

        $this->crudimageMock->expects($this->once())
            ->method('getTestimonialId')
            ->willReturn(7);

        $this->crudimageMock->expects($this->once())
            ->method('getStatus')
            ->willReturn(0);

        $block = new CrudimageView(
            $this->contextMock,
            $this->crudimageFactoryMock,
            $this->filterProviderMock
        );

        $result = $block->getSingleData();
        $this->assertFalse($result);
    }
}