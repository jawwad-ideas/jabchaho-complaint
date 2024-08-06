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
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class ReportsByUser implements FromCollection, WithHeadings,WithStrictNullComparison
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
            $totals = Arr::get($this->resultSet,'totals',0);

            $sumComplaints = 0;

            if(!empty($reportData))
            {
               
                foreach ($reportData as $row)
                {
                    $sumComplaints+= Arr::get($row,'total_complaints',0);
                    
                    $dataStart = array(
                        'user_name'             => Arr::get($row,'user_name'),
                        'total_complaints'      => Arr::get($row,'total_complaints',0)
                    );

                    if(!empty($statusNames))
                    {
                        foreach($statusNames as $status)
                        {
                          $dataEnd[$status]=  (int) Arr::get($row,$status.'_count',0);
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
                    
                        $dataFooter[$status]=  Arr::get($totals,$status.'_count',0);
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

        $statusNames = Arr::get($this->resultSet,'statusNames');

        if(!empty($statusNames))
        {
            foreach($statusNames as $statusName)
            {
                $header[] = ucfirst($statusName);
            }
        }

       return $header;
    }


}
