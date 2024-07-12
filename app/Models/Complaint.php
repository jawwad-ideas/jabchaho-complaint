<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Complaint extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'complaints';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'complaint_num',
        'complaint_status_id',
        'complaint_priority_id',
        'complainant_id',
        'user_id',//mna id
        'mpa_id',
        'level_one',
        'level_two',
        'level_three',
        'city_id',
        'district_id',
        'district_input',
        'sub_division_id',
        'charge_id',
        'union_council_id',
        'ward_id',
        'national_assembly_id',
        'provincial_assembly_id',
        'national_assembly_input',
        'provincial_assembly_input',
        'is_approved',
        'new_area_id',
        'title',
        'description',
        'address',
        'extras',
        'nearby',
        'created_by'
    ];

    //relationship b/w Complaint & ComplaintStatus
    public function complaintStatus()
    {
        return $this->belongsTo(ComplaintStatus::class, 'complaint_status_id');
    }
    //relationship b/w Complaint & complaintPriority
    public function complaintPriority()
    {
        return $this->belongsTo(ComplaintPriority::class, 'complaint_priority_id');
    }
    //relationship b/w Complaint & User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //relationship b/w Complaint & User
    public function userMpa()
    {
        return $this->belongsTo(User::class, 'mpa_id');
    }

    //relationship b/w Complaint & created_by
    public function userBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    //relationship b/w Complaint & Complainant
    public function complainant()
    {
        return $this->belongsTo(Complainant::class, 'complainant_id');
    }
    //relationship b/w Complaint & complaintCategory
    public function complaintCategory()
    {
        return $this->belongsTo(Category::class, 'id', 'complaint_id');
    }
    //relationship b/w Complaint & ComplaintDocument
    public function complaintDocument()
    {
        return $this->belongsTo(ComplaintDocument::class, 'id', 'complaint_id');
    }
    //relationship b/w Complaint & levelOneCategory
    public function levelOneCategory()
    {
        return $this->belongsTo(Category::class, 'level_one');
    }
    //relationship b/w Complaint & levelTwoCategory
    public function levelTwoCategory()
    {
        return $this->belongsTo(Category::class, 'level_two');
    }
    //relationship b/w Complaint & levelThreeCategory
    public function levelThreeCategory()
    {
        return $this->belongsTo(Category::class, 'level_three');
    }
    //relationship b/w Complaint & City
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    //relationship b/w Complaint & District
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
    //relationship b/w Complaint & SubDivision
    public function subDivision()
    {
        return $this->belongsTo(SubDivision::class, 'sub_division_id');
    }
    //relationship b/w Complaint & Charge
    public function charge()
    {
        return $this->belongsTo(Charge::class, 'charge_id');
    }
    //relationship b/w Complaint & UnionCouncil
    public function unionCouncil()
    {
        return $this->belongsTo(UnionCouncil::class, 'union_council_id');
    }
    //relationship b/w Complaint & Ward
    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }
    //relationship b/w Complaint & NationalAssembly
    public function nationalAssembly()
    {
        return $this->belongsTo(NationalAssembly::class, 'national_assembly_id');
    }
    //relationship b/w Complaint & ProvincialAssembly
    public function provincialAssembly()
    {
        return $this->belongsTo(ProvincialAssembly::class, 'provincial_assembly_id');
    }
    //relationship b/w Complaint & NewArea
    public function newArea()
    {
        return $this->belongsTo(NewArea::class, 'new_area_id');
    }
    //relationship b/w Complaint & newAreaGrid
    public function newAreaGrid()
    {
        return $this->belongsTo(NewAreaGrid::class, 'new_area_id');
    }
    //relationship b/w Complaint & UserWiseAreaMapping
    public function UserWiseAreaMapping()
    {
        return $this->belongsTo(UserWiseAreaMapping::class, 'new_area_id');
    }

    //relationship b/w Complaint & FollowUp
    public function ComplaintFollowUps()
    {
        return $this->belongsTo(ComplaintFollowUp::class, 'id','complaint_id');
    }

    #functions

    function getComplaintsByComplainantId($complainantId = 0)
    {
        $complaints = Complaint::where(['complainant_id' => $complainantId])->orderBy('id', 'DESC')->paginate(config('constants.per_page'));
        return $complaints;
    }

    function getComplaintsWithComplainant($complaintId = 0)
    {
        $complainant = Complaint::with('complainant')->where(['id' => $complaintId])->orderBy('id', 'DESC')->first();
        return $complainant->complainant; 
    }

    public function deleteComplaint($complaintId)
    {
        // Find the Complaint model instance based on the ID
        $complaint = Complaint::select('*')->where(['id' => $complaintId]);

        // Check if the complaint exists
        if ($complaint) {
            // Soft delete the complaint
            $complaint->delete();

            return "Complaint deleted successfully.";
        } else {
            return "Complaint not found.";
        }
    }

    public function getComplaintDataById($complaintId)
    {
        $complaintData = Complaint::with('userMpa')->where(['id' => $complaintId])->first();
        return $complaintData;
    }

    public function assignTo($params = array())
    {

        $complaintId = Arr::get($params, 'complaintId');
        $priorityId = Arr::get($params, 'priorityId');

        $assigned = Complaint::where(['id' => $complaintId])->update(['complaint_priority_id' => $priorityId]);
        if ($assigned) {
            return true;
        } else {
            return false;
        }
    }

    function complaintsRespectToYearAndStatus($params = array())
    {
        $result = $wherecondition = array();

        $year = Arr::get($params, 'year');

        if (Arr::get($params, 'id')) {
            $wherecondition['id'] = Arr::get($params, 'id');
        }

        if (Arr::get($params, 'complaint_status_id')) {
            $wherecondition['complaint_status_id'] = Arr::get($params, 'complaint_status_id');
        }

        $data = Complaint::select(
            DB::raw("
            count(id) as total,
            MONTH(updated_at) month
            ")
        )
            ->whereYear('updated_at', '=', $year)
            ->where($wherecondition)
            ->groupBy('month')
            ->get()->toArray();

        if (!empty($data)) {
            foreach ($data as $row) {
                $result[Arr::get($row, 'month')] = Arr::get($row, 'total');
            }
        }
        return $result;
    }

    function complaintCount($params = array())
    {
        
        if (!empty($params['startDate']) && !empty($params['endDate'])) {
            $startDate = $params['startDate'];
            $endDate = $params['endDate'];

            $result = Complaint::whereBetween('created_at', [$startDate, $endDate]);
            if (!empty($params['userId'])) {
                $result = $result->where($params['userType'],$params['userId']);
                $result = $result->where('is_approved',1);
            } 
        }
        else
        {
            if (!empty($params['userId'])) {
                $result = Complaint::where($params['userType'],$params['userId']);
                $result = $result->where('is_approved',1);
            }
            else
            {
                $result = Complaint::all();
            }
        }
        
        if(!empty($params['customStartDate']) && !empty($params['customEndDate'])) {
            $customStartDate = $params['customStartDate'];
            $customEndDate   = $params['customEndDate'];

            $result = Complaint::whereBetween('created_at', [$customStartDate, $customEndDate]);
            if (!empty($params['userId'])) {
                $result = $result->where($params['userType'],$params['userId']);
                $result = $result->where('is_approved',1);
            } 
        }

        return $result->count();
    }

    function complaintStatusCount($params = array())
    {
        $wherecondition = '';
        $complaintStatusesWithCounts = ComplaintStatus::select('name')->withCount([
            'complaints' => function ($query) use ($params) {
                if (!empty($params['startDate']) && !empty($params['endDate'])) {
                    $startDate = $params['startDate'];
                    $endDate = $params['endDate'];
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                    if(!empty($params['userId']))
                    {
                        $userId = $params['userId'];
                        $query->where([$params['userType'] =>$userId]);
                        $query->where('is_approved',1);
                    }
                }
                if (!empty($params['customStartDate']) && !empty($params['customEndDate'])) {
                    $customStartDate = $params['customStartDate'];
                    $customEndDate = $params['customEndDate'];
                    $query->whereBetween('created_at', [$customStartDate, $customEndDate]);
                    if(!empty($params['userId']))
                    {
                        $userId = $params['userId'];
                        $query->where([$params['userType'] =>$userId]);
                        $query->where('is_approved',1);
                    }
                }
                elseif(!empty($params['userId']))
                {
                    $userId = $params['userId'];
                    $query->where([$params['userType']=>$userId]);
                    $query->where('is_approved',1);
                }
                
            }
        ])->get();

        $statusCount = array();
        if (!empty($complaintStatusesWithCounts)) {
            foreach ($complaintStatusesWithCounts as $row) {
                $name = str_replace(' ', '', Arr::get($row, 'name'));
                $statusCount[$name] = Arr::get($row, 'complaints_count');
            }
        }

        return $statusCount;
    }

    //function to fetch mpaComplaintCount
    /**
     * below is the query
     * SELECT users.id,roles.name as 'ROLE',users.name as 'NAME',COUNT(*) as 'COUNT' FROM users
     * JOIN user_wise_area_mappings on users.id=user_wise_area_mappings.user_id
     * JOIN model_has_roles ON users.id=model_has_roles.model_id
     * JOIN roles on model_has_roles.role_id=roles.id
     * JOIN complaints on user_wise_area_mappings.new_area_id=complaints.new_area_id
     * where model_has_roles.role_id=4 GROUP BY users.id ORDER BY `users`.`id` ASC
     */
    function mpaComplaintCount($params = array())
    {
        
        $mpaWiseComplaints = User::select('name','profile_image')
            ->whereHas('complaintsMpa', function ($query) use ($params) {
                $query->when(!empty($params['startDate']) && !empty($params['endDate']), function ($query) use ($params) {
                    $query->whereBetween('created_at', [$params['startDate'], $params['endDate']]);
                    if(!empty($params['userId']))
                    {
                        $query->where([$params['userType'] => $params['userId']]);
                        $query->where('is_approved',1);
                    }
                });
            })
            ->whereHas('complaintsMpa', function ($query) use ($params) {
                $query->when(!empty($params['userId']), function ($query) use ($params)
                {
                    $query->where([ $params['userType'] => $params['userId']]);
                    $query->where('is_approved',1);
                });
            })
            ->whereHas('userRolesAssigned', function ($query) {
                $query->where('role_id', 4);
            })
            ->whereHas('complaintsMpa', function ($query) use ($params) {
                $query->when(!empty($params['customStartDate']) && !empty($params['customEndDate']), function ($query) use ($params) {
                    $query->whereBetween('created_at', [$params['customStartDate'], $params['customEndDate']]);
                    if(!empty($params['userId']))
                    {
                        $query->where([$params['userType'] => $params['userId']]);
                        $query->where('is_approved',1);
                    }
                });
            })
            ->withCount('complaintsMpa')
            ->groupBy('id','complaint.users.name','complaint.users.profile_image')
            ->orderBy('id')
            ->get()->transform(function ($user) {
                return [
                    'name'  => $user->name,
                    'count' => $user->complaints_mpa_count,
                    'image' => $user->profile_image ? asset(config('constants.files.profile') . '/' . $user->profile_image ):  asset('assets/images/default/profile.jpg')
                ];
            });


        return $mpaWiseComplaints;
    }

    //function to fetch mnaComplaintCount
    /**
     * below is the query
     * SELECT users.id,roles.name as 'ROLE',users.name as 'NAME',COUNT(*) as 'COUNT' FROM users
     * JOIN user_wise_area_mappings on users.id=user_wise_area_mappings.user_id
     * JOIN model_has_roles ON users.id=model_has_roles.model_id
     * JOIN roles on model_has_roles.role_id=roles.id
     * JOIN complaints on user_wise_area_mappings.new_area_id=complaints.new_area_id
     * where model_has_roles.role_id=3 GROUP BY users.id ORDER BY `users`.`id` ASC
     */
    function mnaComplaintCount($params = array())
    {
        $mnaWiseComplaints = User::select('name','profile_image')
            ->whereHas('complaints', function ($query) use ($params) {
                $query->when(!empty($params['startDate']) && !empty($params['endDate']), function ($query) use ($params) {
                    $query->whereBetween('created_at', [$params['startDate'], $params['endDate']]);
                    if(!empty($params['userId']))
                    {
                        $query->where([$params['userType'] =>$params['userId']]);
                        $query->where('is_approved',1);
                    }
                });
            })
            ->whereHas('complaints', function ($query) use ($params) {
                $query->when(!empty($params['userId']), function ($query) use ($params)
                {
                    $query->where([$params['userType']=>$params['userId']]);
                    $query->where('is_approved',1);
                });
            })
            ->whereHas('userRolesAssigned', function ($query) {
                $query->where('role_id', 3);
            })
            ->whereHas('complaints', function ($query) use ($params) {
                $query->when(!empty($params['customStartDate']) && !empty($params['customEndDate']), function ($query) use ($params) {
                    $query->whereBetween('created_at', [$params['customStartDate'], $params['customEndDate']]);
                    if(!empty($params['userId']))
                    {
                        $query->where([$params['userType']=>$params['userId']]);
                        $query->where('is_approved',1);
                    }
                });
            })
            ->withCount('complaints')
            ->groupBy('id','complaint.users.name','complaint.users.profile_image')
            ->orderBy('id')
            ->get()->transform(function ($user) {
                return [
                    'name'  => $user->name,
                    'count' => $user->complaints_count,
                    'image' => $user->profile_image ? asset(config('constants.files.profile') . '/' . $user->profile_image ):  asset('assets/images/default/profile.jpg'),
                ];
            });

        return $mnaWiseComplaints;
    }

    //function to fetch categoriesComplaintCount
    /**
     * below is the query
     * SELECT DISTINCT A.id as 'id', A.name as 'category', A.level as 'level', A.parent_id as 'parent_id', COUNT(*) as 'value' FROM categories A,categories B
     * JOIN complaints on B.parent_id=complaints.level_one OR B.parent_id=complaints.level_two
     * WHERE B.parent_id=A.id AND A.level IN(1,2) GROUP BY A.id,B.id;
     */
    function categoriesComplaintCount($params = array())
    {
        $categoryWiseComplaints = Category::select('id', 'name', 'level', 'parent_id')
            ->whereHas('complaintsCategoryLevelOne', function ($query) use ($params) {
                $query->when(!empty($params['startDate']) && !empty($params['endDate']), function ($query) use ($params) {
                    $query->whereBetween('created_at', [$params['startDate'], $params['endDate']]);
                    if(!empty($params['userId']))
                    {
                        $query->where([$params['userType']=>$params['userId']]);
                        $query->where('is_approved',1);
                    }
                });
            })
            ->whereHas('complaintsCategoryLevelOne', function ($query) use ($params) {
                $query->when(!empty($params['userId']), function ($query) use ($params)
                {
                    $query->where([$params['userType']=>$params['userId']]);
                    $query->where('is_approved',1);
                });
            })
            ->whereHas('complaintsCategoryLevelOne', function ($query) use ($params) {
                $query->when(!empty($params['customStartDate']) && !empty($params['customEndDate']), function ($query) use ($params) {
                    $query->whereBetween('created_at', [$params['customStartDate'], $params['customEndDate']]);
                    if(!empty($params['userId']))
                    {
                        $query->where([$params['userType']=>$params['userId']]);
                        $query->where('is_approved',1);
                    }
                });
            })
            ->withCount('complaintsCategoryLevelOne')
            ->groupBy('id','complaint.categories.name','complaint.categories.level','complaint.categories.parent_id')
            ->orderBy('id')
            ->get()
            ->transform(function ($category) {
                $breakdown = Category::select('id', 'name', 'level', 'parent_id')
                    ->whereHas('complaintsCategoryLevelTwo')
                    ->withCount('complaintsCategoryLevelTwo')
                    ->where('parent_id', $category->id)
                    ->groupBy('id','complaint.categories.name','complaint.categories.level','complaint.categories.parent_id')
                    ->orderBy('id')
                    ->get()
                    ->transform(function ($child) {
                        return [
                            'id'        => $child->id,
                            'category'  => $child->name,
                            'level'     => $child->level,
                            'parent_id' => $child->parent_id,
                            'value'     => $child->complaints_category_level_two_count
                        ];
                    });
                return [
                    'id'        => $category->id,
                    'category'  => $category->name,
                    'level'     => $category->level,
                    'parent_id' => $category->parent_id,
                    'value'     => $category->complaints_category_level_one_count,
                    'breakdown' => $breakdown,
                ];
            });

        return $categoryWiseComplaints;
    }

    //function to fetch areasComplaintCount
    /**
     * below is the query
     * select `districts`.`id` as `id`, `districts`.`name` as `district`, `new_areas`.`id` as `area_id`, `new_areas`.`name` as `area`, COUNT(*) AS count from `new_areas`
     * inner join `complaints` on `complaints`.`new_area_id` = `new_areas`.`id`
     * inner join `districts` on `complaints`.`district_id` = `districts`.`id`
     * group by `id`, `district`, `area_id`, `area`
     * order by `id` asc
     */
    function areasComplaintCount($params = array())
    {
        $testQuery = NewAreaGrid::select('new_area_id', 'district_id')->whereHas('complaints', function ($query) use ($params) {
            $query->when(!empty($params['startDate']) && !empty($params['endDate']), function ($query) use ($params) {
                $query->whereBetween('created_at', [$params['startDate'], $params['endDate']]);
            });
            })
            ->with(['newArea', 'district'])
            ->withCount('complaints')
            ->get()
            ->groupBy('district_id')
            ->map(function ($district) {
                return [
                    'district_id' => $district->first()->district_id,
                    'district'    => $district->first()->district->name,
                    'breakdown'   => $district->map(function ($area) {
                        return [
                            'area_id' => $area->new_area_id,
                            'area'    => $area->newArea->name,
                            'count'   => $area->complaints_count,
                        ];
                    }
                ),
                    'total_count' => $district->sum('complaints_count'),
                ];
            });

        return $testQuery;

    }

    public function reAssignTo($params = array())
    {

        $complaintId = Arr::get($params, 'complaintId');
        $mnaId       = Arr::get($params,'mnaId');
        $mpaId       = Arr::get($params,'mpaId');

        $data = [
            'user_id' => $mnaId,
            'mpa_id'  => $mpaId  
        ];
        
        $reAssigned = Complaint::where(['id' => $complaintId])->update($data);
        if ($reAssigned) {
            return true;
        } else {
            return false;
        }
    }

}
