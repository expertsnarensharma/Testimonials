<?php
// filepath: app/code/KiwiCommerce/Testimonials/Test/Unit/Ui/Component/Listing/Column/ThumbnailTest.php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Test\Unit\Ui\Component\Listing\Column;

use KiwiCommerce\Testimonials\Ui\Component\Listing\Column\Thumbnail;
use PHPUnit\Framework\TestCase;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Api\Data\StoreInterface;

class ThumbnailTest extends TestCase
{
    private $contextMock;
    private $uiComponentFactoryMock;
    private $storeManagerMock;
    private $storeMock;

    protected function setUp(): void
    {
        $this->contextMock = $this->createMock(ContextInterface::class);
        $this->uiComponentFactoryMock = $this->createMock(UiComponentFactory::class);
        $this->storeManagerMock = $this->createMock(StoreManagerInterface::class);
        $this->storeMock = $this->createMock(StoreInterface::class);

        $this->storeManagerMock->method('getStore')->willReturn($this->storeMock);
        $this->storeMock->method('getBaseUrl')->willReturn('http://localhost/pub/media/');
    }

    public function testPrepareDataSourceWithProfilePic()
    {
        $dataSource = [
            'data' => [
                'items' => [
                    [
                        'profile_pic' => 'kiwicommerce/testimonials/image.jpg',
                        'title' => 'Test Title'
                    ]
                ]
            ]
        ];

        $thumbnail = $this->getMockBuilder(Thumbnail::class)
            ->setConstructorArgs([
                $this->contextMock,
                $this->uiComponentFactoryMock,
                $this->storeManagerMock,
                [],
                ['name' => 'image']
            ])
            ->onlyMethods(['getData'])
            ->getMock();

        $thumbnail->method('getData')->with('name')->willReturn('image');

        $result = $thumbnail->prepareDataSource($dataSource);

        $this->assertArrayHasKey('image_src', $result['data']['items'][0]);
        $this->assertEquals(
            'http://localhost/pub/media/kiwicommerce/testimonials/image.jpg',
            $result['data']['items'][0]['image_src']
        );
        $this->assertEquals('Test Title', $result['data']['items'][0]['image_alt']);
        $this->assertEquals(
            'http://localhost/pub/media/kiwicommerce/testimonials/image.jpg',
            $result['data']['items'][0]['image_orig_src']
        );
    }

    public function testPrepareDataSourceWithoutProfilePic()
    {
        $dataSource = [
            'data' => [
                'items' => [
                    [
                        'profile_pic' => null,
                        'title' => 'Test Title'
                    ]
                ]
            ]
        ];

        // Mock BP constant for placeholder path
        if (!defined('BP')) {
            define('BP', sys_get_temp_dir());
        }

        $thumbnail = $this->getMockBuilder(Thumbnail::class)
            ->setConstructorArgs([
                $this->contextMock,
                $this->uiComponentFactoryMock,
                $this->storeManagerMock,
                [],
                ['name' => 'image']
            ])
            ->onlyMethods(['getData'])
            ->getMock();

        $thumbnail->method('getData')->with('name')->willReturn('image');

        // Remove placeholder if exists for clean test
        $placeholderPath = BP . '/pub/media/kiwicommerce/testimonials/placeholder/placeholder.png';
        if (file_exists($placeholderPath)) {
            @unlink($placeholderPath);
        }

        $result = $thumbnail->prepareDataSource($dataSource);

        $this->assertArrayHasKey('image_src', $result['data']['items'][0]);
        $this->assertNotEmpty($result['data']['items'][0]['image_src']);
        $this->assertEquals('Place Holder', $result['data']['items'][0]['image_alt']);
        $this->assertNotEmpty($result['data']['items'][0]['image_orig_src']);
    }
}