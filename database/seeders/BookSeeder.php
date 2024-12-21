<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $books = [
            [
                'name' => 'The Great Gatsby',
                'desc' => 'The Great Gatsby is a 1925 novel by American writer F. Scott Fitzgerald. Set in the Jazz Age on Long Island, near New York City, the novel depicts first-person narrator Nick Carraway\'s interactions with mysterious millionaire Jay Gatsby and Gatsby\'s obsession to reunite with his former lover, Daisy Buchanan.',
                'author' => 'F. Scott Fitzgerald',
                'edition' => '1st',
                'count' => '4',
            ],
            [
                'name' => 'To Kill a Mockingbird',
                'desc' => 'To Kill a Mockingbird is a novel by Harper Lee published in 1960. Instantly successful, widely read in high schools and middle schools in the United States, it has become a classic of modern American literature, winning the Pulitzer Prize.',
                'author' => 'Harper Lee',
                'edition' => '1st',
                'count' => '3',
            ],
            [
                'name' => 'Book 1',
                'desc' => 'Book 1 description',
                'author' => 'Author 1',
                'edition' => '1st',
                'count' => '5',
            ],
            [
                'name' => 'Book 2',
                'desc' => 'Book 2 description',
                'author' => 'Author 2',
                'edition' => '1st',
                'count' => '6',
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
