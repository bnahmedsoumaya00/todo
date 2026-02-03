<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // hethi t5al el user yasna3 api yoken bch ya3ml auth te3ou



class User extends Authenticatable // extends Auth.. tejbed el feats kol menou
{
    /** @use HasFactory<\Database\Factories\UserFactory> */

    use HasApiTokens, HasFactory, Notifiable;
        /**
     *
     *
     *
     *@var array<int, string>  // hethi ma3naha enou el next propoiteis bch ykounou array fihom in w string values
     */

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int,string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function todos(){
        return $this->hasMany(Todo::class); // el user ynjm ykoun andou barcha  todos hethi esmha relationship
    }
}
