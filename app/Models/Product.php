<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;


class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    //------- start relationships

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');  //هر چند تا محصول می تواند فقط متعلق به یک دسته بندی باشد
    }

    public function brand(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');  //هر چند تا محصول می تواند فقط متعلق به یک برند باشد
    }

    public function galleries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Gallery::class, 'product_id');  //هر محصول دارد تعداد زیادی گالری و عکس
    }


    //------- end relationships

    public function newProduct(Request $request)
    {

        $imagePath = Carbon::now()->microsecond . '_' . time() . '.' . $request->image->extension();
        $request->image->storeAs('images/products/', $imagePath, 'public');  //1)address  2)image  3)public directory

        $this->query()->create([
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'image' => $imagePath,
            'price' => $request->price,
            'quantity' => $request->quantity,
        ]);
    }


    public function updateProduct(Request $request)
    {
        if ($request->has('image')) {
//          Storage::delete($request->image);
            $imagePath = Carbon::now()->microsecond . "_" . time() . "." . $request->image->extension();
            $request->image->storeAs('images/products/', $imagePath, 'public');
        }

        $this->update([
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'image' => $request->has('image') ? $request->image : $this->image,   //? if exist request (create) image save : image now
            'price' => $request->price,
            'quantity' => $request->quantity,
        ]);
    }


    public function deleteProduct(Product $product): ?bool
    {
        unlink('storage/images/products/' . $product->image);
//      \Storage::delete($product->image);
        return $product->delete();
    }


    //------gallery
    public function newGallery(Request $request)
    {
        if ($request->has('path')) {  //exist image , imagesArray

            foreach ($request->path as $images) {
                $imageGalleryPath = Carbon::now()->microsecond . time() . '.' . $images->extension();//$images -> array image (foreach)
                $images->storeAs('images/galleries/', $imageGalleryPath, 'public');  // 1)address  2)image  3)Directory Public

                $this->galleries()->create([
                    'product_id' => $this->id,
                    'path' => $imageGalleryPath,
                    'mime' => $images->getClientMimeType(),
                ]);
            }

        }
    }


    public function deleteGallery(Gallery $gallery)
    {
//      Storage::delete($gallery->path);  //not ok , most for web.php
//      unlink('storage/images/galleries/' . $gallery->path);
        unlink(public_path('storage/images/galleries/' . $gallery->path)); //public_path -> path public
        $gallery->delete();
    }


}
