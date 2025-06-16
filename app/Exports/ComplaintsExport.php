<?php

namespace App\Exports;

use App\Models\Complaint;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ComplaintsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Complaint::with(['user', 'complaintPriority', 'complaintStatus']);

        if (!empty($this->filters['complaint_number'])) {
            $query->where('complaint_number', 'like', '%' . $this->filters['complaint_number'] . '%');
        }

        if (!empty($this->filters['order_id'])) {
            $query->where('order_id', 'like', '%' . $this->filters['order_id'] . '%');
        }

        if (!empty($this->filters['mobile_number'])) {
            $query->where('mobile_number', 'like', '%' . $this->filters['mobile_number'] . '%');
        }

        if (!empty($this->filters['name'])) {
            $query->where('name', 'like', '%' . $this->filters['name'] . '%');
        }

        if (!empty($this->filters['email'])) {
            $query->where('email', 'like', '%' . $this->filters['email'] . '%');
        }

        if (!empty($this->filters['complaint_status_id'])) {
            $query->where('complaint_status_id', $this->filters['complaint_status_id']);
        }

        if (!empty($this->filters['complaintPriorityId'])) {
            $query->where('complaint_priority_id', $this->filters['complaintPriorityId']);
        }

        if (!empty($this->filters['reportedFromId'])) {
            $query->where('reported_from', $this->filters['reportedFromId']);
        }

        return $query->get();
    }

    public function map($complaint): array
    {
        return [
            $complaint->complaint_number,
            $complaint->order_id,
            config('constants.complaint_reported_from.' . $complaint->reported_from),
            optional($complaint->complaintStatus)->name ?? '',
            config('constants.services.' . $complaint->service_id, ''),            
            optional($complaint->complaintPriority)->name ?? '',           
            config('constants.complaint_type.' . $complaint->complaint_type, ''),  
            $complaint->name,
            $complaint->email,
            $complaint->mobile_number,
            optional($complaint->user)->name ?? 'Unassigned',
            $complaint->created_at->format('d, M, Y h:i A'),
            $complaint->comments ?? '',
        ];
    }

    public function headings(): array
    {
        return [
            'Complaint #',
            'Order ID',
            'Reported From',
            'Status',
            'Service',
            'Priority',
            'Complaint/Inquiry Type',
            'Name',
            'Email',
            'Mobile Number',
            'Assigned To',
            'Created',
            'Additional Comments',
        ];
    }
}
