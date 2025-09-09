<?php

namespace App\Http\Controllers;

use App\Models\Employeee;
use Illuminate\Http\Request;

class EmployeeHistoryController extends Controller
{
    public function show($id)
    {
        $emp = Employeee::with([
            'department',
            'employeeType',
            'positionHistories',
            'mobilities.oldDepartment',
            'mobilities.newDepartment',
            'secondments',
            'extraJobs',
            'salaryPayments',
        ])->findOrFail($id);

        return view('employeee.history', compact('emp'));
    }
}