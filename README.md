# KiwiCommerce_Testimonials Magento 2 Module

A Magento 2 CRUD example module, compatible with Magento 2.4.8-p1 and PHP 8.2.  
Now includes a **frontend widget** for displaying testimonials anywhere (homepage, CMS page, static block).

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


How to Use the Widget (Frontend)
After installing the module, you can display testimonials anywhere on your Magento site using Magento’s built-in widget functionality.

Steps to Add the Testimonials Widget:
In the Admin Panel, navigate to:
Content → Widgets → Add Widget

In the "Add New Widget" screen:

Under Type, select:
Testimonials Widget

Under Design Theme, select your active theme.

Click Continue.

On the "Widget Options" screen:

Block Title — Enter an optional title for the widget (example: Customer Testimonials).

Number of Testimonials to Display — Enter number of testimonials to display (optional, default is 5).

Under Storefront Properties tab:

Select Store Views where this widget will appear.

In Layout Updates:

Click Add Layout Update.

Select Page → for example: CMS Home Page.

Select Container — example: Main Content Top, Sidebar Main, etc.

Click Save to save the widget.

Clear Magento cache:

bash
Copy
Edit
php bin/magento cache:flush
The testimonials will now appear on the selected page and position.