<?php

namespace Database\Seeders;

use App\Models\ListeningSoal;
use App\Models\ModulListening;
use Illuminate\Database\Seeder;

class ModulListeningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Paket Modul 1: At the Airport & Checking In (Beginner)
        $modul1 = ModulListening::updateOrCreate(
            ['judul' => 'At the Airport & Checking In'],
            [
                'deskripsi' => 'Percakapan antara penumpang dan petugas bandara mengenai tiket penerbangan, penimbangan bagasi kabin, dan pemilihan tempat duduk.',
                'tingkat_kesulitan' => 'Beginner',
                'tingkat_kelas' => 'Semua Kelas',
                'audio_file' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3',
                'audio_title' => 'Audio 1 - Airport Conversation',
                'durasi' => '02:15',
                'transkrip' => "Agent: Good morning! Where are you flying to today?\nPassenger: Good morning. I am flying to London Heathrow.\nAgent: May I see your passport and ticket, please?\nPassenger: Here you go.\nAgent: Thank you. Are you checking any bags today?\nPassenger: Just this one suitcase, and I have this backpack as carry-on luggage.\nAgent: Please place your suitcase on the scale... It is 18 kilograms, which is well within the limit.\nPassenger: Great! Could I please get a window seat?\nAgent: Let me check... Yes, seat 14A is available. Here is your boarding pass. Gate 5, boarding starts at 10:15 AM.\nPassenger: Thank you very much! Have a good day.",
                'tujuan_belajar' => [
                    'Memahami ungkapan check-in penerbangan di bandara',
                    'Mengidentifikasi detail nomor kursi, gerbang keberangkatan, dan berat bagasi',
                    'Mendengarkan rekaman audio secara seksama dan menjawab butir soal latihan pemahaman',
                ],
                'xp_reward' => 50,
                'status' => 'aktif',
            ]
        );

        $soals1 = [
            [
                'nomor_soal' => 1,
                'pertanyaan' => 'Where is the passenger flying to today?',
                'pilihan_a' => 'New York JFK',
                'pilihan_b' => 'London Heathrow',
                'pilihan_c' => 'Paris Charles de Gaulle',
                'pilihan_d' => 'Tokyo Haneda',
                'jawaban_benar' => 'B',
                'pembahasan' => 'Pada awal percakapan, penumpang dengan jelas menyatakan: "I am flying to London Heathrow."',
            ],
            [
                'nomor_soal' => 2,
                'pertanyaan' => 'How many bags is the passenger checking in?',
                'pilihan_a' => 'No bags checked',
                'pilihan_b' => 'One suitcase',
                'pilihan_c' => 'Two suitcases',
                'pilihan_d' => 'Three backpacks',
                'jawaban_benar' => 'B',
                'pembahasan' => 'Penumpang menjawab pertanyaan petugas: "Just this one suitcase, and I have this backpack as carry-on luggage."',
            ],
            [
                'nomor_soal' => 3,
                'pertanyaan' => 'What kind of seat did the passenger request?',
                'pilihan_a' => 'A window seat',
                'pilihan_b' => 'An aisle seat',
                'pilihan_c' => 'A front row seat',
                'pilihan_d' => 'Any available seat',
                'jawaban_benar' => 'A',
                'pembahasan' => 'Penumpang menanyakan: "Could I please get a window seat?" dan petugas memberikan kursi 14A.',
            ],
            [
                'nomor_soal' => 4,
                'pertanyaan' => 'What time does boarding start at Gate 5?',
                'pilihan_a' => '09:30 AM',
                'pilihan_b' => '10:00 AM',
                'pilihan_c' => '10:15 AM',
                'pilihan_d' => '11:15 AM',
                'jawaban_benar' => 'C',
                'pembahasan' => 'Petugas menginformasikan: "Gate 5, boarding starts at 10:15 AM."',
            ],
        ];

        foreach ($soals1 as $soal) {
            ListeningSoal::updateOrCreate(
                ['modul_listening_id' => $modul1->id, 'nomor_soal' => $soal['nomor_soal']],
                $soal
            );
        }

        // 2. Paket Modul 2: Ordering Food in a Restaurant (Intermediate)
        $modul2 = ModulListening::updateOrCreate(
            ['judul' => 'Ordering Food in a Restaurant'],
            [
                'deskripsi' => 'Dialog interaktif antara pelanggan dan pelayan restoran mengenai rekomendasi menu makan siang, minuman, dan penagihan nota pembayaran.',
                'tingkat_kesulitan' => 'Intermediate',
                'tingkat_kelas' => 'Semua Kelas',
                'audio_file' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3',
                'audio_title' => 'Audio 2 - Restaurant Order',
                'durasi' => '03:00',
                'transkrip' => "Waiter: Good afternoon, welcome to Green Garden Bistro. Table for two?\nCustomer: Yes, please. A table near the garden if possible.\nWaiter: Right this way. Here are your menus. Can I start you off with something to drink?\nCustomer: I will just have fresh orange juice with no ice, please.\nWaiter: Wonderful. Are you ready to order food or do you need a few more minutes?\nCustomer: What do you recommend for today's special?\nWaiter: Our grilled salmon with herb roasted potatoes is fantastic today.\nCustomer: That sounds delicious! I will have the grilled salmon, please.\nWaiter: Excellent choice. I will have that right out for you.",
                'tujuan_belajar' => [
                    'Memahami tata cara pemesanan menu makanan dan minuman restoran berbahasa Inggris',
                    'Mengidentifikasi ekspresi santun saat menanyakan rekomendasi chef',
                    'Menyelesaikan latihan pemahaman listening audio dengan tepat',
                ],
                'xp_reward' => 60,
                'status' => 'aktif',
            ]
        );

        $soals2 = [
            [
                'nomor_soal' => 1,
                'pertanyaan' => 'Where does the customer prefer to sit?',
                'pilihan_a' => 'Near the entrance door',
                'pilihan_b' => 'A table near the garden',
                'pilihan_c' => 'On the second floor balcony',
                'pilihan_d' => 'At the indoor bar counter',
                'jawaban_benar' => 'B',
                'pembahasan' => 'Pelanggan meminta: "A table near the garden if possible."',
            ],
            [
                'nomor_soal' => 2,
                'pertanyaan' => 'What drink did the customer order?',
                'pilihan_a' => 'Hot chocolate with cream',
                'pilihan_b' => 'Iced lemon tea',
                'pilihan_c' => 'Fresh orange juice with no ice',
                'pilihan_d' => 'Sparkling mineral water',
                'jawaban_benar' => 'C',
                'pembahasan' => 'Pelanggan memesan: "I will just have fresh orange juice with no ice, please."',
            ],
            [
                'nomor_soal' => 3,
                'pertanyaan' => 'What was today\'s special recommended by the waiter?',
                'pilihan_a' => 'Beef tenderloin steak',
                'pilihan_b' => 'Chicken Caesar salad',
                'pilihan_c' => 'Grilled salmon with herb roasted potatoes',
                'pilihan_d' => 'Seafood marinara pasta',
                'jawaban_benar' => 'C',
                'pembahasan' => 'Pelayan menjawab: "Our grilled salmon with herb roasted potatoes is fantastic today."',
            ],
            [
                'nomor_soal' => 4,
                'pertanyaan' => 'What is the name of the restaurant in the dialogue?',
                'pilihan_a' => 'Blue Ocean Bistro',
                'pilihan_b' => 'Green Garden Bistro',
                'pilihan_c' => 'Golden Palace Restaurant',
                'pilihan_d' => 'Sunny Valley Cafe',
                'jawaban_benar' => 'B',
                'pembahasan' => 'Pelayan menyambut di awal: "Good afternoon, welcome to Green Garden Bistro."',
            ],
        ];

        foreach ($soals2 as $soal) {
            ListeningSoal::updateOrCreate(
                ['modul_listening_id' => $modul2->id, 'nomor_soal' => $soal['nomor_soal']],
                $soal
            );
        }
    }
}
