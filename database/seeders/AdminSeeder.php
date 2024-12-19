<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Packages\Basic\Models\Administrator;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Administrator::count() == 0) {
            DB::beginTransaction();

            try {
                $attributes = [
                    'name' => 'Super Admin',
                    'email' => 'support@wcms.com.hk',
                    'password' => Hash::make('Admin@1111'),
                    'enabled' => true
                ];

                $admin = Administrator::create($attributes);

                $admin->syncRoles(['SuperAdmin']);

                DB::commit();
            } catch (\Exception $e) {
                dd($e);
                logger('Create First Admin', ['message' => $e]);
                DB::rollback();
            }
        }
    }
}
