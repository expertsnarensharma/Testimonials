<?php
// filepath: app/code/KiwiCommerce/Testimonials/Test/Unit/Api/ItemsRepositoryInterfaceTest.php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Test\Unit\Api;

use PHPUnit\Framework\TestCase;
use KiwiCommerce\Testimonials\Api\ItemsRepositoryInterface;
use KiwiCommerce\Testimonials\Api\Data\ItemInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class ItemsRepositoryInterfaceTest extends TestCase
{
    public function testGetByIdReturnsItemInterface()
    {
        $itemMock = $this->createMock(ItemInterface::class);
        $repo = $this->getMockForAbstractClass(ItemsRepositoryInterface::class);

        $repo->expects($this->once())
            ->method('getById')
            ->with(1)
            ->willReturn($itemMock);

        $result = $repo->getById(1);
        $this->assertInstanceOf(ItemInterface::class, $result);
    }

    public function testGetByIdThrowsNoSuchEntityException()
    {
        $repo = $this->getMockForAbstractClass(ItemsRepositoryInterface::class);

        $repo->expects($this->once())
            ->method('getById')
            ->with(999)
            ->will($this->throwException(new NoSuchEntityException(__('No such entity'))));

        $this->expectException(NoSuchEntityException::class);
        $repo->getById(999);
    }
}