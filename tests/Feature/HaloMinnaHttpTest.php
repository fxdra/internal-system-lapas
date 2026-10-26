<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Wbp;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HaloMinnaHttpTest extends TestCase
{
    private array $tabelDibuat = [];

    protected function setUp(): void
    {
        parent::setUp();

        if (! Schema::hasTable('admins')) {
            Schema::create('admins', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->string('nip')->unique();
                $table->string('password');
                $table->enum('role', [
                    'superadmin',
                    'admin',
                    'binadik',
                    'petugas',
                ])->default('admin');
                $table->enum('status', [
                    'Aktif',
                    'Nonaktif',
                ])->default('Aktif');
                $table->timestamps();
            });

            $this->tabelDibuat[] = 'admins';
        }

        if (! Schema::hasTable('wbps')) {
            Schema::create('wbps', function (Blueprint $table) {
                $table->id();
                $table->string('no_reg_instansi', 50)->unique();
                $table->string('nama', 100);
                $table->enum('status_wbp', [
                    'AKTIF',
                    'PINDAH UPT',
                    'BON',
                    'SAKIT',
                    'PULANG',
                ])->default('AKTIF');
                $table->timestamps();
            });

            $this->tabelDibuat[] = 'wbps';
        }

        if (! Schema::hasTable('kalender_libur')) {
            Schema::create('kalender_libur', function (Blueprint $table) {
                $table->id();
                $table->date('tanggal')->unique();
                $table->string('nama', 150);
                $table->enum('jenis', [
                    'Libur Nasional',
                    'Cuti Bersama',
                ]);
                $table->timestamps();
            });

            $this->tabelDibuat[] = 'kalender_libur';
        }

        if (! Schema::hasTable('simproin_wbp')) {
            Schema::create('simproin_wbp', function (Blueprint $table) {
                $table->id();
                $table->foreignId('wbp_id')
                    ->unique()
                    ->constrained('wbps')
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();

                $table->string('kode_akses', 20)->nullable()->unique();
                $table->enum('register_f', ['Ya', 'Tidak'])
                    ->default('Tidak');

                $table->enum('jenis_usulan', [
                    'Asimilasi',
                    'Cuti Bersyarat',
                    'Cuti Mengunjungi Keluarga',
                    'Cuti Menjelang Bebas',
                    'Pembebasan Bersyarat',
                    'Belum Usul',
                ])->nullable()->default('Belum Usul');

                $table->date('tgl_otorisasi_sk')->nullable();
                $table->date('tgl_pelaksanaan')->nullable();
                $table->date('tgl_pembebasan')->nullable();

                $table->enum('status_bebas', [
                    'Belum Bebas',
                    'Sudah Bebas',
                    'Batal',
                ])->default('Belum Bebas');

                $table->enum('status_aktif', [
                    'Aktif',
                    'Tidak Aktif',
                    'Batal',
                ])->default('Tidak Aktif');

                $table->timestamps();
            });

            $this->tabelDibuat[] = 'simproin_wbp';
        }
    }

    protected function tearDown(): void
    {
        foreach (array_reverse($this->tabelDibuat) as $tabel) {
            Schema::dropIfExists($tabel);
        }

        parent::tearDown();
    }

    private function buatAdmin(string $role = 'admin'): Admin
    {
        return Admin::create([
            'nama' => 'Admin Tes',
            'nip' => 'TEST-' . uniqid(),
            'password' => Hash::make('password-test'),
            'role' => $role,
            'status' => 'Aktif',
        ]);
    }

    private function buatWbp(string $tanggalSk = '2026-10-24'): Wbp
    {
        $id = DB::table('wbps')->insertGetId([
            'no_reg_instansi' => 'TEST-' . uniqid(),
            'nama' => 'WBP Tes HTTP',
            'status_wbp' => 'AKTIF',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('simproin_wbp')->insert([
            'wbp_id' => $id,
            'kode_akses' => 'TEST-' . $id,
            'register_f' => 'Tidak',
            'jenis_usulan' => 'Belum Usul',
            'tgl_otorisasi_sk' => $tanggalSk,
            'status_bebas' => 'Belum Bebas',
            'status_aktif' => 'Aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Wbp::findOrFail($id);
    }

    public function test_request_tahap_akhir_tanpa_login_ditolak(): void
    {
        $wbp = $this->buatWbp();

        $response = $this->post(
            route('halo-minna.simpan', [
                'wbp' => $wbp->id,
                'bagian' => 'tahap_akhir',
            ]),
            [
                'status_bebas' => 'Belum Bebas',
            ]
        );

        $response->assertRedirect('/admin-login');
    }

    public function test_admin_dapat_menyimpan_tahap_akhir_melalui_http(): void
    {
        $admin = $this->buatAdmin();
        $wbp = $this->buatWbp('2026-10-24');

        $this->actingAs($admin, 'admin');

        $response = $this->post(
            route('halo-minna.simpan', [
                'wbp' => $wbp->id,
                'bagian' => 'tahap_akhir',
            ]),
            [
                'status_bebas' => 'Belum Bebas',
                'tgl_pelaksanaan' => '2026-12-31',
            ]
        );

        $response->assertRedirect(route('halo-minna.index', [
            'wbp_id' => $wbp->id,
        ]));

        $registrasi = DB::table('simproin_wbp')
            ->where('wbp_id', $wbp->id)
            ->first();

        $this->assertNotNull($registrasi);
        $this->assertSame(
            '2026-10-26',
            substr($registrasi->tgl_pelaksanaan, 0, 10)
        );
        $this->assertNotSame(
            '2026-12-31',
            substr($registrasi->tgl_pelaksanaan, 0, 10)
        );
    }

    public function test_role_tidak_diizinkan_mendapatkan_403(): void
    {
        $admin = $this->buatAdmin('petugas');
        $wbp = $this->buatWbp();

        $this->actingAs($admin, 'admin');

        $response = $this->post(
            route('halo-minna.simpan', [
                'wbp' => $wbp->id,
                'bagian' => 'tahap_akhir',
            ]),
            [
                'status_bebas' => 'Belum Bebas',
            ]
        );

        $response->assertForbidden();
    }
}
