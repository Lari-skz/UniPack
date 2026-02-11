<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    /**
     * Defining which fields can be mass assigned
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'due_date',
        'priority',
        'status',
    ];

    /**
     * Defining how attributes should be cast
     */
    protected $casts = [
        'due_date' => 'date', // Converts to Carbon date object
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * A task belongs to a user
     *
     * Example: Task "Study Laravel" belongs to user "John"
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A task belongs to a category
     *
     * Example: Task "Study Laravel" belongs to "Study" category
     * Note: nullable, so task can exist without a category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->withDefault();
    }
}
