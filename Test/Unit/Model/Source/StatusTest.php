<?php
// filepath: app/code/KiwiCommerce/Testimonials/Test/Unit/Model/Source/StatusTest.php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Test\Unit\Model\Source;

use KiwiCommerce\Testimonials\Model\Source\Status;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    public function testGetOptionArrayReturnsExpectedArray()
    {
        $options = Status::getOptionArray();
        $this->assertArrayHasKey(Status::STATUS_ENABLED, $options);
        $this->assertArrayHasKey(Status::STATUS_DISABLED, $options);
        $this->assertEquals('Enabled', (string) $options[Status::STATUS_ENABLED]);
        $this->assertEquals('Disabled', (string) $options[Status::STATUS_DISABLED]);
    }

    public function testGetAllOptionsReturnsExpectedArray()
    {
        $status = new Status();
        $allOptions = $status->getAllOptions();

        $expected = [
            ['value' => Status::STATUS_ENABLED, 'label' => __('Enabled')],
            ['value' => Status::STATUS_DISABLED, 'label' => __('Disabled')],
        ];

        $this->assertCount(2, $allOptions);
        $this->assertEquals($expected[0]['value'], $allOptions[0]['value']);
        $this->assertEquals((string)$expected[0]['label'], (string)$allOptions[0]['label']);
        $this->assertEquals($expected[1]['value'], $allOptions[1]['value']);
        $this->assertEquals((string)$expected[1]['label'], (string)$allOptions[1]['label']);
    }
}