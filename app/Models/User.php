<?php

namespace App\Models;

use App\Models\Parq;
use Coderstm\Enum\AppRag;
use Coderstm\Models\File;
use Coderstm\Enum\AppStatus;
use Coderstm\Models\User as Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Model
{
    protected $dateTimeFormat = 'd M, Y \a\t h:i a';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'is_active',
        // extra
        'title',
        'member_id',
        'note',
        'status',
        'source',
        'gender',
        'rag',
        'collect_id',
        'admin_id',
        'checked',
        'request_parq',
        'request_avatar',
        'release_at',
        'rfid',
        'qrcode',
        'email_marketing',
        'collect_tax',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'qrcode',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'starts_at' => 'datetime',
        'status_change_at' => 'datetime',
        'rag' => AppRag::class,
        'status' => AppStatus::class,
        'is_active' => 'boolean',
        'request_parq' => 'boolean',
        'request_avatar' => 'boolean',
        'checked' => 'boolean',
        'email_marketing' => 'boolean',
        'collect_tax' => 'boolean',
    ];

    protected $appends = [
        'name',
        'member_since',
        'member_id_formated',
        'guard',
        'has_avatar',
        'has_parq',
        'has_blocked',
        'subscribed',
        'has_cancelled',
        'qrcode_url',
    ];

    protected $with = [
        'avatar',
        'address',
        'lastLogin',
        'latestInvoice',
    ];

    public function getMemberIdFormatedAttribute()
    {
        return "{$this->member_id}-28{$this->id}";
    }

    public function getQrcodeUrlAttribute($value)
    {
        return route('users.qrcode', [
            'user' => $this->id
        ]);
    }

    public function getIsEnquiryAttribute($value)
    {
        return $this->status != AppStatus::ACTIVE;
    }

    public function getHasParqAttribute()
    {
        return !empty($this->parq);
    }

    public function getHasBlockedAttribute()
    {
        return $this->isBlocked() && !$this->blocked->disabled;
    }

    public function getHasAvatarAttribute()
    {
        return !empty($this->avatar);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function lastNsBookings(): HasOne
    {
        return $this->hasOne(Booking::class)
            ->withOnly(['schedule'])
            ->onlyLastWeekNoShow()
            ->orderBy('schedules_at', 'desc');
    }

    public function lastLateCancellation(): HasOne
    {
        return $this->hasOne(Booking::class)
            ->withOnly(['schedule'])
            ->onlyLastWeekLateCancellation()
            ->orderBy('schedules_at', 'desc');
    }

    public function schedules(): BelongsToMany
    {
        return $this->belongsToMany(ClassSchedule::class, 'bookings', 'user_id', 'schedule_id');
    }

    public function blocked(): HasOne
    {
        return $this->hasOne(Block::class);
    }

    public function isBlocked()
    {
        return $this->blocked && $this->blocked->is_active();
    }

    public function updateOrCreateBlocked(array $attributes = [])
    {
        return $this->blocked()->updateOrCreate([
            'user_id' => $this->id
        ], [
            'disabled' => optional((object) $attributes)->disabled ?? false,
            'release_at' => optional((object) $attributes)->release_at ?? now()->addDays(3),
            'type' => optional((object) $attributes)->type ?? 'NS',
        ]);
    }

    public function parq(): HasOne
    {
        return $this->hasOne(Parq::class);
    }

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'read_documents', 'user_id', 'file_id');
    }

    public function updateOrCreateParq(array $parq)
    {
        if ($this->parq) {
            return $this->parq()->update((new Parq($parq))->toArray());
        } else {
            return $this->parq()->save(new Parq($parq));
        }
    }

    public function scopeOnlyMember($query)
    {
        return $query->where([
            'status' => AppStatus::ACTIVE
        ]);
    }

    public function scopeOnlyNoShow($query)
    {
        return $query->withCount([
            'bookings as ns_bookings_count' => function ($query) {
                $query->onlyLastWeekNoShow();
            },
        ])->whereHas('bookings', function ($booking) {
            $booking->onlyLastWeekNoShow();
        });
    }

    public function scopeOnlyBlocked($query)
    {
        return $query->with('blocked')->whereHas('blocked', function ($booking) {
            $booking->blocked();
        });
    }

    public function scopeOnlyUnblocked($query)
    {
        return $query->doesntHave('blocked')->orWhereHas('blocked', function ($booking) {
            $booking->unblocked();
        });
    }

    public function scopeOnlyLateCancellation($query)
    {
        return $query->withCount([
            'bookings as late_cancellation_count' => function ($query) {
                $query->onlyLastWeekLateCancellation();
            },
        ])->whereHas('bookings', function ($booking) {
            $booking->onlyLastWeekLateCancellation();
        });
    }

    public function scopeOnlyChecked($query, int $type = 1)
    {
        return $query->where('checked', $type);
    }

    public function scopeOnlyParq($query, bool $has = true)
    {
        if ($has) {
            return $query->has('parq');
        }
        return $query->doesntHave('parq');
    }

    public function scopeOnlyNoParq($query)
    {
        return $query->onlyParq(false);
    }

    public function scopeOnlyPic($query, bool $has = true)
    {
        if ($has) {
            return $query->has('avatar');
        }
        return $query->doesntHave('avatar');
    }

    public function scopeOnlyNoPic($query)
    {
        return $query->onlyPic(false);
    }

    public function scopeWhereTyped($query, string $type = null)
    {
        switch ($type) {
            case 'checked':
                $query->onlyChecked();
                break;

            case 'notchecked':
                $query->onlyChecked(0);
                break;

            case 'parq':
                $query->onlyParq();
                break;

            case 'notparq':
                $query->onlyNoParq();
                break;

            case 'pic':
                $query->onlyPic();
                break;

            case 'notpic':
                $query->onlyNoPic();
                break;

            case 'rolling':
                $query->onlyRolling();
                break;

            case 'ends':
            case 'end_date':
                $query->onlyEnds();
                break;

            case 'month':
            case 'year':
                $query->onlyPlan($type);
                break;

            case 'free':
                $query->onlyFree();
                break;
        }

        return $query;
    }

    public function scopeSortBy($query, $column = 'CREATED_AT_ASC', $direction = 'asc')
    {
        switch ($column) {
            case 'last_login':
                $query->select("users.*")
                    ->leftJoin('logs', function ($join) {
                        $join->on('logs.logable_id', '=', "users.id")
                            ->where('logs.logable_type', '=', $this->getMorphClass())
                            ->where('logs.type', 'login');
                    })
                    ->addSelect(DB::raw('logs.created_at AS last_login_at'))
                    ->groupBy("users.id")
                    ->orderBy('last_login_at', $direction ?? 'asc');
                break;

            case 'last_update':
                $query->select("users.*")
                    ->leftJoin('logs', function ($join) {
                        $join->on('logs.logable_id', '=', "users.id")
                            ->where('logs.logable_type', '=', $this->getMorphClass())
                            ->where('logs.type', 'notes');
                    })
                    ->addSelect(DB::raw('logs.created_at AS last_update_at'))
                    ->groupBy("users.id")
                    ->orderBy('last_update_at', $direction ?? 'asc');
                break;

            case 'created_by':
                $query->select("users.*")
                    ->leftJoin('logs', function ($join) {
                        $join->on('logs.logable_id', '=', "users.id")
                            ->where('logs.logable_type', '=', $this->getMorphClass())
                            ->where('logs.type', 'created');
                    })
                    ->leftJoin('admins', function ($join) {
                        $join->on('logs.admin_id', '=', "admins.id");
                    })
                    ->addSelect(DB::raw('CASE WHEN logs.admin_id IS NOT NULL THEN admins.first_name ELSE JSON_EXTRACT(logs.options, "$.ref") END AS created_by'))
                    ->groupBy("users.id")
                    ->orderBy('created_by', $direction ?? 'asc');
                break;

            case 'last_ns_bookings':
                $query->orderBy(Booking::onlyLastWeekNoShow()->limit(1)->select('schedules_at')->whereColumn('bookings.user_id', 'users.id'), $direction ?? 'asc');
                break;

            case 'price':
                $query->leftJoin('subscriptions', function ($join) {
                    $join->on('subscriptions.user_id', '=', "users.id")->orderByDesc('created_at')->limit(1);
                })->leftJoin('plan_prices', function ($join) {
                    $join->on('plan_prices.stripe_id', '=', "subscriptions.stripe_price");
                })->leftJoin('plans', function ($join) {
                    $join->on('plans.id', '=', "plan_prices.plan_id");
                })->orderBy(DB::raw('plans.label'), $direction ?? 'asc');
                break;

            case 'name':
                $query->orderBy(DB::raw("CONCAT(`first_name`, `last_name`)"), $direction ?? 'asc');
                break;

            default:
                $query->orderBy($column ?: 'created_at', $direction ?? 'asc');
                break;
        }

        return $query;
    }

    static public function getStatsByMonthAndYear($key, $month = null, $year = null)
    {
        $user = static::onlyMember()->whereDateColumn(['month' => $month, 'year' => $year]);
        $cancelled = static::onlyCancelled()->whereDateColumn(['month' => $month, 'year' => $year], 'ends_at');

        switch ($key) {
            case 'total':
                return $user->count();
                break;

            case 'rolling':
                return $user->onlyRolling()->count();
                break;

            case 'rolling_total':
                return $user->onlyRolling()->sumAmount();
                break;

            case 'end_date':
                return $user->onlyEnds()->count();
                break;

            case 'end_date_total':
                return $user->onlyEnds()->sumAmount();
                break;

            case 'month':
            case 'year':
                return $user->onlyPlan($key)->count();
                break;

            case 'free':
                return $user->onlyFree()->count();
                break;

            case 'cancelled':
                return $cancelled->count();
                break;

            case 'cancelled_total':
                return $cancelled->sumAmount(true);
                break;

            default:
                return 0;
                break;
        }
    }

    static public function getStatsByOptions($key, array $options = [])
    {
        $user = static::onlyMember()->whereDateColumn($options);
        $cancelled = static::onlyCancelled()->whereDateColumn($options, 'ends_at');

        switch ($key) {
            case 'total':
                return $user->count();
                break;

            case 'rolling':
                return $user->onlyRolling()->count();
                break;

            case 'rolling_total':
                return $user->onlyRolling()->sumAmount();
                break;

            case 'end_date':
                return $user->onlyEnds()->count();
                break;

            case 'end_date_total':
                return $user->onlyEnds()->sumAmount();
                break;

            case 'month':
            case 'year':
                return $user->onlyPlan($key)->count();
                break;

            case 'free':
                return $user->onlyFree()->count();
                break;

            case 'cancelled':
                return $cancelled->count();
                break;

            case 'cancelled_total':
                return $cancelled->sumAmount(true);
                break;

            default:
                return 0;
                break;
        }
    }

    static public function findByQrcode($qrcode)
    {
        if ($user = static::where('qrcode', $qrcode)->first()) {
            return $user;
        } else {
            throw new ModelNotFoundException('Invalid QR code. Please provide a valid QR code.');
        }
    }

    static public function generateUniqueQRCode()
    {
        $randomInteger = mt_rand(1000000000, 9999999999);

        while (static::where('qrcode', $randomInteger)->exists()) {
            $randomInteger = mt_rand(1000000000, 9999999999);
        }

        return $randomInteger;
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->member_id)) {
                $model->member_id = now()->format('dmy');
            }
            if (empty($model->qrcode)) {
                $model->qrcode = static::generateUniqueQRCode();
            }
        });
    }
}
