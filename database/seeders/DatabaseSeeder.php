<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //se crean 33 libros
        Book::factory(33)->create()->each(function ($book) {
            //para cada libro se generan de manera random entre 5 y 30 reviews
            $numReviews = random_int(5, 30);

            Review::factory()->count($numReviews)
                ->good() //esto hace que las reviews sean buenas
                ->for($book) //crea una asociacion de la clave foranea con el libro (creo)
                ->create(); //y finalmente crea la review
        });


        Book::factory(33)->create()->each(function ($book) {
            $numReviews = random_int(5, 30);

            Review::factory()->count($numReviews)
                ->average()
                ->for($book)
                ->create();
        });
        

        Book::factory(34)->create()->each(function ($book) {
            $numReviews = random_int(5, 30);

            Review::factory()->count($numReviews)
                ->bad() 
                ->for($book) 
                ->create(); 
        });
    }
}
