<?php
// filepath: app/code/KiwiCommerce/Testimonials/Test/Unit/Block/Adminhtml/Items/Edit/Tab/MainTest.php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Test\Unit\Block\Adminhtml\Items\Edit\Tab;

use KiwiCommerce\Testimonials\Block\Adminhtml\Items\Edit\Tab\Main;
use PHPUnit\Framework\TestCase;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Backend\Block\Template\Context;
use Magento\Cms\Model\Wysiwyg\Config as WysiwygConfig;

class MainTest extends TestCase
{
    private $contextMock;
    private $registryMock;
    private $formFactoryMock;
    private $wysiwygConfigMock;

    protected function setUp(): void
    {
        $this->contextMock = $this->createMock(Context::class);
        $this->registryMock = $this->createMock(Registry::class);
        $this->formFactoryMock = $this->createMock(FormFactory::class);
        $this->wysiwygConfigMock = $this->createMock(WysiwygConfig::class);
    }

    public function testGetTabLabel()
    {
        $block = new Main(
            $this->contextMock,
            $this->registryMock,
            $this->formFactoryMock,
            $this->wysiwygConfigMock
        );
        $this->assertEquals('Item Information', (string)$block->getTabLabel());
    }

    public function testGetTabTitle()
    {
        $block = new Main(
            $this->contextMock,
            $this->registryMock,
            $this->formFactoryMock,
            $this->wysiwygConfigMock
        );
        $this->assertEquals('Item Information', (string)$block->getTabTitle());
    }

    public function testCanShowTab()
    {
        $block = new Main(
            $this->contextMock,
            $this->registryMock,
            $this->formFactoryMock,
            $this->wysiwygConfigMock
        );
        $this->assertTrue($block->canShowTab());
    }

    public function testIsHidden()
    {
        $block = new Main(
            $this->contextMock,
            $this->registryMock,
            $this->formFactoryMock,
            $this->wysiwygConfigMock
        );
        $this->assertFalse($block->isHidden());
    }
}