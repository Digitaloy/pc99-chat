<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'message', 'is_bot'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
