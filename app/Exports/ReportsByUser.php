<?php

namespace App\Exports;

use App\Helpers\Helper;
use App\Models\Complaint;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use App\Models\ComplaintStatus;


class ReportsByUser implements FromCollection, WithHeadings
{
    use Exportable;

    protected $resultSet;

    public function __construct($resultSet)
    {
        $this->resultSet = $resultSet;
    }


    public function collection()
    {
        $data = array();
        if(!empty($this->resultSet))
        {
            $reportData = Arr::get($this->resultSet,'reportData');
            $statusNames = Arr::get($this->resultSet,'statusNames');
            $totals = Arr::get($this->resultSet,'totals');

            $sumComplaints = 0;

            dd($reportData);

            if(!empty($reportData))
            {
               
                foreach ($reportData as $row)
                {
                    $sumComplaints+= Arr::get($row,'total_complaints');
                    
                    $dataStart = array(
                        'user_name'             => Arr::get($row,'user_name'),
                        'total_complaints'      => Arr::get($row,'total_complaints')
                    );

                    if(!empty($statusNames))
                    {
                        foreach($statusNames as $status)
                        {
                          $dataEnd[$status]=  Arr::get($row,$status.'_count');
                        }
                    }

                    $data[] = array_merge($dataStart,$dataEnd);
                }
            
                //Footer here
                $dataFooter = array(
                    'user_name'             => 'Grand Total',
                    'total_complaints'      => $sumComplaints,
                );
                if(!empty($statusNames))
                {
                    foreach ($statusNames as $status)
                    {
                    
                        $dataFooter[$status]=  Arr::get($totals,$status.'_count');
                    }
                }

                $data[] = $dataFooter;
                
            }

        }

        return new Collection($data);
    }


    public function headings(): array
    {
        $header = ['Name', 'Total Complaints'];

        $complaintStatusObject = new ComplaintStatus;
        $complaintStatuses = $complaintStatusObject->getComplaintStatuses();

        if(!empty($complaintStatuses))
        {
            foreach($complaintStatuses as $complaintStatus)
            {
                $header[] = ucfirst(Arr::get($complaintStatus, 'name'));
            }
        }

       return $header;
    }


}
