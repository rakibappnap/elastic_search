<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Theme extends Authenticatable
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'caption',
        'type',
    ];

    public $table = 'themes';

    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'userId');
    }

    public function categories()
    {
        return $this->hasMany(ThemeCategory::class, 'themeId', 'themeId')->where('status', '=', 1);
    }
}
