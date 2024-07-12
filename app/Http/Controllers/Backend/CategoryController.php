<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\Backend\CreateCategoryRequest;
use App\Http\Requests\Backend\UpdateCategoryRequest;
use Illuminate\Support\Arr;



class CategoryController extends Controller
{
    public function addForm()
    {
        $categories         = new Category;
        $parentCategory     = $categories->getAllCategories();

        $data['parentCategory'] = $parentCategory;
        return view('backend.categories.create')->with($data);
    }
    
    public function createCategory(CreateCategoryRequest $request)
    {
        $categories = Category::where('parent_id', null)->orderby('name', 'asc')->get();
        $category   = new Category;

        $validateValues = $request->validated();
        $name           = Arr::get($validateValues,'name');
        $level          = Arr::get($validateValues,'level');
        $parent_id      = Arr::get($validateValues,'parent_id');
        
        $data = [
            'name'      => $name,
            'level'     => $level,
            'parent_id' => $parent_id
        ];

        $created    = $category->createCategory($data);

        if($created)
        {
            return redirect()->back()->with('success', 'Category has been created successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);
        }
    }

    public function index(Request $request)
    {

        $categoryObject = new Category;

        $categories = Category::select('categories.id', 'categories.name', 'categories.level', 'categories.parent_id', 'parent.name as parent_name')
                    ->leftJoin('categories as parent', 'categories.parent_id', '=', 'parent.id');

        $name = $request->input('name');
        $levelId = $request->input('level');
        $parentId = $request->input('parent');

        if (!empty($name)) {
            $categories->where('categories.name', 'like', '%' . $name . '%');
        }

        if (!empty($levelId)) {
            $categories->where('categories.level', $levelId);
        }

        if (!empty($parentId)) {
            $categories->where('categories.parent_id', $parentId);
        }

        $parentCategories = $categoryObject->getAllCategories();

        $categories = $categories->paginate(config('constants.per_page'));

        $filterData = [
            'name' => $name,
            'levelId' => $levelId,
            'parentId' => $parentId,
        ];

        $data = [
            'categories'       => $categories,
            'filterData'       => $filterData,
            'parentCategories' => $parentCategories,
        ];

        return view('backend.categories.index', $data);

    }

    public function destroy($categoryId)
    {
        $category   = new Category; 
        $deleted    = $category->deleteCategory($categoryId);
        
        if($deleted)
        {
            return redirect()->route('categories.index')->with('success', 'Category has been deleted successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);

        }
    }

    public function show(Request $request)
    {
        
        $categoryObject             = new Category;
        $categoryId                 = $request->route('categoryId');
        $categoryData               = $categoryObject->getComplaintDataById($categoryId);

        //Full data of category
        $data['categoryData']       = $categoryData;

        return view('backend.categories.show')->with($data);
    }

    public function editForm($categoryId)
    {
        $categoryObject     = new Category;
        $categoryData       = $categoryObject->getCategoryDataById($categoryId);
        $parentCategory     = $categoryObject->getFirstLevel();
        $categories         = $categoryObject->getAllCategories();

        $data['parentCategory'] = $parentCategory;
        $data['categoryData']   = $categoryData;
        $data['categories']     = $categories;

        return view('backend.categories.edit')->with($data);
    }

    public function edit(UpdateCategoryRequest $request)
    {
        $categoryObject = new Category;
        $categoryId     = $request->input('categoryId');
        $validateValues = $request->validated();
        $name           = Arr::get($validateValues,'name');
        $level          = Arr::get($validateValues,'level');
        $parent_id      = Arr::get($validateValues,'parent_id');
        
        $data = [
            'name'      => $name,
            'level'     => $level,
            'parent_id' => $parent_id
        ];

        $updated = $categoryObject->updateCategory($categoryId, $data);

        if($updated)
        {
            return redirect()->back()->with('success', 'Category has been updated successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);
        }
        
    }
	
	
	public function report_index()
    {
        $data = array();
        return view('backend.reports.categories')->with($data);
    }
}
