<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classroom;
use App\Models\School;

class MoroccanEducationLevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first school (or create one if none exists)
        $school = School::first();
        
        if (!$school) {
            $this->command->error('No school found. Please create a school first.');
            return;
        }

        // Moroccan Education Levels
        $levels = [
            // Primary Education (École Primaire)
            ['name' => 'CP1', 'level' => 'CP1', 'description' => 'Cours Préparatoire 1ère année - First Year Preparatory Course'],
            ['name' => 'CP2', 'level' => 'CP2', 'description' => 'Cours Préparatoire 2ème année - Second Year Preparatory Course'],
            ['name' => 'CE1', 'level' => 'CE1', 'description' => 'Cours Élémentaire 1ère année - First Year Elementary Course'],
            ['name' => 'CE2', 'level' => 'CE2', 'description' => 'Cours Élémentaire 2ème année - Second Year Elementary Course'],
            ['name' => 'CM1', 'level' => 'CM1', 'description' => 'Cours Moyen 1ère année - First Year Middle Course'],
            ['name' => 'CM2', 'level' => 'CM2', 'description' => 'Cours Moyen 2ème année - Second Year Middle Course'],
            
            // Middle School (Collège)
            ['name' => '6ème', 'level' => '6ème', 'description' => 'Sixième - Sixth Grade (First Year of Middle School)'],
            ['name' => '5ème', 'level' => '5ème', 'description' => 'Cinquième - Fifth Grade (Second Year of Middle School)'],
            ['name' => '4ème', 'level' => '4ème', 'description' => 'Quatrième - Fourth Grade (Third Year of Middle School)'],
            ['name' => '3ème', 'level' => '3ème', 'description' => 'Troisième - Third Grade (Fourth Year of Middle School)'],
            
            // High School (Lycée)
            ['name' => 'Tronc Commun', 'level' => 'Tronc Commun', 'description' => 'Tronc Commun - Common Core (First Year of High School)'],
            ['name' => '1ère BAC', 'level' => '1ère BAC', 'description' => 'Première Baccalauréat - First Year Baccalaureate'],
            ['name' => '2ème BAC', 'level' => '2ème BAC', 'description' => 'Deuxième Baccalauréat - Second Year Baccalaureate (Final Year)'],
        ];

        foreach ($levels as $level) {
            Classroom::updateOrCreate(
                [
                    'name' => $level['name'],
                    'school_id' => $school->id,
                ],
                [
                    'level' => $level['level'],
                    'description' => $level['description'],
                    'capacity' => 30,
                    'academic_year' => '2024-2025',
                ]
            );
        }

        $this->command->info('Moroccan education levels seeded successfully!');
        $this->command->info('Created ' . count($levels) . ' classroom levels.');
    }
} 