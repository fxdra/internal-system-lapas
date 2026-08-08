<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class PetugasKplpController extends Controller
{
    public function index()
    {
       $petugas = Admin::whereIn('role', [
            'kplp',
            'ka. kplp'
        ])
        ->latest()
        ->get();

        return view('admin-banceuy.petugas-kplp', compact('petugas'));
    }

    public function store(Request $request)
    {
        $id = $request->id;

        $rules = [
            'nama' => 'required|max:255',
            'nip' => 'required|max:255|unique:admins,nip,',
        ];

        // password wajib saat create
        if (!$id) {
            $rules['password'] = 'required|min:6';
        }

        // password optional saat update
        if ($id && $request->password) {
            $rules['password'] = 'min:6';
        }

        $request->validate($rules);

        $data = [
            'nama' => $request->nama,
            'nip' => $request->nip,
            'role' => 'kplp',
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        Admin::updateOrCreate(
            ['id' => $id],
            $data
        );

        return redirect()
            ->back()
            ->with('success', $id ? 'Data berhasil diupdate' : 'Data berhasil ditambahkan');
    }

    public function destroy($id)
    {
        Admin::findOrFail($id)->delete();

        return redirect()
            ->back()
            ->with('success', 'Data berhasil dihapus');
    }
}