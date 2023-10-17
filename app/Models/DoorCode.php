<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoorCode extends Model
{
    use HasFactory;

    protected $table = 'doors_codes';

    protected $fillable = [
        'code_id',
        'door_id'
    ];


    public function codes()
    {
        return $this->hasMany(Code::class,'id');
    }

    public function doors()
    {
        return $this->hasMany(Door::class,'id');
    }
}
