<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\DoctorSchedule;
use App\Models\News;
use App\Models\Package;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class HospitalDemoSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@example.com'], [
            'name' => 'System Administrator',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $hospitals = [
            ['name'=>'CarePlus Central Hospital','slug'=>'careplus-central-hospital','code'=>'CPH-01','phone'=>'+880 2 55000001','email'=>'central@example.com','address'=>'House 12, Road 5, Dhanmondi','city'=>'Dhaka','facilities'=>['24/7 Emergency','ICU','CCU','Modern Operation Theatre','Pharmacy'],'sort_order'=>1],
            ['name'=>'CarePlus Women & Child Hospital','slug'=>'careplus-women-child-hospital','code'=>'CPH-02','phone'=>'+880 2 55000002','email'=>'womenchild@example.com','address'=>'Plot 8, Sector 7, Uttara','city'=>'Dhaka','facilities'=>['Maternity Care','NICU','Pediatric ICU','Ultrasonography'],'sort_order'=>2],
            ['name'=>'CarePlus Chattogram Hospital','slug'=>'careplus-chattogram-hospital','code'=>'CPH-03','phone'=>'+880 31 5500003','email'=>'ctg@example.com','address'=>'CDA Avenue, Panchlaish','city'=>'Chattogram','facilities'=>['Emergency','Dialysis','Cath Lab','Diagnostic Imaging'],'sort_order'=>3],
        ];
        $hospitalModels = [];
        foreach ($hospitals as $data) $hospitalModels[] = Hospital::updateOrCreate(['slug'=>$data['slug']], $data);

        $departmentNames = [
            'Anesthesiology','Cardiology','Dental & Maxillofacial Surgery','Dermatology','ENT & Head-Neck Surgery',
            'Gastroenterology','General & Laparoscopic Surgery','Gynecology & Obstetrics','Medicine','Nephrology',
            'Neurology','Oncology','Ophthalmology','Orthopedics','Pediatrics',
        ];
        $departmentModels = [];
        foreach ($departmentNames as $index => $name) {
            $slug = Str::slug($name);
            $departmentModels[] = Department::updateOrCreate(['slug'=>$slug], [
                'name'=>$name,
                'short_description'=>'Specialist consultation, diagnosis and treatment services in '.$name.'.',
                'description'=>'The '.$name.' department provides patient-focused assessment, evidence-based treatment and coordinated follow-up care.',
                'services'=>"Consultation\nDiagnosis\nTreatment planning\nFollow-up care",
                'is_featured'=>$index < 8,
                'is_active'=>true,
                'sort_order'=>$index + 1,
            ]);
        }

        $doctorNames = [
            'Dr. A. Rahman','Dr. Nusrat Jahan','Dr. Mahmud Hasan','Dr. Farhana Islam','Dr. Saiful Karim',
            'Dr. Tania Ahmed','Dr. Rezaul Haque','Dr. Samira Chowdhury','Dr. Imran Hossain','Dr. Sabiha Sultana',
            'Dr. Kamal Uddin','Dr. Sharmin Akter','Dr. Faisal Ahmed','Dr. Rukhsana Begum','Dr. Tanvir Alam',
            'Dr. Mehedi Hasan','Dr. Jannatul Ferdous','Dr. Omar Faruk','Dr. Sadia Afrin','Dr. Nayeem Kabir',
        ];
        foreach ($doctorNames as $index => $name) {
            $slug = Str::slug($name).'-'.($index+1);
            $doctor = Doctor::updateOrCreate(['slug'=>$slug], [
                'name'=>$name,
                'designation'=>$index % 3 === 0 ? 'Senior Consultant' : 'Consultant',
                'degrees'=>'MBBS, FCPS, MD',
                'specialization'=>$departmentNames[$index % count($departmentNames)],
                'experience'=>($index % 15 + 5).' years',
                'phone'=>'+880 17'.str_pad((string)(10000000 + $index), 8, '0', STR_PAD_LEFT),
                'short_bio'=>'Experienced specialist providing patient-centred care and professional consultation.',
                'biography'=>'This is demonstration profile content. Replace it with verified professional biography, qualifications and clinical interests before publishing.',
                'is_featured'=>$index < 8,
                'is_active'=>true,
                'sort_order'=>$index + 1,
            ]);
            $department = $departmentModels[$index % count($departmentModels)];
            $hospital = $hospitalModels[$index % count($hospitalModels)];
            $doctor->departments()->syncWithoutDetaching([$department->id]);
            $doctor->hospitals()->syncWithoutDetaching([$hospital->id => ['chamber_room'=>'Room '.(101+$index),'appointment_phone'=>$hospital->phone,'is_primary'=>true]]);
            DoctorSchedule::updateOrCreate([
                'doctor_id'=>$doctor->id,'hospital_id'=>$hospital->id,'day_of_week'=>$index % 6 + 1,
            ], ['start_time'=>'17:00:00','end_time'=>'21:00:00','is_available'=>true,'note'=>'By appointment']);
        }

        $packages = [
            ['name'=>'Basic Health Checkup','price'=>2500,'tests'=>['CBC','Blood Sugar','Urine R/E','Chest X-Ray']],
            ['name'=>'Executive Health Checkup','price'=>6500,'tests'=>['CBC','Lipid Profile','Liver Function','Kidney Function','ECG','Chest X-Ray']],
            ['name'=>'Cardiac Screening Package','price'=>5500,'tests'=>['ECG','Echocardiogram','Lipid Profile','Blood Sugar']],
            ['name'=>'Women Wellness Package','price'=>4800,'tests'=>['CBC','TSH','Blood Sugar','USG Whole Abdomen']],
            ['name'=>'Diabetes Care Package','price'=>3200,'tests'=>['Fasting Blood Sugar','HbA1c','Urine R/E','Creatinine']],
        ];
        foreach ($packages as $index=>$item) {
            Package::updateOrCreate(['slug'=>Str::slug($item['name'])], [
                'name'=>$item['name'],'short_description'=>'A carefully selected set of screening tests.','description'=>'Demo package. Confirm test availability, preparation instructions and final pricing with the hospital before publishing.',
                'price'=>$item['price'],'included_tests'=>$item['tests'],'is_featured'=>$index<3,'is_active'=>true,'sort_order'=>$index+1,
            ]);
        }

        for ($i=1; $i<=5; $i++) {
            News::updateOrCreate(['slug'=>'hospital-news-'.$i], [
                'title'=>'Hospital News & Update '.$i,
                'category'=>'Hospital News',
                'excerpt'=>'Latest announcement and healthcare update from CarePlus Hospital.',
                'body'=>'This is sample news content. Replace it with an approved announcement before publishing.',
                'author'=>'CarePlus Communications Team',
                'published_at'=>now()->subDays($i),
                'is_published'=>true,
                'meta_title'=>'Hospital News & Update '.$i,
                'meta_description'=>'Latest hospital news and healthcare updates.',
            ]);
        }

        $settings = [
            'site_name'=>'CarePlus Hospital','hotline'=>'10600','contact_email'=>'info@example.com',
            'contact_phone'=>'+880 1700-000000','address'=>'Dhanmondi, Dhaka, Bangladesh',
            'facebook_url'=>'https://facebook.com/','twitter_url'=>'https://x.com/',
            'youtube_url'=>'https://youtube.com/','footer_text'=>'Your health, our priority.',
        ];
        foreach ($settings as $key=>$value) SiteSetting::updateOrCreate(['key'=>$key], ['group'=>'general','value'=>$value,'type'=>'text']);
    }
}
