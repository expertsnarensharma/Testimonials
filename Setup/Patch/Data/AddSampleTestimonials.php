<?php

declare(strict_types=1);

namespace KiwiCommerce\Testimonials\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class AddSampleTestimonials implements DataPatchInterface
{
    private ModuleDataSetupInterface $moduleDataSetup;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    public function apply(): self
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $table = $this->moduleDataSetup->getTable('kiwicommerce_testimonials');
        $this->moduleDataSetup->getConnection()->insertMultiple(
            $table,
            [
                [
                    'company_name' => 'Acme Corp',
                    'name' => 'Alice Smith',
                    'message' => 'Great service and support!',
                    'post' => 'CEO',
                    'profile_pic' => 'https://placehold.co/600x400/png',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'company_name' => 'Beta Ltd',
                    'name' => 'Bob Johnson',
                    'message' => 'Very satisfied with the product.',
                    'post' => 'CTO',
                    'profile_pic' => 'https://placehold.co/600x400/png',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'company_name' => 'Gamma Solutions',
                    'name' => 'Carol Lee',
                    'message' => 'Exceptional quality and fast delivery.',
                    'post' => 'Project Manager',
                    'profile_pic' => 'https://placehold.co/600x400/png',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'company_name' => 'Delta Innovations',
                    'name' => 'David Kim',
                    'message' => 'Professional team and great results.',
                    'post' => 'Lead Developer',
                    'profile_pic' => 'https://placehold.co/600x400/png',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [   
                    'company_name' => 'Epsilon Enterprises',
                    'name' => 'Eva Green',
                    'message' => 'Highly recommend to others.',
                    'post' => 'Marketing Director',
                    'profile_pic' => 'https://placehold.co/600x400/png',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'company_name' => 'Zeta Tech',
                    'name' => 'Frank Moore',
                    'message' => 'Reliable and efficient service.',
                    'post' => 'Operations Manager',
                    'profile_pic' => 'https://placehold.co/600x400/png',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]
            ]
        );

        $this->moduleDataSetup->getConnection()->endSetup();
        return $this;
    }

    public function revert(): void
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $table = $this->moduleDataSetup->getTable('kiwicommerce_testimonials');
        $this->moduleDataSetup->getConnection()->delete($table, [
            'company_name IN (?)' => [
                'Acme Corp',
                'Beta Ltd',
                'Gamma Solutions',
                'Delta Innovations',
                'Epsilon Enterprises',
                'Zeta Tech'
            ]
        ]);
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }
}