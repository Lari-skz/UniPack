<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /**
     * Define which fields can be mass assigned
     * (Security: prevents users from injecting dangerous fields)
     */
    protected $fillable = [
        'name',
        'description',
        'color',
    ];

    /**
     * A category has many tasks
     *
     * Example: "Study" category has 5 tasks
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
