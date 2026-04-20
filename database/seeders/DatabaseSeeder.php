<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\SqliLabProduct;
use App\Models\SqliLabUser;
use App\Models\Ticket;
use App\Models\User;
use App\Models\VulnerableUser;
use App\Models\XssLabComment;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('🌱 SEEDING DATABASE...');
        $this->command->info('========================================');

        // 1. Panggil UserSeeder untuk mengisi User & Ticket
        $this->command->info('📁 [1/2] Seeding Users and Tickets...');
        $this->call([
            UserSeeder::class,
        ]);
        $this->command->info('   ✓ Users and Tickets seeded successfully.');

        // 2. Buat data untuk lab lainnya (SQLi, Vulnerable Auth, XSS)
        $this->command->info('📁 [2/2] Seeding Other Labs Data...');
        $this->seedOtherLabs();
        
        // 3. Tampilkan ringkasan
        $this->printSummary();
    }

    private function seedOtherLabs(): void
    {
        if (class_exists(SqliLabProduct::class)) {
            SqliLabProduct::truncate();
            SqliLabProduct::insert([
                ['name' => 'Laptop ASUS ROG', 'price' => 15000000, 'description' => 'Gaming laptop', 'stock' => 10],
                ['name' => 'iPhone 15 Pro', 'price' => 18000000, 'description' => 'Smartphone Apple', 'stock' => 25],
            ]);
            $this->command->info('   ✓ Created SQLi Lab products');
        }

        if (class_exists(SqliLabUser::class)) {
            SqliLabUser::truncate();
            SqliLabUser::insert([
                ['username' => 'admin', 'password' => 'supersecret123', 'email' => 'admin@sqlilab.local', 'role' => 'admin'],
                ['username' => 'user1', 'password' => 'password123', 'email' => 'user1@sqlilab.local', 'role' => 'user'],
            ]);
            $this->command->info('   ✓ Created SQLi Lab users');
        }

        if (class_exists(VulnerableUser::class)) {
            VulnerableUser::truncate();
            VulnerableUser::insert([
                ['name' => 'Admin Vulnerable', 'email' => 'admin@vulnerable.test', 'password' => Hash::make('admin123')],
                ['name' => 'User Vulnerable', 'email' => 'user@vulnerable.test', 'password' => Hash::make('user123')],
            ]);
            $this->command->info('   ✓ Created Vulnerable Auth users');
        }

        if (class_exists(XssLabComment::class)) {
            $firstTicket = Ticket::first();
            if ($firstTicket) {
                XssLabComment::truncate();
                XssLabComment::insert([
                    ['ticket_id' => $firstTicket->id, 'author_name' => 'John', 'content' => 'Great article!', 'created_at' => now(), 'updated_at' => now()],
                ]);
                $this->command->info('   ✓ Created XSS Lab comments');
            }
        }
    }

    private function printSummary(): void
    {
        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('✅ DATABASE SEEDED SUCCESSFULLY!');
        $this->command->info('========================================');
        $this->command->info('');
        
        $this->command->info('📋 AUTHORIZATION LAB ACCOUNTS:');
        $this->command->table(
            ['Email', 'Password', 'Role', 'Purpose'],
            [
                ['admin@wikrama.sch.id', 'password', 'admin', 'Full access'],
                ['staff@wikrama.sch.id', 'password', 'staff', 'Handle tickets'],
                ['budi@student.wikrama.sch.id', 'password', 'user', 'Regular user'],
            ]
        );

        $this->command->info('');
        $this->command->info('📋 BAC/IDOR LAB ACCOUNTS:');
        $this->command->table(
            ['Email', 'Password', 'Role', 'Purpose'],
            [
                ['victim@test.com', 'password', 'user', '🎯 TARGET - Has sensitive data'],
                ['attacker@test.com', 'password', 'user', '🔓 ATTACKER - Will try IDOR'],
            ]
        );

        // Dinamis ambil ID tiket Victim untuk panduan test IDOR
        $victim = User::where('email', 'victim@test.com')->first();
        if ($victim) {
            $victimTicket = Ticket::where('user_id', $victim->id)->first();
            if ($victimTicket) {
                $this->command->info('');
                $this->command->warn('🔓 IDOR TEST: Login sebagai attacker, akses /bac-lab/vulnerable/tickets/' . $victimTicket->id);
                $this->command->info('');
            }
        }
    }
}