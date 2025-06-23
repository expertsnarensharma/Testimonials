<?php
// filepath: app/code/KiwiCommerce/Testimonials/Test/Unit/Model/CrudimageTest.php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Test\Unit\Model;

use KiwiCommerce\Testimonials\Model\Crudimage;
use PHPUnit\Framework\TestCase;

class CrudimageTest extends TestCase
{
    public function testConstructInitializesResourceModel()
    {
        $crudimage = $this->getMockBuilder(Crudimage::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['_init'])
            ->getMock();

        $crudimage->expects($this->once())
            ->method('_init')
            ->with(\KiwiCommerce\Testimonials\Model\ResourceModel\Crudimage::class);

        // Call protected _construct via Reflection
        $reflection = new \ReflectionMethod($crudimage, '_construct');
        $reflection->setAccessible(true);
        $reflection->invoke($crudimage);
    }
}