<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class LaporanController extends Controller
{
    // Customer: list reports and display form
    public function index()
    {
        $userId = Auth::id();
        $laporan = Laporan::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
        return view('laporan.customer_index', compact('laporan'));
    }

    // Customer: store report
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'judul.required' => 'Judul laporan harus diisi.',
            'deskripsi.required' => 'Deskripsi laporan harus diisi.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Ensure public/assets/img/laporan directory exists
            $targetDir = public_path('assets/img/laporan');
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            
            $file->move($targetDir, $filename);
            $gambarPath = $filename;
        }

        Laporan::create([
            'user_id' => Auth::id(),
            'judul' => $request->input('judul'),
            'deskripsi' => $request->input('deskripsi'),
            'gambar' => $gambarPath,
            'status' => 'pending'
        ]);

        Session::flash('success', 'Laporan Anda berhasil dikirim.');
        return redirect()->route('customer.laporan');
    }

    // Admin: list all reports
    public function admin_index()
    {
        $laporan = Laporan::with('user.customer')->orderBy('created_at', 'desc')->get();
        return view('laporan.admin_index', compact('laporan'));
    }

    // Admin: update report status
    public function update_status(Request $request, $id)
    {
        $laporan = Laporan::findOrFail($id);
        $status = $request->input('status');

        if (in_array($status, ['pending', 'proses', 'selesai'])) {
            $laporan->update(['status' => $status]);
            Session::flash('success', 'Status laporan berhasil diupdate.');
        } else {
            Session::flash('error', 'Status laporan tidak valid.');
        }

        return redirect()->back();
    }
}
