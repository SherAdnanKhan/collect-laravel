<?php

use App\Models\Affiliation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AffiliationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $affiliations = [
            'cmo' => [
                'ABRAMUS - Brazil',
                'ADAMI - France',
                'AIE - Spain',
                'GRAMEX DK - Denmark',
                'GVL - Germany',
                'PLAYRIGHT - Belgium',
                'PPLI - India',
                'SIG - Switzerland'
            ],
            'pro' => [
                'Indian Performing Rights Organization (IPRS)',
                'AKM / AUSTRO - Austria',
                'ARTISJUS – Hungary',
                'BUMA/STEMRA – Holland and Luxembourg',
                'GEMA – Germany & Russia',
                'IMRO - Ireland',
                'KODA - Denmark',
                'MUSICAUTOR - Bulgaria',
                'OSA – Czech Republic',
                'PRS / MCPS – UK (CAE/IPI is the actual identifier PRS issues)',
                'PPL - UK (IPN is the actual identifier PPL issues)',
                'SABAM - Belgium',
                'SACEM – France',
                'SAMRO - South Africa',
                'SGAE – Spain',
                'SIAE – Italy',
                'SPA - Portugal',
                'STIM / NCB – Sweden',
                'SUISA - Switzerland',
                'TEOSTO - Finland',
                'TONO - Norway',
                'UCMRA-ADA – Romania',
                'ZAiKS - Poland',
                'SESAC - United States',
                'ASCAP - United States',
                'BMI - United States',
                'GMR - United States',
                'SOCAN - Canada'
            ]
        ];

        foreach ($affiliations as $type => $affiliation) {
            foreach ($affiliation as $name) {
                Affiliation::create([
                    'affiliation_type' => $type,
                    'name' => $name,
                    'slug' => Str::slug($name)
                ]);
            }
        }
    }
}
