<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $books = Book::all();

        foreach ($users as $user) {
            $bookIds = $books->random(random_int(3, 5))->pluck('id')->unique()->values()->all();

            $user->favorites()->syncWithoutDetaching($bookIds);
        }
    }
}
