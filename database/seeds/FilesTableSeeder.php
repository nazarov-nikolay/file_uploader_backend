<?php

use Illuminate\Database\Seeder;

class FilesTableSeeder extends Seeder
{
    const COUNT = 50000000;
    const PART = 1000;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $limit = self::PART > 0 ? ceil(self::COUNT / self::PART) : 0;
        
        for ($i=0; $i < $limit; $i++) { 
            factory(App\File::class, self::PART)->create();
        }
    }
}
