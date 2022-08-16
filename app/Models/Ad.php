<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',//img , video
        'video',
        'image',
        'start_date',
        'end_date',
        'user_id',
        'place_id',
        'plat_form',//desktop, mobile, web
    ];
}
