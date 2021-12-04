<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::apiResource('/brands', BrandController::class);
Route::apiResource('/category' , CategoryController::class);


// ---------------------------------- Laravel Api   Lesson 25           00 : 00 (+2)  ------------------------------------

/*
**composer  ----> composer remove laravel/passport  (name package)

//---------------------------------------------------------- route
Route::get("/posts", [PostController::class, "index"]);
Route::get("/posts/{post}", [PostController::class, "show"]);
Route::post("/posts", [PostController::class, "store"]);
Route::put("/posts/{post}", [PostController::class, "update"]);
Route::delete("/posts/{post}", [PostController::class, "destroy"]);


return response()->json([       // api -> response()->json([])   نمایش اطلاعات
    'name' => 'mahdi99k',
    'lastname' => 'shm',
]);

//------------------------------------------------ trait
<?php

namespace App\Traits;
use App\Models\Post;

trait ApiResponse               // شبیه سرویس ها | لازم use

{

    protected function successResponse($code, $data, $message = null)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function errorResponse($code, $message = null)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $code);
    }

}


//------------------------- 2 make ApiController
<? php

namespace App\Http\Controllers;


use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ApiController extends Controller
{

  use ApiResponse;         // استفاده میکنه از تریت و باید use کرد | وصل شدده به کنترلر پست کنترلر

}

//---------------------------------------------------- PostController

//------------------------- index
publicfunction index()
{
//   return $this->successResponse(200 , Post::all() , 'Ok Connection');
     return $this->errorResponse(500, 'No Connection');
}

//------------------------- store Validate

public function store(Request $request, Post $post)
{
    $validate = Validator::make($request->all(), [
        'title' => 'required|string|max:100',
        'slug' => 'required|string|max:255',
        'image' => 'required',
        'alt' => 'required|string|max:100',
        'content' => 'required|string|max:1000',
        'user_id' => 'required',
    ]);

    if ($validate->fails()) {
        return $this->errorResponse(400, $validate->messages());
    }

    $post->newPost($request);                                    // clean code  connect model functional
    $data = $post->orderBy('id', 'desc')->first();             // return end data
    return $this->successResponse(200, $data, 'create post successfully ');
}


//---------------------------------------------------------------------------  Exception Handler.php   مدیریت خطاهای کلی سایت

public function render($request, Throwable $e)                      // error 404 api | برای خطاهای درون مادل وقتی برنگردونه
{
    if ($e instanceof ModelNotFoundException) {
        return $this->errorResponse(404, $e->getMessage());
    }

    if (config('app.debug')) {                                       // بیاد خطاهای واقعی sql و server به کاربر نمایش نده
        return Parent::render($request, $e);
    }

    return $this->errorResponse(500, $e->getMessage());              // اگر غیر از بالایی بود خطای dd500
}

//------------------------------------------------------------------ model CRUD

class Post extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function newPost(Request $request)
    {
        $imagePath = Carbon::now()->microsecond . '.' . $request->image->extension();    //به عکس زمان حال و دیتا تایم اضافه
        $request->image->storeAs('image/posts', $imagePath, 'public');  // 1-address create folder   2-name image  3-part upload

        $this::query()->create([
            "title" => $request->title,
            "slug" => $request->slug,
            "image" => $imagePath,
            "alt" => $request->alt,
            "content" => $request->get('content'),     // get() این دیتا ها مخصوص دیتابیس اسمشون قاطی نشه با اسم های از پیش تعریف شده لاراول
            "user_id" => 1,
        ]);
    }


    public function updatePost(Request $request)
    {
        if ($request->has('image')) {
            $imagePath = Carbon::now()->microsecond . '.' . $request->image->extension();
            $request->image->storageAs('image/posts', $imagePath, 'public');
        }

        $this->update([
            "title" => $request->title,
            "slug" => $request->slug,
            "image" => $request->has('image') ? $imagePath : $this->image,       // اگر عکسی بود جایگزین کن اگه نبود قبلی
            "alt" => $request->alt,
            "content" => $request->get('content'),     // get() این دیتا ها مخصوص دیتابیس اسمشون قاطی نشه با اسم های از پیش تعریف شده لاراول
            "user_id" => 1,
        ]);
    }


    public function deletePost(Post $post)
    {
        $post->delete();
    }

}





//------------------------------------------------------------------ controller CRUD

class PostController extends ApiController
{

    public function index()
    {
//      return $this->successResponse(200 , Post::all() , 'Ok Connection');
        return $this->errorResponse(500, 'No Connection');
    }


    public function show(Post $post)
    {
        return $this->successResponse(200, $post, "Get Ok");
    }


    public function store(Request $request, Post $post)
    {
        $validate = Validator::make($request->all(), [
            "title" => 'required|string|max:100',
            "slug" => 'required|string|max:255',
            "image" => 'required|image|mimes:png,PNG,jpg,jpeg,svg,mpeg|min:10|max:1024',
            "alt" => 'required|string|max:100',
            "content" => 'required|string|max:1000',
            "user_id" => 'required',
        ]);

        if ($validate->fails()) {
            return $this->errorResponse(400, $validate->messages());
        }

        $post->newPost($request);                                    // clean code  connect model functional
        $data = $post->orderBy('id', 'desc')->first();             // return end data
        return $this->successResponse(200, $data, 'create post successfully ');
    }


    public function update(Request $request, Post $post)
    {
        $validate = Validator::make($request->all(), [
            "title" => 'required|string|max:100',
            "slug" => 'required|string|max:255',
            "image" => 'image',
            "alt" => 'required|string|max:100',
            "content" => 'required|string|max:1000',
            "user_id" => 'required',
        ]);

        if ($validate->fails()) {
            return $this->errorResponse(400, $validate->messages());
        }

        $post->updatePost($request);                                    // clean code  connect model functional
        return $this->successResponse(200, $post, 'update post successfully ');
    }


    public function destroy(Post $post)
    {
        $post->deletePost($post);
        return $this->successResponse(200, $post, 'post deleted successfully ');
    }

}

//------------------------------------------------ ApiResources  منابع api
Generating Resources
 To generate a resource class, you may use the make:resource Artisan command. By default, resources will be placed in
the app/Http/Resources directory of your application. Resources extend the Illuminate\Http\Resources\Json\JsonResource class:

*** php artisan make:resource UserResource            // نحوه ساخت ریسورسز



public function show(Post $post)
{
//  return $this->successResponse(200, $post, "Get Ok");

    $dataResponse = new PostResource($post);                         // از طرق ریسورس تشخصی میده پست نمایش میده
    return $this->successResponse(200, $dataResponse, "Get Ok");

//  return new PostResource($post);                               // به صورت خودکار تشخصی میده نمایش میده
}

//------------------------------------------------ ApiResources  منابع api  |  چیزی که میخواهیم نمایش بده


public function toArray($request)
{
//      return parent::toArray($request);   // تمام دیتاها برمیگردونه

    return [                     // ائنایی که خودمون بخواهیم برگردونه
        'id' => $this->id,
        'title' => strtoupper($this->title),  // حروف بزرگ
        'content' => strtolower($this->content),
        'created_at' => $this->created_at->format('Y-m-d | H:i:s'),
    ];

}


//------------------------------------------------------------------ relationship + insert data in resource
//-------------------------- relationship user
public function posts()
{
    return $this->hasMany(Post::class);
}

//------------------ controller user
    public function index()
{
    $user = User::all();
    $dataResponse = UserResource::collection($user->load('posts'));   //load روابط بین یوزر و پست ها مشخص میکنیم مثل with()
    return $this->successResponse(200 , $dataResponse , 'get ok');
}


//------------------- userResource

    public function toArray($request)
{
//      return parent::toArray($request);
    return [
        'id' => $this->id,
        'name' => $this->name,
        'email' => $this->email,
        'created_at' => $this->created_at,
        'posts' => PostResource::collection($this->whenLoaded('posts')), //قسمت های دلخواه تو پست برمیگردونه //whenLoaded وقتی لود یا بارگیری شد نمایش بده
//       'posts' => $this->whenLoaded('posts'), // همه قسمت های جدول پست برمیگردونه
        'response' => $this->slug,
    ];

}

//-------------------------  Controller Post and insert data in resource

    public function show(Post $post)
{
//      return $this->successResponse(200, $post, "Get Ok");

//      return new PostResource($post); // اسم اصلی که مجموعه ای از کالکشن درونش قرار میگیره تغییر میده

    $dataResponse = new PostResource($post);
    return $dataResponse->additional([     // برای اضافه کردن مقدار درون جدول موقع نمایش فقط
        'food' => [
            'slug' => $post->slug,
        ]
    ]);
//      return $this->successResponse('200', $dataResponse, 'Get Ok');
}

//--------------------------------------------------------------------------------------------------------------- paginator

public function index()
{
    $posts = Post::paginate(3);
//  return PostResource::collection($posts); // هم میشه تنها از طریق ریسورس برد یا رو ش پایین بهتر

    return $this->successResponse(200, [
        'posts' => PostResource::collection($posts),
        'links' => PostResource::collection($posts)->response()->getData()->links,                          // paginator orginal
        'meta' => PostResource::collection($posts)->response()->getData()->meta,                          // paginator مشخصات فعال و غیرفعال
    ], 'Ok Connection');                                                                       / * مجموعه ای برمیگردونه | این روش بهتر * /

}


//--------------------------------------------------------------------------------------------------------------- paginator

  $posts = Post::paginate(3);

   return $this->successResponse(200, [
       'posts' => PostResource::collection($posts),
       'links' => PostResource::collection($posts)->response()->getData()->links,                          // paginator orginal
       'meta' => PostResource::collection($posts)->response()->getData()->meta,                          // paginator مشخصات فعال و غیرفعال
   ], 'Ok Connection');                                                                       / * مجموعه ای برمیگردونه | این روش بهتر * /



//--------------------------------------------------------------------------------------------------------------- version api
//--------------- api.php
Route::prefix('v1')->group(function () {       //127.0.0.1:8000/api/v1/posts/

    Route::get("/posts", [V1PostController::class, "index"])->name('post.index');
    Route::get("/posts/{post}", [V1PostController::class, "show"]);
    Route::post("/posts", [V1PostController::class, "store"]);
    Route::put("/posts/{post}", [V1PostController::class, "update"]);
    Route::delete("/posts/{post}", [V1PostController::class, "destroy"]);
    Route::apiResource('users', userController::class);

});


Route::prefix('v2')->group(function () {       //127.0.0.1:8000/api/v2/posts/

    Route::get("/posts", [V2PostController::class, "index"])->name('post.index');
    Route::get("/posts/{post}", [V2PostController::class, "show"]);
    Route::post("/posts", [V2PostController::class, "store"]);
    Route::put("/posts/{post}", [V2PostController::class, "update"]);
    Route::delete("/posts/{post}", [V2PostController::class, "destroy"]);
    Route::apiResource('users', userController::class);

});


//--------------- part 2     changeController
Http -> Controller -> Api -> V1 -> PostController.php
Http -> Controller -> Api -> V2 -> PostController.php


//--------------- part 3      changeResource
app -> Resource -> V1 -> PostResource.php
app -> Resource -> V2 -> PostResource.php





//--------------------------------------------------------------------------------------------------------------- authentication api

// web (session)  -->  api (token) jwt (JsonWebToken is unique for user)
// api package authentication  -->  laravel passport (O-auth -> open Authentication very security) & laravel sanctum
// O-auth --> response user go server  &  server response go google  & google return back response server go (email,name *No Password) in the save website .
// Fa-> درخواست کاربر به سمت سرور |  درخواست سرور به سمت گوگل |  گوگل یک ایمیل برمیگردونه سمت سرور که آیا این ایمیل و اسم به جز پسوورد شما ذخیره بشه درون سایت و رجیستر کنید
// sanctum  --> use session base  |  laravel update before sanctum
// laravel sanctum (web)   |   laravel passport(api)


//--------------------------------------------------------------------------------------------------------------- install package passport
**composer  ---------->  composer remove laravel/passport  (name package)

//------------------------------------------

1) laravel -> packages -> passport  -> composer require laravel/passport
2) php artisan migrate
3) php artisan passport:install

4) After running the passport:install command, add the Laravel\Passport\HasApiTokens trait to your App\Models\User model.
//use Laravel\Sanctum\HasApiTokens;   // change
  use Laravel\Passport\HasApiTokens;

5) Next, you should call the Passport::routes method within the boot method of your App\Providers\AuthServiceProvider.
  *use Laravel\Passport\Passport;
   if (! $this->app->routesAreCached()) {
       Passport::routes();
   }

6)Finally, in your application's config/auth.php configuration file, you should set the driver option of the api authentication guard to passport.
    'api' => [
        'driver' => 'passport',
        'provider' => 'users',
    ],

//--------------------------------------------------------------------------------------------------------------- AuthController Passport

//------------------- web.php
Route::post('register' ,[AuthController::class , 'register']);

//------------------- AuthController
public function register(Request $request)
{
    $validate = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required',
        'c_password' => 'required|same:password',   // same:password شبیه پسوورد باش
    ]);

    if ($validate->fails()) {
        return $this->errorResponse('422', $validate->messages());
    }

    $user = \App\Models\User::query()->create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),          // hash security in database
    ]);
    $token = $user->createToken('mahdi')->accessToken;    //createToken بساز یک توکن | accessToken توکن برگردون

    return $this->successResponse('201', [
        'user' => $user,
        'token' => $token,
    ], 'user created successfully');

}

//--------------------------


//--------------------------------------------------------------------------------------------------------------- Login AuthController

//-------------------------------- web.php
Route::get("/posts", [V2PostController::class, "index"])->name('post.index')->middleware('auth:api');

Route::post('login' ,[AuthController::class , 'login']);


//-------------------------------- AuthController
public function login(Request $request)
{

    $validate = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email',  // exist: باید وجود داشته باشد قبلا ثبت نام کرده باشد
        'password' => 'required',
    ]);

    if ($validate->fails()) {
        return $this->errorResponse('422', $validate->messages());
    }

    $user = User::query()->where('email', '=', $request->email)->first();
    if (!Hash::check($request->password, $user->password)) {
        return $this->errorResponse('422', 'password is incorrect');
    }

    $token = $user->createToken('mahdi')->accessToken;    //createToken بساز یک توکن | accessToken توکن برگردون
    return $this->successResponse('200', [
        'user' => $user,
        'token' => $token,
    ], 'user login website');

}

// -------------- Authentication
1) api token user copy
2) post list -> Bearer Token  -> eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.e....



//--------------------------------------------------------------------------------------------------------------- logout AuthController

//-------------------------------- web.php
Route::post('logout' ,[AuthController::class , 'logout'])->middleware('auth:api');


//-------------------------------- AuthController (logout)

public function logout()
{
    auth()->user()->tokens()->delete();  // the all token delete
    return $this->successResponse('200' , 'logged out' , 'logout ...' ) ;
}

// -------------- Authentication
1) api token user copy
2) Headers -> Accept(key) -> application/json(value)
3) post list -> Bearer Token  -> eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.e....


//--------------------------------------------------------------------------------------------------------------- Installation Sanctum پناهگاه
**composer  ---------->  composer remove laravel/passport  (name package)

//-------------------------------------------

*imporatnt) laravel 8 ->  installed auto in inside laravel 8 *sanctum

1) install  ----->  composer require laravel/sanctum   خودکار تو لاراول 8 نصب
2) *install migrate vendor:publish  ----->  php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
3) Finally, you should run your database migration  ----->  php artisan migrate Or migrate:fresh پاک میکنه همه دوباره میسازه

4) After running the passport:install command, add the Laravel\Sanctum\HasApiTokens trait to your App\Models\User model.
//use Laravel\Passport\HasApiTokens;  // change
  use Laravel\Sanctum\HasApiTokens;


5) Next, you should call the Passport::routes method within the boot method of your App\Providers\AuthServiceProvider.
  *use Laravel\Passport\Passport;
   if (! $this->app->routesAreCached()) {
       Passport::routes();
   }

6)Finally, in your application's config/auth.php configuration file, you should set the driver option of the api authentication guard to passport.
    'api' => [
        'driver' => 'passport',
        'provider' => 'users',
    ],


//--------------------------------------------------------------------------------------------------------------- sanctum (Register,Login,LohOut)

class AuthController extends ApiController
{

    //-------------------------------------- register

    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'c_password' => 'required|same:password',   // same:password شبیه پسوورد باش
        ]);

        if ($validate->fails()) {
            return $this->errorResponse('422', $validate->messages());
        }

        $user = User::query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),          // hash security in database
        ]);
        $token = $user->createToken('myApp')->plainTextToken;    //createToken بساز یک توکن | plainTextToken  توکن sanctum(PersonalAccessToken) برگردون
//      $token = $user->createToken('myApp')->accessToken;     // createToken بساز یک توکن  | accessToken   توکن passport(JWT) برگردون

        return $this->successResponse('201', [
            'user' => $user,
            'token' => $token,
        ], 'user created successfully');

    }


    //-------------------------------------- login

    public function login(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);

        if ($validate->fails()) {
            return $this->errorResponse('422', $validate->messages());
        }

        $user = User::query()->where('email', '=', $request->email)->first();
        if (!Hash::check($request->password, $user->password)) {
            return $this->errorResponse('422', "password is incorrect");
        }

        $token = $user->createToken('mahdi')->plainTextToken;    //createToken بساز یک توکن | accessToken توکن برگردون
        return $this->successResponse('200', [
            'user' => $user,
            'token' => $token,
        ], 'user login website ^-^');

    }


    //-------------------------------------- logout

    public function logout()
    {
        auth()->user()->tokens()->delete();  // the all token delete
        return $this->successResponse('200' , 'logged out' , 'logout...');
    }

}


//--------------------------------------------------------------------------------------------------------------- change Api sanctum with passport

  $token = $user->createToken('myApp')->plainTextToken;       //createToken بساز یک توکن | plainTextToken  توکن sanctum(PersonalAccessToken) برگردون
//$token = $user->createToken('myApp')->accessToken;        // createToken بساز یک توکن  | accessToken   توکن passport(JWT) برگردون

  Route::post('logout' , [SanctumAuthController::class , "logout"])->middleware('auth:sanctum');     // sanctum
//Route::post('logout' , [SanctumAuthController::class , "logout"])->middleware('auth:api');        // passport



//----------------------------------------------------------------------------------------------------------------------
//======================================================================================================================
//**********************************************************************************************************************



//--------------------------------------------------------------------------------------------------------------- project model brand

//link storage in public  ---->  php artisan storage:link

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
            $imagePath = Carbon::now()->microsecond . "_" . time() . "." . $request->image->extension();
            $request->image->storeAs('images/brands/', $imagePath, 'public');
        }

        $this->update([
            'title' => $request->title,
            'image' => $request->has('image') ? $request->image : $this->image,   //? if exist request image save : image now
        ]);
    }


    public function deleteBrand(Brand $brand): ?bool
    {
        return $brand->delete();
    }


//---------------------------------- controller brand

    public function index()
    {
        $brand = Brand::all();
        return $this->successResponse(200, BrandResource::collection($brand), 'brand get ok');
    }


    public function store(Request $request, Brand $brand)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required|string|max:250|unique:brands,title',
            'image' => 'required|image|mimes:jpg,jpeg,png,svg|max:5000',
        ]);

        if ($validate->fails()) {
            return $this->errorResponse(422, $validate->messages());
        }

        $brand->newBrand($request);
        $dataResponse = $brand->orderBy('id', 'desc')->first();
        return $this->successResponse(201, new BrandResource($dataResponse), 'brand created successfully');

    }


    public function show($id)
    {
        //
    }


    public function update(Request $request, Brand $brand)
    {
        //اگر عنوان برابر عنوان درون دیتابیس بود و اگر ایدی دو بار تکرار شده بود و وجود داشت یعنی یونیک نبوده
        $brandUnique = Brand::query()->where('title', '=', $request->title)->where('id', '!=', $brand->id)->exists();
        if ($brandUnique) {
            return $this->errorResponse(422, "The title has already been taken!");
        }

        $validate = Validator::make($request->all(), [
            'title' => 'required|string|max:250',
            'image' => 'image|mimes:jpg,jpeg,png,svg|max:5000',
        ]);

        if ($validate->fails()) {
            return $this->errorResponse(422, $validate->messages());
        }


        $brand->updateBrand($request);
        return $this->successResponse(200, new BrandResource($brand), 'brand updated successfuly');
    }


    public function destroy(Brand $brand)
    {
        $brand->deleteBrand($brand);
        return $this->successResponse(200, $brand->title . ' ' . 'deleted successfully');
    }


//---------------------------------------------------------------------------------------------------------------




*/

