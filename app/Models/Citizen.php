<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Citizen extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'first_name', 'last_name'];

    /**
     * The accessors to append to the citizen's array form.
     *
     * @var array
     */
    protected $appends = ['name'];

    /**
     * Get the citizen's full name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return "{$this->title} {$this->first_name} {$this->last_name}";
    }

    /**
     * Set the citizen's first name.
     *
     * @param  string  $value
     * @return void
     */
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = ucfirst($value);
    }

    /**
     * Set the citizen's first name.
     *
     * @param  string  $value
     * @return void
     */
    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = ucfirst($value);
    }

    /**
     * Get the citizen's customer record.
     */
    public function customer()
    {
        return $this->morphOne(Customer::class, 'customer');
    }
}
