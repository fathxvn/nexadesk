<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $password = Hash::make('password');
            $now = now()->seconds(0);

            $admin = User::create([
                'name' => 'Admin Helpdesk',
                'email' => 'admin@nexadesk.test',
                'password' => $password,
                'role' => 'admin',
            ]);

            $technicianNames = [
                'Bima Pratama',
                'Dewi Lestari',
                'Rizky Ramadhan',
                'Sinta Maharani',
                'Arif Setiawan',
                'Maya Wulandari',
                'Fajar Nugroho',
                'Nabila Putri',
                'Hendra Saputra',
                'Intan Permata',
            ];

            $technicians = collect($technicianNames)->map(function (string $name, int $index) use ($password) {
                return User::create([
                    'name' => $name,
                    'email' => 'technician' . ($index + 1) . '@nexadesk.test',
                    'password' => $password,
                    'role' => 'technician',
                ]);
            });

            $firstNames = [
                'Ahmad', 'Siti', 'Rizal', 'Dian', 'Agus', 'Rina', 'Fathan', 'Ayu', 'Dimas', 'Putri',
                'Bayu', 'Nadia', 'Andi', 'Fitri', 'Raka', 'Laras', 'Yoga', 'Anisa', 'Ilham', 'Mira',
            ];

            $lastNames = [
                'Prakoso', 'Anggraini', 'Saputra', 'Wulandari', 'Santoso',
                'Permata', 'Wijaya', 'Kartika', 'Nugraha', 'Lestari',
            ];

            $users = collect(range(1, 100))->map(function (int $number) use ($firstNames, $lastNames, $password) {
                $firstName = $firstNames[($number - 1) % count($firstNames)];
                $lastName = $lastNames[intdiv($number - 1, count($firstNames)) % count($lastNames)];

                return User::create([
                    'name' => $firstName . ' ' . $lastName,
                    'email' => 'user' . $number . '@nexadesk.test',
                    'password' => $password,
                    'role' => 'user',
                ]);
            });

            $statuses = array_merge(
                array_fill(0, 120, 'open'),
                array_fill(0, 90, 'in_progress'),
                array_fill(0, 60, 'resolved'),
                array_fill(0, 30, 'closed'),
            );

            $priorities = array_merge(
                array_fill(0, 60, 'high'),
                array_fill(0, 150, 'medium'),
                array_fill(0, 90, 'low'),
            );

            $categories = [
                'network',
                'hardware',
                'software',
                'email',
                'account_access',
                'printer',
                'other',
            ];

            $ticketTemplates = [
                'network' => [
                    ['Internet kantor tidak stabil', 'Koneksi internet sering terputus saat meeting online dan akses aplikasi internal.'],
                    ['WiFi lantai dua tidak muncul', 'Beberapa karyawan tidak dapat menemukan SSID kantor pada laptop kerja.'],
                    ['VPN tidak bisa tersambung', 'Koneksi VPN gagal saat user bekerja dari luar kantor.'],
                ],
                'hardware' => [
                    ['Laptop tidak bisa menyala', 'Perangkat tidak merespon walau charger sudah terhubung.'],
                    ['Monitor berkedip saat digunakan', 'Layar eksternal berkedip setiap beberapa menit ketika dipakai bekerja.'],
                    ['Keyboard beberapa tombol rusak', 'Tombol enter dan shift tidak berfungsi normal pada keyboard kerja.'],
                ],
                'software' => [
                    ['Aplikasi kasir menutup sendiri', 'Aplikasi tertutup tiba-tiba saat transaksi sedang berlangsung.'],
                    ['Microsoft Office gagal aktivasi', 'User tidak dapat membuka dokumen karena lisensi perlu aktivasi ulang.'],
                    ['Aplikasi HR lambat dibuka', 'Halaman dashboard HR membutuhkan waktu lama untuk dimuat.'],
                ],
                'email' => [
                    ['Email perusahaan tidak bisa login', 'User tidak dapat masuk walaupun password sudah diperbarui.'],
                    ['Email masuk terlambat diterima', 'Pesan dari vendor baru masuk setelah beberapa jam.'],
                    ['Tidak bisa kirim lampiran email', 'Lampiran PDF gagal terkirim ke alamat eksternal.'],
                ],
                'account_access' => [
                    ['Akses shared folder ditolak', 'User finance tidak bisa membuka folder bersama divisi.'],
                    ['Akun ERP terkunci', 'Login ERP gagal karena akun terkunci setelah beberapa percobaan.'],
                    ['Reset password akun internal', 'User membutuhkan reset password untuk portal internal perusahaan.'],
                ],
                'printer' => [
                    ['Printer administrasi offline', 'Printer terlihat offline meskipun perangkat menyala.'],
                    ['Hasil print bergaris', 'Dokumen tercetak dengan garis hitam di beberapa halaman.'],
                    ['Tidak bisa scan ke email', 'Fitur scan to email pada printer departemen tidak berjalan.'],
                ],
                'other' => [
                    ['Permintaan instalasi aplikasi', 'User membutuhkan instalasi aplikasi pendukung pekerjaan.'],
                    ['Perlu pengecekan perangkat meeting room', 'Perangkat presentasi ruang rapat tidak menampilkan output.'],
                    ['Pertanyaan akses sistem baru', 'User membutuhkan panduan akses sistem operasional baru.'],
                ],
            ];

            $userComments = [
                'Mohon dibantu pengecekannya karena sudah mengganggu pekerjaan harian.',
                'Kendala masih terjadi setelah perangkat direstart.',
                'Saya sudah mencoba langkah dasar, tetapi masalah belum selesai.',
                'Issue ini cukup urgent karena digunakan untuk pekerjaan tim.',
                'Mohon update jika diperlukan informasi tambahan dari sisi user.',
            ];

            $staffComments = [
                'Ticket sudah kami terima dan sedang dilakukan pengecekan awal.',
                'Kami sedang koordinasi dengan tim terkait untuk memastikan penyebabnya.',
                'Mohon dicoba kembali setelah perubahan konfigurasi yang kami lakukan.',
                'Kami akan memantau beberapa saat untuk memastikan kendala tidak muncul lagi.',
                'Solusi sementara sudah diberikan, silakan kabari jika kendala berulang.',
            ];

            $internalNotes = [
                'Perlu cek log perangkat dan histori perubahan konfigurasi.',
                'Kemungkinan terkait permission user, validasi ulang dengan owner aplikasi.',
                'Prioritaskan karena berdampak ke operasional divisi.',
                'Sudah dicek remote, perlu follow up onsite bila masih gagal.',
                'Pastikan dokumentasi penyelesaian ditambahkan sebelum ticket ditutup.',
            ];

            foreach (range(1, 300) as $number) {
                $status = $statuses[$number - 1];
                $priority = $priorities[($number - 1) % count($priorities)];
                $category = $categories[($number - 1) % count($categories)];
                $template = $ticketTemplates[$category][($number - 1) % count($ticketTemplates[$category])];
                $requester = $users[($number - 1) % $users->count()];
                $assignedTechnician = $this->assignedTechnicianFor($status, $number, $technicians);
                $sla = $this->makeSlaDates($priority, $status, $number, $now);

                $ticketId = DB::table('tickets')->insertGetId([
                    'user_id' => $requester->id,
                    'assigned_to_user_id' => $assignedTechnician?->id,
                    'title' => $template[0],
                    'description' => $template[1] . ' Nomor referensi demo: ND-' . str_pad((string) $number, 4, '0', STR_PAD_LEFT) . '.',
                    'category' => $category,
                    'attachment_path' => null,
                    'priority' => $priority,
                    'status' => $status,
                    'sla_started_at' => $sla['started_at'],
                    'sla_due_at' => $sla['due_at'],
                    'sla_resolved_at' => $sla['resolved_at'],
                    'sla_breached_at' => $sla['breached_at'],
                    'created_at' => $sla['started_at'],
                    'updated_at' => $sla['updated_at'],
                ]);

                $this->insertActivity(
                    $ticketId,
                    $requester->id,
                    'created',
                    'Ticket created',
                    $sla['started_at']->copy()->addMinutes(1)
                );

                if ($assignedTechnician) {
                    $this->insertActivity(
                        $ticketId,
                        $assignedTechnician->id,
                        'assigned',
                        'Assigned to ' . $assignedTechnician->name,
                        $sla['started_at']->copy()->addMinutes(20)
                    );
                }

                if ($status !== 'open') {
                    $this->insertActivity(
                        $ticketId,
                        $assignedTechnician?->id ?? $admin->id,
                        'status_changed',
                        'Status changed from Open to ' . ucfirst(str_replace('_', ' ', $status)),
                        $sla['started_at']->copy()->addHours(1)
                    );
                }

                if ($number % 5 !== 0) {
                    $commentCount = ($number % 4) + 1;

                    foreach (range(1, $commentCount) as $commentNumber) {
                        $isStaffReply = $commentNumber % 2 === 0 && $assignedTechnician;
                        $author = $isStaffReply ? $assignedTechnician : $requester;
                        $messagePool = $isStaffReply ? $staffComments : $userComments;
                        $message = $messagePool[($number + $commentNumber) % count($messagePool)];
                        $commentAt = $sla['started_at']->copy()->addHours($commentNumber)->addMinutes($number % 45);

                        DB::table('ticket_comments')->insert([
                            'ticket_id' => $ticketId,
                            'user_id' => $author->id,
                            'message' => $message,
                            'created_at' => $commentAt,
                            'updated_at' => $commentAt,
                        ]);

                        $this->insertActivity(
                            $ticketId,
                            $author->id,
                            'comment',
                            'Comment added',
                            $commentAt->copy()->addMinute()
                        );
                    }
                }

                if ($assignedTechnician && $number % 3 !== 0) {
                    $noteCount = ($number % 2) + 1;

                    foreach (range(1, $noteCount) as $noteNumber) {
                        $noteAuthor = $noteNumber % 2 === 0 ? $admin : $assignedTechnician;
                        $noteAt = $sla['started_at']->copy()->addHours(2 + $noteNumber)->addMinutes($number % 30);
                        $body = $internalNotes[($number + $noteNumber) % count($internalNotes)];

                        DB::table('ticket_internal_notes')->insert([
                            'ticket_id' => $ticketId,
                            'user_id' => $noteAuthor->id,
                            'body' => $body,
                            'created_at' => $noteAt,
                            'updated_at' => $noteAt,
                        ]);

                        $this->insertActivity(
                            $ticketId,
                            $noteAuthor->id,
                            'internal_note_added',
                            'Internal note added',
                            $noteAt->copy()->addMinute()
                        );
                    }
                }
            }
        });
    }

    private function assignedTechnicianFor(string $status, int $number, $technicians): ?User
    {
        if ($status === 'open' && $number % 3 !== 0) {
            return null;
        }

        if ($status === 'in_progress' && $number % 10 === 0) {
            return null;
        }

        return $technicians[($number - 1) % $technicians->count()];
    }

    private function makeSlaDates(string $priority, string $status, int $number, Carbon $now): array
    {
        $hours = match ($priority) {
            'high' => 8,
            'medium' => 24,
            default => 72,
        };

        if (in_array($status, ['resolved', 'closed'], true)) {
            $startedAt = $now->copy()->subDays(10 + ($number % 20))->subHours($number % 8);
            $dueAt = $startedAt->copy()->addHours($hours);
            $resolvedAt = $number % 4 === 0
                ? $dueAt->copy()->addHours(2 + ($number % 12))
                : $dueAt->copy()->subHours(1 + ($number % max(1, min($hours - 1, 8))));

            return [
                'started_at' => $startedAt,
                'due_at' => $dueAt,
                'resolved_at' => $resolvedAt,
                'breached_at' => $resolvedAt->greaterThan($dueAt) ? $dueAt->copy()->addMinute() : null,
                'updated_at' => $resolvedAt,
            ];
        }

        $slaBucket = $number % 10;

        if ($slaBucket <= 2) {
            $dueAt = $now->copy()->subHours(1 + ($number % 12));
            $breachedAt = $number % 2 === 0 ? $dueAt->copy()->addMinute() : null;
        } elseif ($slaBucket <= 4) {
            $dueAt = $now->copy()->addHours(1 + ($number % 4));
            $breachedAt = null;
        } else {
            $dueAt = $now->copy()->addHours(6 + ($number % 48));
            $breachedAt = null;
        }

        $startedAt = $dueAt->copy()->subHours($hours);

        return [
            'started_at' => $startedAt,
            'due_at' => $dueAt,
            'resolved_at' => null,
            'breached_at' => $breachedAt,
            'updated_at' => $startedAt->copy()->addHours(1 + ($number % 8)),
        ];
    }

    private function insertActivity(int $ticketId, int $userId, string $type, string $description, Carbon $createdAt): void
    {
        DB::table('ticket_activities')->insert([
            'ticket_id' => $ticketId,
            'user_id' => $userId,
            'type' => $type,
            'description' => $description,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);
    }
}
