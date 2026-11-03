<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\News;
use App\Models\Package;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'doctorCount' => Doctor::count(),
            'departmentCount' => Department::count(),
            'hospitalCount' => Hospital::count(),
            'packageCount' => Package::count(),
            'newsCount' => News::count(),
            // These tables are introduced in the Appointment and Contact phases.
            'appointmentCount' => 0,
            'newMessageCount' => 0,
        ]);
    }
}
