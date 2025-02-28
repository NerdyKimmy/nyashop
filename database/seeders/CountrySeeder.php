<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ukrainian regions (oblasts, autonomous republic, and cities with special status)
        $uaStates = [
            '01' => 'Vinnytska oblast',
            '02' => 'Volynska oblast',
            '03' => 'Dniprovska oblast',
            '04' => 'Donetska oblast',
            '05' => 'Zhytomyrska oblast',
            '06' => 'Zakarpatska oblast',
            '07' => 'Zaporizka oblast',
            '08' => 'Ivano-Frankivska oblast',
            '09' => 'Kyivska oblast',
            '10' => 'Kirovohradska oblast',
            '11' => 'Luhanska oblast',
            '12' => 'Lvivska oblast',
            '13' => 'Mykolaivska oblast',
            '14' => 'Odeska oblast',
            '15' => 'Poltavska oblast',
            '16' => 'Rivnenska oblast',
            '17' => 'Sumska oblast',
            '18' => 'Ternopilska oblast',
            '19' => 'Kharkivska oblast',
            '20' => 'Khersonska oblast',
            '21' => 'Khmelnytska oblast',
            '22' => 'Cherkaska oblast',
            '23' => 'Chernivetska oblast',
            '24' => 'Chernihivska oblast',
            '25' => 'Autonomous Republic of Crimea',
            '26' => 'Kyiv (city with special status)',
            '27' => 'Sevastopol (city with special status)',
        ];

        // Romanian counties (counties and the municipality of Bucharest)
        $roStates = [
            'AB' => 'Alba',
            'AR' => 'Arad',
            'AG' => 'Arges',
            'BC' => 'Bacau',
            'BH' => 'Bihor',
            'BN' => 'Bistrita-Nasaud',
            'BT' => 'Botosani',
            'BV' => 'Brasov',
            'BR' => 'Braila',
            'B'  => 'Bucharest',
            'BZ' => 'Buzau',
            'CS' => 'Caras-Severin',
            'CL' => 'Calarasi',
            'CJ' => 'Cluj',
            'CT' => 'Constanta',
            'CV' => 'Covasna',
            'DB' => 'Dambovita',
            'DJ' => 'Dolj',
            'GL' => 'Galati',
            'GR' => 'Giurgiu',
            'GJ' => 'Gorj',
            'HR' => 'Harghita',
            'HD' => 'Hunedoara',
            'IL' => 'Ialomita',
            'IS' => 'Iasi',
            'IF' => 'Ilfov',
            'MM' => 'Maramures',
            'MH' => 'Mehedinti',
            'MS' => 'Mures',
            'NT' => 'Neamt',
            'OT' => 'Olt',
            'PH' => 'Prahova',
            'SJ' => 'Salaj',
            'SM' => 'Satu Mare',
            'SB' => 'Sibiu',
            'SV' => 'Suceava',
            'TR' => 'Teleorman',
            'TM' => 'Timis',
            'TL' => 'Tulcea',
            'VL' => 'Valcea',
            'VS' => 'Vaslui',
            'VN' => 'Vrancea',
        ];

        // Moldovan regions (example)
        $mdaStates = [
            '01' => 'Chisinau',
            '02' => 'Balti',
            '03' => 'Bender',
            '04' => 'Gagauzia',
        ];

        // Estonian counties
        $estStates = [
            '01' => 'Harju County',
            '02' => 'Hiiu County',
            '03' => 'Ida-Viru County',
            '04' => 'Jarva County',
            '05' => 'Jogeva County',
            '06' => 'Laane County',
            '07' => 'Laane-Viru County',
            '08' => 'Polva County',
            '09' => 'Parnu County',
            '10' => 'Rapla County',
            '11' => 'Saare County',
            '12' => 'Tartu County',
            '13' => 'Valga County',
            '14' => 'Viljandi County',
            '15' => 'Voru County',
        ];

        // Latvian regions (example)
        $lvaStates = [
            '01' => 'Riga',
            '02' => 'Daugavpils',
            '03' => 'Liepaja',
            '04' => 'Jelgava',
            '05' => 'Jurmala',
        ];

        // Lithuanian counties
        $ltuStates = [
            '01' => 'Alytus County',
            '02' => 'Kaunas County',
            '03' => 'Klaipeda County',
            '04' => 'Marijampole County',
            '05' => 'Panevezys County',
            '06' => 'Siauliai County',
            '07' => 'Taurage County',
            '08' => 'Utena County',
            '09' => 'Vilnius County',
            '10' => 'Telsiai County',
        ];

        // List of countries with states added for all
        $countries = [
            [
                'code'   => 'ukr',
                'name'   => 'Ukraine',
                'states' => json_encode($uaStates),
            ],
            [
                'code'   => 'rou',
                'name'   => 'Romania',
                'states' => json_encode($roStates),
            ],
            [
                'code'   => 'mda',
                'name'   => 'Moldova',
                'states' => json_encode($mdaStates),
            ],
            [
                'code'   => 'est',
                'name'   => 'Estonia',
                'states' => json_encode($estStates),
            ],
            [
                'code'   => 'lva',
                'name'   => 'Latvia',
                'states' => json_encode($lvaStates),
            ],
            [
                'code'   => 'ltu',
                'name'   => 'Lithuania',
                'states' => json_encode($ltuStates),
            ],
        ];

        Country::insert($countries);
    }
}
