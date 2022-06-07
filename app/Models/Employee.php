<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Employee extends Model
{
    use HasFactory;

    protected $table = "employees";

    protected $fillable = ['category_id', 'name', 'phone', 'hobby', 'profile_pic'];

    protected $with = ['category'];
    public function category(){
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
