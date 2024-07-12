<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintDocument extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'complaint_documents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'complaint_id',
        'table_name',
        'table_action_id',
        'document_name',
        'file',
        'original_file'
    ];

    public function getComplaintDocumentById($complaintId)
    {
        $complaintDocuments = ComplaintDocument::select('*')->where(['complaint_id'=>$complaintId])->get();
        return $complaintDocuments;
    }
}
