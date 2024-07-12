<?php

namespace App\Http\Controllers;

use App\Models\Complainant;
use App\Models\Complaint;
use Illuminate\Http\Request;
class ApiController extends Controller
{
  public function getData(Request $request)
  {
    // Fetch data from the database (replace with your logic)
    $complainId = $request->get('id');

    // Handle cases where the post is not found
    if (!$complainId) {
      return response()->json(['error' => 'Please Provide Complain Id'], 404);
    }

    $complainData = Complaint::with('complainant','newArea','provincialAssembly','nationalAssembly','ward','unionCouncil','charge','subDivision','district','city','levelThreeCategory','levelTwoCategory','levelOneCategory','complaintDocument','user','complaintPriority','complaintStatus','ComplaintFollowUps')->where(['complaint_num' => $complainId])->orderBy('id', 'DESC')->first();

    // Handle cases where the post is not found
    if (empty($complainData)) {
        return response()->json(['error' => 'No Complaint Found'], 404);
      }

    return response()->json($complainData);
  }
}