<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        //PARTS: Kategori Bagian
        DB::table('parts')->insert([
            'id_part' => 'PRT-000',
            'name' => 'Kepemimpinan',
        ]);

        /*
        DB::table('parts')->insert([
            'id_part' => 'PRT-001',
            'name' => 'Kepemimpinan',
        ]);
        */

        DB::table('parts')->insert([
            'id_part' => 'PRT-001',
            'name' => 'Umum',
        ]);

        DB::table('parts')->insert([
            'id_part' => 'PRT-002',
            'name' => 'Tim Teknis',
        ]);

        DB::table('parts')->insert([
            'id_part' => 'PRT-999',
            'name' => 'Developer',
        ]);

        //POSITIONS: Jabatan
        DB::table('positions')->insert([
            'id_position' => 'DPT-000',
            //'id_part' => 'PRT-000',
            'name' => 'Developer',
            //'part' => 'Developer',
        ]);

        DB::table('positions')->insert([
            'id_position' => 'DPT-001',
            //'id_part' => 'PRT-001',
            'name' => 'Kepala BPS Jawa Timur',
            //'part' => 'Kepala BPS',
        ]);

        DB::table('positions')->insert([
            'id_position' => 'DPT-002',
            //'id_part' => 'PRT-002',
            'name' => 'Kepala Bagian Umum',
            //'part' => 'Bagian Umum',
        ]);

        DB::table('positions')->insert([
            'id_position' => 'DPT-003',
            //'id_part' => 'PRT-002',
            'name' => 'Pranata Keuangan APBN Penyelia',
            //'part' => 'Bagian Umum',
        ]);

        DB::table('positions')->insert([
            'id_position' => 'DPT-004',
            //'id_part' => 'PRT-002',
            'name' => 'Analis Kepegawaian Ahli Muda ',
            //'part' => 'Bagian Umum',
        ]);

        DB::table('positions')->insert([
            'id_position' => 'DPT-005',
            //'id_part' => 'PRT-003',
            'name' => 'Statistisi Ahli Pertama',
            //'part' => 'Tim Teknis',
        ]);

        DB::table('positions')->insert([
            'id_position' => 'DPT-006',
            //'id_part' => 'PRT-003',
            'name' => 'Statistisi Ahli Muda',
            //'part' => 'Tim Teknis',
        ]);

        DB::table('positions')->insert([
            'id_position' => 'DPT-007',
            //'id_part' => 'PRT-003',
            'name' => 'Statistisi Ahli Madya',
            //'part' => 'Tim Teknis',
        ]);

        DB::table('positions')->insert([
            'id_position' => 'DPT-008',
            //'id_part' => 'PRT-003',
            'name' => 'Statistisi Ahli Utama',
            //'part' => 'Tim Teknis',
        ]);

        DB::table('positions')->insert([
            'id_position' => 'DPT-009',
            //'id_part' => 'PRT-003',
            'name' => 'Pranata Komputer Ahli Pertama',
            //'part' => 'Tim Teknis',
        ]);

        DB::table('positions')->insert([
            'id_position' => 'DPT-010',
            //'id_part' => 'PRT-003',
            'name' => 'Pranata Komputer Ahli Muda',
            //'part' => 'Tim Teknis',
        ]);

        DB::table('positions')->insert([
            'id_position' => 'DPT-011',
            //'id_part' => 'PRT-003',
            'name' => 'Pranata Komputer Ahli Madya',
            //'part' => 'Tim Teknis',
        ]);

        DB::table('positions')->insert([
            'id_position' => 'DPT-012',
            //'id_part' => 'PRT-003',
            'name' => 'Pranata Komputer Ahli Utama',
            //'part' => 'Tim Teknis',
        ]);

        //TEAMS: Tim Fungsi
        DB::table('teams')->insert([
            'id_team' => 'TIM-000', //TIM-000-xxx
            'id_part' => 'PRT-000',
            'name' => 'Pimpinan BPS',
        ]);

        DB::table('teams')->insert([
            'id_team' => 'TIM-001',
            'id_part' => 'PRT-001',
            'name' => 'Umum',
        ]);

        DB::table('teams')->insert([
            'id_team' => 'TIM-002',
            'id_part' => 'PRT-002',
            'name' => 'IPDS',
        ]);

        DB::table('teams')->insert([
            'id_team' => 'TIM-003',
            'id_part' => 'PRT-002',
            'name' => 'Diseminasi',
        ]);

        DB::table('teams')->insert([
            'id_team' => 'TIM-004',
            'id_part' => 'PRT-002',
            'name' => 'Sosial',
        ]);

        DB::table('teams')->insert([
            'id_team' => 'TIM-005',
            'id_part' => 'PRT-002',
            'name' => 'Produksi',
        ]);

        DB::table('teams')->insert([
            'id_team' => 'TIM-006',
            'id_part' => 'PRT-002',
            'name' => 'Distribusi',
        ]);

        DB::table('teams')->insert([
            'id_team' => 'TIM-007',
            'id_part' => 'PRT-002',
            'name' => 'Nerwilis',
        ]);

        DB::table('teams')->insert([
            'id_team' => 'TIM-999',
            'id_part' => 'PRT-999',
            'name' => 'Developer',
        ]);

        //SUB TEAMS: Pecahan Tim
        DB::table('sub_teams')->insert([
            'id_sub_team' => 'STM-000',
            'id_team' => 'TIM-000',
            'name' => 'Pimpinan BPS',
        ]);

        DB::table('sub_teams')->insert([
            'id_sub_team' => 'STM-001',
            'id_team' => 'TIM-001',
            'name' => 'Umum',
        ]);

        DB::table('sub_teams')->insert([
            'id_sub_team' => 'STM-002',
            'id_team' => 'TIM-002',
            'name' => 'Pengolahan Data',
        ]);

        DB::table('sub_teams')->insert([
            'id_sub_team' => 'STM-003',
            'id_team' => 'TIM-002',
            'name' => 'Jaringan',
        ]);

        DB::table('sub_teams')->insert([
            'id_sub_team' => 'STM-004',
            'id_team' => 'TIM-002',
            'name' => 'ZI dan SDI',
        ]);

        DB::table('sub_teams')->insert([
            'id_sub_team' => 'STM-005',
            'id_team' => 'TIM-003',
            'name' => 'Humas dan Postat',
        ]);

        DB::table('sub_teams')->insert([
            'id_sub_team' => 'STM-006',
            'id_team' => 'TIM-004',
            'name' => 'Susenas dan Sakerduk',
        ]);

        DB::table('sub_teams')->insert([
            'id_sub_team' => 'STM-007',
            'id_team' => 'TIM-004',
            'name' => 'Hansos dan Descan',
        ]);

        DB::table('sub_teams')->insert([
            'id_sub_team' => 'STM-008',
            'id_team' => 'TIM-005',
            'name' => 'Pertanian',
        ]);

        DB::table('sub_teams')->insert([
            'id_sub_team' => 'STM-009',
            'id_team' => 'TIM-005',
            'name' => 'Industri dan PEK',
        ]);

        DB::table('sub_teams')->insert([
            'id_sub_team' => 'STM-010',
            'id_team' => 'TIM-006',
            'name' => 'Distribusi dan Jasa',
        ]);

        DB::table('sub_teams')->insert([
            'id_sub_team' => 'STM-011',
            'id_team' => 'TIM-006',
            'name' => 'Harga',
        ]);

        DB::table('sub_teams')->insert([
            'id_sub_team' => 'STM-012',
            'id_team' => 'TIM-007',
            'name' => 'Neraca Produksi dan Konsuksi',
        ]);

        DB::table('sub_teams')->insert([
            'id_sub_team' => 'STM-013',
            'id_team' => 'TIM-007',
            'name' => 'Analisis dan IPS',
        ]);

        DB::table('sub_teams')->insert([
            'id_sub_team' => 'STM-999',
            'id_team' => 'TIM-999',
            'name' => 'Developer',
        ]);

        //OFFICERS: Pegawai
        DB::table('officers')->insert([
            'id_officer' => 'OFF-000',
            'nip' => '000000000',
            'name' => 'Muhammad Afriza Hanif',
            'id_position' => 'DPT-000',
            'id_sub_team_1' => 'STM-999',
            'email' => 'firzavista728@gmail.com',
            'phone' => '081217248427',
            'place_birth' => 'Surabaya',
            'date_birth' => '1996/04/08',
            'gender' => 'Laki-Laki',
            'religion' => 'Islam',
            'is_lead' => 'Yes',
        ]);

        DB::table('officers')->insert([
            'id_officer' => 'OFF-001',
            'nip' => '340014119',
            'name' => 'Dr. Ir. Zulkipli, M.Si',
            'id_position' => 'DPT-001',
            'id_sub_team_1' => 'STM-000',
            'email' => 'izqpli@yahoo.com',
            'phone' => '08123545822',
            'place_birth' => 'Surabaya',
            'date_birth' => '2000/01/01',
            'gender' => 'Laki-Laki',
            'religion' => 'Islam',
            'is_lead' => 'Yes',
        ]);

        DB::table('officers')->insert([
            'id_officer' => 'OFF-002',
            'nip' => '340013834',
            'name' => 'Satriyo Wibowo, SP, M.M',
            'id_position' => 'DPT-002',
            'id_sub_team_1' => 'STM-001',
            'id_sub_team_2' => 'STM-007',
            'email' => 'satriyo@bps.com',
            'phone' => '081234567890',
            'place_birth' => 'Surabaya',
            'date_birth' => '2000/01/01',
            'gender' => 'Laki-Laki',
            'religion' => 'Islam',
            'is_lead' => 'Yes',
        ]);

        DB::table('officers')->insert([
            'id_officer' => 'OFF-003',
            'nip' => '340056728',
            'name' => 'La Ode Ahmad Arafat S.ST,',
            'id_position' => 'DPT-003',
            'id_sub_team_1' => 'STM-010',
            'email' => 'laode@bps.com',
            'phone' => '081234567891',
            'place_birth' => 'Surabaya',
            'date_birth' => '2000/01/01',
            'gender' => 'Perempuan',
            'religion' => 'Islam',
            'is_lead' => 'No',
        ]);

        DB::table('officers')->insert([
            'id_officer' => 'OFF-004',
            'nip' => '340012614',
            'name' => 'Akhmad Yuliadi',
            'id_position' => 'DPT-004',
            'id_sub_team_1' => 'STM-013',
            'email' => 'akhmad@bps.com',
            'phone' => '081234567892',
            'place_birth' => 'Surabaya',
            'date_birth' => '2000/01/01',
            'gender' => 'Laki-Laki',
            'religion' => 'Islam',
            'is_lead' => 'No',
        ]);

        DB::table('officers')->insert([
            'id_officer' => 'OFF-005',
            'nip' => '340020343',
            'name' => 'Widia Puspitasari SST, M.Stat,',
            'id_position' => 'DPT-006',
            'id_sub_team_1' => 'STM-013',
            'id_sub_team_2' => 'STM-012',
            'email' => 'widia@bps.com',
            'phone' => '081234567893',
            'place_birth' => 'Surabaya',
            'date_birth' => '2000/01/01',
            'gender' => 'Perempuan',
            'religion' => 'Islam',
            'is_lead' => 'No',
        ]);

        DB::table('officers')->insert([
            'id_officer' => 'OFF-006',
            'nip' => '340018542',
            'name' => 'Peni Meivita, S.Si., M.M.',
            'id_position' => 'DPT-006',
            'id_sub_team_1' => 'STM-008',
            'email' => 'peni@bps.com',
            'phone' => '081234567894',
            'place_birth' => 'Surabaya',
            'date_birth' => '2000/01/01',
            'gender' => 'Perempuan',
            'religion' => 'Islam',
            'is_lead' => 'No',
        ]);

        DB::table('officers')->insert([
            'id_officer' => 'OFF-007',
            'nip' => '340015905',
            'name' => 'Abdullah Hakim SE,',
            'id_position' => 'DPT-007',
            'id_sub_team_1' => 'STM-008',
            'email' => 'abdullah@bps.com',
            'phone' => '081234567895',
            'place_birth' => 'Surabaya',
            'date_birth' => '2000/01/01',
            'gender' => 'Laki-Laki',
            'religion' => 'Islam',
            'is_lead' => 'No',
        ]);

        //USERS: Pengguna
        DB::table('users')->insert([
            'id_user' => 'USR-000',
            'username' => 'developer',
            //'email' => 'dev@bps.com',
            'password' => '$2y$10$0fQtK9jo.PjQwCItVUlOaevSTelCFz1Lc/Z8dIuFeK/3u5BmkXZzS',
            'part' => 'Dev',
            'id_officer' => 'OFF-000',
        ]);

        DB::table('users')->insert([
            'id_user' => 'USR-001',
            'username' => 'testadmin',
            //'email' => 'testadmin@bps.com',
            'password' => '$2y$10$0fQtK9jo.PjQwCItVUlOaevSTelCFz1Lc/Z8dIuFeK/3u5BmkXZzS',
            'part' => 'Admin',
            'id_officer' => 'OFF-002',
        ]);

        /*
        DB::table('users')->insert([
            'id_user' => 'USR-002',
            'username' => 'testkabag',
            //'email' => 'testkabag@bps.com',
            'password' => '$2y$10$0fQtK9jo.PjQwCItVUlOaevSTelCFz1Lc/Z8dIuFeK/3u5BmkXZzS',
            'part' => 'KBU',
            'id_officer' => 'OFF-003',
        ]);

        DB::table('users')->insert([
            'id_user' => 'USR-003',
            'username' => 'testketim1',
            //'email' => 'testketim1@bps.com',
            'password' => '$2y$10$0fQtK9jo.PjQwCItVUlOaevSTelCFz1Lc/Z8dIuFeK/3u5BmkXZzS',
            'part' => 'KTT',
            'id_officer' => 'OFF-006',
        ]);

        DB::table('users')->insert([
            'id_user' => 'USR-004',
            'username' => 'testketim2',
            //'email' => 'testketim2@bps.com',
            'password' => '$2y$10$0fQtK9jo.PjQwCItVUlOaevSTelCFz1Lc/Z8dIuFeK/3u5BmkXZzS',
            'part' => 'KTT',
            'id_officer' => 'OFF-007',
        ]);
        */

        DB::table('users')->insert([
            'id_user' => 'USR-002',
            'username' => 'testkbps',
            //'email' => 'testkbps@bps.com',
            'password' => '$2y$10$0fQtK9jo.PjQwCItVUlOaevSTelCFz1Lc/Z8dIuFeK/3u5BmkXZzS',
            'part' => 'KBPS',
            'id_officer' => 'OFF-001',
        ]);

        //PERIOODS: Periode
        DB::table('periods')->insert([
            'id_period' => 'PRD-01-24',
            'name' => 'Januari 2024',
            'month' => 'Januari',
            'year' => '2024',
            'active_days' => '23',
            'status' => 'Scoring',
        ]);

        DB::table('periods')->insert([
            'id_period' => 'PRD-02-24',
            'name' => 'Februari 2024',
            'month' => 'Februari',
            'year' => '2024',
            'active_days' => '17',
            'status' => 'Pending',
        ]);

        //CRITERIAS: Kriteria
        DB::table('categories')->insert([
            'id_category' => 'CAT-001',
            'name' => 'Kedisiplinan',
            //'type' => 'Kehadiran',
            'source' => 'Presensi',
        ]);

        DB::table('categories')->insert([
            'id_category' => 'CAT-002',
            'name' => 'Keterampilan Teknis',
            //'type' => 'Prestasi Kerja',
            'source' => 'CKP',
        ]);

        DB::table('categories')->insert([
            'id_category' => 'CAT-003',
            'name' => 'Perilaku BerAkhlak',
            //'type' => 'Prestasi Kerja',
            'source' => 'SKP',
        ]);

        //SUB CRITERIAS: Sub Kriteria
        //Kedisiplinan
        DB::table('criterias')->insert([
            'id_criteria' => 'CRT-001',
            'id_category' => 'CAT-001',
            'name' => 'Kehadiran',
            'weight' => '0.10',
            'attribute' => 'Benefit',
            'level' => '7',
            'max' => '23',
            'need' => 'Ya',
            'source' => 'tanpa_kabar',
        ]);

        DB::table('criterias')->insert([
            'id_criteria' => 'CRT-002',
            'id_category' => 'CAT-001',
            'name' => 'Keterlambatan',
            'weight' => '0.05',
            'attribute' => 'Cost',
            'level' => '5',
            'max' => '10350',
            'need' => 'Ya',
            'source' => 'kjk',
        ]);

        //Keterampilan Teknis
        DB::table('criterias')->insert([
            'id_criteria' => 'CRT-003',
            'id_category' => 'CAT-002',
            'name' => 'Capaian Kinerja Pegawai',
            'weight' => '0.15',
            'attribute' => 'Benefit',
            'level' => '9',
            'max' => '100',
            'need' => 'Ya',
            'source' => 'ckp',
        ]);

        //Perilaku BerAkhlak
        DB::table('criterias')->insert([
            'id_criteria' => 'CRT-004',
            'id_category' => 'CAT-003',
            'name' => 'Berorientasi Pelayanan',
            'weight' => '0.10',
            'attribute' => 'Benefit',
            'level' => '3',
            'max' => '100',
            'need' => 'Ya',
            'source' => 'berorientasi_pelayanan',
        ]);

        DB::table('criterias')->insert([
            'id_criteria' => 'CRT-005',
            'id_category' => 'CAT-003',
            'name' => 'Akuntabel',
            'weight' => '0.10',
            'attribute' => 'Benefit',
            'level' => '3',
            'max' => '100',
            'need' => 'Ya',
            'source' => 'akuntabel',
        ]);

        DB::table('criterias')->insert([
            'id_criteria' => 'CRT-006',
            'id_category' => 'CAT-003',
            'name' => 'Kompeten',
            'weight' => '0.10',
            'attribute' => 'Benefit',
            'level' => '3',
            'max' => '100',
            'need' => 'Ya',
            'source' => 'kompeten',
        ]);

        DB::table('criterias')->insert([
            'id_criteria' => 'CRT-007',
            'id_category' => 'CAT-003',
            'name' => 'Harmonis',
            'weight' => '0.10',
            'attribute' => 'Benefit',
            'level' => '3',
            'max' => '100',
            'need' => 'Ya',
            'source' => 'harmonis',
        ]);

        DB::table('criterias')->insert([
            'id_criteria' => 'CRT-008',
            'id_category' => 'CAT-003',
            'name' => 'Loyal',
            'weight' => '0.10',
            'attribute' => 'Benefit',
            'level' => '3',
            'max' => '100',
            'need' => 'Ya',
            'source' => 'loyal',
        ]);

        DB::table('criterias')->insert([
            'id_criteria' => 'CRT-009',
            'id_category' => 'CAT-003',
            'name' => 'Adaptif',
            'weight' => '0.10',
            'attribute' => 'Benefit',
            'level' => '3',
            'max' => '100',
            'need' => 'Ya',
            'source' => 'adaptif',
        ]);

        DB::table('criterias')->insert([
            'id_criteria' => 'CRT-010',
            'id_category' => 'CAT-003',
            'name' => 'Kolaboratif',
            'weight' => '0.10',
            'attribute' => 'Benefit',
            'level' => '3',
            'max' => '100',
            'need' => 'Ya',
            'source' => 'kolaboratif',
        ]);

        //CRIPS: Pemilihan Nilai
        //Presensi
        DB::table('crips')->insert([
            'id_crips' => 'CRP-001-001', //Criteria-Crips
            'id_criteria' => 'CRT-001',
            'name' => 'Test',
            //'description' => '',
            'value_from' => '13',
            //'value_to' => '',
            'value_type' => 'Less',
            'score' => '1',
        ]);
        DB::table('crips')->insert([
            'id_crips' => 'CRP-001-002', //Criteria-Crips
            'id_criteria' => 'CRT-001',
            'name' => 'Test',
            //'description' => '',
            'value_from' => '14',
            'value_to' => '16',
            'value_type' => 'Between',
            'score' => '2',
        ]);
        DB::table('crips')->insert([
            'id_crips' => 'CRP-001-003', //Criteria-Crips
            'id_criteria' => 'CRT-001',
            'name' => 'Test',
            //'description' => '',
            'value_from' => '17',
            'value_to' => '19',
            'value_type' => 'Between',
            'score' => '3',
        ]);
        DB::table('crips')->insert([
            'id_crips' => 'CRP-001-004', //Criteria-Crips
            'id_criteria' => 'CRT-001',
            'name' => 'Test',
            //'description' => '',
            'value_from' => '20',
            'value_to' => '22',
            'value_type' => 'Between',
            'score' => '4',
        ]);
        DB::table('crips')->insert([
            'id_crips' => 'CRP-001-005', //Criteria-Crips
            'id_criteria' => 'CRT-001',
            'name' => 'Test',
            //'description' => '',
            'value_from' => '23',
            //'value_to' => '',
            'value_type' => 'More',
            'score' => '5',
        ]);

        DB::table('crips')->insert([
            'id_crips' => 'CRP-002-001', //Criteria-Crips
            'id_criteria' => 'CRT-002',
            'name' => 'Test',
            //'description' => '',
            'value_from' => '31',
            //'value_to' => '',
            'value_type' => 'More',
            'score' => '1',
        ]);
        DB::table('crips')->insert([
            'id_crips' => 'CRP-002-002', //Criteria-Crips
            'id_criteria' => 'CRT-002',
            'name' => 'Test',
            //'description' => '',
            'value_from' => '21',
            'value_to' => '30',
            'value_type' => 'Between',
            'score' => '2',
        ]);
        DB::table('crips')->insert([
            'id_crips' => 'CRP-002-003', //Criteria-Crips
            'id_criteria' => 'CRT-002',
            'name' => 'Test',
            //'description' => '',
            'value_from' => '11',
            'value_to' => '20',
            'value_type' => 'Between',
            'score' => '3',
        ]);
        DB::table('crips')->insert([
            'id_crips' => 'CRP-002-004', //Criteria-Crips
            'id_criteria' => 'CRT-002',
            'name' => 'Test',
            //'description' => '',
            'value_from' => '1',
            'value_to' => '10',
            'value_type' => 'Between',
            'score' => '4',
        ]);
        DB::table('crips')->insert([
            'id_crips' => 'CRP-002-005', //Criteria-Crips
            'id_criteria' => 'CRT-002',
            'name' => 'Test',
            //'description' => '',
            'value_from' => '0',
            //'value_to' => '',
            'value_type' => 'Less',
            'score' => '5',
        ]);

        //CKP
        DB::table('crips')->insert([
            'id_crips' => 'CRP-003-001', //Criteria-Crips
            'id_criteria' => 'CRT-003',
            'name' => 'Test',
            //'description' => '',
            'value_from' => '80',
            //'value_to' => '',
            'value_type' => 'Less',
            'score' => '1',
        ]);
        DB::table('crips')->insert([
            'id_crips' => 'CRP-003-002', //Criteria-Crips
            'id_criteria' => 'CRT-003',
            'name' => 'Test',
            //'description' => '',
            'value_from' => '81',
            'value_to' => '85',
            'value_type' => 'Between',
            'score' => '2',
        ]);
        DB::table('crips')->insert([
            'id_crips' => 'CRP-003-003', //Criteria-Crips
            'id_criteria' => 'CRT-003',
            'name' => 'Test',
            //'description' => '',
            'value_from' => '86',
            'value_to' => '90',
            'value_type' => 'Between',
            'score' => '3',
        ]);
        DB::table('crips')->insert([
            'id_crips' => 'CRP-003-004', //Criteria-Crips
            'id_criteria' => 'CRT-003',
            'name' => 'Test',
            //'description' => '',
            'value_from' => '91',
            'value_to' => '95',
            'value_type' => 'Between',
            'score' => '4',
        ]);
        DB::table('crips')->insert([
            'id_crips' => 'CRP-003-005', //Criteria-Crips
            'id_criteria' => 'CRT-003',
            'name' => 'Test',
            //'description' => '',
            'value_from' => '96',
            //'value_to' => '',
            'value_type' => 'More',
            'score' => '5',
        ]);

        //SKP
        for($x = 4; $x <= 10; $x++){
            $y = str_pad($x, 3, '0', STR_PAD_LEFT);
            DB::table('crips')->insert([
                'id_crips' => 'CRP-'.$y.'-001', //Criteria-Crips
                'id_criteria' => 'CRT-'.$y,
                'name' => 'Test',
                //'description' => '',
                'value_from' => '80',
                //'value_to' => '',
                'value_type' => 'Less',
                'score' => '1',
            ]);
            DB::table('crips')->insert([
                'id_crips' => 'CRP-'.$y.'-002', //Criteria-Crips
                'id_criteria' => 'CRT-'.$y,
                'name' => 'Test',
                //'description' => '',
                'value_from' => '81',
                'value_to' => '85',
                'value_type' => 'Between',
                'score' => '2',
            ]);
            DB::table('crips')->insert([
                'id_crips' => 'CRP-'.$y.'-003', //Criteria-Crips
                'id_criteria' => 'CRT-'.$y,
                'name' => 'Test',
                //'description' => '',
                'value_from' => '86',
                'value_to' => '90',
                'value_type' => 'Between',
                'score' => '3',
            ]);
            DB::table('crips')->insert([
                'id_crips' => 'CRP-'.$y.'-004', //Criteria-Crips
                'id_criteria' => 'CRT-'.$y,
                'name' => 'Test',
                //'description' => '',
                'value_from' => '91',
                'value_to' => '95',
                'value_type' => 'Between',
                'score' => '4',
            ]);
            DB::table('crips')->insert([
                'id_crips' => 'CRP-'.$y.'-005', //Criteria-Crips
                'id_criteria' => 'CRT-'.$y,
                'name' => 'Test',
                //'description' => '',
                'value_from' => '96',
                //'value_to' => '',
                'value_type' => 'More',
                'score' => '5',
            ]);
        }

        //SETTING (Pengaturan)
        DB::table('settings')->insert([
            'id_setting' => 'STG-001',
            'name' => 'Perhitungan Kehadiran',
            'value' => 'CRT-001',
        ]);

        DB::table('settings')->insert([
            'id_setting' => 'STG-002',
            'name' => 'Sorting Kedua',
            'value' => 'CRT-003',
        ]);
    }
}
