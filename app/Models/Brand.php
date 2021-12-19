<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];


    public function products()
    {
        return $this->hasMany(Product::class);
    }



    public function newBrand(Request $request)
    {
        $imagePath = Carbon::now()->microsecond . '_' . time() . '.' . $request->image->extension();
        $request->image->storeAs('images/brands/', $imagePath, 'public');  // 1)address  2)image  3)Directory Public

        $this->query()->create([
            'title' => $request->title,
            'image' => $imagePath,
        ]);
    }


    public function updateBrand(Request $request)
    {
        if ($request->has('image')) {
//          Storage::delete($request->image);
            $imagePath = Carbon::now()->microsecond . "_" . time() . "." . $request->image->extension();
            $request->image->storeAs('images/brands/', $imagePath, 'public');
        }

        $this->update([
            'title' => $request->title,
            'image' => $request->has('image') ? $request->image : $this->image,      //? if exist request (create) image save : image now
        ]);
    }


    public function deleteBrand(Brand $brand): ?bool
    {
        unlink('storage/images/brands/' . $brand->image);
//      \Storage::delete($product->image);
        return $brand->delete();
    }

}
