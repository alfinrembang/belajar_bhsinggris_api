<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kosakata;
use Illuminate\Http\Request;

class KosakataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Kosakata::orderBy('id', 'asc')->get();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'english'   => 'required|string|max:100',
            'indonesia' => 'required|string|max:100',
            'contoh'    => 'nullable|string|max:255',
        ]);

        $kosakata = Kosakata::create($validated);

        return response()->json([
            'message' => 'Kosakata berhasil ditambahkan',
            'data'    => $kosakata
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kosakata = Kosakata::find($id);

        if (!$kosakata) {
            return response()->json(['message' => 'Kosakata tidak ditemukan'], 404);
        }

        return response()->json($kosakata);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kosakata = Kosakata::find($id);

        if (!$kosakata) {
            return response()->json(['message' => 'Kosakata tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'english'   => 'sometimes|required|string|max:100',
            'indonesia' => 'sometimes|required|string|max:100',
            'contoh'    => 'nullable|string|max:255',
        ]);

        $kosakata->update($validated);

        return response()->json([
            'message' => 'Kosakata berhasil diperbarui',
            'data'    => $kosakata
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kosakata = Kosakata::find($id);

        if (!$kosakata) {
            return response()->json(['message' => 'Kosakata tidak ditemukan'], 404);
        }

        $kosakata->delete();

        return response()->json([
            'message' => 'Kosakata berhasil dihapus'
        ]);
    }
}
