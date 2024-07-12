<?php

namespace App\Exports;

use App\Helpers\Helper;
use App\Models\Complaint;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ComplaintsExport implements FromQuery, WithHeadings
{
    use Exportable;

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $complaints = Complaint::query();
        $filtersApplied = false;

        if ($this->request->filled('start_date')) {
            $complaints->where('created_at', '>=', $this->request->input('start_date'));
            $filtersApplied = true;
        }

        if ($this->request->filled('end_date')) {
            $complaints->where('created_at', '<=', $this->request->input('end_date'));
            $filtersApplied = true;
        }

        if ($this->request->filled('period')) {
            $filterValue = $this->request->input('period');
            $datesArray = Helper::getDateByFilterValue($filterValue);
            $startDate = Arr::get($datesArray, 'startDate');
            $endDate = Arr::get($datesArray, 'endDate');
            $complaints = $complaints->whereBetween('created_at', [$startDate, $endDate]);
            $filtersApplied = true;
        }

        if ($this->request->filled('cnic')) {
            $complaints->whereHas('complainant', function ($query) {
                $query->where('cnic', 'like', '%' . $this->request->input('cnic') . '%');
            });
            $filtersApplied = true;
        }

        if ($this->request->filled('mobile_number')) {
            $complaints->whereHas('complainant', function ($query) {
                $query->where('mobile_number', 'like', '%' . $this->request->input('mobile_number') . '%');
            });
            $filtersApplied = true;
        }

        if ($this->request->filled('level_one')) {
            if(is_array($this->request->input('level_one'))){
                $complaints->whereIn('level_one', $this->request->input('level_one'));
                $filtersApplied = true;
            }else{
                $explodedArray = explode(",", $this->request->input('level_one'));
                $complaints->whereIn('level_one', $explodedArray);
                $filtersApplied = true;
            }
        }

        if ($this->request->filled('level_two')) {
            if(is_array($this->request->input('level_two'))){
                $complaints->whereIn('level_two', $this->request->input('level_two'));
                $filtersApplied = true;
            }else{
                $explodedArray = explode(",", $this->request->input('level_two'));
                $complaints->whereIn('level_two', $explodedArray);
                $filtersApplied = true;
            }
        }

        if ($this->request->filled('level_three')) {
            if(is_array($this->request->input('level_three'))){
                $complaints->whereIn('level_three', $this->request->input('level_three'));
                $filtersApplied = true;
            }else{
                $explodedArray = explode(",", $this->request->input('level_three'));
                $complaints->whereIn('level_three', $explodedArray);
                $filtersApplied = true;
            }
        }

        if ($this->request->filled('title')) {
            $complaints->where('title', $this->request->input('title'));
            $filtersApplied = true;
        }

        if ($this->request->filled('city_id')) {
            $complaints->where('city_id', $this->request->input('city_id'));
            $filtersApplied = true;
        }

        if ($this->request->filled('new_area_id')) {
            $complaints->where('new_area_id', $this->request->input('new_area_id'));
            $filtersApplied = true;
        }

        if ($this->request->filled('district_id')) {
            $complaints->where('district_id', $this->request->input('district_id'));
            $filtersApplied = true;
        }

        if ($this->request->filled('sub_division_id')) {
            $complaints->where('sub_division_id', $this->request->input('sub_division_id'));
            $filtersApplied = true;
        }

        if ($this->request->filled('union_council_id')) {
            $complaints->where('union_council_id', $this->request->input('union_council_id'));
            $filtersApplied = true;
        }

        if ($this->request->filled('charge_id')) {
            $complaints->where('charge_id', $this->request->input('charge_id'));
            $filtersApplied = true;
        }

        if ($this->request->filled('ward_id')) {
            $complaints->where('ward_id', $this->request->input('ward_id'));
            $filtersApplied = true;
        }

        if ($this->request->filled('provincial_assembly_id')) {
            $complaints->where('provincial_assembly_id', $this->request->input('provincial_assembly_id'));
            $filtersApplied = true;
        }

        if ($this->request->filled('national_assembly_id')) {
            $complaints->where('national_assembly_id', $this->request->input('national_assembly_id'));
            $filtersApplied = true;
        }

        if ($this->request->filled('complaint_status_id')) {
            $complaints->where('complaint_status_id', $this->request->input('complaint_status_id'));
            $filtersApplied = true;
        }

        if ($this->request->filled('mna_id')) {
            $complaints->where('user_id', $this->request->input('mna_id'));
            $filtersApplied = true;
        }

        if ($this->request->filled('mpa_id')) {
            $complaints->where('mpa_id', $this->request->input('mpa_id'));
            $filtersApplied = true;
        }

        if ($this->request->filled('complaint_approved_id')) {
            $complaints->where('is_approved', $this->request->input('complaint_approved_id'));
            $filtersApplied = true;
        }

        if (!$filtersApplied) {
            return null; // Return empty collection if no filters applied
        }

        $counts = $complaints->selectRaw('
            COUNT(*) AS total,
            COUNT(CASE WHEN complaint_status_id = 1 THEN 1 END) AS complaint_registered,
            COUNT(CASE WHEN complaint_status_id = 2 THEN 1 END) AS in_process,
            COUNT(CASE WHEN complaint_status_id = 3 THEN 1 END) AS hold,
            COUNT(CASE WHEN complaint_status_id = 4 THEN 1 END) AS resolved,
            COUNT(CASE WHEN complaint_status_id = 5 THEN 1 END) AS closed,
            COUNT(CASE WHEN is_approved = 1 THEN 1 END) AS approved,
            COUNT(CASE WHEN is_approved = 0 THEN 1 END) AS pending_approval,
            COUNT(CASE WHEN city_id = 1 THEN 1 END) AS karachi_complaints,
            COUNT(CASE WHEN city_id = 2 THEN 1 END) AS hyderabad_complaints'
        );

        return $counts;
    }


    public function headings(): array
    {
        return [
            'Total Complaints',
            'Complaint Registered',
            'In Process',
            'Hold',
            'Resolved',
            'Closed',
            'Approved',
            'Pending Approval',
            'Karachi Complaints',
            'Hyderabad Complaints',
        ];
    }
}
