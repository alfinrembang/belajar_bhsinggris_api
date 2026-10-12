<table>
    <thead>
        <tr>
            <th colspan="10" style="font-size: 14pt; font-weight: bold; text-align: center; height: 30px;">
                LAPORAN REKAPITULASI HASIL BELAJAR SISWA
            </th>
        </tr>
        <tr>
            <th colspan="10" style="font-size: 10pt; font-weight: normal; text-align: center; color: #4B5563;">
                Mata Pelajaran: Bahasa Inggris | Standar KKM: {{ $kkm }} | Waktu Unduh: {{ now()->translatedFormat('d F Y, H:i') }} WIB
            </th>
        </tr>
        <tr>
            <th colspan="10" style="font-size: 10pt; font-weight: normal; text-align: center; color: #4B5563;">
                Ringkasan: Total Siswa: {{ $totalSiswa }} | Rerata Kelas: {{ $rataRataKelas }} | Tuntas: {{ $totalTuntas }} | Remedial: {{ $totalRemedial }}
            </th>
        </tr>
        <tr>
            <th colspan="10"></th>
        </tr>
        <tr>
            <th style="background-color: #1E6BFF; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #9CA3AF; height: 26px;">No</th>
            <th style="background-color: #1E6BFF; color: #FFFFFF; font-weight: bold; text-align: left; border: 1px solid #9CA3AF;">Nama Lengkap Siswa</th>
            <th style="background-color: #1E6BFF; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #9CA3AF;">NIS</th>
            <th style="background-color: #1E6BFF; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #9CA3AF;">NISN</th>
            <th style="background-color: #1E6BFF; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #9CA3AF;">Rombel Kelas</th>
            <th style="background-color: #1E6BFF; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #9CA3AF;">Materi (30%)</th>
            <th style="background-color: #1E6BFF; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #9CA3AF;">Listening (30%)</th>
            <th style="background-color: #1E6BFF; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #9CA3AF;">Kuis (40%)</th>
            <th style="background-color: #1E6BFF; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #9CA3AF;">Nilai Akhir</th>
            <th style="background-color: #1E6BFF; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #9CA3AF;">Status Ketuntasan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($siswaCollection as $index => $item)
            @php
                $bgColor = ($index % 2 === 0) ? '#FFFFFF' : '#F9FAFB';
                $statusColor = $item['is_tuntas'] ? '#059669' : '#DC2626';
            @endphp
            <tr>
                <td style="background-color: {{ $bgColor }}; text-align: center; border: 1px solid #D1D5DB;">{{ $index + 1 }}</td>
                <td style="background-color: {{ $bgColor }}; text-align: left; font-weight: bold; border: 1px solid #D1D5DB;">{{ $item['nama_lengkap'] }}</td>
                <td style="background-color: {{ $bgColor }}; text-align: center; border: 1px solid #D1D5DB;">'{{ $item['nis'] ?: '-' }}</td>
                <td style="background-color: {{ $bgColor }}; text-align: center; border: 1px solid #D1D5DB;">'{{ $item['nisn'] ?: '-' }}</td>
                <td style="background-color: {{ $bgColor }}; text-align: center; border: 1px solid #D1D5DB;">{{ $item['kelas_lengkap'] ?: '-' }}</td>
                <td style="background-color: {{ $bgColor }}; text-align: center; border: 1px solid #D1D5DB;">{{ $item['rata_materi'] }}</td>
                <td style="background-color: {{ $bgColor }}; text-align: center; border: 1px solid #D1D5DB;">{{ $item['rata_listening'] }}</td>
                <td style="background-color: {{ $bgColor }}; text-align: center; border: 1px solid #D1D5DB;">{{ $item['rata_quiz'] }}</td>
                <td style="background-color: {{ $bgColor }}; text-align: center; font-weight: bold; border: 1px solid #D1D5DB;">{{ $item['nilai_akhir'] }}</td>
                <td style="background-color: {{ $bgColor }}; text-align: center; font-weight: bold; color: {{ $statusColor }}; border: 1px solid #D1D5DB;">
                    {{ $item['is_tuntas'] ? 'Tuntas' : 'Perlu Remedial' }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10" style="text-align: center; padding: 20px; color: #6B7280; border: 1px solid #D1D5DB;">
                    Tidak ada data penilaian siswa untuk filter yang dipilih.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
