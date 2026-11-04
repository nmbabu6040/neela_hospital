<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['doctor','department','hospital'])->latest();
        if ($request->filled('status')) $query->where('status',$request->status);
        if ($request->filled('q')) {
            $term = $request->q;
            $query->where(fn($q) => $q->where('patient_name','like',"%$term%")->orWhere('patient_phone','like',"%$term%"));
        }
        $appointments = $query->paginate(15)->withQueryString();
        return view('admin.appointments.index',compact('appointments'));
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['doctor','department','hospital']);
        return view('admin.appointments.show',compact('appointment'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $v = $request->validate([
            'status'=>'required|in:pending,confirmed,cancelled,completed',
            'admin_note'=>'nullable|string|max:2000'
        ]);
        $appointment->update($v);
        return back()->with('success','Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('admin.appointments.index')->with('success','Appointment deleted.');
    }
}
