<?php
// filepath: app/code/KiwiCommerce/Testimonials/Test/Unit/Controller/Adminhtml/Items/MassDeleteTest.php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Test\Unit\Controller\Adminhtml\Items;

use KiwiCommerce\Testimonials\Controller\Adminhtml\Items\MassDelete;
use PHPUnit\Framework\TestCase;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use KiwiCommerce\Testimonials\Model\ResourceModel\Crudimage\CollectionFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Data\Collection\AbstractDb;

class MassDeleteTest extends TestCase
{
    private $contextMock;
    private $filterMock;
    private $collectionFactoryMock;
    private $collectionMock;
    private $messageManagerMock;
    private $resultFactoryMock;
    private $resultRedirectMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getMessageManager', 'getResultFactory'])
            ->getMock();

        $this->filterMock = $this->createMock(Filter::class);
        $this->collectionFactoryMock = $this->createMock(CollectionFactory::class);
        $abstractDbMock = $this->getMockBuilder(AbstractDb::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getResource'])
            ->getMock();

        $this->collectionMock = $this->getMockBuilder(\Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getSize', 'getIterator', 'getResource'])
            ->setConstructorArgs([$abstractDbMock])
            ->getMock();

        $this->messageManagerMock = $this->createMock(ManagerInterface::class);
        $this->resultFactoryMock = $this->createMock(ResultFactory::class);
        $this->resultRedirectMock = $this->createMock(Redirect::class);

        $this->contextMock->method('getMessageManager')->willReturn($this->messageManagerMock);
        $this->contextMock->method('getResultFactory')->willReturn($this->resultFactoryMock);
    }

    public function testExecuteDeletesRecordsAndRedirects(): void
    {
        $record1 = $this->getMockBuilder(\Magento\Framework\Model\AbstractModel::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['delete'])
            ->getMock();
        $record2 = $this->getMockBuilder(\Magento\Framework\Model\AbstractModel::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['delete'])
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

        $record1->expects($this->once())->method('delete');
        $record2->expects($this->once())->method('delete');

        $this->messageManagerMock->expects($this->once())
            ->method('addSuccessMessage')
            ->with(__('A total of %1 record(s) have been deleted.', count($records)));

        $this->resultFactoryMock->expects($this->once())
            ->method('create')
            ->with(ResultFactory::TYPE_REDIRECT)
            ->willReturn($this->resultRedirectMock);

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/')
            ->willReturnSelf();

        $controller = new MassDelete(
            $this->contextMock,
            $this->filterMock,
            $this->collectionFactoryMock
        );

        // Use reflection to set protected properties
        $reflection = new \ReflectionClass($controller);

        $resultFactoryProperty = $reflection->getProperty('resultFactory');
        $resultFactoryProperty->setAccessible(true);
        $resultFactoryProperty->setValue($controller, $this->resultFactoryMock);

        $messageManagerProperty = $reflection->getProperty('messageManager');
        $messageManagerProperty->setAccessible(true);
        $messageManagerProperty->setValue($controller, $this->messageManagerMock);

        $result = $controller->execute();
        $this->assertSame($this->resultRedirectMock, $result);
    }
}