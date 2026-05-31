<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin Helpdesk',
            'email' => 'admin@nexadesk.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $technician = User::create([
            'name' => 'Teknisi IT',
            'email' => 'tech@nexadesk.test',
            'password' => Hash::make('password'),
            'role' => 'technician',
        ]);

        $users = collect([
            ['name' => 'Fathan', 'email' => 'fathan@nexadesk.test'],
            ['name' => 'Aldi', 'email' => 'aldi@nexadesk.test'],
            ['name' => 'Salsa', 'email' => 'salsa@nexadesk.test'],
            ['name' => 'Raka', 'email' => 'raka@nexadesk.test'],
            ['name' => 'Nadia', 'email' => 'nadia@nexadesk.test'],
        ])->map(function ($user) {
            return User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password'),
                'role' => 'user',
            ]);
        });

        $ticketTemplates = [
            ['title' => 'Internet kantor tidak stabil', 'description' => 'Koneksi internet sering putus saat digunakan untuk meeting online.', 'priority' => 'high', 'status' => 'open'],
            ['title' => 'Printer tidak bisa digunakan', 'description' => 'Printer divisi administrasi tidak merespon saat melakukan print dokumen.', 'priority' => 'medium', 'status' => 'in_progress'],
            ['title' => 'Email perusahaan tidak bisa login', 'description' => 'User tidak dapat login ke email perusahaan meskipun password sudah benar.', 'priority' => 'high', 'status' => 'resolved'],
            ['title' => 'Laptop lambat saat membuka aplikasi', 'description' => 'Laptop terasa sangat lambat ketika membuka browser dan aplikasi office.', 'priority' => 'medium', 'status' => 'open'],
            ['title' => 'Mouse wireless tidak terdeteksi', 'description' => 'Mouse wireless tidak terbaca di laptop kerja.', 'priority' => 'low', 'status' => 'closed'],
            ['title' => 'Aplikasi kasir error', 'description' => 'Aplikasi kasir menutup sendiri saat digunakan untuk transaksi.', 'priority' => 'high', 'status' => 'in_progress'],
            ['title' => 'Monitor tidak menampilkan gambar', 'description' => 'Monitor menyala tetapi tidak menampilkan tampilan dari CPU.', 'priority' => 'medium', 'status' => 'open'],
            ['title' => 'WiFi tidak muncul di perangkat', 'description' => 'Nama WiFi kantor tidak muncul pada beberapa laptop karyawan.', 'priority' => 'medium', 'status' => 'resolved'],
            ['title' => 'Keyboard beberapa tombol rusak', 'description' => 'Tombol enter dan shift pada keyboard tidak berfungsi normal.', 'priority' => 'low', 'status' => 'open'],
            ['title' => 'Akses shared folder ditolak', 'description' => 'User tidak bisa membuka folder sharing divisi finance.', 'priority' => 'high', 'status' => 'open'],
        ];

        foreach ($ticketTemplates as $index => $data) {
            $owner = $users[$index % $users->count()];

            $ticket = Ticket::create([
                'user_id' => $owner->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'priority' => $data['priority'],
                'status' => $data['status'],
            ]);

            $ticket->comments()->create([
                'user_id' => $owner->id,
                'message' => 'Mohon dibantu untuk pengecekan kendala ini.',
            ]);

            if ($data['status'] !== 'open') {
                $ticket->comments()->create([
                    'user_id' => $technician->id,
                    'message' => 'Baik, ticket sudah kami terima dan sedang dilakukan pengecekan.',
                ]);
            }
        }
    }
}