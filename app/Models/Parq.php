<?php

namespace App\Models;

use Coderstm\Models\Log;
use Coderstm\Traits\Logable;
use Coderstm\Traits\SerializeDate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Parq extends Model
{
    use Logable, SerializeDate;

    protected $fillable = [
        'name',
        'dob',
        'accept',
        'email',
        'questions',
        'emergency_contact_name',
        'emergency_contact_phone_number',
        'allergies',
        'seen',
    ];

    protected $casts = [
        'accept' => 'boolean',
        'seen' => 'boolean',
        'questions' => 'collection',
    ];

    protected $with = [
        'updatedBy.admin',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function updatedBy()
    {
        return $this->morphOne(Log::class, 'logable')->latestOfMany();
    }
}
