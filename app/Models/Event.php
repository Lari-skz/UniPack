<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    /**
     * Defining which fields can be mass assigned
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'event_date',
        'event_time',
        'color',
    ];

    /**
     * Defining how attributes should be cast
     */
    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime', // Stores as datetime for better handling
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * An event belongs to a user
     *
     * Example: Event "Math Exam" belongs to user "John"
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
