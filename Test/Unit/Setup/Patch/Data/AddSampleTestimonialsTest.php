<?php
// filepath: app/code/KiwiCommerce/Testimonials/Test/Unit/Setup/Patch/Data/AddSampleTestimonialsTest.php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Test\Unit\Setup\Patch\Data;

use KiwiCommerce\Testimonials\Setup\Patch\Data\AddSampleTestimonials;
use PHPUnit\Framework\TestCase;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;

class AddSampleTestimonialsTest extends TestCase
{
    private $moduleDataSetupMock;
    private $connectionMock;

    protected function setUp(): void
    {
        $this->moduleDataSetupMock = $this->createMock(ModuleDataSetupInterface::class);
        $this->connectionMock = $this->createMock(AdapterInterface::class);
        $this->moduleDataSetupMock->method('getConnection')->willReturn($this->connectionMock);
        $this->moduleDataSetupMock->method('getTable')->with('kiwicommerce_testimonials')->willReturn('kiwicommerce_testimonials');
    }

    public function testApplyInsertsSampleData()
    {
        $this->connectionMock->expects($this->once())->method('startSetup');
        $this->connectionMock->expects($this->once())->method('endSetup');
        $this->connectionMock->expects($this->once())
            ->method('insertMultiple')
            ->with(
                'kiwicommerce_testimonials',
                $this->callback(function ($data) {
                    return is_array($data) && count($data) === 6;
                })
            );

        $patch = new AddSampleTestimonials($this->moduleDataSetupMock);
        $result = $patch->apply();
        $this->assertInstanceOf(AddSampleTestimonials::class, $result);
    }

    public function testRevertDeletesSampleData()
    {
        $this->connectionMock->expects($this->once())->method('startSetup');
        $this->connectionMock->expects($this->once())->method('endSetup');
        $this->connectionMock->expects($this->once())
            ->method('delete')
            ->with(
                'kiwicommerce_testimonials',
                $this->callback(function ($where) {
                    return isset($where['company_name IN (?)']) &&
                        is_array($where['company_name IN (?)']) &&
                        count($where['company_name IN (?)']) === 6;
                })
            );

        $patch = new AddSampleTestimonials($this->moduleDataSetupMock);
        $patch->revert();
    }

    public function testGetDependenciesReturnsArray()
    {
        $patch = new AddSampleTestimonials($this->moduleDataSetupMock);
        $this->assertIsArray($patch->getDependencies());
    }

    public function testGetAliasesReturnsArray()
    {
        $patch = new AddSampleTestimonials($this->moduleDataSetupMock);
        $this->assertIsArray($patch->getAliases());
    }
}