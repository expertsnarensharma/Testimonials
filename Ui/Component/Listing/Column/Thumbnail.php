<?php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Ui\Component\Listing\Column;

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
                if (!empty($item['profile_pic'])) {
                    $item[$fieldName . '_src'] = $path . $item['profile_pic'];
                    $item[$fieldName . '_alt'] = $item['title'] ?? '';
                    $item[$fieldName . '_orig_src'] = $path . $item['profile_pic'];
                } else {
                    $placeholder = 'kiwicommerce/testimonials/placeholder/placeholder.png';
                    // Download placeholder.jpg if it does not exist
                    $placeholderPath = BP . '/pub/media/' . $placeholder;
                    if (!file_exists($placeholderPath)) {
                        $placeholderDir = dirname($placeholderPath);
                        if (!is_dir($placeholderDir)) {
                            mkdir($placeholderDir, 0775, true);
                        }
                        // Download a sample placeholder image from a public domain source
                        $imageUrl = 'https://placehold.co/600x400/png';
                        file_put_contents($placeholderPath, file_get_contents($imageUrl));
                    }
                    $mediaDir = BP . '/pub/media/';
                    if (!is_dir(dirname($mediaDir . $placeholder))) {
                        mkdir(dirname($mediaDir . $placeholder), 0775, true);
                    }
                    $placeholderPath = $mediaDir . $placeholder;
                    if (!file_exists($placeholderPath)) {
                        // Optionally, you can log a warning or copy a default image here
                        $item[$fieldName . '_src'] = '';
                        $item[$fieldName . '_alt'] = 'No Image';
                        $item[$fieldName . '_orig_src'] = '';
                    } else {
                        $item[$fieldName . '_src'] = $path . $placeholder;
                        $item[$fieldName . '_alt'] = 'Place Holder';
                        $item[$fieldName . '_orig_src'] = $path . $placeholder;
                    }
                }
            }
        }

        return $dataSource;
    }
}