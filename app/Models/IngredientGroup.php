<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngredientGroup extends Model
{
    protected $fillable = ['name', 'description'];

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'ingredient_group_items');
    }
}
