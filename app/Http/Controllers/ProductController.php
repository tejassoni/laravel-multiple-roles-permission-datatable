<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Models\ProductImagePivot;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Requests\ProductStatusUpdateRequest;

class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        //KEY : MULTIPERMISSION
        $this->middleware('permission:product-list|product-create|product-edit|product-show|product-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:product-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:product-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:product-delete', ['only' => ['destroy']]);
        $this->middleware('permission:product-show', ['only' => ['show']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['getProductImagesHasMany', 'category.subcategories'])->orderBy('updated_at', 'desc')->get();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parent_category = Category::where('status', Category::STATUS_ACTIVE)->get();
        //   $subCategories = SubCategory::where('status', Category::STATUS_ACTIVE)->get();
        return view('products.create', compact('parent_category', /*'subCategories'*/));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        try {
            // inserted main product details data at product table
            $created = Product::firstOrCreate(['name' => $request->name, 'description' => $request->description,'status' => $request->status,'price' => $request->price, 'qty' => $request->qty, 'user_id' => auth()->user()->id]);
            // multiple images upload
            if ($request->hasFile('images')) { // Images founds               
                $filehandle = $this->_multipleFileUploads($request, 'images', 'public/products');
                if ($filehandle['status']) { // files are uploaded successfully
                    $productImgsDetailsArr = [];
                    foreach ($filehandle['data'] as $keyFile => $valFile) {
                        $productImgsDetailsArr[] = ['filename' => $valFile['name'], 'filemeta' => json_encode($valFile), 'product_id' => $created->id, 'created_at' => now(), 'updated_at' => now()];
                    }
                    // Insert Bulk Product Images data
                    ProductImagePivot::insertOrIgnore($productImgsDetailsArr);
                }
            }
            // prepare cateogy_product table relational data logic
            $productCategoriesArr = [];
            foreach ($request->select_parent_cat as $keyCat => $valCat) {
                $productCategoriesArr[] = ['category_id' => $valCat, 'sub_category_id' => $request->select_sub_cat[$keyCat]];
            }
            // insert cateogy_product table relational data
            if (sizeof($productCategoriesArr) > 0) {
                $created->category()->sync($productCategoriesArr);
            }
            if ($created) { // inserted success
                \Log::info(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Success insert data : " . json_encode([request()->all(), $created]));
                return redirect()->route('products.index')
                    ->withSuccess('Created successfully...!');
            }
            throw new \Exception('fails not created..!', 403);
        } catch (\Illuminate\Database\QueryException $e) { // Handle query exception
            \Log::error(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Error Query inserting data : " . $e->getMessage() . '');
            // You can also return a response to the user
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "error occurs failed to proceed...! " . $e->getMessage());
        } catch (\Exception $e) { // Handle any runtime exception
            \Log::error(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Error inserting data : " . $e->getMessage() . '');
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "error occurs failed to proceed...! " . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->with(['getProductImagesHasMany', 'category.subcategories']);
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->with(['getProductImagesHasMany', 'category.subcategories']);        
        $parent_category = Category::where('status', Category::STATUS_ACTIVE)->get();
        return view('products.edit', compact('product', 'parent_category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        try {            
            if ($request->filled('img_delete')) { // multiple files delete from database and storage folders
                $imageIds = explode(',', $request->img_delete);
                $storageFolder = '/public/products/';
                $this->deleteMultipleImages($imageIds, $storageFolder);
            }            
            
            if ($request->hasFile('images')) { // // multiple images upload at storage folder and add data to table
                $filehandle = $this->_multipleFileUploads($request, 'images', 'public/products/');
                if ($filehandle['status']) { // files are uploaded successfully
                    $productImgsDetailsArr = [];
                    foreach ($filehandle['data'] as $valFile) {
                        $productImgsDetailsArr[] = ['filename' => $valFile['name'], 'filemeta' => json_encode($valFile), 'product_id' => $product->id, 'created_at' => now(), 'updated_at' => now()];
                    }
                    // Insert Bulk Product Images data
                    ProductImagePivot::insertOrIgnore($productImgsDetailsArr);
                }
            }
            // delete existed assigned product's category
            $product->category()->detach();
             // prepare cateogy_product table relational data logic             
             foreach ($request->select_parent_cat as $keyParentCat => $valParentCat) {
                $product->category()->attach($valParentCat,['sub_category_id' =>$request->select_sub_cat[$keyParentCat]]);
             } 
             // final update of product master table
             $product->update(['name' => $request->name, 'description' => $request->description,'status' => $request->status, 'price' => $request->price, 'qty' => $request->qty, 'user_id' => auth()->user()->id]);

            \Log::info(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Success updating data : " . json_encode([request()->all(), $product]));

            return redirect()->route('products.index')
                ->withSuccess('Updated Successfully...!');
        } catch (\Illuminate\Database\QueryException $e) { // Handle query exception
            \Log::error(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Error Query updating data : " . $e->getMessage() . '');
            // You can also return a response to the user
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "error occurs failed to proceed...! " . $e->getMessage());
        } catch (\Exception $e) { // Handle any runtime exception
            \Log::error(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Error updating data : " . $e->getMessage() . '');
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "error occurs failed to proceed...! " . $e->getMessage());
        }
    }

    /**
     * Delete multiple images from database and storage folder
     */
    public static function deleteMultipleImages($imageIds = [], $storageFolder = "")
    {
        if (sizeof($imageIds) > 0 && !empty($storageFolder)) {
            $fileNames = [];
            foreach ($imageIds as $valImg) {
                $prodImg = ProductImagePivot::find($valImg);
                if (Storage::exists($storageFolder . $prodImg->filename)) {
                    $fileNames[] = storage_path($storageFolder . $prodImg->filename);
                    Storage::delete($storageFolder . $prodImg->filename);
                }
                $prodImg->delete();
            } // Loops Ends        
            \Log::info(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Success delete data : " . json_encode(['deletedIds' => $imageIds, 'deletedFiles' => $fileNames]));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();
            // delete existed assigned product's category
            $product->category()->detach();
            \Log::info(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Success deleting data : " . json_encode([request()->all(), $product]));
            return redirect()->route('products.index')
                ->withSuccess('Deleted Successfully.');
        } catch (\Illuminate\Database\QueryException $e) { // Handle query exception
            \Log::error(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Error Query deleting data : " . $e->getMessage() . '');
            // You can also return a response to the user
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "error occurs failed to proceed...! " . $e->getMessage());
        } catch (\Exception $e) { // Handle any runtime exception
            \Log::error(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Error deleting data : " . $e->getMessage() . '');
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "error occurs failed to proceed...! " . $e->getMessage());
        }
    }

    /**
     * _multipleFileUploads : Complete Fileupload Handling
     * @param  Request $request
     * @param  $htmlFormFilename : input type file name
     * @param  $uploadFileToPath : Public folder paths 'foldername/subfoldername' Example public/user
     * @return File save with array return
     */
    private function _multipleFileUploads($request = "", $htmlFormFilename = "", $uploadFileToPath = "")
    {
        try {
            // check parameter empty Validation
            if (empty($request) || empty($htmlFormFilename) || empty($uploadFileToPath)) {
                throw new \Exception("Required Parameters are missing", 400);
            }
            // check if folder exist at public directory if not exist then create folder 0777 permission
            if (!file_exists($uploadFileToPath)) {
                $oldMask = umask(0);
                mkdir($uploadFileToPath, 0777, true);
                umask($oldMask);
            }

            $multiFileArr = [];
            foreach ($request->$htmlFormFilename as $imgKey => $imgVal) {
                $fileNameOnly = preg_replace("/[^a-z0-9\_\-]/i", '', basename($imgVal->getClientOriginalName(), '.' . $imgVal->getClientOriginalExtension()));
                $fileFullName = $fileNameOnly . "_" . date('dmY') . "_" . time() . "." . $imgVal->getClientOriginalExtension();
                $path = $imgVal->storeAs($uploadFileToPath, $fileFullName);
                // $imgVal->move(public_path($uploadFileToPath), $fileFullName);
                $multiFileArr[] = array('name' => $fileFullName, 'url' => url('storage/' . str_replace('public/', '', $uploadFileToPath) . '/' . $fileFullName), 'path' => \storage_path('app/' . $path), 'extenstion' => $imgVal->getClientOriginalExtension(), 'type' => $imgVal->getMimeType(), 'size' => $imgVal->getSize());
            }
            $resp['status'] = true;
            $resp['data'] = $multiFileArr;
            $resp['message'] = "Files are uploaded successfully..!";
        } catch (\Exception $ex) {
            $resp['status'] = false;
            $resp['data'] = ['name' => null];
            $resp['message'] = 'Files are not uploaded...!';
            $resp['ex_message'] = $ex->getMessage();
            $resp['ex_code'] = $ex->getCode();
            $resp['ex_file'] = $ex->getFile();
            $resp['ex_line'] = $ex->getLine();
        }
        return $resp;
    }

    /**
     * Get Sub Categories details by Parent CategoryId
     */
    public function getSubCategoryByParentCatId()
    {
        try {
            if (!request()->ajax()) {
                throw new \Exception("Requested Method not valid...!", \Illuminate\Http\Response::HTTP_BAD_REQUEST);
            }
            $parentCatId = request()->category_id;
            $categoryListByParentCatId = SubCategory::select('id', 'name')->whereHas('parentcategories', function ($query) use ($parentCatId) {
                $query->where('category_id', $parentCatId);
            })
                ->where('status', SubCategory::STATUS_ACTIVE)
                ->get();

            if ($categoryListByParentCatId->isNotEmpty()) {
                \Log::info(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Success get sub category data : " . json_encode([request()->all(), $categoryListByParentCatId]));
                return response()->json([
                    'status' => true,
                    'data' => $categoryListByParentCatId,
                    'message' => 'Result get Successfully..!'
                ]);
            }
            throw new \Exception("Parent Category Details not found...!", \Illuminate\Http\Response::HTTP_NOT_FOUND);
        } catch (\Illuminate\Database\QueryException $e) { // Handle query exception
            \Log::error(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Error Query deleting data : " . $e->getMessage() . '');
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'Error Query..! ' . $e->getMessage()
            ], (!empty($e->getCode())) ? $e->getCode() : 401);
        } catch (\Exception $e) { // Handle any runtime exception
            \Log::error(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Error deleting data : " . $e->getMessage() . '');
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'Error Triggers..! ' . $e->getMessage()
            ], (!empty($e->getCode())) ? $e->getCode() : 401);
        }
    }

    /**
     * Update the status.
     */
    public function changeStatus(ProductStatusUpdateRequest $request)
    {
        try {
            $product = Product::findOrFail($request->product_id);
            $product->update(['status' => $request->status]);
            \Log::info(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Success status updating data : " . json_encode([request()->all(), $product]));
            return response()->json([
                'status' => true,
                'data' => $product,
                'message' => 'Success status updating data..!'
            ]);            
        } catch (\Illuminate\Database\QueryException $e) { // Handle query exception
            \Log::error(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Error Query updating data : " . $e->getMessage());
            // You can also return a response to the user            
                return response()->json([
                    'status' => false,
                    'data' => [],
                    'message' => "error occurs failed to proceed...! " . $e->getMessage()
                ]); 
        } catch (\Exception $e) { // Handle any runtime exception
            \Log::error(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Error updating data : " . $e->getMessage() . '');
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => "error occurs failed to proceed...! " . $e->getMessage()
            ]); 
        }
    }

    /**
     * Search filter records on basis of inputs.
     */
    public function filterCategory(Request $request)
    {        
        try {
            $query = Product::query(); // using relationships 
            // Search Filters
            $query->when($request->filled('name'), function ($query) use ($request) {
                return $query->where('name','like','%'.$request->name.'%');            
            })->when($request->filled('status'), function ($query) use ($request) {
                return $query->where('status', $request->status);
            })->when($request->filled('from_date') && $request->filled('to_date'), function ($query) use ($request) {                        
                return $query->whereDate("created_at", '>=', $request->from_date)
                ->whereDate("created_at", '<=', $request->to_date);
            });           
            $products = $query->get();
            return view('products.index', compact('products'));
        } catch (\Exception $e) { // Handle any runtime exception
            \Log::error(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Error updating data : " . $e->getMessage() . '');
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "error occurs failed to proceed...! " . $e->getMessage());
        }
    }
}
