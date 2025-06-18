<?php

declare(strict_types=1);

namespace Biren\Crudimage\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;

class Thumbnail extends \Magento\Ui\Component\Listing\Columns\Column
{
    public const NAME = 'image';
    public const ALT_FIELD = 'name';

    protected StoreManagerInterface $storeManager;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param StoreManagerInterface $storeManager
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        StoreManagerInterface $storeManager,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->storeManager = $storeManager;
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
            $fieldName = $this->getData('name');
            $path = $this->storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            );
            foreach ($dataSource['data']['items'] as &$item) {
                if (!empty($item['image'])) {
                    $item[$fieldName . '_src'] = $path . $item['image'];
                    $item[$fieldName . '_alt'] = $item['title'] ?? '';
                    $item[$fieldName . '_orig_src'] = $path . $item['image'];
                } else {
                    // please place your placeholder image at pub/media/biren/crudimage/placeholder/placeholder.jpg
                    $placeholder = 'biren/crudimage/placeholder/placeholder.jpg';
                    $item[$fieldName . '_src'] = $path . $placeholder;
                    $item[$fieldName . '_alt'] = 'Place Holder';
                    $item[$fieldName . '_orig_src'] = $path . $placeholder;
                }
            }
        }

        return $dataSource;
    }
}