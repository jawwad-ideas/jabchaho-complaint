<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class ReportController extends Controller
{
    public function getReportByUser(Request $request)
    {
        try
        {
            // Fetch users with their associated complaints and statuses
            $users = User::with('complaints')->get();
            dd($users);
        }
        catch(\Exception $e)
        {
            return $this->getCustomExceptionMessage($e);
        }
    }

}
