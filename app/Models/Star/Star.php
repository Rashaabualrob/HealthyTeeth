<?php

namespace App\Models\Star;

use Illuminate\Database\Eloquent\Model;
use App\Models\Registeration\Registeration;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Star extends Model
{
    use HasFactory;
    protected $fillable=['topic','sum','clients_num','status','created_at',	'updated_at'];


}

