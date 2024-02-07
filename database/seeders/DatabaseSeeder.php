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

        //PARTS: Tipe User untuk Login
        DB::table('parts')->insert([
            'id_part' => 'PRT-001',
            'name' => 'Kepemimpinan',
        ]);

        DB::table('parts')->insert([
            'id_part' => 'PRT-002',
            'name' => 'Badan Umum',
        ]);

        DB::table('parts')->insert([
            'id_part' => 'PRT-003',
            'name' => 'Tim Teknis Fungsi',
        ]);

        //DEPARTMENTS: Jabatan
        DB::table('departments')->insert([
            'id_department' => 'DPT-000',
            //'id_part' => 'PRT-001',
            'name' => 'Developer',
        ]);

        DB::table('departments')->insert([
            'id_department' => 'DPT-001',
            //'id_part' => 'PRT-001',
            'name' => 'Pegawaian',
        ]);

        DB::table('departments')->insert([
            'id_department' => 'DPT-002',
            //'id_part' => 'PRT-002',
            'name' => 'Kepala Badan Umum',
        ]);

        DB::table('departments')->insert([
            'id_department' => 'DPT-003',
            //'id_part' => 'PRT-003',
            'name' => 'Ketua Tim Teknis',
        ]);

        DB::table('departments')->insert([
            'id_department' => 'DPT-004',
            //'id_part' => 'PRT-004',
            'name' => 'Kepala BPS Jawa Timur',
        ]);

        //OFFICERS: Pegawai
        DB::table('officers')->insert([
            'id_officer' => 'OFF-000',
            //'nip_bps' => '000000000',
            //'nip' => '000000000',
            'name' => 'Developer',
            //'org_code' => '00000',
            'id_department' => 'DPT-000',
            'id_part' => 'PRT-001',
            //'status' => 'PNS',
            //'last_group' => 'I/a', //Golongan Akhir
            //'last_education' => 'S1',
            'place_birth' => 'Surabaya',
            'date_birth' => '2000/01/01',
            'gender' => 'Laki-Laki',
            'religion' => 'Islam',
            //'id_user' => '1',
        ]);

        DB::table('officers')->insert([
            'id_officer' => 'OFF-001',
            //'nip_bps' => '123456789',
            //'nip' => '123456789',
            'name' => 'Pegawai Kepegawaian',
            //'org_code' => '00000',
            'id_department' => 'DPT-001',
            'id_part' => 'PRT-001',
            //'status' => 'PNS',
            //'last_group' => 'IV/a', //Golongan Akhir
            //'last_education' => 'S1',
            'place_birth' => 'Surabaya',
            'date_birth' => '2000/01/01',
            'gender' => 'Laki-Laki',
            'religion' => 'Islam',
            //'id_user' => '1',
        ]);

        DB::table('officers')->insert([
            'id_officer' => 'OFF-002',
            //'nip_bps' => '987654321',
            //'nip' => '987654321',
            'name' => 'Kepala Badan Umum',
            //'org_code' => '00000',
            'id_department' => 'DPT-002',
            'id_part' => 'PRT-001',
            //'status' => 'PNS',
            //'last_group' => 'IV/a', //Golongan Akhir
            //'last_education' => 'S1',
            'place_birth' => 'Surabaya',
            'date_birth' => '2000/01/01',
            'gender' => 'Laki-Laki',
            'religion' => 'Islam',
        ]);

        DB::table('officers')->insert([
            'id_officer' => 'OFF-003',
            //'nip_bps' => '135798642',
            //'nip' => '135798642',
            'name' => 'Kepala BPS Jatim',
            //'org_code' => '00000',
            'id_department' => 'DPT-004',
            'id_part' => 'PRT-001',
            //'status' => 'PNS',
            //'last_group' => 'IV/a', //Golongan Akhir
            //'last_education' => 'S1',
            'place_birth' => 'Surabaya',
            'date_birth' => '2000/01/01',
            'gender' => 'Laki-Laki',
            'religion' => 'Islam',
        ]);

        //USERS: Pengguna
        DB::table('users')->insert([
            'id_user' => 'USR-000',
            'username' => 'developer',
            'email' => 'dev@bps.com',
            'password' => '$2y$10$0fQtK9jo.PjQwCItVUlOaevSTelCFz1Lc/Z8dIuFeK/3u5BmkXZzS',
            'part' => 'Dev',
            'id_officer' => 'OFF-000',
        ]);

        DB::table('users')->insert([
            'id_user' => 'USR-001',
            'username' => 'testadmin',
            'email' => 'testadmin@bps.com',
            'password' => '$2y$10$0fQtK9jo.PjQwCItVUlOaevSTelCFz1Lc/Z8dIuFeK/3u5BmkXZzS',
            'part' => 'Admin',
            'id_officer' => 'OFF-001',
        ]);

        DB::table('users')->insert([
            'id_user' => 'USR-002',
            'username' => 'testkabag',
            'email' => 'testkabag@bps.com',
            'password' => '$2y$10$0fQtK9jo.PjQwCItVUlOaevSTelCFz1Lc/Z8dIuFeK/3u5BmkXZzS',
            'part' => 'KBU',
            'id_officer' => 'OFF-002',
        ]);

        DB::table('users')->insert([
            'id_user' => 'USR-003',
            'username' => 'testkbps',
            'email' => 'testkbps@bps.com',
            'password' => '$2y$10$0fQtK9jo.PjQwCItVUlOaevSTelCFz1Lc/Z8dIuFeK/3u5BmkXZzS',
            'part' => 'KBPS',
            'id_officer' => 'OFF-003',
        ]);

        //PERIOODS: Periode
        DB::table('periods')->insert([
            'id_period' => 'PRD-01-24',
            'name' => 'Januari 2024',
            'status' => 'In Progress',
        ]);

        DB::table('periods')->insert([
            'id_period' => 'PRD-02-24',
            'name' => 'Februari 2024',
            'status' => 'In Progress',
        ]);

        //CRITERIAS: Kriteria
        DB::table('criterias')->insert([
            'id_criteria' => 'CRT-001',
            'name' => 'Kedisiplinan',
            'type' => 'Kehadiran',
        ]);

        DB::table('criterias')->insert([
            'id_criteria' => 'CRT-002',
            'name' => 'Keterampilan Teknis',
            'type' => 'Prestasi Kerja',
        ]);

        DB::table('criterias')->insert([
            'id_criteria' => 'CRT-003',
            'name' => 'Perilaku BerAkhlak',
            'type' => 'Prestasi Kerja',
        ]);

        //SUB CRITERIAS: Sub Kriteria
        //Kedisiplinan
        DB::table('sub_criterias')->insert([
            'id_sub_criteria' => 'SUB-001',
            'id_criteria' => 'CRT-001',
            'name' => 'Kehadiran Pegawai',
            'weight' => '0.14',
            'attribute' => 'Benefit',
            'level' => '9',
            'need' => 'Ya',
        ]);

        DB::table('sub_criterias')->insert([
            'id_sub_criteria' => 'SUB-002',
            'id_criteria' => 'CRT-001',
            'name' => 'Ketepatan Waktu',
            'weight' => '0.14',
            'attribute' => 'Benefit',
            'level' => '9',
            'need' => 'Ya',
        ]);

        //Keterampilan Teknis
        DB::table('sub_criterias')->insert([
            'id_sub_criteria' => 'SUB-003',
            'id_criteria' => 'CRT-002',
            'name' => 'Kecepatan Peneyesaian Tugas',
            'weight' => '0.10',
            'attribute' => 'Benefit',
            'level' => '9',
            'need' => 'Ya',
        ]);

        DB::table('sub_criterias')->insert([
            'id_sub_criteria' => 'SUB-004',
            'id_criteria' => 'CRT-002',
            'name' => 'Kerajinan',
            'weight' => '0.10',
            'attribute' => 'Benefit',
            'level' => '9',
            'need' => 'Ya',
        ]);

        DB::table('sub_criterias')->insert([
            'id_sub_criteria' => 'SUB-005',
            'id_criteria' => 'CRT-002',
            'name' => 'Kualitas Hasil Kerja',
            'weight' => '0.10',
            'attribute' => 'Benefit',
            'level' => '9',
            'need' => 'Ya',
        ]);

        //Perilaku BerAkhlak
        DB::table('sub_criterias')->insert([
            'id_sub_criteria' => 'SUB-006',
            'id_criteria' => 'CRT-003',
            'name' => 'Berorientasi Pelayanan',
            'weight' => '0.06',
            'attribute' => 'Benefit',
            'level' => '9',
            'need' => 'Ya',
        ]);

        DB::table('sub_criterias')->insert([
            'id_sub_criteria' => 'SUB-007',
            'id_criteria' => 'CRT-003',
            'name' => 'Akuntabel',
            'weight' => '0.06',
            'attribute' => 'Benefit',
            'level' => '9',
            'need' => 'Ya',
        ]);

        DB::table('sub_criterias')->insert([
            'id_sub_criteria' => 'SUB-008',
            'id_criteria' => 'CRT-003',
            'name' => 'Kompeten',
            'weight' => '0.06',
            'attribute' => 'Benefit',
            'level' => '9',
            'need' => 'Ya',
        ]);

        DB::table('sub_criterias')->insert([
            'id_sub_criteria' => 'SUB-009',
            'id_criteria' => 'CRT-003',
            'name' => 'Harmonis',
            'weight' => '0.06',
            'attribute' => 'Benefit',
            'level' => '9',
            'need' => 'Ya',
        ]);

        DB::table('sub_criterias')->insert([
            'id_sub_criteria' => 'SUB-010',
            'id_criteria' => 'CRT-003',
            'name' => 'Loyal',
            'weight' => '0.06',
            'attribute' => 'Benefit',
            'level' => '9',
            'need' => 'Ya',
        ]);

        DB::table('sub_criterias')->insert([
            'id_sub_criteria' => 'SUB-011',
            'id_criteria' => 'CRT-003',
            'name' => 'Adaptif',
            'weight' => '0.06',
            'attribute' => 'Benefit',
            'level' => '9',
            'need' => 'Ya',
        ]);

        DB::table('sub_criterias')->insert([
            'id_sub_criteria' => 'SUB-012',
            'id_criteria' => 'CRT-003',
            'name' => 'Kolaboratif',
            'weight' => '0.06',
            'attribute' => 'Benefit',
            'level' => '9',
            'need' => 'Ya',
        ]);

        //PRESENCES: Data Kehadiran (Beta)
        DB::table('presences')->insert([
            'id_presence' => 'PRS-01-24-001-001', //Periode-Officer-SubCriteria
            'id_period' => 'PRD-01-24',
            'id_officer' => 'OFF-001',
            'id_sub_criteria' => 'SUB-001',
            'input' => '31',
            'status' => 'Pending',
        ]);

        DB::table('presences')->insert([
            'id_presence' => 'PRS-01-24-001-002', //Periode-Officer-SubCriteria
            'id_period' => 'PRD-01-24',
            'id_officer' => 'OFF-001',
            'id_sub_criteria' => 'SUB-002',
            'input' => '31',
            'status' => 'Pending',
        ]);
    }
}
