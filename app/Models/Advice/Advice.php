<?php

namespace App\Models\Advice;

use Illuminate\Database\Eloquent\Model;
use App\Models\Registeration\Registeration;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Advice extends Model
{
    use HasFactory;
    protected $fillable=['link','title','body','status','created_at','updated_at'];


}

