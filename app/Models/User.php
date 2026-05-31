<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Ticket;
use App\Models\TicketComment;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isTechnician()
    {
        return $this->role === 'technician';
    }

    public function isStaff()
    {
        return in_array($this->role, ['admin', 'technician']);
    }

    public function ticketComments()
    {
        return $this->hasMany(TicketComment::class);
    }
}
