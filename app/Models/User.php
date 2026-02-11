<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Defining which fields can be mass assigned
     */
protected $fillable = [
    'name',
    'email',
    'password',
    'is_admin',
];


    /**
     * Hiding these fields when converting to JSON
     * (Security: never expose password or tokens in API responses)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Defining how attributes should be cast
     */

protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
    'is_admin' => 'boolean',
];

    /**
     * A user has many tasks
     *
     * Example: User "John" has 10 tasks
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * A user has many events
     *
     * Example: User "John" has 5 calendar events
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
 * Get the categories for the user.
 */
public function categories()
{
    return $this->hasMany(Category::class);
}
}
