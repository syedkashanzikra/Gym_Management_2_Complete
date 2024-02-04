<?php

namespace App\Models\Shop;

use App\Models\Status;
use Coderstm\Traits\Core;
use App\Services\Resource;
use App\Traits\OrderStatus;
use Coderstm\Models\Refund;
use Illuminate\Support\Arr;
use Coderstm\Models\Address;
use Coderstm\Models\Payment;
use App\Models\Shop\Location;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Shop\Order\Contact;
use App\Models\Shop\Order\TaxLine;
use Illuminate\Support\Collection;
use App\Models\Shop\CartRepository;
use App\Models\Shop\Order\Customer;
use App\Models\Shop\Order\LineItem;
use App\Models\Shop\Product\Inventory;
use App\Models\Shop\Order\DiscountLine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Order extends Model
{
    use Core, OrderStatus;

    const STATUS_OPEN = 'Open';
    const STATUS_PENDING = 'Pending';
    const STATUS_COMPLETED = 'Completed';
    const STATUS_CANCELLED = 'Cancelled';
    const STATUS_DECLINED = 'Declined';
    const STATUS_DISPUTED = 'Disputed';
    const STATUS_ARCHIVED = 'Archived';

    const STATUS_PAYMENT_PENDING = 'Payment pending';
    const STATUS_PAYMENT_FAILED = 'Payment failed';
    const STATUS_PAYMENT_SUCCESS = 'Payment success';
    const STATUS_PARTIALLY_PAID = 'Partially paid';
    const STATUS_PAID = 'Paid';

    const STATUS_UNFULFILLED = 'Unfulfilled';
    const STATUS_FULFILLED = 'Fulfilled';
    const STATUS_PARTIALLY_FULFILLED = 'Partially fulfilled';
    const STATUS_AWAITING_PICKUP = 'Awaiting pickup';

    const STATUS_REFUNDED = 'Refunded';
    const STATUS_PARTIALLY_REFUNDED = 'Partially refunded';

    const STATUS_RETURN_INPROGRESS = 'Return in progress';
    const STATUS_RETURNED = 'Returned';

    const STATUS_MANUAL_VERIFICATION_REQUIRED = 'Manual verification required';

    const REASON_CUSTOMER = 'Customer changed/cancelled order';
    const REASON_SIZE_TOO_SMALL = 'Size was too small';
    const REASON_SIZE_TOO_LARGE = 'Size was too large';
    const REASON_UNWANTED = 'Customer changed their mind';
    const REASON_NOT_AS_DESCRIBED = 'Item not as described';
    const REASON_WRONG_ITEM = 'Received the wrong item';
    const REASON_DEFECTIVE = 'Damaged or defective';
    const REASON_FRAUD = 'Fraudulent order';
    const REASON_INVENTORY = 'Items unavailable';
    const REASON_DECLINED = 'Payment declined';
    const REASON_UNKNOWN = 'Unknown';

    protected $fillable = [
        'customer',
        'location',
        'customer_id',
        'location_id',
        'billing_address',
        'billing_address_id',
        'note',
        'collect_tax',
        'attributes',
        'source',
        'sub_total',
        'tax_total',
        'discount_total',
        'grand_total',
    ];

    protected $dateTimeFormat = 'd M, Y \a\t h:i a';

    protected $casts = [
        'collect_tax' => 'boolean',
        'attributes' => 'collection',
    ];

    protected $hidden = [
        'customer_id',
        'location_id',
        'billing_address_id',
    ];

    protected $with = [
        'status',
        'customer',
        'contact',
        'billing_address',
        'line_items',
        'tax_lines',
        'discount',
    ];

    protected $appends = [
        'total_line_items',
        'due_amount',
        'refundable_amount',
        'formated_id',
        'has_due',
        'has_payment',
        'can_edit',
        'can_refund',
        'is_cancelled',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function billing_address()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function line_items()
    {
        return $this->morphMany(LineItem::class, 'itemable')->where('quantity', '>', 0);
    }

    public function tax_lines()
    {
        return $this->morphMany(TaxLine::class, 'taxable');
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    public function status()
    {
        return $this->morphMany(Status::class, 'statusable');
    }

    public function discount()
    {
        return $this->morphOne(DiscountLine::class, 'discountable');
    }

    public function contact()
    {
        return $this->morphOne(Contact::class, 'contactable');
    }

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }

    public function hasDiscount(): bool
    {
        return !is_null($this->discount) ?: false;
    }

    public function getFormatedIdAttribute()
    {
        return "#{$this->id}";
    }

    public function getDueAmountAttribute()
    {
        return round($this->grand_total - $this->paid_total, 2);
    }

    public function getRefundableAmountAttribute()
    {
        return round($this->paid_total - $this->refund_total, 2);
    }

    public function getHasDueAttribute()
    {
        return $this->due_amount > 0;
    }

    public function getHasPaymentAttribute()
    {
        return $this->paid_total > 0;
    }

    public function getTotalLineItemsAttribute()
    {
        if (!$this->line_items_quantity) {
            return '0 Items';
        }
        return "{$this->line_items_quantity} Item" . ($this->line_items_quantity > 1 ? 's' : '');
    }

    public function setCustomerAttribute($attributes)
    {
        if ($attributes) {
            $customer = Customer::firstOrCreate([
                'email' => has($attributes)->email,
            ], $attributes);

            // add address
            if ($customer->wasRecentlyCreated) {
                $customer->updateOrCreateAddress($attributes['address'] ?? []);
            }

            $this->attributes['customer_id'] = $customer->id;
        }
    }

    public function setLocationAttribute($location)
    {
        $this->attributes['location_id'] = has($location)->id;
    }

    public function setBillingAddressAttribute($address)
    {
        if ($address) {
            if ($this->billing_address_id) {
                $this->billing_address->update($address);
            } else {
                $address = $this->addresses()->save((new Address($address))->replicate([
                    'id'
                ])->fill([
                    'ref' => 'billing_address',
                ]));
                $this->attributes['billing_address_id'] = $address->id;
            }
        }
    }

    public function syncLineItems(Collection $line_items, $detach = true)
    {
        if ($detach) {
            // delete removed line_items
            $this->line_items()
                ->whereNotIn('id', $line_items->pluck('id')->filter())
                ->each(function ($item) {
                    $item->delete();
                });
        }

        // update or create line_items
        foreach ($line_items as $item) {
            // update or create the product
            $product = $this->line_items()->updateOrCreate([
                'id' => has($item)->id,
            ], $item);

            // update the discount
            if (!empty(has($item)->discount)) {
                $product->discount()->updateOrCreate([
                    'id' => has($item['discount'])->id,
                ], (new DiscountLine($item['discount']))->toArray());
            } else {
                $product->discount()->delete();
            }
        }
    }

    public function syncLineItemsWithoutDetach(Collection $line_items)
    {
        $this->syncLineItems($line_items, false);
    }

    public function duplicate()
    {
        $replicate = new Resource(static::load([
            'billing_address',
        ])->replicate()->toArray());

        return static::createOrUpdate($replicate);
    }

    public static function createOrUpdate(Resource $resource)
    {
        $order = static::updateOrCreate([
            'id' => has($resource)->id
        ], $resource->input());

        return $order->saveRelated($resource);
    }

    public function saveRelated(Resource $resource)
    {
        $cartRepository = new CartRepository($resource->input());

        $resource->merge([
            'sub_total' => $cartRepository->sub_total,
            'tax_lines' => $cartRepository->tax_lines->toArray(),
            'tax_total' => $cartRepository->tax_total,
            'discount_total' => $cartRepository->discount_total,
            'grand_total' => $cartRepository->grand_total,
        ]);

        $this->fill($resource->only([
            'sub_total',
            'tax_total',
            'discount_total',
            'grand_total',
        ]))->save();

        // update order line_items
        if ($resource->filled('line_items')) {
            $this->syncLineItems(collect($resource->input('line_items')));
        }

        // update customer
        if ($resource->boolean('contact.update_customer_profile') && $this->customer) {
            $this->customer->update(Arr::only($resource->contact, ['email', 'phone_number']));
        }

        // update order contact
        if ($resource->hasAny(['contact.email', 'contact.phone_number'])) {
            if ($this->contact) {
                $this->contact->update((new Contact($resource->contact))->toArray());
            } else {
                $this->contact()->save(new Contact($resource->contact));
            }
        }

        // update order discount
        if ($resource->filled('discount')) {
            if ($this->discount) {
                $this->discount->update((new DiscountLine($resource->discount))->toArray());
            } else {
                $this->discount()->save(new DiscountLine($resource->discount));
            }
        }

        // remove discount
        if ($resource->boolean('discount_removed') ?: false) {
            $this->discount()->delete();
        }

        // update order tax lines
        if ($resource->filled('tax_lines')) {
            $tax_lines = collect($resource->tax_lines);
            $this->tax_lines()->whereNotIn('id', $tax_lines->pluck('id')->filter())->delete();
            $tax_lines->each(function ($tax) {
                $this->tax_lines()->updateOrCreate([
                    'id' => has($tax)->id,
                ], $tax);
            });
        }

        // current instance
        return $this;
    }

    public function markAsPaid($paymentMethod)
    {
        if ($this->has_due) {
            $this->payments()->create([
                'payment_method_id' => $paymentMethod,
                'amount' => $this->due_amount,
                'capturable' => false,
                'status' => 'success',
                'note' => 'Marked the manual payment as received',
            ]);

            // marked order status as paid
            $this->markedAsPaid();
            $this->markedAsCompleted();
        }

        return $this->fresh([
            'payments', 'status'
        ]);
    }

    protected function adjustInventory(LineItem $lineItem, $location_id)
    {
        $inventory = Inventory::where([
            'location_id' => $location_id,
            'variant_id' => $lineItem->variant_id,
        ])->first();
        if ($inventory) {
            $inventory->setAvailable($lineItem->quantity);
        }
        return $inventory;
    }

    public function scopeSortBy($query, $column = 'CREATED_AT_ASC', $direction = 'asc')
    {
        switch ($column) {
            case 'CUSTOMER_NAME_ASC':
                $query->orderByRaw('(SELECT CONCAT(`first_name`, `first_name`) AS name FROM users WHERE users.id = orders.customer_id) ASC');
                break;

            case 'CUSTOMER_NAME_DESC':
                $query->orderByRaw('(SELECT CONCAT(`first_name`, `first_name`) AS name FROM users WHERE users.id = orders.customer_id) DESC');
                break;

            case 'CREATED_AT_DESC':
                $query->orderBy('created_at', 'desc');
                break;

            case 'CREATED_AT_ASC':
            default:
                $query->orderBy('created_at', 'asc');
                break;
        }

        return $query;
    }

    public function scopeOnlyOwner($query)
    {
        return $query->where('customer_id', current_user()->id);
    }

    public function scopeWhereHasStatus($query, $status)
    {
        return $query->whereHas('status', function ($q) use ($status) {
            $q->where('label', $status);
        });
    }

    public function scopeWhereInStatus($query, array $status = [])
    {
        return $query->whereHas('status', function ($q) use ($status) {
            $q->whereIn('label', $status);
        });
    }

    public function restock()
    {
        foreach ($this->line_items as $product) {
            $this->adjustInventory($product, $this->location_id);
        };
    }

    public function posPdf()
    {
        return Pdf::loadView('pdfs.order-pos', [
            'id' => $this->formated_id,
            'phone_number' => optional($this->contact)->phone_number,
            'customer_name' => optional($this->customer)->name ?? 'NA',
            'line_items' => $this->line_items,
            'location' => optional($this->location)->address_label,
            'sub_total' => format_amount($this->sub_total * 100),
            'tax_total' => format_amount($this->tax_total * 100),
            'discount_total' => format_amount($this->discount_total * 100),
            'grand_total' => format_amount($this->grand_total * 100),
            'paid_total' => format_amount($this->paid_total * 100),
            'due_amount' => format_amount($this->due_amount * 100),
            'created_at' => $this->created_at->format('d-m-Y h:i a'),
        ])->setPaper([0, 0, 260.00, 600.80]);
    }

    public function receiptPdf()
    {
        return Pdf::loadView('pdfs.order-receipt', [
            'id' => $this->formated_id,
            'phone_number' => optional($this->contact)->phone_number,
            'customer_name' => optional($this->customer)->name ?? 'NA',
            'billing_address' => optional($this->billing_address)->label,
            'line_items' => $this->line_items,
            'location' => optional($this->location)->address_label,
            'sub_total' => format_amount($this->sub_total * 100),
            'tax_total' => format_amount($this->tax_total * 100),
            'discount_total' => format_amount($this->discount_total * 100),
            'grand_total' => format_amount($this->grand_total * 100),
            'paid_total' => format_amount($this->paid_total * 100),
            'due_amount' => format_amount($this->due_amount * 100),
            'created_at' => $this->created_at->format('d-m-Y h:i a'),
        ]);
    }

    protected static function booted()
    {
        parent::booted();
        static::creating(function ($model) {
            if (empty($model->location)) {
                $model->location = Location::first()->toArray();
            }
        });
        static::created(function ($model) {
            $model->attachStatus(self::STATUS_OPEN);
        });
        static::addGlobalScope('count', function (Builder $builder) {
            $builder->withSum('payments as paid_total', 'amount');
            $builder->withSum('line_items as line_items_quantity', 'quantity');
            $builder->withSum('refunds as refund_total', 'amount');
        });
    }
}
