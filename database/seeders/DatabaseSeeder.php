<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\Type;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Type::create(['name' => 'income']);
        Type::create(['name' => 'expanse']);

        Category::create(['name' => 'business', 'type_id' => 1]);
        Category::create(['name' => 'investments', 'type_id' => 1]);
        Category::create(['name' => 'extra income', 'type_id' => 1]);
        Category::create(['name' => 'deposits', 'type_id' => 1]);
        Category::create(['name' => 'lottery', 'type_id' => 1]);
        Category::create(['name' => 'gifts', 'type_id' => 1]);
        Category::create(['name' => 'salary', 'type_id' => 1]);
        Category::create(['name' => 'savings', 'type_id' => 1]);
        Category::create(['name' => 'rental income', 'type_id' => 1]);
        Category::create(['name' => 'bills', 'type_id' => 2]);
        Category::create(['name' => 'clothes', 'type_id' => 2]);
        Category::create(['name' => 'car', 'type_id' => 2]);
        Category::create(['name' => 'food', 'type_id' => 2]);
        Category::create(['name' => 'house', 'type_id' => 2]);
        Category::create(['name' => 'shopping', 'type_id' => 2]);
        Category::create(['name' => 'travel', 'type_id' => 2]);
        Category::create(['name' => 'entertainment', 'type_id' => 2]);
        Category::create(['name' => 'pets', 'type_id' => 2]);
        Category::create(['name' => 'phone', 'type_id' => 2]);

        Transaction::create(['amount' => 100, 'category_id' => 1, 'type_id' => 1, 'date' => '2022-10-10']);
    }
}
