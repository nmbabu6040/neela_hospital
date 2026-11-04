<?php
namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Hospital;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    public function create()
    {
        $doctors = Doctor::where('is_active', true)->orderBy('name')->get();
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $hospitals = Hospital::where('is_active', true)->orderBy('name')->get();
        return view('appointments.create', compact('doctors','departments','hospitals'));
    }

    public function hospitalsForDoctor(Doctor $doctor)
    {
        return response()->json($doctor->hospitals()->where('hospitals.is_active', true)
            ->orderBy('hospitals.name')->get(['hospitals.id','hospitals.name']));
    }

    public function doctorsForDepartment(Department $department)
    {
        return response()->json($department->doctors()->where('doctors.is_active', true)
            ->orderBy('doctors.name')->get(['doctors.id','doctors.name','doctors.designation']));
    }

    public function timeSlots(Request $request)
    {
        $v = $request->validate([
            'doctor_id'=>'required|exists:doctors,id',
            'hospital_id'=>'required|exists:hospitals,id',
            'date'=>'required|date|after_or_equal:today'
        ]);
        $doctor = Doctor::where('is_active', true)->findOrFail($v['doctor_id']);
        $hospital = Hospital::where('is_active', true)->findOrFail($v['hospital_id']);
        abort_unless($doctor->hospitals()->whereKey($hospital->id)->exists(), 422);

        $date = Carbon::parse($v['date']);
        $schedules = DoctorSchedule::where('doctor_id',$doctor->id)
            ->where('hospital_id',$hospital->id)->where('day_of_week',$date->dayOfWeek)
            ->where('is_available',true)->orderBy('start_time')->get();

        $slots = [];
        foreach ($schedules as $schedule) {
            $cursor = Carbon::parse($v['date'].' '.$schedule->start_time);
            $end = Carbon::parse($v['date'].' '.$schedule->end_time);
            while ($cursor->copy()->addMinutes(30)->lte($end)) {
                $value = $cursor->format('H:i:s');
                $booked = Appointment::where('doctor_id',$doctor->id)->where('hospital_id',$hospital->id)
                    ->whereDate('appointment_date',$v['date'])->where('appointment_time',$value)
                    ->where('status','!=','cancelled')->exists();
                if (!$booked && $cursor->gt(now())) {
                    $slots[] = ['value'=>$value,'label'=>$cursor->format('h:i A')];
                }
                $cursor->addMinutes(30);
            }
        }
        return response()->json($slots);
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'doctor_id'=>'required|exists:doctors,id',
            'department_id'=>'nullable|exists:departments,id',
            'hospital_id'=>'required|exists:hospitals,id',
            'appointment_date'=>'required|date|after_or_equal:today',
            'appointment_time'=>'required|date_format:H:i:s',
            'patient_name'=>'required|string|max:150',
            'patient_phone'=>'required|string|max:30',
            'patient_email'=>'nullable|email|max:150',
            'patient_age'=>'nullable|integer|min:0|max:120',
            'patient_gender'=>'nullable|in:male,female,other',
            'patient_message'=>'nullable|string|max:2000'
        ]);
        $doctor = Doctor::where('is_active',true)->findOrFail($v['doctor_id']);
        $hospital = Hospital::where('is_active',true)->findOrFail($v['hospital_id']);
        if (!$doctor->hospitals()->whereKey($hospital->id)->exists()) {
            throw ValidationException::withMessages(['hospital_id'=>'এই চিকিৎসক নির্বাচিত হাসপাতালে চেম্বার করেন না।']);
        }
        if (!empty($v['department_id']) && !$doctor->departments()->whereKey($v['department_id'])->exists()) {
            throw ValidationException::withMessages(['department_id'=>'নির্বাচিত বিভাগটি এই চিকিৎসকের সঙ্গে যুক্ত নয়।']);
        }
        $date = Carbon::parse($v['appointment_date']);
        $validSchedule = DoctorSchedule::where('doctor_id',$doctor->id)->where('hospital_id',$hospital->id)
            ->where('day_of_week',$date->dayOfWeek)->where('is_available',true)
            ->where('start_time','<=',$v['appointment_time'])->where('end_time','>',$v['appointment_time'])->exists();
        if (!$validSchedule) throw ValidationException::withMessages(['appointment_time'=>'এই সময়ে চিকিৎসকের চেম্বার নেই।']);

        $taken = Appointment::where('doctor_id',$doctor->id)->where('hospital_id',$hospital->id)
            ->whereDate('appointment_date',$v['appointment_date'])->where('appointment_time',$v['appointment_time'])
            ->where('status','!=','cancelled')->exists();
        if ($taken) throw ValidationException::withMessages(['appointment_time'=>'এই সময়টি ইতোমধ্যে বুক করা হয়েছে।']);

        $appointment = Appointment::create($v + ['status'=>'pending']);
        return redirect()->route('appointments.success',$appointment)
            ->with('booking_code','APT-'.str_pad((string)$appointment->id,6,'0',STR_PAD_LEFT));
    }

    public function success(Appointment $appointment)
    {
        $appointment->load(['doctor','hospital','department']);
        return view('appointments.success',compact('appointment'));
    }
}
