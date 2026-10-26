<?php

namespace Tests\Feature;

use App\Http\Controllers\HaloMinnaController;
use App\Models\Wbp;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HitungTanggalPelaksanaanTest extends TestCase
{
    private array $tabelDibuat = [];

    protected function setUp(): void
    {
        parent::setUp();

        // Semua tabel tes dibuat hanya di koneksi database testing.
        if (! Schema::hasTable('wbps')) {
            Schema::create('wbps', function (Blueprint $table) {
                $table->id();
                $table->string('no_reg_instansi', 50)->unique();
                $table->string('nama', 100);
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
        // Hapus hanya tabel yang dibuat oleh tes ini,
        // dengan urutan yang aman untuk foreign key.
        foreach (array_reverse($this->tabelDibuat) as $tabel) {
            Schema::dropIfExists($tabel);
        }

        parent::tearDown();
    }

    private function hitung(?string $tanggalSk): ?string
    {
        $controller = app(HaloMinnaController::class);

        $method = new \ReflectionMethod(
            HaloMinnaController::class,
            'hitungTanggalPelaksanaan'
        );

        return $method->invoke($controller, $tanggalSk);
    }

    private function tambahLibur(
        string $tanggal,
        string $jenis = 'Libur Nasional'
    ): void {
        DB::table('kalender_libur')->insert([
            'tanggal' => $tanggal,
            'nama' => 'Hari libur pengujian',
            'jenis' => $jenis,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function buatRegistrasi(string $tanggalSk): Wbp
    {
        $wbpId = DB::table('wbps')->insertGetId([
            'no_reg_instansi' => 'TEST-' . uniqid(),
            'nama' => 'WBP Pengujian',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('simproin_wbp')->insert([
            'wbp_id' => $wbpId,
            'kode_akses' => 'TEST-' . $wbpId,
            'register_f' => 'Tidak',
            'jenis_usulan' => 'Belum Usul',
            'tgl_otorisasi_sk' => $tanggalSk,
            'tgl_pelaksanaan' => null,
            'status_bebas' => 'Belum Bebas',
            'status_aktif' => 'Aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Wbp::findOrFail($wbpId);
    }

    private function simpanTahapAkhir(
        Wbp $wbp,
        array $input = []
    ) {
        $request = Request::create(
            '/admin-banceuy/halo-minna/' . $wbp->id . '/simpan/tahap_akhir',
            'POST',
            array_merge([
                'tgl_pembebasan' => null,
                'status_bebas' => 'Belum Bebas',
            ], $input)
        );

        return app(HaloMinnaController::class)->simpanBagian(
            $request,
            $wbp,
            'tahap_akhir'
        );
    }

    // 7 tes perhitungan yang sudah ada.

    public function test_hari_kerja_biasa_menggunakan_tanggal_sk(): void
    {
        $this->assertSame('2026-10-26', $this->hitung('2026-10-26'));
    }

    public function test_hari_sabtu_bergeser_ke_senin(): void
    {
        $this->assertSame('2026-10-26', $this->hitung('2026-10-24'));
    }

    public function test_hari_minggu_bergeser_ke_senin(): void
    {
        $this->assertSame('2026-10-26', $this->hitung('2026-10-25'));
    }

    public function test_libur_nasional_bergeser_ke_hari_kerja(): void
    {
        $this->tambahLibur('2026-10-27');

        $this->assertSame('2026-10-28', $this->hitung('2026-10-27'));
    }

    public function test_cuti_bersama_bergeser_ke_hari_kerja(): void
    {
        $this->tambahLibur('2026-10-27', 'Cuti Bersama');

        $this->assertSame('2026-10-28', $this->hitung('2026-10-27'));
    }

    public function test_libur_berurutan_dan_akhir_pekan_dilewati(): void
    {
        $this->tambahLibur('2026-10-30');
        $this->tambahLibur('2026-11-02', 'Cuti Bersama');

        $this->assertSame('2026-11-03', $this->hitung('2026-10-30'));
    }

    public function test_tanggal_sk_kosong_menghasilkan_null(): void
    {
        $this->assertNull($this->hitung(null));
    }

    // Tes integrasi: menyimpan melalui simpanBagian().

    public function test_tahap_akhir_menyimpan_tanggal_pelaksanaan_ke_database(): void
    {
        $wbp = $this->buatRegistrasi('2026-10-26');

        $this->simpanTahapAkhir($wbp);

        $registrasi = DB::table('simproin_wbp')
            ->where('wbp_id', $wbp->id)
            ->first();

        $this->assertNotNull($registrasi);
        $this->assertSame('2026-10-26', substr($registrasi->tgl_pelaksanaan, 0, 10));
        $this->assertSame('2026-10-26', $registrasi->tgl_otorisasi_sk);
        $this->assertSame('Belum Bebas', $registrasi->status_bebas);
    }

    public function test_tahap_akhir_menyimpan_hasil_perhitungan_akhir_pekan(): void
    {
        $wbp = $this->buatRegistrasi('2026-10-24');

        $this->simpanTahapAkhir($wbp);

        $registrasi = DB::table('simproin_wbp')
            ->where('wbp_id', $wbp->id)
            ->first();

        $this->assertNotNull($registrasi);
        $this->assertSame('2026-10-24', $registrasi->tgl_otorisasi_sk);
        $this->assertSame(
            '2026-10-26',
            substr($registrasi->tgl_pelaksanaan, 0, 10)
        );
    }

    public function test_input_tanggal_pelaksanaan_tidak_menimpa_hasil_perhitungan(): void
    {
        $wbp = $this->buatRegistrasi('2026-10-24');

        $this->simpanTahapAkhir($wbp, [
            'tgl_pelaksanaan' => '2026-12-31',
        ]);

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
}
