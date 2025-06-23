<?php
// filepath: app/code/KiwiCommerce/Testimonials/Test/Unit/Block/CrudimageListDataTest.php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Test\Unit\Block;

use KiwiCommerce\Testimonials\Block\CrudimageListData;
use PHPUnit\Framework\TestCase;
use Magento\Framework\View\Element\Template\Context;
use KiwiCommerce\Testimonials\Model\CrudimageFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\View\LayoutInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Page\Config as PageConfig;
use Magento\Framework\View\Page\Title as PageTitle;

class CrudimageListDataTest extends TestCase
{
    private $contextMock;
    private $crudimageFactoryMock;
    private $crudimageMock;
    private $collectionMock;
    private $layoutMock;
    private $pagerBlockMock;
    private $requestMock;
    private $pageConfigMock;
    private $pageTitleMock;

    protected function setUp(): void
    {
        $this->crudimageFactoryMock = $this->createMock(CrudimageFactory::class);
        $this->crudimageMock = $this->getMockBuilder(\KiwiCommerce\Testimonials\Model\Crudimage::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getCollection'])
            ->getMock();
        $this->collectionMock = $this->getMockBuilder(\Magento\Framework\Data\Collection\AbstractDb::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['addFieldToFilter', 'setPageSize', 'setCurPage', 'load'])
            ->getMock();

        $this->crudimageFactoryMock->method('create')->willReturn($this->crudimageMock);
        $this->crudimageMock->method('getCollection')->willReturn($this->collectionMock);

        $this->layoutMock = $this->createMock(LayoutInterface::class);
        $this->pagerBlockMock = $this->getMockBuilder(Template::class)
            ->disableOriginalConstructor()
            ->addMethods(['setAvailableLimit', 'setShowPerPage', 'setCollection'])
            ->getMock();

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

    public function testPrepareLayoutSetsPageTitleAndPager()
    {
        $this->requestMock->method('getParam')->willReturnMap([
            ['p', null, 1],
            ['limit', null, 5]
        ]);

        $this->collectionMock->expects($this->once())->method('addFieldToFilter')->with('status', '1')->willReturnSelf();
        $this->collectionMock->expects($this->once())->method('setPageSize')->with(5)->willReturnSelf();
        $this->collectionMock->expects($this->once())->method('setCurPage')->with(1)->willReturnSelf();
        $this->collectionMock->expects($this->once())->method('load')->willReturnSelf();

        $this->pageTitleMock->expects($this->once())->method('set')->with(__('KiwiCommerce Testimonial Module List Page'));

        $block = $this->getMockBuilder(CrudimageListData::class)
            ->setConstructorArgs([$this->contextMock, $this->crudimageFactoryMock])
            ->onlyMethods(['getLayout', 'setChild', 'getCrudimageCollection', 'getChildHtml'])
            ->getMock();

        $block->method('getLayout')->willReturn($this->layoutMock);
        $block->method('getCrudimageCollection')->willReturn($this->collectionMock);
        $block->expects($this->once())->method('setChild')->with('pager', $this->anything())->willReturnSelf();

        $this->layoutMock->expects($this->once())
            ->method('createBlock')
            ->with(\Magento\Theme\Block\Html\Pager::class, 'kiwicommerce.crudimage.pager')
            ->willReturn($this->pagerBlockMock);

        $this->pagerBlockMock->expects($this->once())->method('setAvailableLimit')->with([5 => 5, 10 => 10, 15 => 15])->willReturnSelf();
        $this->pagerBlockMock->expects($this->once())->method('setShowPerPage')->with(true)->willReturnSelf();
        $this->pagerBlockMock->expects($this->once())->method('setCollection')->with($this->collectionMock)->willReturnSelf();

        $block->expects($this->once())->method('getCrudimageCollection')->willReturn($this->collectionMock);

        $result = $block->_prepareLayout();
        $this->assertInstanceOf(CrudimageListData::class, $result);
    }

    public function testGetCrudimageCollectionReturnsCollection()
    {
        $this->requestMock->method('getParam')->willReturnMap([
            ['p', null, 2],
            ['limit', null, 10]
        ]);

        $this->collectionMock->expects($this->once())->method('addFieldToFilter')->with('status', '1')->willReturnSelf();
        $this->collectionMock->expects($this->once())->method('setPageSize')->with(10)->willReturnSelf();
        $this->collectionMock->expects($this->once())->method('setCurPage')->with(2)->willReturnSelf();

        $block = new CrudimageListData($this->contextMock, $this->crudimageFactoryMock);
        $result = $block->getCrudimageCollection();
        $this->assertInstanceOf(\Magento\Framework\Data\Collection\AbstractDb::class, $result);
    }

    public function testGetPagerHtmlReturnsChildHtml()
    {
        $block = $this->getMockBuilder(CrudimageListData::class)
            ->setConstructorArgs([$this->contextMock, $this->crudimageFactoryMock])
            ->onlyMethods(['getChildHtml'])
            ->getMock();

        $block->expects($this->once())->method('getChildHtml')->with('pager')->willReturn('<pager-html>');

        $this->assertEquals('<pager-html>', $block->getPagerHtml());
    }
}