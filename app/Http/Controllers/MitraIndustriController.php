<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MitraIndustri;
use Illuminate\Support\Facades\File;

class MitraIndustriController extends Controller
{
    public function index()
    {
        $mitras = MitraIndustri::orderBy('id', 'desc')->get();
        return view('admin.mitra_industri.index', compact('mitras'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
        ]);

        $mitra = new MitraIndustri();
        $mitra->name = $request->name;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('assets/img/mitra_industri'), $imageName);
            $mitra->image_path = 'assets/img/mitra_industri/' . $imageName;
        }

        $mitra->save();

        return redirect()->back()->with('success', 'Mitra Industri berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
        ]);

        $mitra = MitraIndustri::findOrFail($id);
        $mitra->name = $request->name;

        if ($request->hasFile('image')) {
            // Delete old image
            if (File::exists(public_path($mitra->image_path))) {
                File::delete(public_path($mitra->image_path));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('assets/img/mitra_industri'), $imageName);
            $mitra->image_path = 'assets/img/mitra_industri/' . $imageName;
        }

        $mitra->save();

        return redirect()->back()->with('success', 'Mitra Industri berhasil diupdate!');
    }

    public function destroy($id)
    {
        $mitra = MitraIndustri::findOrFail($id);
        
        if (File::exists(public_path($mitra->image_path))) {
            File::delete(public_path($mitra->image_path));
        }
        
        $mitra->delete();

        return redirect()->back()->with('success', 'Mitra Industri berhasil dihapus!');
    }

    public function toggleStatus($id)
    {
        $mitra = MitraIndustri::findOrFail($id);
        $mitra->is_active = !$mitra->is_active;
        $mitra->save();

        $status = $mitra->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Mitra Industri berhasil $status!");
    }
}
