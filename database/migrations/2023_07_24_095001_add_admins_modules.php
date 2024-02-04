<?php

use Coderstm\Models\Module;
use Coderstm\Models\Permission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $permissions = [
            'Members' => [
                'sort_order' => 1,
                'icon' => 'fas fa-user-tag',
                'url' => 'members',
                'show_menu' => 1,
                'sub_items' => [
                    'Enquiry',
                ],
            ],
            'Classes' => [
                'sort_order' => 1,
                'icon' => 'fas fa-book-sparkles',
                'url' => 'classes',
                'show_menu' => 1,
                'sub_items' => [
                    'View',
                    'Edit',
                    'List',
                    'New',
                    'Delete',
                ],
            ],
            'Locations' => [
                'sort_order' => 1,
                'icon' => 'fas fa-location-dot',
                'url' => 'locations',
                'show_menu' => 1,
                'sub_items' => [
                    'View',
                    'Edit',
                    'List',
                    'New',
                    'Delete',
                ],
            ],
            'Week Schedules' => [
                'sort_order' => 1,
                'icon' => 'fas fa-calendar-days',
                'url' => 'class-schedules',
                'show_menu' => 1,
                'sub_items' => [
                    'View',
                    'Edit',
                    'List',
                    'New',
                    'Delete',
                ],
            ],
            'Templates' => [
                'sort_order' => 1,
                'icon' => 'fas fa-rectangle-history',
                'url' => 'templates',
                'show_menu' => 1,
                'sub_items' => [
                    'View',
                    'Edit',
                    'List',
                    'New',
                    'Delete',
                ],
            ],
            'Instructors' => [
                'sort_order' => 1,
                'icon' => 'fas fa-chalkboard-user',
                'url' => 'instructors',
                'show_menu' => 1,
                'sub_items' => [
                    'View',
                    'Edit',
                    'List',
                    'New',
                    'Delete',
                ],
            ],
            'Finance' => [
                'sort_order' => 1,
                'icon' => 'fas fa-coins',
                'url' => 'finance',
                'show_menu' => 1,
                'sub_items' => [
                    'Membership',
                    'Plans',
                ],
            ],
            'Announcements' => [
                'sort_order' => 1,
                'icon' => 'fas fa-bullhorn',
                'url' => 'announcements',
                'show_menu' => 1,
                'sub_items' => [
                    'View',
                    'Edit',
                    'List',
                    'New',
                    'Delete',
                ],
            ],
            'Products' => [
                'sort_order' => 1,
                'icon' => 'fas fa-box-open-full',
                'url' => 'products',
                'show_menu' => 1,
                'sub_items' => [
                    'View',
                    'Edit',
                    'List',
                    'New',
                    'Delete',
                ],
            ],
            'Enquiries' => [
                'sort_order' => 1,
                'icon' => 'fas fa-square-question',
                'url' => 'enquiries',
                'show_menu' => 1,
                'sub_items' => [
                    'View',
                    'Edit',
                    'List',
                    'New',
                    'Delete',
                ],
            ],
            'Tasks' => [
                'sort_order' => 16,
                'show_menu' => 1,
                'icon' => 'fas fa-list-check',
                'url' => 'tasks',
                'sub_items' => [
                    'View',
                    'Edit',
                    'List',
                    'New',
                    'Delete',
                ],
            ],
            'Reports' => [
                'sort_order' => 3,
                'icon' => 'fas fa-chart-user',
                'url' => 'reports',
                'show_menu' => 1,
                'sub_items' => [
                    'Daily Reports',
                    'Monthly Reports',
                    'Yearly Reports',
                ],
            ],
            'Settings' => [
                'sort_order' => 20,
                'icon' => 'fas fa-gear',
                'url' => 'settings',
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

        foreach ($permissions as $name => $item) {
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
