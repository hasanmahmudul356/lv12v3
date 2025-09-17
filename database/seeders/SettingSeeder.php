<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::truncate();

        $users = [
            [
                'key'=>'app_name',
                'type'=>'text',
                'setting_type'=>'general_setting',
                'value'=>'BOILERPLATE',
                'is_visible'=>1,
            ],
            [
                'key'=>'app_logo',
                'type'=>'file',
                'setting_type'=>'general_setting',
                'value'=>null,
                'is_visible'=>1,
            ],
            [
                'key'=>'notify_per_minuit',
                'type'=>'number',
                'setting_type'=>'notification_setting',
                'value'=>1,
                'is_visible'=>1,
            ],
        ];

        Setting::insert($users);
    }
}
