# KiwiCommerce_Testimonials Magento 2 Module

A Magento 2 CRUD example module, compatible with Magento 2.4.8-p1 and PHP 8.2.

## Requirements

- Magento 2.4.8-p1 or later
- PHP 8.1 or 8.2

## Installation

### Option 1: Copy to app/code

1. Copy the module to `app/code/KiwiCommerce/Testimonials`.
2. Run:
    ```bash
    php bin/magento setup:upgrade
    php bin/magento setup:di:compile
    php bin/magento cache:flush
    ```

### Option 2: Install via Composer (VCS)

1. Add the VCS repository to your root `composer.json`:
    ```json
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/expertsnarensharma/Testimonials.git"
        }
    ],
    ```
2. Require the module:
    ```bash
    composer require kiwicommerce/magento2-testimonials-module
    ```
3. Enable the module:
    ```bash
    php bin/magento module:enable KiwiCommerce_Testimonials
    php bin/magento setup:upgrade
    php bin/magento setup:di:compile
    php bin/magento cache:flush
    ```

## Enable the Module

If not already enabled, add to `app/etc/config.php` under the 'modules' section:
```php
'KiwiCommerce_Testimonials' => 1,
```

## Clear Cache

```bash
php bin/magento cache:flush
```

---

**This module is fully compatible with Magento 2.4.8-p1 and PHP 8.2.**