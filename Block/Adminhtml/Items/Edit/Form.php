<?php

declare(strict_types=1);

namespace Biren\Crudimage\Block\Adminhtml\Items\Edit;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Data\Form as MagentoForm;

class Form extends Generic
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct(): void
    {
        parent::_construct();
        $this->setId('crudimage_items_form');
        $this->setTitle(__('Item Information'));
    }

    /**
     * Prepare form before rendering HTML
     *
     * @return $this
     */
    protected function _prepareForm(): self
    {
        /** @var MagentoForm $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getUrl('biren_crudimage/items/save'),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data'
                ],
            ]
        );
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}