<?php
// filepath: app/code/KiwiCommerce/Testimonials/Test/Unit/Controller/Adminhtml/Items/MassStatusTest.php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Test\Unit\Controller\Adminhtml\Items;

use KiwiCommerce\Testimonials\Controller\Adminhtml\Items\MassStatus;
use PHPUnit\Framework\TestCase;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use KiwiCommerce\Testimonials\Model\ResourceModel\Crudimage\CollectionFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\Collection\AbstractDb;

class MassStatusTest extends TestCase
{
    private $contextMock;
    private $filterMock;
    private $collectionFactoryMock;
    private $collectionMock;
    private $resultFactoryMock;
    private $resultRedirectMock;
    private $messageManagerMock;
    private $requestMock;

    protected function setUp(): void
    {
        $this->contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getMessageManager', 'getRequest'])
            ->getMock();

        $this->filterMock = $this->createMock(Filter::class);
        $this->collectionFactoryMock = $this->createMock(CollectionFactory::class);
        $this->collectionMock = $this->getMockBuilder(AbstractDb::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getSize', 'getIterator'])
            ->getMock();

        $this->resultFactoryMock = $this->createMock(ResultFactory::class);
        $this->resultRedirectMock = $this->createMock(Redirect::class);
        $this->messageManagerMock = $this->createMock(ManagerInterface::class);
        $this->requestMock = $this->createMock(RequestInterface::class);

        $this->contextMock->method('getMessageManager')->willReturn($this->messageManagerMock);
        $this->contextMock->method('getRequest')->willReturn($this->requestMock);
    }

    public function testExecuteSetsStatusAndRedirects()
    {
        $record1 = $this->getMockBuilder(\Magento\Framework\Model\AbstractModel::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['setStatus', 'save'])
            ->getMock();
        $record2 = $this->getMockBuilder(\Magento\Framework\Model\AbstractModel::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['setStatus', 'save'])
            ->getMock();

        $records = [$record1, $record2];

        $this->collectionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->collectionMock);

        $this->filterMock->expects($this->once())
            ->method('getCollection')
            ->with($this->collectionMock)
            ->willReturn($this->collectionMock);

        $this->collectionMock->expects($this->once())
            ->method('getSize')
            ->willReturn(count($records));

        $this->collectionMock->expects($this->once())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator($records));

        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('status')
            ->willReturn(1);

        $record1->expects($this->once())->method('setStatus')->with(1)->willReturnSelf();
        $record1->expects($this->once())->method('save');
        $record2->expects($this->once())->method('setStatus')->with(1)->willReturnSelf();
        $record2->expects($this->once())->method('save');

        $this->messageManagerMock->expects($this->once())
            ->method('addSuccessMessage')
            ->with(__('A total of %1 record(s) have been %2.', count($records), __('enabled')));

        $this->resultFactoryMock->expects($this->once())
            ->method('create')
            ->with(ResultFactory::TYPE_REDIRECT)
            ->willReturn($this->resultRedirectMock);

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/')
            ->willReturnSelf();

        $controller = new MassStatus(
            $this->contextMock,
            $this->filterMock,
            $this->collectionFactoryMock
        );
        $controller->resultFactory = $this->resultFactoryMock;
        $controller->messageManager = $this->messageManagerMock;

        $result = $controller->execute();
        $this->assertSame($this->resultRedirectMock, $result);
    }
}