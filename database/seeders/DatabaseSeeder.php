<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Packet;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Store::create([
            'name' => 'Ikan Bakar Pantura',
            'area' => 'Merakurak'
        ]);

        User::create([
            'store_id' => 1,
            'name' => 'Admin',
            'username' => Str::slug('Admin', '_'),
            'phone' => '0812345676',
            'password' => Hash::make('123456'),
            'role' => 'admin'
        ]);

        User::create([
            'store_id' => 1,
            'name' => 'Erick Setyawan',
            'username' => Str::slug('Erick Setyawan', '_'),
            'phone' => '0812345678',
            'password' => Hash::make('123456')
        ]);

        Category::create([
            'store_id' => 1,
            'name' => 'Ramadhan Kareem',
            'slug' => Str::slug('Ramadhan Kareem'),
            'order_number' => 1
        ]);

        Category::create([
            'store_id' => 1,
            'name' => 'Basic',
            'slug' => Str::slug('Basic'),
            'order_number' => 2
        ]);

        Packet::create([
            'store_id' => 1,
            'category_id' => 1,
            'name' => 'Rekomendasi Terlaris',
            'slug' => Str::slug('Rekomendasi Terlaris'),
            'image' => 'e4E59F1R3t42VSkIRoLWlCWEVJPvS4XX5vnrnukx.png',
        ]);

        Packet::create([
            'store_id' => 1,
            'category_id' => 1,
            'name' => 'Paket 5 Orang',
            'slug' => Str::slug('Paket 5 Orang'),
            'image' => 'VpuA0vYDWSe7WqpRdXg6WKLFHWXjHEmFrK21nuUw.jpg',
        ]);

        Packet::create([
            'store_id' => 1,
            'category_id' => 1,
            'name' => 'Paket 10 Orang',
            'slug' => Str::slug('Paket 10 Orang'),
            'image' => '8EBBRiigXYsfZZLuXXIgKt3Z9WHlPE9FuNG1Gsq8.jpg',
        ]);

        Packet::create([
            'store_id' => 1,
            'category_id' => 2,
            'name' => 'Lele',
            'slug' => random_int(1000, 9999),
            'image' => 'f9V3giixiZgWgNcZvwDELv319j9VX8Skvl14zz08.png',
        ]);

        Packet::create([
            'store_id' => 1,
            'category_id' => 2,
            'name' => 'Ayam',
            'slug' => random_int(1000, 9999),
            'image' => 'FnajIL87BJkGX8whFYzr78MwQxDaVBUQYFMiS6L7.png',
        ]);

        Packet::create([
            'store_id' => 1,
            'category_id' => 2,
            'name' => 'Bebek',
            'slug' => random_int(1000, 9999),
            'image' => 'R7WlK1ez9YWJ0K6SRzQnz6u4nPD6ynNx4RSPERVz.png',
        ]);

        Packet::create([
            'store_id' => 1,
            'category_id' => 2,
            'name' => 'Ayam Hantaran 1 Ekor',
            'slug' => random_int(1000, 9999),
            'image' => 'YGFjU9T83INrNj35RYIyvqBEuF01Grx2cihNhQtW.png',
        ]);

        Packet::create([
            'store_id' => 1,
            'category_id' => 2,
            'name' => 'Kerang Ijo',
            'slug' => random_int(1000, 9999),
            'image' => 'CSRR5TIqv99DP4YBkhmtiGMU4OcvTItRgPfa5siz.png',
        ]);

        Packet::create([
            'store_id' => 1,
            'category_id' => 2,
            'name' => 'Udang',
            'slug' => random_int(1000, 9999),
            'image' => 'J6uhDM1ZDfhBH2TORDgwNOHMQ44HwP0OSZzfnQkb.png',
        ]);

        Packet::create([
            'store_id' => 1,
            'category_id' => 2,
            'name' => 'Cumi',
            'slug' => random_int(1000, 9999),
            'image' => 'S3nk6ZvPjylnF2IMZsqXGUvYRAzkJgRtdxsLrl6z.png',
        ]);

        Packet::create([
            'store_id' => 1,
            'category_id' => 2,
            'name' => 'Gurame',
            'slug' => random_int(1000, 9999),
            'image' => 'WGP3r7c8QKziJcPrRAwFxGSEo3mU1VWLJ4njYhvT.png',
        ]);

        Packet::create([
            'store_id' => 1,
            'category_id' => 2,
            'name' => 'Kerapu',
            'slug' => random_int(1000, 9999),
            'image' => 'qzWE3JEShVbN82hVuM5n8Lv0Y2UDJYIHs1Wieqmo.png',
        ]);

        Packet::create([
            'store_id' => 1,
            'category_id' => 2,
            'name' => 'Kakap Putih',
            'slug' => random_int(1000, 9999),
            'image' => 'sB3j39P0KbLv0YBOkKQJ3sbuFJOJ582T0Bu8FojQ.png',
        ]);

        Packet::create([
            'store_id' => 1,
            'category_id' => 2,
            'name' => 'Kakap Merah',
            'slug' => random_int(1000, 9999),
            'image' => 'iXeSapsAmMIfrWFREAhDnLlFJTK0O3fn8trI3r6R.png',
        ]);

        Packet::create([
            'store_id' => 1,
            'category_id' => 2,
            'name' => 'Tumisan',
            'slug' => random_int(1000, 9999),
            'image' => 'eDurJA0t7Fjdn62ksod1nPKl0zpbgIpU7Zrf8TzB.png',
        ]);

        Packet::create([
            'store_id' => 1,
            'category_id' => 2,
            'name' => 'Minuman',
            'slug' => random_int(1000, 9999),
            'image' => 'NTtp5tTIHBL9Tm1e6VWdGognpxhplrlZmTHZWNn3.png',
        ]);

        Packet::create([
            'store_id' => 1,
            'category_id' => 2,
            'name' => 'Lain-Lain',
            'slug' => random_int(1000, 9999),
            'image' => 'LPBW2fItM3RL7uiJnX4O7ygp2rbxBrFtowrc6qu2.png',
        ]);

        Product::create([
            'store_id' => 1,
            'name' => 'Lele Goreng',
            'slug' => Str::slug('Lele Goreng'),
            'price' => 12000,
            'image' => '2V6PcHKHoRd8SIxTahpghknHl6olm3sP4i33dsbk.jpg'
        ]);

        Product::create([
            'store_id' => 1,
            'name' => 'Ayam Goreng',
            'slug' => Str::slug('Ayam Goreng'),
            'price' => 16000,
            'image' => '2V6PcHKHoRd8SIxTahpghknHl6olm3sP4i33dsbk.jpg'
        ]);
    }
}
