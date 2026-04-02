<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PenanggungJawab;
use App\Models\Kategori;
use Illuminate\Http\Request;

class CategoryAssignmentController extends Controller
{
    public function index()
    {
        $penanggungJawabs = PenanggungJawab::with('kategoris')
            ->latest()
            ->get();
            
        $categories = Kategori::all();

        return view('admin.category_assignments', compact('penanggungJawabs', 'categories'));
    }

    /**
     * Store a new Penanggung Jawab (Person)
     */
    public function storePerson(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:penanggung_jawabs,email',
            'jabatan' => 'nullable|string|max:255',
        ]);

        PenanggungJawab::create($data);

        return redirect()->route('admin.category-assignments.index')->with('success', 'Personil baru berhasil ditambahkan.');
    }

    /**
     * Update an existing Penanggung Jawab (Person Info)
     */
    public function updatePerson(Request $request, $id)
    {
        $pj = PenanggungJawab::findOrFail($id);
        
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:penanggung_jawabs,email,' . $id,
            'jabatan' => 'nullable|string|max:255',
        ]);

        $pj->update($data);

        return redirect()->route('admin.category-assignments.index')->with('success', 'Data personil berhasil diperbarui.');
    }

    /**
     * Delete a Penanggung Jawab
     */
    public function destroyPerson($id)
    {
        $pj = PenanggungJawab::findOrFail($id);
        $pj->delete();

        return redirect()->route('admin.category-assignments.index')->with('success', 'Personil berhasil dihapus.');
    }

    /**
     * Update Category Assignments for a PJ
     */
    public function updateAssignments(Request $request, $pjId)
    {
        $request->validate([
            'category_ids' => 'array',
            'category_ids.*' => 'exists:kategoris,id'
        ]);

        $pj = PenanggungJawab::findOrFail($pjId);
        
        // Reset all categories previously assigned to this person
        Kategori::where('penanggung_jawab_id', $pjId)->update(['penanggung_jawab_id' => null]);
        
        if ($request->has('category_ids')) {
            Kategori::whereIn('id', $request->category_ids)->update(['penanggung_jawab_id' => $pjId]);
        }

        return redirect()->route('admin.category-assignments.index')->with('success', 'Penugasan kategori berhasil diperbarui.');
    }

    /**
     * Update Category Info (Email)
     */
    public function updateCategory(Request $request, $id)
    {
        $category = Kategori::findOrFail($id);
        
        $data = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $category->update($data);

        return redirect()->route('admin.category-assignments.index')->with('success', 'Email kategori berhasil diperbarui.');
    }

    /**
     * Store a new Category
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:kategoris,name',
            'email' => 'nullable|email|max:255',
        ]);

        Kategori::create([
            'name' => $request->name,
            'email' => $request->email,
            'details' => $request->name, // Default details to name since it's not nullable
        ]);

        return redirect()->route('admin.category-assignments.index')->with('success', 'Kategori baru berhasil ditambahkan.');
    }
}
