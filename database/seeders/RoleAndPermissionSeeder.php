<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Daftar permissions
        $permissions = [
            // View / Read
            'view-dashboard',
            'view-data',
            'export-data',

            // Manage / Write
            'create-data',
            'edit-data',
            'delete-data',
            'import-data',
            'clear-data',
            'manage-settings',
            'generate-ai',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Buat Role: Admin
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->syncPermissions(Permission::all());

        // Buat Role: Guest
        // Guest hanya memiliki hak akses view/read & export (bila diizinkan) tanpa aksi manipulasi data apa pun
        $guestRole = Role::firstOrCreate(['name' => 'Guest']);
        $guestRole->syncPermissions([
            'view-dashboard',
            'view-data',
            'export-data',
        ]);

        // Berikan role Admin ke user admin yang ada atau buat baru jika belum ada
        $adminUser = User::firstWhere('email', 'admin@email.com');
        if (! $adminUser) {
            $adminUser = User::firstWhere('email', 'admin@sicepot.id');
        }

        if (! $adminUser) {
            $adminUser = User::create([
                'name' => 'Admin SICEPOT',
                'email' => 'admin@email.com',
                'password' => Hash::make('password'),
            ]);
        }

        if (! $adminUser->hasRole('Admin')) {
            $adminUser->assignRole($adminRole);
        }

        // Buat Akun Guest Contoh
        $guestUser = User::firstWhere('email', 'guest@email.com');
        if (! $guestUser) {
            $guestUser = User::create([
                'name' => 'Guest Account',
                'email' => 'guest@email.com',
                'password' => Hash::make('guest12345'),
            ]);
        }

        if (! $guestUser->hasRole('Guest')) {
            $guestUser->syncRoles([$guestRole]);
        }
    }
}
