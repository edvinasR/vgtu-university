<?php

use Illuminate\Database\Seeder;

class TeisiuSeederis extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
             DB::table('teises')->insert([
            'pavadinimas' => 'Administratorius',
            'tvarkyti_mokiniu_informacija' => 1,
            'tvarkyti_instruktorius' => 1,
            'tvarkyti_grupes' => 1,
            'tvarkyti_teorines_paskaitas' => 1,
            'tvarkyti_pazymius' => 1,
            'tvarkyti_praktines_paskaitas' => 1,
            'rasyti_ivertinimus_mokiniui' => 1,
        	]);
            
            
            DB::table('teises')->insert([
            		'pavadinimas' => 'Mokinys',
            		'tvarkyti_mokiniu_informacija' => 0,
            		'tvarkyti_instruktorius' => 0,
            		'tvarkyti_grupes' => 0,
            		'tvarkyti_teorines_paskaitas' => 0,
            		'tvarkyti_pazymius' => 0,
            		'tvarkyti_praktines_paskaitas' => 0,
            		'rasyti_ivertinimus_mokiniui' => 0,
            ]);
            DB::table('teises')->insert([
            		'pavadinimas' => 'KET dėstytojas',
            		'tvarkyti_mokiniu_informacija' => 0,
            		'tvarkyti_instruktorius' => 0,
            		'tvarkyti_grupes' => 0,
            		'tvarkyti_teorines_paskaitas' => 1,
            		'tvarkyti_pazymius' => 1,
            		'tvarkyti_praktines_paskaitas' => 0,
            		'rasyti_ivertinimus_mokiniui' => 1,
            ]);
            
            DB::table('teises')->insert([
            		'pavadinimas' => 'Praktinio vairavimo instruktorius',
            		'tvarkyti_mokiniu_informacija' => 0,
            		'tvarkyti_instruktorius' => 0,
            		'tvarkyti_grupes' => 0,
            		'tvarkyti_teorines_paskaitas' => 0,
            		'tvarkyti_pazymius' => 1,
            		'tvarkyti_praktines_paskaitas' => 1,
            		'rasyti_ivertinimus_mokiniui' => 1,
            ]);
    }
}
