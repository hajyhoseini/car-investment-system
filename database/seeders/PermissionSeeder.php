<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // ریست کش permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // =============================================
        // 1. تعریف همه مجوزها
        // =============================================
        
        // مجوزهای خودرو
        $carPermissions = [
            'view cars',
            'create cars',
            'edit cars',
            'delete cars',
            'sell cars',
        ];

        // مجوزهای سرمایه‌گذار
        $investorPermissions = [
            'view investors',
            'create investors',
            'edit investors',
            'delete investors',
        ];

        // مجوزهای سرمایه‌گذاری
        $investmentPermissions = [
            'view investments',
            'create investments',
            'edit investments',
            'delete investments',
        ];

        // مجوزهای فروش
        $salePermissions = [
            'view sales',
            'create sales',
            'view profits',
        ];

        // مجوزهای دارایی
        $assetPermissions = [
            'view assets',
            'create assets',
            'edit assets',
            'delete assets',
        ];

        // مجوزهای تعهدات
        $liabilityPermissions = [
            'view liabilities',
            'create liabilities',
            'edit liabilities',
            'delete liabilities',
        ];

        // مجوزهای کاربران
        $userPermissions = [
            'view users',
            'create users',
            'edit users',
            'delete users',
        ];

        // مجوزهای داشبورد
        $dashboardPermissions = [
            'view dashboard',
            'view admin dashboard',
        ];

        // مجوزهای اشخاص
        $peoplePermissions = [
            'view people',
            'create people',
            'edit people',
            'delete people',
        ];

        // مجوزهای تراکنش (دریافت و پرداخت)
        $transactionPermissions = [
            'view transactions',
            'create transactions',
            'edit transactions',
            'delete transactions',
        ];

        // مجوزهای حساب (Asset)
        $accountPermissions = [
            'view accounts',
            'create accounts',
            'edit accounts',
            'delete accounts',
        ];

        // مجوزهای روش پرداخت
        $paymentMethodPermissions = [
            'view payment-methods',
            'create payment-methods',
            'edit payment-methods',
            'delete payment-methods',
        ];

        // مجوزهای هزینه‌ها
        $expensePermissions = [
            'view expenses',
            'create expenses',
            'edit expenses',
            'delete expenses',
        ];

        // مجوزهای مطالبات
        $receivablePermissions = [
            'view receivables',
            'create receivables',
            'edit receivables',
            'delete receivables',
        ];

        // ادغام همه مجوزها
        $allPermissions = array_merge(
            $carPermissions,
            $investorPermissions,
            $investmentPermissions,
            $salePermissions,
            $assetPermissions,
            $liabilityPermissions,
            $userPermissions,
            $dashboardPermissions,
            $peoplePermissions,
            $transactionPermissions,
            $accountPermissions,
            $paymentMethodPermissions,
            $expensePermissions,
            $receivablePermissions // اضافه کردن مجوزهای مطالبات
        );

        // ایجاد مجوزها
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // =============================================
        // 2. ایجاد نقش‌ها
        // =============================================
        
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $managerRole = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $investorRole = Role::firstOrCreate(['name' => 'investor', 'guard_name' => 'web']);
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $accountantRole = Role::firstOrCreate(['name' => 'accountant', 'guard_name' => 'web']);

        // =============================================
        // 3. اختصاص مجوزها به نقش‌ها
        // =============================================

        // ادمین: همه مجوزها
        $adminRole->syncPermissions($allPermissions);

        // مدیر: مجوزهای مدیریتی (بدون حذف)
        $managerRole->syncPermissions([
            // خودرو
            'view cars', 'create cars', 'edit cars', 'sell cars',
            // سرمایه‌گذار
            'view investors', 'create investors', 'edit investors',
            // سرمایه‌گذاری
            'view investments', 'create investments', 'edit investments',
            // فروش
            'view sales', 'create sales', 'view profits',
            // دارایی
            'view assets', 'create assets', 'edit assets',
            // تعهدات
            'view liabilities', 'create liabilities', 'edit liabilities',
            // اشخاص
            'view people', 'create people', 'edit people',
            // تراکنش
            'view transactions', 'create transactions', 'edit transactions',
            // حساب
            'view accounts', 'create accounts', 'edit accounts',
            // هزینه
            'view expenses', 'create expenses', 'edit expenses',
            // مطالبات
            'view receivables', 'create receivables', 'edit receivables',
            // داشبورد
            'view dashboard',
        ]);

        // سرمایه‌گذار: فقط مشاهده
        $investorRole->syncPermissions([
            'view dashboard',
            'view cars',
            'view investors',
            'view investments',
            'view sales',
            'view profits',
        ]);

        // کاربر عادی: فقط داشبورد
        $userRole->syncPermissions([
            'view dashboard',
        ]);

        // حسابدار: مدیریت مالی
        $accountantRole->syncPermissions([
            'view dashboard',
            'view assets', 'create assets', 'edit assets',
            'view liabilities', 'create liabilities', 'edit liabilities',
            'view transactions', 'create transactions',
            'view accounts', 'create accounts', 'edit accounts',
            'view expenses', 'create expenses',
            'view receivables', 'create receivables', 'edit receivables',
        ]);

        // =============================================
        // 4. اختصاص نقش به کاربران پیش‌فرض (اصلاح شده با syncRoles)
        // =============================================

        // کاربر ادمین
        $adminUser = User::where('email', 'admin@example.com')->first();
        if ($adminUser) {
            $adminUser->syncRoles(['admin']); // تغییر از assignRole به syncRoles
            $this->command->info('✅ نقش admin به کاربر admin@example.com اختصاص یافت.');
        }

        // کاربر مدیر
        $managerUser = User::where('email', 'manager@example.com')->first();
        if ($managerUser) {
            $managerUser->syncRoles(['manager']); // تغییر از assignRole به syncRoles
            $this->command->info('✅ نقش manager به کاربر manager@example.com اختصاص یافت.');
        }

        // کاربر سرمایه‌گذار (سارا)
        $investorUser = User::where('email', 'sara@example.com')->first();
        if ($investorUser) {
            $investorUser->syncRoles(['investor']); // تغییر از assignRole به syncRoles
            $this->command->info('✅ نقش investor به کاربر sara@example.com اختصاص یافت.');
        }

        // کاربر عادی
        $normalUser = User::where('email', 'user@example.com')->first();
        if ($normalUser) {
            $normalUser->syncRoles(['user']); // تغییر از assignRole به syncRoles
            $this->command->info('✅ نقش user به کاربر user@example.com اختصاص یافت.');
        }

        // =============================================
        // 5. گزارش نهایی
        // =============================================
        
        $this->command->info('🎉 تمام مجوزها و نقش‌ها با موفقیت ایجاد شدند.');
        
        $this->command->table(
            ['نقش', 'تعداد مجوزها'],
            [
                ['admin', $adminRole->permissions()->count()],
                ['manager', $managerRole->permissions()->count()],
                ['investor', $investorRole->permissions()->count()],
                ['user', $userRole->permissions()->count()],
                ['accountant', $accountantRole->permissions()->count()],
            ]
        );
    }
}