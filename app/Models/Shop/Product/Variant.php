<?php

namespace App\Models\Shop\Product;

use Coderstm\Traits\Core;
use App\Services\Resource;
use App\Models\Shop\Product;
use App\Models\Shop\LineItem;
use Coderstm\Traits\Fileable;
use App\Models\Shop\Product\Weight;
use App\Models\Shop\Product\Inventory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Shop\Product\Variant\Option;

class Variant extends Model
{
    use Core, Fileable;

    protected $fillable = [
        'price',
        'compare_at_price',
        'cost_per_item',
        'taxable',
        'track_inventory',
        'out_of_stock_track_inventory',
        'sku',
        'origin',
        'harmonized_system_code',
        'barcode',
        'product_id',
        'is_default',
    ];

    protected $appends = [
        'inventories_available',
        'title',
        'price_formated',
        'in_stock',
    ];

    protected $with = [
        'options',
        'weight',
        'thumbnail',
        'inventories.location:id,name',
    ];

    protected $casts = [
        'taxable' => 'boolean',
        'track_inventory' => 'boolean',
        'out_of_stock_track_inventory' => 'boolean',
        'is_default' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class)->withOnly(['thumbnail']);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function active_inventories()
    {
        return $this->hasMany(Inventory::class)->active();
    }

    public function line_items()
    {
        return $this->hasMany(LineItem::class);
    }

    public function weight()
    {
        return $this->morphOne(Weight::class, 'weightable');
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function getInventoriesAvailableAttribute()
    {
        $location = $this->active_inventories_count;
        $available = $this->active_inventories_sum_available;
        if (!$this->track_inventory) {
            return $location > 1 ? "Stocked at {$location} locations" : "Stocked at {$location} location";
        }
        return $location > 1 ? "{$available} available at {$location} locations" : "{$available} available at {$location} location";
    }

    public function getInStockAttribute()
    {
        if ($this->track_inventory && $this->out_of_stock_track_inventory) {
            return $this->active_inventories_sum_available > 0;
        }
        return true;
    }

    public function getTitleAttribute()
    {
        if ($this->is_default) {
            return 'Default';
        }

        return $this->options->map(function ($option) {
            return $option->value;
        })->join(' / ');
    }

    public function getPriceFormatedAttribute()
    {
        return format_amount($this->price * 100);
    }

    public function scopeOnlyTrackInventory($query)
    {
        return $query->where('track_inventory', 1);
    }

    public function scopeStockIn($query)
    {
        return $query->where(function ($query) {
            $query->where('track_inventory', 1)
                ->where('out_of_stock_track_inventory', 1);
        })->orWhere(function ($query) {
            $query->where('track_inventory', 1)
                ->where('out_of_stock_track_inventory', 0)
                ->having('active_inventories_sum_available', '>', 0);
        })->orWhere('track_inventory', 0);
    }

    public function saveRelated(Resource $resource)
    {
        // Update variant thumbnail
        if ($resource->filled('thumbnail.id')) {
            $this->product->media()->syncWithoutDetaching($resource->input('thumbnail.id'));
            $this->media()->sync($resource->input('thumbnail.id'));
        } else {
            $this->media()->detach();
        }

        // Update variant stacks
        if ($resource->filled('inventories')) {
            foreach (collect($resource['inventories']) as $inventory) {
                $inventory['location_id'] = $inventory['location']['id'];
                $this->inventories()->updateOrCreate([
                    'location_id' => $inventory['location_id'],
                ], $inventory);
            }
        }

        // Update weight
        if ($this->weight) {
            $this->weight()->update((new Weight($resource->weight))->toArray());
        } else {
            $this->weight()->save(new Weight($resource->weight));
        }

        // Update variant options
        if ($resource->filled('options')) {
            foreach (collect($resource['options']) as $item) {
                if ($item['id']) {
                    $option = Option::find($item['id']);
                } else {
                    $productOption = $this->product->options()->firstOrCreate([
                        'name' => $item['name']
                    ], $item);

                    $option = $this->options()->updateOrCreate([
                        'option_id' => $productOption->id,
                    ], $item);
                }

                if ($option->value != $item['value']) {
                    $option->update([
                        'value' => $item['value'],
                    ]);
                    if (isset($option['values'])) {
                        $values = collect($option['values'])->pluck('name')->toArray();
                        if (!in_array($item['value'], $values)) {
                            $option->attribute->updateValue($item['value']);
                        }
                    }
                }
            }
        }

        // current instance
        return $this;
    }

    protected static function booted()
    {
        parent::booted();
        static::deleted(function ($model) {
            $model->inventories()->each(function ($item) {
                $item->delete();
            });
            $model->options()->each(function ($item) {
                $item->delete();
            });
        });
        static::deleting(function ($model) {
            $model->line_items()->update([
                'is_variant_deleted' => true,
            ]);
        });
        static::restoring(function ($model) {
            $model->line_items()->withTrashed()->update([
                'is_variant_deleted' => false,
            ]);
        });
        static::addGlobalScope('count', function (Builder $builder) {
            $builder->withCount(['active_inventories'])
                ->withSum('active_inventories', 'available');
        });
    }
}
