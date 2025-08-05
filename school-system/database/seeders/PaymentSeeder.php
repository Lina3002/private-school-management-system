<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\School;
use App\Models\ParentModel;
use App\Models\Staff;
use App\Models\Pay;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $schools = School::all();
        foreach ($schools as $school) {
            // Ensure at least 3 demo parents
            $parents = ParentModel::where('school_id', $school->id)->get();
            if ($parents->count() < 3) {
                for ($i = $parents->count(); $i < 3; $i++) {
                    $parents->push(ParentModel::create([
                        'first_name' => 'ParentFirst'.$i,
                        'last_name' => 'ParentLast'.$i,
                        'email' => 'parent'.$i.'_'.$school->id.'@demo.com',
                        'password' => bcrypt('password'),
                        'school_id' => $school->id,
                    ]));
                }
            }
            // Ensure at least 3 demo staff
            $staffs = Staff::where('school_id', $school->id)->get();
            if ($staffs->count() < 3) {
                for ($i = $staffs->count(); $i < 3; $i++) {
                    $staffs->push(Staff::create([
                        'first_name' => 'StaffFirst'.$i,
                        'last_name' => 'StaffLast'.$i,
                        'email' => 'staff'.$i.'_'.$school->id.'@demo.com',
                        'password' => bcrypt('password'),
                        'phone' => '06000000'.($i+1),
                        'CIN' => 'CIN'.$i.$school->id,
                        'address' => 'School '.$school->id.' Address',
                        'school_id' => $school->id,
                        'job_title_id' => 1,
                    ]));
                }
            }
            // Ensure at least 5 demo students
            $students = \App\Models\Student::where('school_id', $school->id)->get();
            if ($students->count() < 5) {
                for ($i = $students->count(); $i < 5; $i++) {
                    $students->push(\App\Models\Student::create([
                        'massar_code' => 'MASSAR'.$i.$school->id,
                        'first_name' => 'StudentFirst'.$i,
                        'last_name' => 'StudentLast'.$i,
                        'gender' => $i%2==0 ? 'male' : 'female',
                        'photo' => '',
                        'email' => 'student'.$i.'_'.$school->id.'@demo.com',
                        'password' => bcrypt('password'),
                        'birth_date' => now()->subYears(10+$i)->format('Y-m-d'),
                        'driving_service' => false,
                        'address' => 'School '.$school->id.' Student Address',
                        'emergency_phone' => '07000000'.($i+1),
                        'city_of_birth' => 'City'.$i,
                        'country_of_birth' => 'Country'.$i,
                        'school_id' => $school->id,
                    ]));
                }
            }
            $parents = ParentModel::where('school_id', $school->id)->get();
            $staffs = Staff::where('school_id', $school->id)->get();
            // Seed 10 payments per school
            for ($i = 0; $i < 10; $i++) {
                $amount = rand(100, 1000);
                $date = Carbon::now()->subDays(rand(0, 30));
                $payment = Payment::create([
                    'school_id' => $school->id,
                    'amount' => $amount,
                    'date' => $date,
                    'method' => 'cash',
                    'type' => 'school',
                    'duedate' => Carbon::now()->addDays(rand(1, 30)),
                    'payment_status' => 'paid',
                    'receipt_file' => '',
                ]);
                // Attach to parent and staff via pays
                $parent = $parents->random();
                $staff = $staffs->random();
                Pay::create([
                    'parent_id' => $parent->id,
                    'payment_id' => $payment->id,
                    'school_id' => $school->id,
                    'staff_id' => $staff->id,
                    'paid_at' => $date,
                ]);
            }
        }
    }
}
