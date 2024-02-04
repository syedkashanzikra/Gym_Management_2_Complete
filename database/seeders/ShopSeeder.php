<?php

namespace Database\Seeders;

use App\Models\Shop\Product;
use App\Models\Shop\Location;
use Illuminate\Database\Seeder;
use App\Models\Shop\Product\Vendor;
use App\Models\Shop\Product\Weight;
use App\Models\Shop\Product\Variant;
use App\Models\Shop\Product\Category;
use App\Models\Shop\Product\Attribute;
use App\Models\Shop\Product\Inventory;
use App\Models\Shop\Product\Collection;
use App\Models\Shop\Product\Variant\Option as VariantOption;

class ShopSeeder extends Seeder
{
    protected $attributes = [
        [
            'name' => 'Color',
            'values' => [
                'Red',
                'Green',
                'Orange',
                'Blue',
            ],
        ],
        [
            'name' => 'Size',
            'values' => [
                'M',
                'S',
                'L',
                'XL',
                'XXL',
            ],
        ],
    ];

    /**
     * Seed the application's database for shop.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->attributes as $each) {
            $attribute = Attribute::firstOrCreate([
                'name' => $each['name'],
            ]);
            foreach ($each['values'] as $value) {
                $attribute->values()->firstOrCreate([
                    'name' => $value,
                ]);
            }
        }

        Location::factory()->count(2)->create();
        Category::factory()->count(5)->create();
        Vendor::factory()->count(5)->create();
        Collection::factory()->count(5)->create();

        Product::factory()->count(5)->create()->each(function ($product) {
            Variant::factory()->count(1)->create([
                'product_id' => $product->id,
                'is_default' => true,
            ])->each(function ($variant) {
                $variant->weight()->create(Weight::factory()->make()->toArray());
                Location::all()->each(function ($location) use ($variant) {
                    Inventory::factory()->create([
                        'variant_id' => $variant->id,
                        'location_id' => $location->id,
                    ]);
                });
            });
            if ($product->has_variant) {
                foreach (Attribute::all() as $attr) {
                    $option = $product->options()->create([
                        'name' => $attr->name,
                        'attribute_id' => $attr->id,
                    ]);
                    $option->attribue_values()->sync($attr->values);
                }
                $collection = collect($this->attributes[0]['values']);
                $matrix = $collection->crossJoin($this->attributes[1]['values']);
                foreach ($matrix->all() as $values) {
                    Variant::factory()->count(1)->create([
                        'product_id' => $product->id,
                    ])->each(function ($variant) use ($product, $values) {
                        $variant->weight()->create(Weight::factory()->make()->toArray());
                        Location::all()->each(function ($location) use ($variant) {
                            Inventory::firstOrCreate(
                                [
                                    'variant_id' => $variant->id,
                                    'location_id' => $location->id,
                                ],
                                [
                                    'available' => $variant->track_inventory ? rand(-10, 10) : 0,
                                    'active' => rand(0, 1),
                                ]
                            );
                        });
                        foreach ($values as $key => $value) {
                            VariantOption::firstOrCreate([
                                'variant_id' => $variant->id,
                                'option_id' => $product->options[$key]->id,
                                'position' => $key,
                                'value' => $value,
                            ]);
                        }
                    });
                }
            }
        });
    }
}
