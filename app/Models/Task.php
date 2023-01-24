<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /*
     * task status:
     * 0 - опубликовано
     * 1 - выполнено в срок
     * 2 - выполнено после срока
     * 3 - просрочено не выполнено
     * */


    use HasFactory;
    public function from_u(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,'from_user');
    }
    public function to_u(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user');
    }
}
