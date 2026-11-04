
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            // Doctor, Department and Hospital
            $table->foreignId('doctor_id')
                ->constrained('doctors')
                ->cascadeOnDelete();

            $table->foreignId('department_id')
                ->nullable()
                ->constrained('departments')
                ->nullOnDelete();

            $table->foreignId('hospital_id')
                ->nullable()
                ->constrained('hospitals')
                ->nullOnDelete();

            // Patient information
            $table->string('patient_name');
            $table->string('patient_phone', 30);
            $table->string('patient_email')->nullable();
            $table->unsignedTinyInteger('patient_age')->nullable();
            $table->string('patient_gender', 20)->nullable();
            $table->text('patient_message')->nullable();

            // Appointment information
            $table->date('appointment_date');
            $table->time('appointment_time');

            // Booking status
            $table->string('status', 30)->default('pending');

            $table->text('admin_note')->nullable();

            $table->timestamps();

            $table->index(['doctor_id', 'appointment_date']);
            $table->index(['hospital_id', 'appointment_date']);
            $table->index(['status', 'appointment_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
