<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class Customer extends Model
{
    use HasFactory, Prunable;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['service_id', 'customer_type', 'customer_id', 'queued_at'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'queued_at' => 'datetime:H:i',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['customer'];

    /**
     * The accessors to append to the customer's array form.
     *
     * @var array
     */
    protected $appends = ['avatar', 'full_name', 'name', 'type'];

    /**
     * Get the customer's avatar.
     *
     * @return string
     */
    public function getAvatarAttribute()
    {
        return "https://eu.ui-avatars.com/api/?name={$this->name}";
    }

    /**
     * Get the customer's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        if ($this->customer && $this->customer->title) {
            return "{$this->customer->title} {$this->name}";
        }

        return $this->name;
    }

    /**
     * Get the customer's name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        if (blank($this->customer_id)) {
            return 'Anonymous';
        }

        return $this->customer->name;
    }

    /**
     * Get the customer type.
     *
     * @return string
     */
    public function getTypeAttribute()
    {
        if (blank($this->customer_type)) {
            return 'Anonymous';
        }

        return class_basename($this->customer_type);
    }

    /**
     * Get the parent customer model (citizen or organisation).
     */
    public function customer()
    {
        return $this->morphTo();
    }

    /**
     * Get the parent service.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the prunable model query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function prunable()
    {
        return static::where('queued_at', '<=', now()->startOfDay());
    }
}
