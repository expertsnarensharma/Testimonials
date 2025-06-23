<?php
// filepath: app/code/KiwiCommerce/Testimonials/Test/Unit/Model/ResourceModel/Crudimage/CollectionTest.php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Test\Unit\Model\ResourceModel\Crudimage;

use KiwiCommerce\Testimonials\Model\ResourceModel\Crudimage\Collection;
use PHPUnit\Framework\TestCase;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CollectionTest extends TestCase
{
    public function testConstructSetsIdFieldNameAndInit()
    {
        $entityFactory = $this->createMock(EntityFactoryInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $fetchStrategy = $this->createMock(FetchStrategyInterface::class);
        $eventManager = $this->createMock(ManagerInterface::class);

        $adapter = $this->createMock(AdapterInterface::class);
        $resource = $this->createMock(AbstractDb::class);

        $collection = $this->getMockBuilder(Collection::class)
            ->setConstructorArgs([$entityFactory, $logger, $fetchStrategy, $eventManager, $adapter, $resource])
            ->onlyMethods(['_init'])
            ->getMock();

        $collection->expects($this->once())
            ->method('_init')
            ->with(
                \KiwiCommerce\Testimonials\Model\Crudimage::class,
                \KiwiCommerce\Testimonials\Model\ResourceModel\Crudimage::class
            );

        // Call protected _construct via Reflection
        $reflection = new \ReflectionMethod($collection, '_construct');
        $reflection->setAccessible(true);
        $reflection->invoke($collection);

        $this->assertEquals('testimonial_id', $collection->getIdFieldName());
    }
}