<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ThemeCategory extends Authenticatable
{

    public $table = 'themeCategories';

    public function category()
    {
        return $this->belongsTo(Category::class, 'categoryId', 'id');
    }
}
