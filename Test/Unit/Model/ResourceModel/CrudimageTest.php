<?php
// filepath: app/code/KiwiCommerce/Testimonials/Test/Unit/Model/ResourceModel/CrudimageTest.php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Test\Unit\Model\ResourceModel;

use KiwiCommerce\Testimonials\Model\ResourceModel\Crudimage;
use PHPUnit\Framework\TestCase;

class CrudimageTest extends TestCase
{
    public function testConstructSetsMainTableAndIdFieldName()
    {
        $resource = $this->getMockBuilder(Crudimage::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['_init'])
            ->getMock();

        $resource->expects($this->once())
            ->method('_init')
            ->with('kiwicommerce_testimonials', 'testimonial_id');

        // Call protected _construct via Reflection
        $reflection = new \ReflectionMethod($resource, '_construct');
        $reflection->setAccessible(true);
        $reflection->invoke($resource);
    }
}