<?php
// filepath: app/code/KiwiCommerce/Testimonials/Test/Unit/Controller/Adminhtml/Items/IndexTest.php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Test\Unit\Controller\Adminhtml\Items;

use KiwiCommerce\Testimonials\Controller\Adminhtml\Items\Index;
use PHPUnit\Framework\TestCase;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\Page as ResultPage;
use Magento\Backend\App\Action\Context;

class IndexTest extends TestCase
{
    private $contextMock;
    private $resultPageFactoryMock;
    private $resultPageMock;
    private $configMock;
    private $titleMock;

    protected function setUp(): void
    {
        $this->contextMock = $this->createMock(Context::class);
        $this->resultPageFactoryMock = $this->createMock(PageFactory::class);
        $this->resultPageMock = $this->createMock(ResultPage::class);

        $this->configMock = $this->getMockBuilder(\Magento\Framework\View\Page\Config::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getTitle'])
            ->getMock();

        $this->titleMock = $this->getMockBuilder(\Magento\Framework\View\Page\Title::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['prepend'])
            ->getMock();

        $this->resultPageMock->method('getConfig')->willReturn($this->configMock);
        $this->configMock->method('getTitle')->willReturn($this->titleMock);

        $this->resultPageFactoryMock->method('create')->willReturn($this->resultPageMock);
    }

    public function testExecuteReturnsResultPage()
    {
        $this->resultPageMock->expects($this->once())
            ->method('setActiveMenu')
            ->with('KiwiCommerce_Testimonials::test')
            ->willReturnSelf();

        $this->titleMock->expects($this->once())
            ->method('prepend')
            ->with(__('Testimonial Items'))
            ->willReturnSelf();

        $this->resultPageMock->expects($this->exactly(2))
            ->method('addBreadcrumb')
            ->withConsecutive(
                [__('Testimonial'), __('Testimonial')],
                [__('Items'), __('Items')]
            )
            ->willReturnSelf();

        $controller = $this->getMockBuilder(Index::class)
            ->setConstructorArgs([
                $this->contextMock,
                $this->resultPageFactoryMock
            ])
            ->onlyMethods([])
            ->getMockForAbstractClass();

        // Set the protected property resultPageFactory
        $reflection = new \ReflectionProperty($controller, 'resultPageFactory');
        $reflection->setAccessible(true);
        $reflection->setValue($controller, $this->resultPageFactoryMock);

        $result = $controller->execute();
        $this->assertSame($this->resultPageMock, $result);
    }
}