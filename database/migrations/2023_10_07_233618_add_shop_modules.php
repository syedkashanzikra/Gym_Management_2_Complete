<?php

use Coderstm\Models\Module;
use Illuminate\Support\Str;
use Coderstm\Models\Permission;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    protected $modules = [
        'Products' => [
            'sort_order' => 2,
            'icon' => 'fas fa-box-open-full',
            'url' => 'products',
            'show_menu' => 1,
            'sub_items' => [
                'View',
                'Edit',
                'List',
                'New',
                'Delete',
                'Inventory',
                'Categories',
                'Attributes',
                'Inventory',
                'Collections',
            ],
        ],
        'Orders' => [
            'sort_order' => 2,
            'icon' => 'fas fa-cart-arrow-down',
            'url' => 'orders',
            'show_menu' => 1,
            'sub_items' => [
                'View',
                'Edit',
                'List',
                'New',
                'Delete',
            ],
        ],
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        foreach ($this->modules as $name => $item) {
            $module = Module::updateOrCreate([
                'name' => $name,
            ], [
                'icon' => $item['icon'],
                'url' => $item['url'],
                'show_menu' => $item['show_menu'],
                'sort_order' => $item['sort_order'],
            ]);

            foreach ($item['sub_items'] as $item) {
                Permission::updateOrCreate([
                    'scope' => Str::slug($module['name']) . ':' . Str::slug($item),
                ], [
                    'module_id' => $module['id'],
                    'action' => $item,
                ]);
            }
        }
    }
};
