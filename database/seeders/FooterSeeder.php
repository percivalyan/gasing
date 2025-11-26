<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Footer;

class FooterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Footer::create([
            'phone'             => '08123456789',
            'email'             => 'info@yayasangasingpapua.org',
            'address_street'    => 'Jl. Contoh Alamat Yayasan Gasing Papua, Jayapura',
            'address_post_code' => '99111',
        ]);
    }
}
