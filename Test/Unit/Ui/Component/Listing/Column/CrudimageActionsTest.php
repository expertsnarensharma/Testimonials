<?php
// filepath: app/code/KiwiCommerce/Testimonials/Test/Unit/Ui/Component/Listing/Column/CrudimageActionsTest.php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Test\Unit\Ui\Component\Listing\Column;

use KiwiCommerce\Testimonials\Ui\Component\Listing\Column\CrudimageActions;
use PHPUnit\Framework\TestCase;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

class CrudimageActionsTest extends TestCase
{
    private $urlBuilderMock;
    private $contextMock;
    private $uiComponentFactoryMock;

    protected function setUp(): void
    {
        $this->urlBuilderMock = $this->createMock(UrlInterface::class);
        $this->contextMock = $this->createMock(ContextInterface::class);
        $this->uiComponentFactoryMock = $this->createMock(UiComponentFactory::class);
    }

    public function testPrepareDataSourceAddsEditAction()
    {
        $dataSource = [
            'data' => [
                'items' => [
                    [
                        'testimonial_id' => 123,
                        'name' => 'Test Name'
                    ]
                ]
            ]
        ];

        $expectedUrl = 'http://example.com/admin/kiwicommerce_testimonials/items/edit/id/123/';

        $this->urlBuilderMock->expects($this->once())
            ->method('getUrl')
            ->with(CrudimageActions::URL_PATH_EDIT, ['id' => 123])
            ->willReturn($expectedUrl);

        $crudimageActions = new CrudimageActions(
            $this->urlBuilderMock,
            $this->contextMock,
            $this->uiComponentFactoryMock,
            [],
            []
        );

        // Simulate getData('name') returns 'actions'
        $reflection = new \ReflectionProperty($crudimageActions, 'data');
        $reflection->setAccessible(true);
        $reflection->setValue($crudimageActions, ['name' => 'actions']);

        $result = $crudimageActions->prepareDataSource($dataSource);

        $this->assertArrayHasKey('actions', $result['data']['items'][0]);
        $this->assertArrayHasKey('edit', $result['data']['items'][0]['actions']);
        $this->assertEquals($expectedUrl, $result['data']['items'][0]['actions']['edit']['href']);
        $this->assertEquals('Edit', (string)$result['data']['items'][0]['actions']['edit']['label']);
    }

    public function testPrepareDataSourceWithoutTestimonialId()
    {
        $dataSource = [
            'data' => [
                'items' => [
                    [
                        'name' => 'Test Name'
                    ]
                ]
            ]
        ];

        $crudimageActions = new CrudimageActions(
            $this->urlBuilderMock,
            $this->contextMock,
            $this->uiComponentFactoryMock,
            [],
            []
        );

        // Simulate getData('name') returns 'actions'
        $reflection = new \ReflectionProperty($crudimageActions, 'data');
        $reflection->setAccessible(true);
        $reflection->setValue($crudimageActions, ['name' => 'actions']);

        $result = $crudimageActions->prepareDataSource($dataSource);

        $this->assertArrayNotHasKey('actions', $result['data']['items'][0]);
    }
}