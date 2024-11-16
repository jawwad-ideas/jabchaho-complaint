<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use  App\Models\OrdersImages;
class Orders extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_number',
        'status',
        'adminuser',
        'updated_at'
    ];



    public function createOrder($data = array())
    {
        $inserted = Orders::insertGetId($data);
        if($inserted)
        {
            return $inserted;
        }
        else
        {
            return false;
        }
    }


    public function images() {
        return $this->hasMany(OrdersImages::class, 'order_id', 'id');
    }

    /*
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
        return Orders::where(['level' => 1])->get()->toArray();
    }

    function getSubCategories($categoryId =0)
    {
        return Orders::where(['parent_id' => $categoryId])->get()->toArray();
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
        $category = Orders::select('*')->where(['id'=>$categoryId]);

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
        $categoryData = Orders::select('*')->where(['id'=>$categoryId])->first();
        return $categoryData;
    }

    public function updateCategory($categoryId, $data = array())
    {
        $updated = Orders::where(['id'=>$categoryId])->update($data);
        if($updated)
        {
            return true;
        }
        else
        {
            return false;
        }
    }*/

/*    public function getAllCategories()
    {
        return Orders::all();
    }

    function getSecondLevel()
    {
        return Orders::where(['level' => 2])->get()->toArray();
    }

    function getThirdLevel()
    {
        return Orders::where(['level' => 3])->get()->toArray();
    }*/
}
