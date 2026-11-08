<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoundItem extends Model
{
    protected $fillable = [
    'user_id',
    'item_name',
    'category',
    'description',
    'location',
    'storage_location',
    'found_date',
    'image'
];
    //
}
