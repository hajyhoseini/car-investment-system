<?php
// database/seeders/ExpensePermissionsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class ExpensePermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // ریست کش permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // =============================================
        // ایجاد مجوزهای هزینه‌ها
        // =============================================
        $expensePermissions = [
            'view expenses',
            'create expenses',
            'edit expenses',
            'delete expenses',
        ];

        foreach ($expensePermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // =============================================
        // پیدا کردن نقش‌ها
        // =============================================
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $managerRole = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $accountantRole = Role::firstOrCreate(['name' => 'accountant', 'guard_name' => 'web']);

        // =============================================
        // اختصاص مجوزها به نقش‌ها
        // =============================================

        // ادمین: همه مجوزهای هزینه‌ها
        $adminRole->givePermissionTo($expensePermissions);

        // مدیر: مشاهده، ایجاد و ویرایش (بدون حذف)
        $managerRole->givePermissionTo([
            'view expenses',
            'create expenses',
            'edit expenses',
        ]);

        // حسابدار: مشاهده و ایجاد
        $accountantRole->givePermissionTo([
            'view expenses',
            'create expenses',
        ]);

        // =============================================
        // اختصاص مجوزها به کاربر ادمین
        // =============================================
        $adminUser = User::where('email', 'admin@example.com')->first();
        if ($adminUser) {
            $adminUser->givePermissionTo($expensePermissions);
            $this->command->info('✅ مجوزهای هزینه‌ها به کاربر ادمین اختصاص یافت.');
        }

        $this->command->info('🎉 مجوزهای هزینه‌ها با موفقیت ایجاد و به نقش‌ها اختصاص یافتند.');
    }
}