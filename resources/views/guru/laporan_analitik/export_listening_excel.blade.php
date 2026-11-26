<table>
    <thead>
        <tr>
            <th colspan="11" style="font-size: 14pt; font-weight: bold; text-align: center; height: 30px;">
                REKAPITULASI EVALUASI NILAI MODUL LISTENING
            </th>
        </tr>
        <tr>
            <th colspan="11" style="font-size: 10pt; font-weight: normal; text-align: center; color: #4B5563;">
                Mata Pelajaran: Bahasa Inggris | Standar KKM: {{ $kkm }} | Modul Listening: {{ $listeningPilihan ? $listeningPilihan->judul : 'Seluruh Modul Listening' }} | Waktu Unduh: {{ now()->translatedFormat('d F Y, H:i') }} WIB
            </th>
        </tr>
        <tr>
            <th colspan="11" style="font-size: 10pt; font-weight: normal; text-align: center; color: #4B5563;">
                Ringkasan: Total Pengerjaan: {{ $totalRiwayat }} | Rerata Nilai: {{ $rataRataSkor }} | Tuntas: {{ $totalTuntas }} | Perlu Remedial: {{ $totalRemedial }}
            </th>
        </tr>
        <tr>
            <th colspan="11"></th>
        </tr>
        <tr>
            <th style="background-color: #4EA87D; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #9CA3AF; height: 26px;">No</th>
            <th style="background-color: #4EA87D; color: #FFFFFF; font-weight: bold; text-align: left; border: 1px solid #9CA3AF;">Nama Lengkap Siswa</th>
            <th style="background-color: #4EA87D; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #9CA3AF;">NIS</th>
            <th style="background-color: #4EA87D; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #9CA3AF;">NISN</th>
            <th style="background-color: #4EA87D; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #9CA3AF;">Rombel Kelas</th>
            <th style="background-color: #4EA87D; color: #FFFFFF; font-weight: bold; text-align: left; border: 1px solid #9CA3AF;">Judul Modul Listening</th>
            <th style="background-color: #4EA87D; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #9CA3AF;">Tingkat Kesulitan</th>
            <th style="background-color: #4EA87D; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #9CA3AF;">Skor Nilai</th>
            <th style="background-color: #4EA87D; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #9CA3AF;">Standar KKM</th>
            <th style="background-color: #4EA87D; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #9CA3AF;">Status Ketuntasan</th>
            <th style="background-color: #4EA87D; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #9CA3AF;">Waktu Selesai</th>
        </tr>
    </thead>
    <tbody>
        @forelse($riwayatListening as $index => $item)
            @php
                $bgColor = ($index % 2 === 0) ? '#FFFFFF' : '#F9FAFB';
                $isTuntas = ($item->skor ?? 0) >= $kkm;
                $statusColor = $isTuntas ? '#059669' : '#DC2626';
            @endphp
            <tr>
                <td style="background-color: {{ $bgColor }}; text-align: center; border: 1px solid #D1D5DB;">{{ $index + 1 }}</td>
                <td style="background-color: {{ $bgColor }}; text-align: left; font-weight: bold; border: 1px solid #D1D5DB;">{{ $item->siswa?->nama_lengkap ?? '-' }}</td>
                <td style="background-color: {{ $bgColor }}; text-align: center; border: 1px solid #D1D5DB;">'{{ $item->siswa?->nis ?: '-' }}</td>
                <td style="background-color: {{ $bgColor }}; text-align: center; border: 1px solid #D1D5DB;">'{{ $item->siswa?->nisn ?: '-' }}</td>
                <td style="background-color: {{ $bgColor }}; text-align: center; border: 1px solid #D1D5DB;">{{ $item->siswa?->kelas_lengkap ?: '-' }}</td>
                <td style="background-color: {{ $bgColor }}; text-align: left; border: 1px solid #D1D5DB;">{{ $item->modulListening?->judul ?? '-' }}</td>
                <td style="background-color: {{ $bgColor }}; text-align: center; border: 1px solid #D1D5DB;">{{ $item->modulListening?->tingkat_kesulitan ?? '-' }}</td>
                <td style="background-color: {{ $bgColor }}; text-align: center; font-weight: bold; border: 1px solid #D1D5DB;">{{ $item->skor ?? 0 }}</td>
                <td style="background-color: {{ $bgColor }}; text-align: center; border: 1px solid #D1D5DB;">{{ $kkm }}</td>
                <td style="background-color: {{ $bgColor }}; text-align: center; font-weight: bold; color: {{ $statusColor }}; border: 1px solid #D1D5DB;">
                    {{ $isTuntas ? 'Tuntas' : 'Perlu Remedial' }}
                </td>
                <td style="background-color: {{ $bgColor }}; text-align: center; border: 1px solid #D1D5DB;">
                    {{ $item->completed_at ? \Carbon\Carbon::parse($item->completed_at)->translatedFormat('d M Y, H:i') : '-' }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="11" style="text-align: center; padding: 20px; color: #6B7280; border: 1px solid #D1D5DB;">
                    Tidak ada riwayat pengerjaan modul listening siswa untuk filter yang dipilih.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
