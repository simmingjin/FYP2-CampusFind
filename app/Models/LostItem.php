<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LostItem extends Model
{
    protected $fillable = [
    'user_id',
    'item_name',
    'category',
    'description',
    'location',
    'lost_date',
    'image'
];
    //
}
