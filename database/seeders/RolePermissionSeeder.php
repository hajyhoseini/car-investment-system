<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // ریست کش permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ایجاد مجوزها (Permissions)
        $permissions = [
            // مجوزهای خودرو
            'view cars',
            'create cars',
            'edit cars',
            'delete cars',
            'sell cars',
            
            // مجوزهای سرمایه‌گذار
            'view investors',
            'create investors',
            'edit investors',
            'delete investors',
            
            // مجوزهای سرمایه‌گذاری
            'view investments',
            'create investments',
            'edit investments',
            'delete investments',
            
            // مجوزهای فروش
            'view sales',
            'create sales',
            'view profits',
            
            // مجوزهای دارایی‌ها
            'view assets',
            'create assets',
            'edit assets',
            'delete assets',
            
            // مجوزهای تعهدات
            'view liabilities',
            'create liabilities',
            'edit liabilities',
            'delete liabilities',
            
            // مجوزهای مدیریت کاربران
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // مجوزهای داشبورد
            'view dashboard',
            'view admin dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // ایجاد نقش‌ها (Roles)
        
        // نقش ادمین (دسترسی به همه چیز)
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all()); // همه مجوزها به ادمین

        // نقش مدیر (مدیریت محتوا - بدون حذف)
        $managerRole = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $managerRole->syncPermissions([
            'view dashboard',
            'view cars',
            'create cars',
            'edit cars',
            'view investors',
            'create investors',
            'edit investors',
            'view investments',
            'create investments',
            'view sales',
            'create sales',
            'view profits',
            'view assets',
            'view liabilities',
        ]);

        // نقش سرمایه‌گذار (فقط مشاهده)
        $investorRole = Role::firstOrCreate(['name' => 'investor', 'guard_name' => 'web']);
        $investorRole->syncPermissions([
            'view dashboard',
            'view cars',
            'view investors',
            'view investments',
            'view sales',
            'view profits',
        ]);

        // نقش کاربر عادی (دسترسی محدود)
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $userRole->syncPermissions([
            'view dashboard',
        ]);

        // اختصاص نقش‌ها به کاربران موجود
        $adminUser = User::where('email', 'admin@example.com')->first();
        if ($adminUser) {
            $adminUser->assignRole('admin');
        }

        $managerUser = User::where('email', 'manager@example.com')->first();
        if (!$managerUser) {
            $managerUser = User::create([
                'name' => 'مدیر محتوا',
                'email' => 'manager@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
        }
        $managerUser->assignRole('manager');

        // اختصاص نقش سرمایه‌گذار به کاربر سارا احمدی (اگه وجود داره)
        $saraUser = User::where('email', 'sara@example.com')->first();
        if ($saraUser) {
            $saraUser->assignRole('investor');
        }

        $this->command->info('نقش‌ها و مجوزها با موفقیت ایجاد شدند.');
    }
}