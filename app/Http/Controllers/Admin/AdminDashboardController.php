<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\PengaturanAkademik;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama administrator beserta daftar akun guru.
     */
    public function index(Request $request): View
    {
        $search = $request->query('q');

        $query = User::where('role', 'guru')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $gurus = $query->paginate(10)->withQueryString();

        $totalGuru = User::where('role', 'guru')->count();
        $totalAdmin = User::where('role', 'admin')->count();
        $totalSiswa = Siswa::count();
        $totalMateri = Materi::count();

        // Ambil rombel dari database siswa & sediakan daftar rombel sekolah
        $rombelDariSiswa = Siswa::select('kelas', 'jurusan', 'no_kelas')
            ->distinct()
            ->get()
            ->map->kelas_lengkap
            ->filter()
            ->values()
            ->toArray();

        $defaultRombels = [
            '10 RPL 1', '10 RPL 2', '10 DKV 1', '10 DKV 2', '10 TSM 1', '10 BD 1', '10 MPLB 1',
            '11 RPL 1', '11 RPL 2', '11 DKV 1', '11 DKV 2', '11 TSM 1', '11 BD 1', '11 MPLB 1',
            '12 RPL 1', '12 RPL 2', '12 DKV 1', '12 DKV 2', '12 TSM 1', '12 BD 1', '12 MPLB 1',
        ];

        $daftarRombel = array_values(array_unique(array_merge($rombelDariSiswa, $defaultRombels)));
        sort($daftarRombel);

        $pengaturanAkademik = PengaturanAkademik::getActiveSetting();

        return view('admin.dashboard', compact(
            'gurus',
            'totalGuru',
            'totalAdmin',
            'totalSiswa',
            'totalMateri',
            'search',
            'daftarRombel',
            'pengaturanAkademik'
        ));
    }

    /**
     * Mendaftarkan akun guru baru oleh administrator.
     */
    public function storeGuru(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'nip' => ['nullable', 'string', 'max:50', 'unique:users,nip'],
            'password' => ['required', 'string', 'min:6'],
            'kelas_diampu' => ['nullable', 'array'],
            'kelas_diampu.*' => ['string'],
        ], [
            'name.required' => 'Nama lengkap guru wajib diisi!',
            'email.required' => 'Alamat email wajib diisi!',
            'email.email' => 'Format email tidak valid!',
            'email.unique' => 'Email ini sudah terdaftar oleh pengguna lain!',
            'nip.unique' => 'NIP ini sudah terdaftar oleh guru lain!',
            'password.required' => 'Kata sandi wajib diisi!',
            'password.min' => 'Kata sandi minimal 6 karakter!',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nip' => $validated['nip'] ?? null,
            'kelas_diampu' => $request->input('kelas_diampu', []),
            'role' => 'guru',
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.dashboard')->with('success', "Akun guru '{$validated['name']}' berhasil ditambahkan ke sistem!");
    }

    /**
     * Memperbarui informasi profil akun guru.
     */
    public function updateGuru(Request $request, User $guru): RedirectResponse
    {
        if ($guru->role !== 'guru') {
            return back()->with('error', 'Hanya akun guru yang dapat dikelola melalui menu ini!');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($guru->id)],
            'nip' => ['nullable', 'string', 'max:50', Rule::unique('users', 'nip')->ignore($guru->id)],
            'kelas_diampu' => ['nullable', 'array'],
            'kelas_diampu.*' => ['string'],
        ], [
            'name.required' => 'Nama lengkap guru wajib diisi!',
            'email.required' => 'Alamat email wajib diisi!',
            'email.email' => 'Format email tidak valid!',
            'email.unique' => 'Email ini sudah terdaftar oleh pengguna lain!',
            'nip.unique' => 'NIP ini sudah terdaftar oleh guru lain!',
        ]);

        $guru->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nip' => $validated['nip'] ?? null,
            'kelas_diampu' => $request->input('kelas_diampu', []),
        ]);

        return redirect()->route('admin.dashboard')->with('success', "Data akun guru '{$guru->name}' berhasil diperbarui!");
    }

    /**
     * Mereset kata sandi akun guru oleh administrator.
     */
    public function resetPasswordGuru(Request $request, User $guru): RedirectResponse
    {
        if ($guru->role !== 'guru') {
            return back()->with('error', 'Hanya akun guru yang dapat direset kata sandinya!');
        }

        $validated = $request->validate([
            'new_password' => ['required', 'string', 'min:6'],
        ], [
            'new_password.required' => 'Kata sandi baru wajib diisi!',
            'new_password.min' => 'Kata sandi minimal 6 karakter!',
        ]);

        $guru->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return redirect()->route('admin.dashboard')->with('success', "Kata sandi akun guru '{$guru->name}' berhasil direset!");
    }

    /**
     * Menghapus akun guru dari sistem.
     */
    public function destroyGuru(User $guru): RedirectResponse
    {
        if ($guru->id === auth()->id() || $guru->role !== 'guru') {
            return back()->with('error', 'Anda tidak dapat menghapus akun administrator atau akun Anda sendiri!');
        }

        $guruName = $guru->name;
        $guru->delete();

        return redirect()->route('admin.dashboard')->with('success', "Akun guru '{$guruName}' berhasil dihapus dari sistem!");
    }

    /**
     * Membuka atau menutup periode kenaikan kelas untuk siswa di aplikasi mobile.
     * Hanya dapat diakses oleh Administrator Utama.
     */
    public function toggleKenaikanKelas(Request $request): RedirectResponse
    {
        $pengaturan = PengaturanAkademik::getActiveSetting();
        $isAktif = ! $pengaturan->is_periode_kenaikan_aktif;

        $pengaturan->is_periode_kenaikan_aktif = $isAktif;
        $pengaturan->tahun_ajaran = PengaturanAkademik::getTahunAjaranOtomatis();

        if ($isAktif) {
            $pengaturan->opened_at = now();
        } else {
            $pengaturan->closed_at = now();
        }

        if ($request->filled('keterangan')) {
            $pengaturan->keterangan = trim($request->input('keterangan'));
        }

        $pengaturan->save();

        $pesan = $isAktif
            ? "Periode kenaikan kelas ({$pengaturan->tahun_ajaran}) berhasil DIBUKA! Siswa sekarang dapat memperbarui rombel & no absen di aplikasi mobile."
            : 'Periode kenaikan kelas berhasil DITUTUP. Rombel siswa kembali dikunci untuk mencegah perubahan sepihak.';

        return redirect()->route('admin.dashboard')->with('success', $pesan);
    }
}
