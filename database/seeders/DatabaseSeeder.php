<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $password = Hash::make('password');

            $departments = collect([
                ['name' => 'IT Support', 'description' => 'Menangani perangkat, printer, dan kebutuhan IT umum.'],
                ['name' => 'Network', 'description' => 'Menangani konektivitas jaringan, WiFi, internet, dan VPN.'],
                ['name' => 'Server', 'description' => 'Menangani server dan infrastruktur backend.'],
                ['name' => 'Application', 'description' => 'Menangani aplikasi, email, dan akses akun pengguna.'],
            ])->mapWithKeys(function (array $data) {
                $department = Department::updateOrCreate(
                    ['name' => $data['name']],
                    ['description' => $data['description']]
                );

                return [$data['name'] => $department];
            });

            $admin = $this->user(
                'Admin Helpdesk',
                'admin@nexadesk.test',
                'admin',
                $password
            );
            $technician = $this->user(
                'Teknisi IT',
                'technician@nexadesk.test',
                'technician',
                $password,
                $departments['IT Support']->id
            );
            $networkTechnician = $this->user(
                'Teknisi Network',
                'network.technician@nexadesk.test',
                'technician',
                $password,
                $departments['Network']->id
            );
            $user = $this->user('Demo User', 'user@nexadesk.test', 'user', $password);
            $secondUser = $this->user('Second User', 'user2@nexadesk.test', 'user', $password);

            $tickets = [
                [
                    'user' => $user,
                    'technician' => null,
                    'department' => $departments['Network'],
                    'title' => 'VPN kantor tidak tersambung',
                    'description' => 'User tidak dapat terhubung ke VPN kantor dari luar jaringan.',
                    'category' => 'network',
                    'priority' => 'high',
                    'status' => 'open',
                    'source' => 'web',
                ],
                [
                    'user' => $secondUser,
                    'technician' => $technician,
                    'department' => $departments['IT Support'],
                    'title' => 'Printer administrasi offline',
                    'description' => 'Printer terlihat offline walaupun perangkat sudah menyala.',
                    'category' => 'printer',
                    'priority' => 'medium',
                    'status' => 'in_progress',
                    'source' => 'web',
                ],
                [
                    'user' => $user,
                    'technician' => $networkTechnician,
                    'department' => $departments['Network'],
                    'title' => 'Internet kantor tidak stabil',
                    'description' => 'Koneksi internet sering terputus saat meeting online.',
                    'category' => 'network',
                    'priority' => 'high',
                    'status' => 'in_progress',
                    'source' => 'email',
                    'email_from' => 'user@nexadesk.test',
                    'email_from_name' => 'Demo User',
                    'email_subject' => 'Internet kantor tidak stabil',
                    'email_body' => 'Mohon bantuan, koneksi internet kantor sering terputus.',
                    'email_message_id' => '<demo-email-1@nexadesk.test>',
                    'email_received_at' => now()->subHours(3),
                ],
                [
                    'user' => $secondUser,
                    'technician' => null,
                    'department' => $departments['Application'],
                    'title' => 'Akun ERP terkunci',
                    'description' => 'Akun ERP terkunci setelah beberapa percobaan login.',
                    'category' => 'account_access',
                    'priority' => 'medium',
                    'status' => 'open',
                    'source' => 'email',
                    'email_from' => 'user2@nexadesk.test',
                    'email_from_name' => 'Second User',
                    'email_subject' => 'Tidak bisa login ERP',
                    'email_body' => 'Akun ERP saya terkunci. Mohon dibantu.',
                    'email_message_id' => '<demo-email-2@nexadesk.test>',
                    'email_received_at' => now()->subHour(),
                ],
                [
                    'user' => $user,
                    'technician' => $technician,
                    'department' => $departments['IT Support'],
                    'title' => 'Laptop lambat saat membuka aplikasi',
                    'description' => 'Laptop terasa lambat ketika membuka browser dan aplikasi office.',
                    'category' => 'hardware',
                    'priority' => 'low',
                    'status' => 'resolved',
                    'source' => 'web',
                ],
            ];

            foreach ($tickets as $index => $data) {
                $startedAt = now()->subHours(8 - $index);
                $ticket = Ticket::updateOrCreate(
                    ['title' => $data['title'], 'user_id' => $data['user']->id],
                    [
                        'assigned_to_user_id' => $data['technician']?->id,
                        'department_id' => $data['department']->id,
                        'description' => $data['description'],
                        'category' => $data['category'],
                        'priority' => $data['priority'],
                        'status' => $data['status'],
                        'source' => $data['source'],
                        'email_from' => $data['email_from'] ?? null,
                        'email_from_name' => $data['email_from_name'] ?? null,
                        'email_subject' => $data['email_subject'] ?? null,
                        'email_body' => $data['email_body'] ?? null,
                        'email_message_id' => $data['email_message_id'] ?? null,
                        'email_received_at' => $data['email_received_at'] ?? null,
                        'sla_started_at' => $startedAt,
                        'sla_due_at' => (new Ticket)->slaDueAtForPriority($data['priority'], $startedAt),
                        'sla_resolved_at' => $data['status'] === 'resolved' ? now()->subHour() : null,
                    ]
                );

                $ticket->activities()->firstOrCreate(
                    ['type' => 'created'],
                    [
                        'user_id' => $data['user']->id,
                        'description' => 'Ticket created',
                    ]
                );
            }

            $admin->update(['department_id' => null]);
        });
    }

    private function user(
        string $name,
        string $email,
        string $role,
        string $password,
        ?int $departmentId = null
    ): User {
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => $password,
                'role' => $role,
            ]
        );

        $user->department_id = $departmentId;
        $user->save();

        return $user;
    }
}
