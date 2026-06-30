<?php

namespace App\Http\Controllers;

use App\Models\Info;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function index()
    {
        // Mengambil data diurutkan dari tanggal publish terbaru
        $pengumumans = Info::orderBy('tanggal_publish', 'desc')->get();
        return view('info.index', compact('pengumumans'));
    }

    public function create()
    {
        return view('info.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_pengumuman' => 'required|string|max:255',
            'isi_informasi' => 'required|string',
            'tipe_warna' => 'required|in:amber,emerald,sky',
            'tanggal_publish' => 'required|date'
        ]);

        Info::create($request->all());

        return redirect()->route('info.index')->with('success', 'Pengumuman berhasil diterbitkan!');
    }

    public function edit($id)
    {
        $info = Info::findOrFail($id);
        return view('info.edit', compact('info'));
    }

    public function update(Request $request, $id)
    {
        $info = Info::findOrFail($id);

        $request->validate([
            'judul_pengumuman' => 'required|string|max:255',
            'isi_informasi' => 'required|string',
            'tipe_warna' => 'required|in:amber,emerald,sky',
            'tanggal_publish' => 'required|date'
        ]);

        $info->update($request->all());

        return redirect()->route('info.index')->with('success', 'Pengumuman berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $info = Info::findOrFail($id);
        $info->delete();

        return redirect()->route('info.index')->with('success', 'Pengumuman berhasil dihapus!');
    }
}