<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

use App\Models\Product;
use App\Models\Category;
use App\Models\ImageUpload;

use Carbon\Carbon;
use Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($_GET['search']) {
            $query = $_GET['search'];
            if ($_GET['filterCategory'] != 0) {
                $products = Product::where(function ($subquery) use ($query) {
                    $subquery->where('product_name', 'LIKE', "%" . $query . "%")
                        ->orWhere('stripped_description', 'LIKE', "%" . $query . "%")
                        ->orWhereHas('category', function (Builder $queryBuilder) use ($query) {
                            $queryBuilder->where('name', 'LIKE', '%' . $query . '%');
                        });
                })->where('category_id', $_GET['filterCategory'])->with('category')->latest()->paginate(5);
            } else {
                $products = Product::where(function ($subquery) use ($query) {
                    $subquery->where('product_name', 'LIKE', "%" . $query . "%")
                        ->orWhere('stripped_description', 'LIKE', "%" . $query . "%")
                        ->orWhereHas('category', function (Builder $queryBuilder) use ($query) {
                            $queryBuilder->where('name', 'LIKE', '%' . $query . '%');
                        });
                })->with('category')->latest()->paginate(5);
            }
        } else {

            if ($_GET['filterCategory'] != 0) {
                $products = Product::where('category_id', $_GET['filterCategory'])->with('category')->latest()->paginate(5);
            } else {
                $products = Product::with('category')->latest()->paginate(5);
            }
        }

        $temp = (object) array('id' => 0, 'name' => 'All');
        $categories = Category::all()->prepend($temp);

        return response()->json([
            'products'      => $products,
            'categories'    => $categories,
        ]);
    }

    public function create()
    {
        $categories = Category::all();
        $now = Carbon::now()->format('Y-m-d h:i A');

        return response()->json([
            'categories'    => $categories,
            'now'           => $now,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'product_name'  => 'required|string|unique:products',
            'category_id'   => 'required',
            'description'   => 'required',
        ]);

        if ($request->step == 1) {
            return response()->json(['step' => 1, 'status' => 200]);
        }

        $this->validate($request, [
            'images'        => 'required|array',
        ]);

        if ($request->step == 2) {
            return response()->json(['step' => 2, 'status' => 200]);
        }

        $this->validate($request, [
            'date_and_time' => 'required',
        ]);

        if ($request->step == 3) {
            return response()->json(['step' => 3, 'status' => 200]);
        }

        if (isset($request->category_id['id'])) {
            $category_id = $request->category_id['id'];
        } else {
            $category = Category::create([
                'name' => $request->category_id['value']
            ]);
            $category_id = $category->id;
        }

        $product = Product::create([
            'product_name'          => $request->product_name,
            'category_id'           => $category_id,
            'date_and_time'         => Carbon::parse($request->date_and_time),
            'description'           => $request->description,
            'stripped_description' => strip_tags($request->description),
        ]);

        if ($request->images) {
            foreach ($request->images as $key => $image) {
                $filename = time() . $key . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                $path = 'img/products/';
                $full_path = public_path('img/products/') . $filename;
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }

                Image::make($image)->save($full_path);

                ImageUpload::create([
                    'product_id'    => $product->id,
                    'file_name'     => $filename,
                    'path'          => $path,
                ]);
            }
        }

        return response()->json(['step' => 4, 'status' => 200]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id)->load('category', 'upload');
        $product->date_and_time = Carbon::parse($product->date_and_time)->format('Y-m-d h:i A');

        $categories = Category::all();

        return response()->json([
            'product'       => $product,
            'categories'    => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'product_name'  => 'required|string|unique:products,product_name,' . $id,
            'category_id'   => 'required',
            'description'   => 'required',
        ]);

        if ($request->step == 1) {
            return response()->json(['step' => 1, 'status' => 200]);
        }

        $this->validate($request, [
            'images'        => 'required|array',
        ]);

        if ($request->step == 2) {
            return response()->json(['step' => 2, 'status' => 200]);
        }

        $this->validate($request, [
            'date_and_time' => 'required',
        ]);

        if ($request->step == 3) {
            return response()->json(['step' => 3, 'status' => 200]);
        }

        if (isset($request->category_id['id'])) {
            $category_id = $request->category_id['id'];
        } else {
            $category = Category::create([
                'name' => $request->category_id['value']
            ]);
            $category_id = $category->id;
        }

        $product = Product::find($id)->update([
            'product_name'          => $request->product_name,
            'category_id'           => $category_id,
            'date_and_time'         => Carbon::parse($request->date_and_time),
            'description'           => $request->description,
            'stripped_description' => strip_tags($request->description),
        ]);

        if ($request->images) {
            foreach ($request->images as $key => $image) {
                if (explode('/', $image)[0] == 'data:image') {
                    $filename = $key . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                    $path = 'img/products/';
                    $full_path = public_path('img/products/') . $filename;
                    if (!file_exists($path)) {
                        mkdir($path, 0755, true);
                    }

                    Image::make($image)->save($full_path);

                    ImageUpload::create([
                        'product_id'    => $id,
                        'file_name'     => $filename,
                        'path'          => $path,
                    ]);
                }
            }
        }

        if ($request->removeimages) {
            foreach ($request->removeimages as $removeimage) {
                $filename = explode('/', $removeimage)[3];
                unlink(public_path($removeimage));

                ImageUpload::where('file_name', $filename)->delete();
            }
        }

        return response()->json(['step' => 4, 'status' => 200]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id)->load('category', 'upload');

        foreach ($product->upload as $image) {
            unlink(public_path($image->path . $image->file_name));

            $image->delete();
        }

        $product->delete();
    }
}
