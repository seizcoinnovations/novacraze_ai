<?php

namespace App\Yantrana\Components\Subvendor\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Yantrana\Base\BaseModel;

class SubVendor extends BaseModel
{
    protected $table = 'sub_vendors';

    protected $fillable = [];

    // public function category()
    // {
    //     return $this->belongsTo(Category::class);
    // }
}
