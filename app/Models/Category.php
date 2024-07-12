<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'level',
        'parent_id'
    ];

    //relation b/w Category & complaintsCategoryLevelOne
    public function complaintsCategoryLevelOne(){
        return $this->hasMany(Complaint::class,'level_one','id');
    }
    //relation b/w Category & complaintsCategoryLevelTwo
    public function complaintsCategoryLevelTwo(){
        return $this->hasMany(Complaint::class,'level_two','id');
    }


    function getFirstLevel()
    {
        return Category::where(['level' => 1])->get()->toArray();
    }

    function getSubCategories($categoryId =0)
    {
        return Category::where(['parent_id' => $categoryId])->get()->toArray();
    }

    function getComplaintsByComplainantId($complainantId=0)
    {
        $complaints = Complaint::where(['complainant_id'=>$complainantId])->get();
        return $complaints;
    }

    #functions

    public function deleteCategory($categoryId)
    {
        // Find the Complaint model instance based on the ID
        $category = Category::select('*')->where(['id'=>$categoryId]);

        // Check if the Category exists
        if ($category) {
            // Soft delete the Category
            $category->delete();

            return "Category deleted successfully.";
        } else {
            return "Category not found.";
        }
    }

    public function getCategoryDataById($categoryId)
    {
        $categoryData = Category::select('*')->where(['id'=>$categoryId])->first();
        return $categoryData;
    }

    public function updateCategory($categoryId, $data = array())
    {
        $updated = Category::where(['id'=>$categoryId])->update($data);
        if($updated)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function createCategory($data = array())
    {
        $inserted = Category::insert($data);
        if($inserted)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function getAllCategories()
    {
        return Category::all();
    }

    function getSecondLevel()
    {
        return Category::where(['level' => 2])->get()->toArray();
    }

    function getThirdLevel()
    {
        return Category::where(['level' => 3])->get()->toArray();
    }
}
