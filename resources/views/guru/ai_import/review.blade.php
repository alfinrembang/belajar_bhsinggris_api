<x-layout 
    title="Kurasi Ekstraksi AI - Study English Prima" 
    pageTitle="Kurasi Hasil Ekstraksi AI" 
    pageSubtitle="Tinjau, edit, dan sempurnakan hasil pembacaan materi & soal dari dokumen sebelum disimpan resmi." 
    active="{{ $aiImport->tipe_target === 'quiz' ? 'quiz' : 'materi' }}"
>
    <!-- Header Banner AI Document -->
    <x-ai-review-banner :aiImport="$aiImport" :judul="$hasil['judul'] ?? null" />

    <!-- Alert Notifikasi Error Validasi Jika Ada -->
    <x-alert-errors />

    <!-- Form Kurasi Hasil AI -->
    <form method="POST" 
          action="{{ $aiImport->tipe_target === 'quiz' ? route('guru.ai-import.confirm-quiz', $aiImport->id) : route('guru.ai-import.confirm-materi', $aiImport->id) }}" 
          class="space-y-6 pb-20">
        @csrf

        <!-- SECTION 1: INFORMASI UTAMA & METADATA -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 lg:p-8 shadow-xs space-y-6">
            <x-section-header 
                number="01" 
                :title="$aiImport->tipe_target === 'quiz' ? 'Informasi Paket Kuis' : 'Informasi Utama Modul Materi'" 
                subtitle="Parameter penting materi pembelajaran" 
                color="blue" 
            />

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <!-- Judul -->
                <x-form-input 
                    class="md:col-span-2"
                    label="Judul {{ $aiImport->tipe_target === 'quiz' ? 'Kuis' : 'Materi' }}"
                    name="judul"
                    :value="$hasil['judul'] ?? ''"
                    required
                />

                <!-- Kategori -->
                <x-form-select label="Kategori Pembelajaran" name="kategori" required>
                    @php $currentKat = old('kategori', $hasil['kategori'] ?? 'Grammar'); @endphp
                    @if(isset($kategoriList) && $kategoriList->isNotEmpty())
                        @foreach($kategoriList as $k)
                            @php $kNama = is_object($k) ? $k->nama : $k; @endphp
                            <option value="{{ $kNama }}" {{ strcasecmp($currentKat, $kNama) === 0 ? 'selected' : '' }}>{{ $kNama }}</option>
                        @endforeach
                    @else
                        <option value="Grammar" {{ strcasecmp($currentKat, 'Grammar') === 0 ? 'selected' : '' }}>Grammar</option>
                        <option value="Vocabulary" {{ strcasecmp($currentKat, 'Vocabulary') === 0 ? 'selected' : '' }}>Vocabulary</option>
                        <option value="Reading" {{ strcasecmp($currentKat, 'Reading') === 0 ? 'selected' : '' }}>Reading</option>
                        <option value="Conversation" {{ strcasecmp($currentKat, 'Conversation') === 0 ? 'selected' : '' }}>Conversation</option>
                        <option value="Speaking" {{ strcasecmp($currentKat, 'Speaking') === 0 ? 'selected' : '' }}>Speaking</option>
                        <option value="General" {{ strcasecmp($currentKat, 'General') === 0 ? 'selected' : '' }}>General English</option>
                    @endif
                </x-form-select>

                <!-- Tingkat Kelas -->
                <x-form-select label="Tingkat Kelas" name="tingkat_kelas" required>
                    @php $currentKelas = old('tingkat_kelas', $hasil['tingkat_kelas'] ?? 'Kelas 10'); @endphp
                    <option value="Kelas 10" {{ str_contains($currentKelas, '10') ? 'selected' : '' }}>Kelas 10</option>
                    <option value="Kelas 11" {{ str_contains($currentKelas, '11') ? 'selected' : '' }}>Kelas 11</option>
                    <option value="Kelas 12" {{ str_contains($currentKelas, '12') ? 'selected' : '' }}>Kelas 12</option>
                    <option value="Kelas 7" {{ str_contains($currentKelas, '7') ? 'selected' : '' }}>Kelas 7 (SMP)</option>
                    <option value="Kelas 8" {{ str_contains($currentKelas, '8') ? 'selected' : '' }}>Kelas 8 (SMP)</option>
                    <option value="Kelas 9" {{ str_contains($currentKelas, '9') ? 'selected' : '' }}>Kelas 9 (SMP)</option>
                    <option value="Umum" {{ strcasecmp($currentKelas, 'Umum') === 0 ? 'selected' : '' }}>Tingkat Umum / Semua Kelas</option>
                </x-form-select>

                <!-- Target Rombel (Opsional) -->
                <x-form-input 
                    label="Target Rombel"
                    name="target_rombel"
                    :value="old('target_rombel')"
                    hint="Opsional"
                    placeholder="Contoh: X-A, XI MIPA 1 (kosongkan jika semua)"
                />

                <!-- XP Reward -->
                <x-form-input 
                    type="number"
                    label="Reward Siswa (XP)"
                    name="xp_reward"
                    :value="old('xp_reward', $aiImport->tipe_target === 'quiz' ? 60 : 50)"
                    required
                    min="0"
                    max="1000"
                />

                <!-- Status Publikasi -->
                <x-form-select label="Status Publikasi" name="status" required>
                    <option value="aktif">Langsung Tayang (Aktif)</option>
                    <option value="draft">Simpan Sebagai Draft</option>
                </x-form-select>

                @if($aiImport->tipe_target === 'quiz')
                    <!-- Durasi Menit (Kuis) -->
                    <x-form-input 
                        type="number"
                        label="Durasi Pengerjaan (Menit)"
                        name="durasi_menit"
                        :value="old('durasi_menit', $hasil['durasi_menit'] ?? 20)"
                        required
                        min="5"
                        max="180"
                    />

                    <!-- Kode Room Kuis -->
                    <x-form-input 
                        label="Kode Room Masuk"
                        name="kode_room"
                        :value="old('kode_room')"
                        hint="Auto-generate jika kosong"
                        placeholder="Contoh: ENG-892"
                        class="uppercase"
                    />
                @endif
            </div>

            <!-- Deskripsi Singkat -->
            <x-form-textarea 
                class="pt-2"
                :label="$aiImport->tipe_target === 'quiz' ? 'Petunjuk / Deskripsi Kuis' : 'Rangkuman / Deskripsi Singkat Materi'"
                :name="$aiImport->tipe_target === 'quiz' ? 'deskripsi' : 'deskripsi_singkat'"
                :value="$hasil[$aiImport->tipe_target === 'quiz' ? 'deskripsi' : 'deskripsi_singkat'] ?? ''"
                rows="2"
                required
            />

            @if($aiImport->tipe_target !== 'quiz')
                <!-- Konten Penjelasan Materi -->
                <x-form-textarea 
                    label="Penjelasan Materi Lengkap (Format Teks/Markdown)"
                    name="penjelasan"
                    :value="$hasil['penjelasan'] ?? ''"
                    rows="6"
                    fontMono
                    required
                />

                <!-- Contoh Teks / Dialog Bacaan -->
                <x-form-textarea 
                    label="Contoh Kalimat / Teks Bacaan / Percakapan"
                    name="contoh_teks"
                    :value="$hasil['contoh_teks'] ?? ''"
                    rows="4"
                    hint="Bahasa Inggris & Terjemahan"
                />
            @endif
        </div>

        <!-- SECTION 2: BUTIR SOAL LATIHAN / KUIS (Partial Component) -->
        @include('guru.ai_import.section-soal')

        <!-- Floating Action Footer -->
        <x-floating-action-footer 
            :cancelUrl="route($aiImport->tipe_target === 'quiz' ? 'guru.quiz.index' : 'guru.materi.index')"
            cancelText="Batal"
            :submitText="'Konfirmasi & Terbitkan ' . ($aiImport->tipe_target === 'quiz' ? 'Kuis' : 'Materi')"
            statusNotice="Pastikan data di atas sudah sesuai sebelum mengonfirmasi"
        />
    </form>
</x-layout>
