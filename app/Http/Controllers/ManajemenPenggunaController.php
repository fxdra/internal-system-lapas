<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class ManajemenPenggunaController extends Controller
{
    /*
     * Batas maksimal akun aktif setiap role.
     * Superadmin tidak dibatasi.
     */
    private const ROLE_LIMIT = [
        'admin'       => 2,
        'ka_kplp'     => 1,
        'kplp'        => 2,
        'registrasi'  => 2,
        'binadik'     => 2,
        'giatja'      => 2,
        'klinik'      => 2,
        'kamtib'      => 2,
    ];


    public function index()
    {
        $users = Admin::orderBy('role')
            ->orderBy('nama')
            ->get()
            ->map(function ($user) {
                $user->role_label = $this->getRoleLabel($user->role);

                return $user;
            });

        return view('admin-banceuy.manajemen-pengguna', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'nip'      => 'required|max:255|unique:admins,nip',
            'password' => 'required|min:6',
            'role'     => 'required',
            'status'   => 'required|in:Aktif,Nonaktif',
        ]);

        if ($request->status == 'Aktif') {
            $this->checkRoleLimit($request->role);
        }

        Admin::create([
            'nama'       => $request->nama,
            'nip'        => $request->nip,
            'password'   => Hash::make($request->password),
            'role'       => $request->role,
            'status'     => $request->status,
            'last_login' => null,
        ]);

        return redirect()
            ->route('manajemen-pengguna.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nip'  => [
                'required',
                Rule::unique('admins', 'nip')->ignore($admin->id),
            ],
            'role'   => 'required',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        if (
            $request->status == 'Aktif' &&
            (
                $admin->role != $request->role ||
                $admin->status != 'Aktif'
            )
        ) {
            $this->checkRoleLimit($request->role, $admin->id);
        }

        $admin->nama   = $request->nama;
        $admin->nip    = $request->nip;
        $admin->role   = $request->role;
        $admin->status = $request->status;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()
            ->route('manajemen-pengguna.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);

        if ($admin->role === 'superadmin') {

            $jumlahSuperadmin = Admin::where('role', 'superadmin')
                ->where('status', 'Aktif')
                ->count();

            if ($jumlahSuperadmin <= 1) {
                return back()->withErrors([
                    'Masih tersisa satu akun Superadmin. Akun terakhir tidak boleh dihapus.'
                ]);
            }
        }

        $admin->delete();

        return redirect()
            ->route('manajemen-pengguna.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }

    private function getRoleLabel($role)
    {
        return match ($role) {
            'superadmin' => 'Superadmin',
            'admin'      => 'Admin',
            'ka_kplp'    => 'Ka. KPLP',
            'kplp'       => 'KPLP',
            'registrasi' => 'Registrasi',
            'binadik'    => 'Binadik',
            'giatja'     => 'Giatja',
            'klinik'     => 'Klinik',
            'kamtib'     => 'Kamtib',
            default      => ucfirst($role),
        };
    }

    /**
     * Cek kuota akun aktif setiap role.
     */
    private function checkRoleLimit($role, $ignoreId = null)
    {
        // Superadmin tidak dibatasi
        if ($role === 'superadmin') {
            return;
        }

        if (!isset(self::ROLE_LIMIT[$role])) {
            return;
        }

        $query = Admin::where('role', $role)
            ->where('status', 'Aktif');

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        $jumlah = $query->count();

        if ($jumlah >= self::ROLE_LIMIT[$role]) {

            $label = $this->getRoleLabel($role);

            throw ValidationException::withMessages([
                'role' => "{$label} sudah mencapai batas maksimal "
                    . self::ROLE_LIMIT[$role]
                    . " akun aktif. Silakan nonaktifkan salah satu akun terlebih dahulu."
            ]);
        }
    }
}
