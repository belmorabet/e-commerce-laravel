<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Product;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Coupon;
use App\Models\OldSlug;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {   
        $titles=['To Kill a Mockingbird'];
        $slugs=['to-kill-a-mockingbird'];
        $categories=[
            'Adventure stories', 'Classics', 'Crime', 'Fairy tales, fables, and folk tales', 'Fantasy',
            'Historical fiction', 'Horror', 'Humour and satire', 'Literary fiction', 'Mystery', 'Poetry', 'Plays',
            'Romance', 'Science fiction', 'Short stories', 'Thrillers', 'War', 'Women’s fiction', 'Young adult',
            'Autobiography and memoir', 'Biography', 'Essays', 'Non-fiction novel', 'Self-help'
        ];
        $categories_slugs=[
            'adventure-stories', 'classics', 'crime', 'fairy-tales-fables-and-folk-tales', 'fantasy', 'historical-fiction',
            'horror', 'humour-and-satire', 'literary-fiction', 'mystery', 'poetry', 'plays', 'romance', 'science-fiction',
            'short-stories', 'thrillers', 'war', 'womens-fiction', 'young-adult', 'autobiography-and-memoir', 'biography',
            'essays', 'non-fiction-novel', 'self-help'
        ];
        $categoriesProducts=[6, 6, 5, 5, 10, 9, 2, 9];

        $images=['products/ToKillAMockingbirdBook.jpg'];

        
        $prices=[24.99];

        for ($i = 0; $i < count($categories) ;$i++) {
            Category::factory()->create([
                'name' => $categories[$i],
                'slug' => $categories_slugs[$i]
             ]);
        }
        
        $descriptions=['The unforgettable novel of a childhood in a sleepy Southern town and the crisis of conscience that rocked 
                        it, To Kill A Mockingbird became both an instant bestseller and a critical success when it was first published in 1960. It went 
                        on to win the Pulitzer Prize in 1961 and was later made into an Academy Award-winning film, also a classic.
                        Compassionate, dramatic, and deeply moving, To Kill A Mockingbird takes readers to the roots of human behavior - to innocence and
                        experience, kindness and cruelty, love and hatred, humor and pathos. Now with over 18 million copies in print and translated
                         into forty languages, this regional story by a young Alabama woman claims universal appeal. Harper Lee always considered 
                         her book to be a simple love story. Today it is regarded as a masterpiece of American literature.'];


        $dimensions=['6.69 x 4.13 x 0.98 inches', '4.19 x 0.85 x 7.5 inches', '5.08 x 0.91 x 7.76 inches', '5.5 x 1.75 x 8.25 inches',
            '6 x 0.25 x 9 inches', '5.5 x 1.2 x 8.44 inches', '5.2 x 0.47 x 7.68 inches', '6 x 0.23 x 9 inches'];
        

            for ($i = 0; $i < count($titles) ;$i++){
                Product::factory()->create([
                    'image' => $images[$i],
                    'title' => $titles[$i],
                    'slug' => $slugs[$i],
                    'category_id' => $categoriesProducts[$i],
                    'description' => $descriptions[$i],
                    'price' => $prices[$i],
                    'dimensions' => $dimensions[$i],
                    'type' => 'Hardcover',
                    'created_at' => '2023-02-' . implode([$i])
                ]);
            }
    
        Comment::factory()->create([
            'product_id' => 1,
            'body' => 'Great product!',
        ]);

        Coupon::factory()->create([
            'name' => 'Holiday Coupon',
            'code' => 'HOLIDAY-COUPON-2023',
            'type' => 'fixed',
            'value' => 20,
            'valid_from' => '2023-01-14',
            'valid_until' => '2025-12-12'
        ]);

        Coupon::factory()->create([
            'name' => 'Christmas Coupon',
            'code' => 'CHRISTMAS-COUPON-2023',
            'type' => 'percent_off',
            'percent_off' => 20,
            'valid_from' => '2023-01-14',
            'valid_until' => '2025-12-12'
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'is_admin' => true,
            'password' => bcrypt('Admin1234!'),
            'avatar' => 'avatar4.png'
        ]);

        OldSlug::factory()->create([
            'slug' => 'old-slug',
            'product_id' => 1
        ]);
    }
}
