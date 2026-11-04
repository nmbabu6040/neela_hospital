<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DoctorController as PublicDoctorController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DoctorController as AdminDoctorController;

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\Admin\DepartmentController as AdminDepartmentController;
use App\Http\Controllers\Admin\HospitalController as AdminHospitalController;

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\DoctorScheduleController;
use App\Http\Middleware\EnsureAdmin;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/language/{locale}', [HomeController::class, 'switchLanguage'])->name('language.switch');
Route::get('/doctors', [PublicDoctorController::class, 'index'])->name('doctors.index');

Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
Route::get('/departments/{slug}', [DepartmentController::class, 'show'])->name('departments.show');
Route::get('/hospitals', [HospitalController::class, 'index'])->name('hospitals.index');
Route::get('/hospitals/{slug}', [HospitalController::class, 'show'])->name('hospitals.show');
Route::get('/doctors/{slug}', [PublicDoctorController::class, 'show'])->name('doctors.show');

Route::get('/appointment', [AppointmentController::class, 'create'])->name('appointments.create');
Route::post('/appointment', [AppointmentController::class, 'store'])->name('appointments.store');
Route::get('/appointment/success/{appointment}', [AppointmentController::class, 'success'])->name('appointments.success');
Route::get('/appointment-data/doctors/{doctor}/hospitals', [AppointmentController::class, 'hospitalsForDoctor']);
Route::get('/appointment-data/departments/{department}/doctors', [AppointmentController::class, 'doctorsForDepartment']);
Route::get('/appointment-slots', [AppointmentController::class, 'timeSlots'])->name('appointments.slots');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    });
    Route::middleware([EnsureAdmin::class])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::resource('doctors', AdminDoctorController::class)->except(['show']);
        Route::resource('departments', AdminDepartmentController::class)->except(['show']);
        Route::resource('hospitals', AdminHospitalController::class)->except(['show']);

        Route::get('/appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/{appointment}', [AdminAppointmentController::class, 'show'])->name('appointments.show');
        Route::patch('/appointments/{appointment}', [AdminAppointmentController::class, 'update'])->name('appointments.update');
        Route::delete('/appointments/{appointment}', [AdminAppointmentController::class, 'destroy'])->name('appointments.destroy');

        Route::resource('schedules', DoctorScheduleController::class)->except(['show']);
    });
});
