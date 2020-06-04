<?php

use App\Models\CreditRole;
use Keboola\Csv\CsvReader;
use Illuminate\Database\Seeder;

class CreditRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rolesFile = new CsvReader(__DIR__ . '/csv/all_roles.csv');

        CreditRole::unsetEventDispatcher();

        foreach ($rolesFile as $roleRow) {
            $type = $roleRow[0];
            $ddex_key = $roleRow[1];
            $name = $roleRow[2];

            CreditRole::updateOrCreate([
                'ddex_key' => $ddex_key,
                'type'     => $type,
            ], [
                'name'         => $name,
                'type'         => $type,
                'ddex_key'     => $ddex_key,
                'user_defined' => $ddex_key === 'UserDefined'
            ]);
        }
    }
}
