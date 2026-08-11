<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testimoni;

class TestimoniController extends Controller
{
    public function index()
    {
        $testimonis = Testimoni::orderBy('id', 'desc')->get();
        return view('admin.testimoni.index', compact('testimonis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        Testimoni::create([
            'name' => $request->name,
            'role' => $request->role,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Testimoni berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        $testimoni = Testimoni::findOrFail($id);
        $testimoni->update([
            'name' => $request->name,
            'role' => $request->role,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Testimoni berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $testimoni = Testimoni::findOrFail($id);
        $testimoni->delete();

        return redirect()->back()->with('success', 'Testimoni berhasil dihapus!');
    }

    public function toggleStatus($id)
    {
        $testimoni = Testimoni::findOrFail($id);
        $testimoni->is_active = !$testimoni->is_active;
        $testimoni->save();

        $status = $testimoni->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Testimoni berhasil $status!");
    }
}
