<?php

namespace App\Yantrana\Components\Subvendor\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Yantrana\Base\BaseModel;

class Category extends BaseModel
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [];

    public function subvendors()
    {
        return $this->hasMany(SubVendor::class);
    }
}
