<?php

namespace App\Exports;

use App\Models\Complaint;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportByComplaintsExport implements FromQuery, WithHeadings
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

        if ($this->request->filled('start_date')) {
            $complaints->where('created_at', '>=', $this->request->input('start_date'));
        }

        if ($this->request->filled('end_date')) {
            $complaints->where('created_at', '<=', $this->request->input('end_date'));
        }

        if ($this->request->filled('cnic')) {
            $complaints->whereHas('complainant', function ($query) {
                $query->where('cnic', $this->request->input('cnic'));
            });
        }

        if ($this->request->filled('mobile_number')) {
            $complaints->whereHas('complainant', function ($query) {
                $query->where('mobile_number', $this->request->input('mobile_number'));
            });
        }

        if ($this->request->filled('level_one')) {
            $complaints->where('level_one', $this->request->input('level_one'));
        }

        if ($this->request->filled('level_two')) {
            $complaints->where('level_two', $this->request->input('level_two'));
        }

        if ($this->request->filled('level_three')) {
            $complaints->where('level_three', $this->request->input('level_three'));
        }

        if ($this->request->filled('title')) {
            $complaints->where('title', $this->request->input('title'));
        }

        if ($this->request->filled('city_id')) {
            $complaints->where('city_id', $this->request->input('city_id'));
        }

        if ($this->request->filled('new_area_id')) {
            $complaints->where('new_area_id', $this->request->input('new_area_id'));
        }

        if ($this->request->filled('district_id')) {
            $complaints->where('district_id', $this->request->input('district_id'));
        }

        if ($this->request->filled('sub_division_id')) {
            $complaints->where('sub_division_id', $this->request->input('sub_division_id'));
        }

        if ($this->request->filled('union_council_id')) {
            $complaints->where('union_council_id', $this->request->input('union_council_id'));
        }

        if ($this->request->filled('charge_id')) {
            $complaints->where('charge_id', $this->request->input('charge_id'));
        }

        if ($this->request->filled('ward_id')) {
            $complaints->where('ward_id', $this->request->input('ward_id'));
        }

        if ($this->request->filled('provincial_assembly_id')) {
            $complaints->where('provincial_assembly_id', $this->request->input('provincial_assembly_id'));
        }

        if ($this->request->filled('national_assembly_id')) {
            $complaints->where('national_assembly_id', $this->request->input('national_assembly_id'));
        }
        
        $complaints =$complaints->select(
            'complaints.title',
            'complaints.description',
            'users.name AS assigned_to',
            'cities.name AS city_name',
            'districts.name AS district_name',
            'new_areas.name AS new_area_name',
            'provincial_assemblies.name AS provincial_assembly_name',
            'national_assemblies.name AS national_assembly_name'
        )
        ->leftJoin('users', 'complaints.user_id', '=', 'users.id')
        ->leftJoin('cities', 'complaints.city_id', '=', 'cities.id')
        ->leftJoin('districts', 'complaints.district_id', '=', 'districts.id')
        ->leftJoin('new_areas', 'complaints.new_area_id', '=', 'new_areas.id')
        ->leftJoin('provincial_assemblies', 'complaints.provincial_assembly_id', '=', 'provincial_assemblies.id')
        ->leftJoin('national_assemblies', 'complaints.national_assembly_id', '=', 'national_assemblies.id');
        
        return $complaints;
    }

    public function headings(): array
    {
        return [
            'Title',
            'Description',
            'Assigned To',
            'City',
            'District',
            'New Area',
            'Provincial Assembly',
            'National Assembly',
        ];
    }
}
