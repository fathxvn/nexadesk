<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_uas_seeder_creates_a_small_clear_demo_dataset(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(4, Department::count());
        $this->assertSame(5, User::count());
        $this->assertSame(1, User::where('role', 'admin')->count());
        $this->assertSame(2, User::where('role', 'technician')->count());
        $this->assertSame(2, User::where('role', 'user')->count());
        $this->assertLessThanOrEqual(5, Ticket::count());

        $this->assertDatabaseHas('users', [
            'email' => 'admin@nexadesk.test',
            'role' => 'admin',
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'technician@nexadesk.test',
            'role' => 'technician',
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'user@nexadesk.test',
            'role' => 'user',
        ]);
    }
}
