<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\Participant;
use App\Models\RoundSetting;
use App\Models\Round;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

         Admin::create([
            'name' => 'Main Admin',
            'email' => 'admin@competition.com',
            'password' => Hash::make('123456')
        ]);

        Participant::create([
            'queue_number' => 1,
            'name' => 'Ahmad',
            'school' => 'SMAN 1 Makassar',
            'status' => 'active'
        ]);

        Participant::create([
            'queue_number' => 2,
            'name' => 'Siti',
            'school' => 'SMAN 2 Makassar',
            'status' => 'active'
        ]);

        Participant::create([
            'queue_number' => 3,
            'name' => 'Budi',
            'school' => 'SMKN 1 Makassar',
            'status' => 'active'
        ]);

        Participant::create(['queue_number' => 4, 'name' => 'Participant 4', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 5, 'name' => 'Participant 5', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 6, 'name' => 'Participant 6', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 7, 'name' => 'Participant 7', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 8, 'name' => 'Participant 8', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 9, 'name' => 'Participant 9', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 10, 'name' => 'Participant 10', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 11, 'name' => 'Participant 11', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 12, 'name' => 'Participant 12', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 13, 'name' => 'Participant 13', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 14, 'name' => 'Participant 14', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 15, 'name' => 'Participant 15', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 16, 'name' => 'Participant 16', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 17, 'name' => 'Participant 17', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 18, 'name' => 'Participant 18', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 19, 'name' => 'Participant 19', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 20, 'name' => 'Participant 20', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 21, 'name' => 'Participant 21', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 22, 'name' => 'Participant 22', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 23, 'name' => 'Participant 23', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 24, 'name' => 'Participant 24', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 25, 'name' => 'Participant 25', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 26, 'name' => 'Participant 26', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 27, 'name' => 'Participant 27', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 28, 'name' => 'Participant 28', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 29, 'name' => 'Participant 29', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 30, 'name' => 'Participant 30', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 31, 'name' => 'Participant 31', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 32, 'name' => 'Participant 32', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 33, 'name' => 'Participant 33', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 34, 'name' => 'Participant 34', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 35, 'name' => 'Participant 35', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 36, 'name' => 'Participant 36', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 37, 'name' => 'Participant 37', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 38, 'name' => 'Participant 38', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 39, 'name' => 'Participant 39', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);
        Participant::create(['queue_number' => 40, 'name' => 'Participant 40', 'school' => 'SMKN 1 Makassar', 'status' => 'active']);

        $round = Round::create([
            'title' => 'Endurance Matrix',
            'number' => 1,
            'segment' => 1,
            'type' => 'numeric',
            'is_active' => true
        ]);

        RoundSetting::create([
            'round_id' => $round->id,
            'correct_answer' => '45'
        ]);
    }
}
