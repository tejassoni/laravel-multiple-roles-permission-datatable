<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Requests\SubCategoryStoreRequest;
use App\Http\Requests\SubCategoryUpdateRequest;
use App\Http\Requests\SubCategoryStatusUpdateRequest;

class SubCategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        //KEY : MULTIPERMISSION
        $this->middleware('permission:subcategory-list|subcategory-create|subcategory-edit|subcategory-show|subcategory-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:subcategory-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:subcategory-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:subcategory-delete', ['only' => ['destroy']]);
        $this->middleware('permission:subcategory-show', ['only' => ['show']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subcategories = SubCategory::with(['getCatUserHasOne', 'parentcategories:name'])
            ->where('user_id', auth()->user()->id)
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('subcategory.index', compact('subcategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parent_category = Category::where('status', Category::STATUS_ACTIVE)
            ->get();
        return view('subcategory.create', \compact('parent_category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubCategoryStoreRequest $request)
    {
        try {
            $created = SubCategory::firstOrCreate(['name' => $request->name, 'description' => $request->description,'status' => $request->status, 'user_id' => auth()->user()->id]);
            $created->parentcategories()->attach($request->select_parent_cat);
            if ($created) { // inserted success
                \Log::info(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Success inserting data : " . json_encode([request()->all(),$created]));
                return redirect()->route('subcategory.index')
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
    public function show(SubCategory $subcategory)
    {
        $parentCategoryNames = $subcategory->parentcategories()->pluck('categories.name')->toArray();
        return view('subcategory.show', compact('subcategory','parentCategoryNames'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubCategory $subcategory)
    {
        $parent_category = Category::where('status', Category::STATUS_ACTIVE)->get();
        $selectedParentCatIds = $subcategory->parentcategories()->pluck('categories.id')->toArray();
        return view('subcategory.edit', compact('subcategory', 'parent_category','selectedParentCatIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubCategoryUpdateRequest $request, SubCategory $subcategory)
    {
        try {
            $subcategory->updateOrFail(['name' => $request->name, 'description' => $request->description,'status' => $request->status, 'user_id' => auth()->user()->id]);
            $subcategory->parentcategories()->sync($request->select_parent_cat);
            \Log::info(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Success updating data : " . json_encode([request()->all(), $subcategory]));
            return redirect()->route('subcategory.index')
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
     * Remove the specified resource from storage.
     */
    public function destroy(SubCategory $subcategory)
    {
        try {
            $subcategory->parentcategories()->detach();
            $subcategory->delete();
            \Log::info(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Success deleting data : " . json_encode([request()->all(), $subcategory]));
            return redirect()->route('subcategory.index')
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
     * Update the status. CHANGESTATUS
     */
    public function changeStatus(SubCategoryStatusUpdateRequest $request)
    {
        try {
            $subcategory = SubCategory::findOrFail($request->subcategory_id);
            $subcategory->update(['status' => $request->status]);
            \Log::info(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Success status updating data : " . json_encode([request()->all(), $subcategory]));
            return response()->json([
                'status' => true,
                'data' => $subcategory,
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
     * Search filter records on basis of inputs. FILTERSEARCH
     */
    public function filterSubCategory(Request $request)
    {        
        try {
            $query = SubCategory::query()->with(['getCatUserHasOne', 'parentcategories:name']); // using relationships 
            // Search Filters
            $query->when($request->filled('subcategoryname'), function ($query) use ($request) {
                return $query->where('name','like','%'.$request->subcategoryname.'%');
            })->when($request->filled('parentcategoryname'), function ($query) use ($request) { // relationship filters        
                return $query->whereHas('parentcategories', function ($subquery) use ($request) { // relationship filters 
                   return $subquery->where('name', 'like','%'.$request->parentcategoryname.'%');                    
                });
            })->when($request->filled('createdby'), function ($query) use ($request) { // relationship filters        
                return $query->whereHas('getCatUserHasOne', function ($subquery) use ($request) { // relationship filters 
                   return $subquery->where('name', 'like','%'.$request->createdby.'%');                    
                });
            })->when($request->filled('status'), function ($query) use ($request) {
                return $query->where('status', $request->status);
            });           
            $subcategories = $query->get();
            return view('subcategory.index', compact('subcategories'));
        } catch (\Exception $e) { // Handle any runtime exception
            \Log::error(" file '" . __CLASS__ . "' , function '" . __FUNCTION__ . "' , Message : Error updating data : " . $e->getMessage() . '');
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "error occurs failed to proceed...! " . $e->getMessage());
        }
    }

}