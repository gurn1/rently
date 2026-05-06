<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['property_manager_id','title','slug','description','key_features','address','latitude','longitude','price','property_type','bedrooms','bathrooms','size','availability_status'])]
class Property extends Model
{
    use HasFactory;
}
