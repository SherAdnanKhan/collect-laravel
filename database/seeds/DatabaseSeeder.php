<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SessionTypesTableSeeder::class);
        $this->call(InstrumentsTableSeeder::class);
        $this->call(SongTypesTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(CreditRolesTableSeeder::class);
        $this->call(LanguagesTableSeeder::class);
        $this->call(RecordingTypesTableSeeder::class);
        $this->call(TimezoneSeeder::class);
    }
}
