<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dialog extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function reciver(){
        return $this->hasOne(User::class, 'id', 'reciver_id');
    }
}
