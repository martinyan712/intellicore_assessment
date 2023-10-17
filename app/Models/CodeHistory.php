<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeHistory extends Model
{
    use HasFactory;

    protected $table = 'codehistory';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'isActive',
        'expired_at',
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


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'isActive' => 'boolean',
        'expired_at'=>'datetime'
    ];
}
