<?php

use Illuminate\Database\Seeder;

// 注册种子文件
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(UserAddressesSeeder::class);
        $this->call(CategoriesSeeder::class);  // 放在 ProductsSeeder 之前
        $this->call(ProductsSeeder::class);
        $this->call(CouponCodesSeeder::class);
        $this->call(OrdersSeeder::class);
    }
}
