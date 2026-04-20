<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================
        // 1. CREATE ALL USERS
        // ============================================
        
        // Authorization Lab Users
        $admin = User::create([
            'name' => 'Administrator', 'email' => 'admin@wikrama.sch.id',
            'password' => Hash::make('password'), 'role' => 'admin', 'email_verified_at' => now(),
        ]);

        $staff = User::create([
            'name' => 'Staff Helpdesk', 'email' => 'staff@wikrama.sch.id',
            'password' => Hash::make('password'), 'role' => 'staff', 'email_verified_at' => now(),
        ]);

        $budi = User::create([
            'name' => 'Budi Santoso', 'email' => 'budi@student.wikrama.sch.id',
            'password' => Hash::make('password'), 'role' => 'user', 'email_verified_at' => now(),
        ]);

        $siti = User::create([
            'name' => 'Siti Rahayu', 'email' => 'siti@student.wikrama.sch.id',
            'password' => Hash::make('password'), 'role' => 'user', 'email_verified_at' => now(),
        ]);

        // BAC/IDOR Lab Users
        $victim = User::create([
            'name' => 'Korban (Victim)', 'email' => 'victim@test.com',
            'password' => Hash::make('password'), 'role' => 'user', 'email_verified_at' => now(),
        ]);

        $attacker = User::create([
            'name' => 'Penyerang (Attacker)', 'email' => 'attacker@test.com',
            'password' => Hash::make('password'), 'role' => 'user', 'email_verified_at' => now(),
        ]);


        // ============================================
        // 2. CREATE ALL TICKETS
        // ============================================

        // --- Tickets untuk Authorization Lab ---
        Ticket::create(['user_id' => $budi->id, 'assigned_to' => $staff->id, 'title' => 'Tidak bisa login ke e-learning', 'description' => 'Saya sudah coba reset password tapi tetap tidak bisa masuk.', 'priority' => 'high', 'status' => 'in_progress']);
        Ticket::create(['user_id' => $budi->id, 'assigned_to' => null, 'title' => 'Request akses lab komputer', 'description' => 'Mohon dibukakan akses ke lab.', 'priority' => 'medium', 'status' => 'open']);
        Ticket::create(['user_id' => $siti->id, 'assigned_to' => $staff->id, 'title' => 'Printer di perpustakaan error', 'description' => 'Printer mengeluarkan kertas kosong.', 'priority' => 'medium', 'status' => 'in_progress']);
        Ticket::create(['user_id' => $siti->id, 'assigned_to' => null, 'title' => 'WiFi lambat di kelas XII RPL', 'description' => 'Koneksi lambat, sulit akses materi.', 'priority' => 'low', 'status' => 'open']);
        Ticket::create(['user_id' => $budi->id, 'assigned_to' => $staff->id, 'title' => 'Password email terlupa', 'description' => 'Lupa password email sekolah.', 'priority' => 'high', 'status' => 'closed']);

        // --- Tickets untuk BAC/IDOR Lab ---
        Ticket::create(['user_id' => $victim->id, 'title' => '[CONFIDENTIAL] Laporan Keuangan Q1', 'description' => 'Data keuangan rahasia perusahaan. Total pendapatan: Rp 500.000.000.', 'status' => 'open', 'priority' => 'high']);
        Ticket::create(['user_id' => $victim->id, 'title' => '[PRIVATE] Data Pribadi Karyawan', 'description' => 'NIK: 3201xxxxx, Alamat: Jl. Rahasia No. 123.', 'status' => 'in_progress', 'priority' => 'high']);
        Ticket::create(['user_id' => $attacker->id, 'title' => 'Tiket Biasa Attacker', 'description' => 'Ini adalah tiket normal milik attacker.', 'status' => 'open', 'priority' => 'low']);
        Ticket::create(['user_id' => $attacker->id, 'title' => 'Permintaan Support', 'description' => 'Tiket support biasa.', 'status' => 'open', 'priority' => 'medium']);
        Ticket::create(['user_id' => $admin->id, 'title' => '[ADMIN] System Maintenance', 'description' => 'Jadwal maintenance server.', 'status' => 'open', 'priority' => 'medium']);
    }
}