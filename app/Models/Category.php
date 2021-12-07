<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Category extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
       return $this->belongsTo(Category::class , 'parent_id');  // ارتباط با خود جدول که متعلق به کل جدول
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Category::class , 'parent_id');
    }



    public function newCategory( Request $request)
    {
        $this->query()->create([
            'title' => $request->title,
            'parent_id' => $request->parent_id,
        ]);
    }

    public function updateCategory(Request $request)
    {
        $this->update([
           'title' => $request->title,
           'parent_id' => $request->parent_id,
        ]);
    }


    public function deleteCategory(Category $category): ?bool
    {
        return $category->delete();
    }


}
