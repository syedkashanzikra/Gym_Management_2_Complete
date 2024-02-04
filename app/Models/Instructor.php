<?php

namespace App\Models;

use Coderstm\Models\File;
use Coderstm\Traits\Core;
use Coderstm\Enum\AppStatus;
use Coderstm\Traits\Fileable;
use Coderstm\Traits\HasMorphToOne;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Instructor extends Model
{
    use Core, Fileable, HasMorphToOne;

    protected $table = 'admins';

    protected $dateTimeFormat = 'd M, Y \a\t h:i a';

    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'description',
        'urls',
        'insurance',
        'qualification',
        'document',
        'is_pt',
        'hourspw',
        'rentpw',
        'status',
    ];

    protected $casts = [
        'urls' => 'collection',
        'insurance' => 'boolean',
        'qualification' => 'boolean',
        'document' => 'boolean',
        'is_pt' => 'boolean',
    ];

    protected $with = [
        'avatar',
    ];

    protected $appends = ['name'];

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->first_name} {$this->last_name}",
        );
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(ClassList::class, 'instructor_class_lists', 'instructor_id', 'class_id')->onlyActive()->withPivot('cost');
    }

    public function syncClasses(Collection $classes, bool $detach = true)
    {
        $classes = $classes->filter(function ($class) {
            return isset($class['pivot']['cost']) && $class['pivot']['cost'] > 0;
        })->mapWithKeys(function ($class) {
            return [$class['id'] => [
                'cost' => $class['pivot']['cost'],
            ]];
        });
        if ($detach) {
            $this->classes()->sync($classes);
        } else {
            $this->classes()->syncWithoutDetaching($classes);
        }
        return $this;
    }

    public function syncClassesDetaching(Collection $classes)
    {
        return $this->syncClasses($classes, false);
    }

    public function insurance_file()
    {
        return $this->morphToOne(File::class, 'fileable')->wherePivot('type', 'insurance');
    }

    public function qualification_file()
    {
        return $this->morphToOne(File::class, 'fileable')->wherePivot('type', 'qualification');
    }

    public function document_file()
    {
        return $this->morphToOne(File::class, 'fileable')->wherePivot('type', 'document');
    }

    public function scopeOnlyActive($query)
    {
        return $query->whereStatus(AppStatus::ACTIVE);
    }

    public function scopeSortBy($query, $column = 'created_at', $direction = 'asc')
    {
        switch ($column) {
            case 'name':
                $query->select("{$this->getTable()}.*")
                    ->addSelect(DB::raw("CONCAT(`first_name`, `first_name`) AS name"))
                    ->orderBy('name', $direction ?? 'asc');
                break;

            default:
                $query->orderBy($column ?: 'created_at', $direction ?? 'asc');
                break;
        }

        return $query;
    }

    protected static function booted()
    {
        parent::booted();
        static::creating(function ($model) {
            $model->is_instructor = 1;
        });
        static::addGlobalScope('instructor', function (Builder $builder) {
            $builder->where('is_instructor', 1);
        });
    }
}
