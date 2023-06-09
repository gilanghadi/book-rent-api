<?php

namespace Database\Seeders;

use App\Models\Book;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Book::truncate();
        Schema::enableForeignKeyConstraints();


        $faker = Factory::create();
        for ($i = 0; $i < 7000; $i++) {
            $data[] = [
                'book_code' => $faker->randomNumber(5, true),
                'title' => $faker->word(40),
                'slug' => $faker->word(40),
                'cover' => $faker->imageUrl(640, 480, 'animals', true),
            ];
        }
        foreach (array_chunk($data, 1000) as $item) {
            Book::insert($item);
        }
    }
}
