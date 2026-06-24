<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $password = Hash::make('password');
            $now = now()->seconds(0);

            $departments = collect([
                ['name' => 'IT Support', 'description' => 'Menangani perangkat, printer, dan kebutuhan IT umum.'],
                ['name' => 'Network', 'description' => 'Menangani konektivitas jaringan, WiFi, internet, dan VPN.'],
                ['name' => 'Server', 'description' => 'Menangani server, infrastruktur, dan layanan backend.'],
                ['name' => 'Application', 'description' => 'Menangani aplikasi, email, dan akses akun pengguna.'],
            ])->mapWithKeys(function (array $department) {
                $model = Department::updateOrCreate(
                    ['name' => $department['name']],
                    ['description' => $department['description']]
                );

                return [$department['name'] => $model];
            });

            User::updateOrCreate(
                ['email' => 'admin@nexadesk.test'],
                [
                    'name' => 'Admin Helpdesk',
                    'password' => $password,
                    'role' => 'admin',
                ]
            );

            $technicianData = [
                ['name' => 'Teknisi IT', 'email' => 'tech.it@nexadesk.test', 'department' => 'IT Support'],
                ['name' => 'Teknisi Network', 'email' => 'tech.network@nexadesk.test', 'department' => 'Network'],
                ['name' => 'Teknisi Server', 'email' => 'tech.server@nexadesk.test', 'department' => 'Server'],
                ['name' => 'Teknisi Application', 'email' => 'tech.application@nexadesk.test', 'department' => 'Application'],
            ];

            $technicians = collect($technicianData)->mapWithKeys(function (array $data) use ($departments, $password) {
                $technician = User::updateOrCreate(
                    ['email' => $data['email']],
                    [
                        'name' => $data['name'],
                        'password' => $password,
                        'role' => 'technician',
                    ]
                );

                $technician->department_id = $departments[$data['department']]->id;
                $technician->save();

                return [$data['department'] => $technician];
            });

            $users = collect([
                ['name' => 'Fathan', 'email' => 'fathan@nexadesk.test'],
                ['name' => 'Aldi', 'email' => 'aldi@nexadesk.test'],
                ['name' => 'Salsa', 'email' => 'salsa@nexadesk.test'],
                ['name' => 'Raka', 'email' => 'raka@nexadesk.test'],
                ['name' => 'Nadia', 'email' => 'nadia@nexadesk.test'],
            ])->map(function (array $data) use ($password) {
                return User::updateOrCreate(
                    ['email' => $data['email']],
                    [
                        'name' => $data['name'],
                        'password' => $password,
                        'role' => 'user',
                    ]
                );
            });

            $categoryDepartments = [
                'network' => 'Network',
                'hardware' => 'IT Support',
                'printer' => 'IT Support',
                'software' => 'Application',
                'email' => 'Application',
                'account_access' => 'Application',
                'other' => 'IT Support',
            ];

            $ticketTemplates = [
                ['title' => 'Internet kantor tidak stabil', 'description' => 'Koneksi internet sering putus saat digunakan untuk meeting online.', 'category' => 'network', 'priority' => 'high', 'status' => 'open'],
                ['title' => 'Printer tidak bisa digunakan', 'description' => 'Printer divisi administrasi tidak merespon saat melakukan print dokumen.', 'category' => 'printer', 'priority' => 'medium', 'status' => 'in_progress'],
                ['title' => 'Email perusahaan tidak bisa login', 'description' => 'User tidak dapat login ke email perusahaan meskipun password sudah benar.', 'category' => 'email', 'priority' => 'high', 'status' => 'resolved'],
                ['title' => 'Laptop lambat saat membuka aplikasi', 'description' => 'Laptop terasa sangat lambat ketika membuka browser dan aplikasi office.', 'category' => 'hardware', 'priority' => 'medium', 'status' => 'open'],
                ['title' => 'Mouse wireless tidak terdeteksi', 'description' => 'Mouse wireless tidak terbaca di laptop kerja.', 'category' => 'hardware', 'priority' => 'low', 'status' => 'closed'],
                ['title' => 'Aplikasi kasir error', 'description' => 'Aplikasi kasir menutup sendiri saat digunakan untuk transaksi.', 'category' => 'software', 'priority' => 'high', 'status' => 'in_progress'],
                ['title' => 'Monitor tidak menampilkan gambar', 'description' => 'Monitor menyala tetapi tidak menampilkan tampilan dari CPU.', 'category' => 'hardware', 'priority' => 'medium', 'status' => 'open'],
                ['title' => 'WiFi tidak muncul di perangkat', 'description' => 'Nama WiFi kantor tidak muncul pada beberapa laptop karyawan.', 'category' => 'network', 'priority' => 'medium', 'status' => 'resolved'],
                ['title' => 'Reset password akun internal', 'description' => 'User membutuhkan reset password untuk portal internal perusahaan.', 'category' => 'account_access', 'priority' => 'low', 'status' => 'open'],
                ['title' => 'Permintaan instalasi aplikasi', 'description' => 'User membutuhkan instalasi aplikasi pendukung pekerjaan.', 'category' => 'other', 'priority' => 'high', 'status' => 'open'],
            ];

            foreach ($ticketTemplates as $index => $data) {
                $owner = $users[$index % $users->count()];
                $departmentName = $categoryDepartments[$data['category']];
                $assignedTechnician = $data['status'] === 'open'
                    ? null
                    : $technicians[$departmentName];
                $sla = $this->makeSlaDates($data['priority'], $data['status'], $index + 1, $now);

                $ticket = Ticket::create([
                    'user_id' => $owner->id,
                    'assigned_to_user_id' => $assignedTechnician?->id,
                    'department_id' => $departments[$departmentName]->id,
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'category' => $data['category'],
                    'priority' => $data['priority'],
                    'status' => $data['status'],
                    'sla_started_at' => $sla['started_at'],
                    'sla_due_at' => $sla['due_at'],
                    'sla_resolved_at' => $sla['resolved_at'],
                    'sla_breached_at' => $sla['breached_at'],
                ]);

                $ticket->comments()->create([
                    'user_id' => $owner->id,
                    'message' => 'Mohon dibantu untuk pengecekan kendala ini.',
                ]);

                if ($assignedTechnician) {
                    $ticket->comments()->create([
                        'user_id' => $assignedTechnician->id,
                        'message' => 'Baik, ticket sudah kami terima dan sedang dilakukan pengecekan.',
                    ]);
                }
            }
        });
    }

    private function makeSlaDates(string $priority, string $status, int $number, Carbon $now): array
    {
        $hours = match ($priority) {
            'high' => 8,
            'medium' => 24,
            default => 72,
        };

        $startedAt = $now->copy()->subDays($number)->subHours($number % 6);
        $dueAt = $startedAt->copy()->addHours($hours);

        if (! in_array($status, ['resolved', 'closed'], true)) {
            return [
                'started_at' => $startedAt,
                'due_at' => $dueAt,
                'resolved_at' => null,
                'breached_at' => $dueAt->isPast() ? $dueAt->copy()->addMinute() : null,
            ];
        }

        $resolvedAt = $number % 2 === 0
            ? $dueAt->copy()->subHour()
            : $dueAt->copy()->addHours(2);

        return [
            'started_at' => $startedAt,
            'due_at' => $dueAt,
            'resolved_at' => $resolvedAt,
            'breached_at' => $resolvedAt->greaterThan($dueAt) ? $dueAt->copy()->addMinute() : null,
        ];
    }
}
