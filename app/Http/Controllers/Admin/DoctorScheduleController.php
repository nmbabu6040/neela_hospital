<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Hospital;
use Illuminate\Http\Request;

class DoctorScheduleController extends Controller
{
    public function index() {
        $schedules=DoctorSchedule::with(['doctor','hospital'])->orderBy('day_of_week')->orderBy('start_time')->paginate(20);
        return view('admin.schedules.index',compact('schedules'));
    }
    public function create() {
        $doctors=Doctor::where('is_active',true)->orderBy('name')->get();
        $hospitals=Hospital::where('is_active',true)->orderBy('name')->get();
        return view('admin.schedules.create',compact('doctors','hospitals'));
    }
    public function store(Request $request) {
        DoctorSchedule::create($this->validated($request));
        return redirect()->route('admin.schedules.index')->with('status','Doctor schedule created successfully.');
    }
    public function edit(DoctorSchedule $schedule) {
        $doctors=Doctor::orderBy('name')->get(); $hospitals=Hospital::orderBy('name')->get();
        return view('admin.schedules.edit',compact('schedule','doctors','hospitals'));
    }
    public function update(Request $request, DoctorSchedule $schedule) {
        $schedule->update($this->validated($request));
        return redirect()->route('admin.schedules.index')->with('status','Doctor schedule updated successfully.');
    }
    public function destroy(DoctorSchedule $schedule) {
        $schedule->delete();
        return redirect()->route('admin.schedules.index')->with('status','Doctor schedule deleted successfully.');
    }
    private function validated(Request $request): array {
        return $request->validate([
            'doctor_id'=>['required','integer','exists:doctors,id'],
            'hospital_id'=>['required','integer','exists:hospitals,id'],
            'day_of_week'=>['required','integer','between:0,6'],
            'start_time'=>['required','date_format:H:i'],
            'end_time'=>['required','date_format:H:i','after:start_time'],
            'is_available'=>['nullable','boolean'],
            'note'=>['nullable','string','max:1000'],
        ]) + ['is_available'=>$request->boolean('is_available')];
    }
}
