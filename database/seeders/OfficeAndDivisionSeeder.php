<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Office;
use App\Models\Division;

class OfficeAndDivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $offices = [
            ['name' => 'Iloilo Regional Office', 'code' => 'RO'],
            ['name' => 'Aklan Provincial Office', 'code' => 'DTI Aklan'],
            ['name' => 'Antique Provincial Office', 'code' => 'DTI Antique'],
            ['name' => 'Capiz Provincial Office', 'code' => 'DTI Capiz'],
            ['name' => 'Guimaras Provincial Office', 'code' => 'DTI Guimaras'],
            ['name' => 'Iloilo Provincial Office', 'code' => 'DTI Iloilo'],
            ['name' => 'Negros Occidental Provincial Office', 'code' => 'DTI Negros Occ'],
        ];

        $allDivisions = [
            ['code' => 'ORD', 'name' => 'Office of the Regional Director'],
            ['code' => 'OPD', 'name' => 'Office of the Provincial Director'],
            ['code' => 'BDD', 'name' => 'Business Development Division'],
            ['code' => 'IDD', 'name' => 'Industry Development Division'],
            ['code' => 'CPD', 'name' => 'Consumer Protection Division'],
            ['code' => 'FAD', 'name' => 'Finance and Administration Division'],
            ['code' => 'COA', 'name' => 'Consumer On Audit'],
        ];

        foreach ($offices as $officeData) {
            $office = Office::updateOrCreate(
                ['code' => $officeData['code']],
                ['name' => $officeData['name']]
            );

            foreach ($allDivisions as $divData) {
                // Logic: ORD is only for Regional Office (RO)
                if ($divData['code'] === 'ORD' && $office->code !== 'RO') {
                    continue;
                }

                // Logic: OPD is only for Provincial Offices (not RO)
                if ($divData['code'] === 'OPD' && $office->code === 'RO') {
                    continue;
                }

                Division::updateOrCreate(
                    [
                        'office_id' => $office->id,
                        'code' => $divData['code']
                    ],
                    ['name' => $divData['name']]
                );
            }
        }
    }
}
