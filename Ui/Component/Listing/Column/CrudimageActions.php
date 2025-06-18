<?php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

class CrudimageActions extends \Magento\Ui\Component\Listing\Columns\Column
{
    public const URL_PATH_EDIT = 'kiwicommerce_testimonials/items/edit';

    /**
     * URL builder
     *
     * @var UrlInterface
     */
    protected UrlInterface $_urlBuilder;

    /**
     * Constructor
     *
     * @param UrlInterface $urlBuilder
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        UrlInterface $urlBuilder,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        $this->_urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['crudimage_id'])) {
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->_urlBuilder->getUrl(
                                self::URL_PATH_EDIT,
                                [
                                    'id' => $item['crudimage_id']
                                ]
                            ),
                            'label' => __('Edit')
                        ],
                    ];
                }
            }
        }
        return $dataSource;
    }
}