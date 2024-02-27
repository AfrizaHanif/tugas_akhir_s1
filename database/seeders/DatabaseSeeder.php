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
            'name' => 'Developer',
        ]);

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
            'name' => 'Developer',
        ]);

        DB::table('departments')->insert([
            'id_department' => 'DPT-001',
            'name' => 'Kepala BPS Jawa Timur',
        ]);

        DB::table('departments')->insert([
            'id_department' => 'DPT-002',
            'name' => 'Kepegawaian',
        ]);

        DB::table('departments')->insert([
            'id_department' => 'DPT-003',
            'name' => 'Kepala Badan Umum',
        ]);

        DB::table('departments')->insert([
            'id_department' => 'DPT-004',
            'name' => 'Pegawai Badan Umum 1',
        ]);

        DB::table('departments')->insert([
            'id_department' => 'DPT-005',
            'name' => 'Pegawai Badan Umum 2',
        ]);

        DB::table('departments')->insert([
            'id_department' => 'DPT-006',
            'name' => 'Statistisi Ahli Pertama',
        ]);

        DB::table('departments')->insert([
            'id_department' => 'DPT-007',
            'name' => 'Statistisi Ahli Muda',
        ]);

        DB::table('departments')->insert([
            'id_department' => 'DPT-008',
            'name' => 'Statistisi Ahli Madya',
        ]);

        DB::table('departments')->insert([
            'id_department' => 'DPT-009',
            'name' => 'Statistisi Ahli Utama',
        ]);

        //OFFICERS: Pegawai
        DB::table('officers')->insert([
            'id_officer' => 'OFF-000',
            //'nip_bps' => '000000000',
            //'nip' => '000000000',
            'name' => 'Developer',
            //'org_code' => '00000',
            'id_department' => 'DPT-000',
            'id_part' => 'PRT-000',
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
            'name' => 'Kepala BPS Jatim',
            'id_department' => 'DPT-004',
            'id_part' => 'PRT-001',
            'place_birth' => 'Surabaya',
            'date_birth' => '2000/01/01',
            'gender' => 'Laki-Laki',
            'religion' => 'Islam',
        ]);

        DB::table('officers')->insert([
            'id_officer' => 'OFF-002',
            'name' => 'Pegawai Kepegawaian',
            'id_department' => 'DPT-001',
            'id_part' => 'PRT-001',
            'place_birth' => 'Surabaya',
            'date_birth' => '2000/01/01',
            'gender' => 'Laki-Laki',
            'religion' => 'Islam',
        ]);

        DB::table('officers')->insert([
            'id_officer' => 'OFF-003',
            'name' => 'Kepala Badan Umum',
            'id_department' => 'DPT-003',
            'id_part' => 'PRT-001',
            'place_birth' => 'Surabaya',
            'date_birth' => '2000/01/01',
            'gender' => 'Laki-Laki',
            'religion' => 'Islam',
        ]);

        DB::table('officers')->insert([
            'id_officer' => 'OFF-004',
            'name' => 'Pegawai Badan Umum 1A',
            'id_department' => 'DPT-004',
            'id_part' => 'PRT-002',
            'place_birth' => 'Surabaya',
            'date_birth' => '2000/01/01',
            'gender' => 'Laki-Laki',
            'religion' => 'Islam',
        ]);

        DB::table('officers')->insert([
            'id_officer' => 'OFF-005',
            'name' => 'Pegawai Badan Umum 1B',
            'id_department' => 'DPT-004',
            'id_part' => 'PRT-002',
            'place_birth' => 'Surabaya',
            'date_birth' => '2000/01/01',
            'gender' => 'Laki-Laki',
            'religion' => 'Islam',
        ]);

        DB::table('officers')->insert([
            'id_officer' => 'OFF-006',
            'name' => 'Pegawai Badan Umum 2A',
            'id_department' => 'DPT-005',
            'id_part' => 'PRT-002',
            'place_birth' => 'Surabaya',
            'date_birth' => '2000/01/01',
            'gender' => 'Laki-Laki',
            'religion' => 'Islam',
        ]);

        DB::table('officers')->insert([
            'id_officer' => 'OFF-007',
            'name' => 'Pegawai Badan Umum 2B',
            'id_department' => 'DPT-005',
            'id_part' => 'PRT-002',
            'place_birth' => 'Surabaya',
            'date_birth' => '2000/01/01',
            'gender' => 'Laki-Laki',
            'religion' => 'Islam',
        ]);

        DB::table('officers')->insert([
            'id_officer' => 'OFF-008',
            'name' => 'Ketua Tim Teknis 1',
            'id_department' => 'DPT-009',
            'id_part' => 'PRT-001',
            'place_birth' => 'Surabaya',
            'date_birth' => '2000/01/01',
            'gender' => 'Laki-Laki',
            'religion' => 'Islam',
        ]);

        DB::table('officers')->insert([
            'id_officer' => 'OFF-009',
            'name' => 'Pegawai Tim Teknis 1A',
            'id_department' => 'DPT-009',
            'id_part' => 'PRT-003',
            'place_birth' => 'Surabaya',
            'date_birth' => '2000/01/01',
            'gender' => 'Laki-Laki',
            'religion' => 'Islam',
        ]);

        DB::table('officers')->insert([
            'id_officer' => 'OFF-010',
            'name' => 'Pegawai Tim Teknis 1B',
            'id_department' => 'DPT-009',
            'id_part' => 'PRT-003',
            'place_birth' => 'Surabaya',
            'date_birth' => '2000/01/01',
            'gender' => 'Laki-Laki',
            'religion' => 'Islam',
        ]);

        DB::table('officers')->insert([
            'id_officer' => 'OFF-011',
            'name' => 'Ketua Tim Teknis 2',
            'id_department' => 'DPT-008',
            'id_part' => 'PRT-001',
            'place_birth' => 'Surabaya',
            'date_birth' => '2000/01/01',
            'gender' => 'Laki-Laki',
            'religion' => 'Islam',
        ]);

        DB::table('officers')->insert([
            'id_officer' => 'OFF-012',
            'name' => 'Pegawai Tim Teknis 2A',
            'id_department' => 'DPT-008',
            'id_part' => 'PRT-003',
            'place_birth' => 'Surabaya',
            'date_birth' => '2000/01/01',
            'gender' => 'Laki-Laki',
            'religion' => 'Islam',
        ]);

        DB::table('officers')->insert([
            'id_officer' => 'OFF-013',
            'name' => 'Pegawai Tim Teknis 2B',
            'id_department' => 'DPT-008',
            'id_part' => 'PRT-003',
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
            'id_officer' => 'OFF-002',
        ]);

        DB::table('users')->insert([
            'id_user' => 'USR-002',
            'username' => 'testkabag',
            'email' => 'testkabag@bps.com',
            'password' => '$2y$10$0fQtK9jo.PjQwCItVUlOaevSTelCFz1Lc/Z8dIuFeK/3u5BmkXZzS',
            'part' => 'KBU',
            'id_officer' => 'OFF-003',
        ]);

        DB::table('users')->insert([
            'id_user' => 'USR-003',
            'username' => 'testketim1',
            'email' => 'testketim1@bps.com',
            'password' => '$2y$10$0fQtK9jo.PjQwCItVUlOaevSTelCFz1Lc/Z8dIuFeK/3u5BmkXZzS',
            'part' => 'KTM1',
            'id_officer' => 'OFF-008',
        ]);

        DB::table('users')->insert([
            'id_user' => 'USR-004',
            'username' => 'testketim2',
            'email' => 'testketim2@bps.com',
            'password' => '$2y$10$0fQtK9jo.PjQwCItVUlOaevSTelCFz1Lc/Z8dIuFeK/3u5BmkXZzS',
            'part' => 'KTM2',
            'id_officer' => 'OFF-011',
        ]);

        DB::table('users')->insert([
            'id_user' => 'USR-005',
            'username' => 'testkbps',
            'email' => 'testkbps@bps.com',
            'password' => '$2y$10$0fQtK9jo.PjQwCItVUlOaevSTelCFz1Lc/Z8dIuFeK/3u5BmkXZzS',
            'part' => 'KBPS',
            'id_officer' => 'OFF-001',
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
            'name' => 'Kecepatan Penyelesaian Tugas',
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
