<?php

namespace Database\Seeders;

use App\Models\FishingSoal;
use Illuminate\Database\Seeder;

class FishingGameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $soals = [
            // LEVEL 1: Danau Tenang (Mudah) - Basic Daily & Personality
            [
                'level' => 1,
                'difficulty' => 'mudah',
                'word' => 'GENEROUS',
                'part_of_speech' => 'Adjective (Kata Sifat)',
                'meaning' => 'Dermawan / Suka Memberi',
                'options' => [
                    'Dermawan / Suka Memberi',
                    'Pelit / Kikir',
                    'Pemarah',
                    'Pemberani',
                ],
                'explanation' => 'Kata sifat yang menggambarkan seseorang yang senang berbagi rezeki atau membantu orang lain tanpa pamrih.',
                'example_sentence' => 'Mr. Jack is a generous teacher who often buys books for his students.',
                'pull_energy' => 20,
            ],
            [
                'level' => 1,
                'difficulty' => 'mudah',
                'word' => 'PATIENT',
                'part_of_speech' => 'Adjective (Kata Sifat)',
                'meaning' => 'Sabar / Tenang Menunggu',
                'options' => [
                    'Sombong / Angkuh',
                    'Sabar / Tenang Menunggu',
                    'Cemas / Khawatir',
                    'Malas / Lamban',
                ],
                'explanation' => 'Kata sifat untuk orang yang mampu menahan diri, tidak mudah marah, dan sabar saat menghadapi kesulitan.',
                'example_sentence' => 'You need to be patient when learning a new foreign language.',
                'pull_energy' => 20,
            ],
            [
                'level' => 1,
                'difficulty' => 'mudah',
                'word' => 'DISCOVER',
                'part_of_speech' => 'Verb (Kata Kerja)',
                'meaning' => 'Menemukan Hal Baru',
                'options' => [
                    'Menyembunyikan Rahasia',
                    'Menghilangkan Jejak',
                    'Menemukan Hal Baru',
                    'Melupakan Masa Lalu',
                ],
                'explanation' => 'Kata kerja tindakan saat seseorang mencari dan berhasil menjumpai sesuatu yang sebelumnya tidak diketahui.',
                'example_sentence' => 'Scientists discover a new species of fish in the deep ocean.',
                'pull_energy' => 20,
            ],
            [
                'level' => 1,
                'difficulty' => 'mudah',
                'word' => 'HONEST',
                'part_of_speech' => 'Adjective (Kata Sifat)',
                'meaning' => 'Jujur / Berkata Benar',
                'options' => [
                    'Jujur / Berkata Benar',
                    'Bohong / Dusta',
                    'Curang / Tidak Adil',
                    'Licik / Banyak Akal',
                ],
                'explanation' => 'Kata sifat untuk perilaku yang lurus hati, dapat dipercaya, dan tidak berbohong.',
                'example_sentence' => 'An honest student will not cheat during the examination.',
                'pull_energy' => 20,
            ],
            [
                'level' => 1,
                'difficulty' => 'mudah',
                'word' => 'COURAGE',
                'part_of_speech' => 'Noun (Kata Benda)',
                'meaning' => 'Keberanian / Kegagahan',
                'options' => [
                    'Kelemahan Hati',
                    'Ketakutan Berlebih',
                    'Keberanian / Kegagahan',
                    'Kebencian Mendalam',
                ],
                'explanation' => 'Kata benda abstrak yang merujuk pada rasa berani dan tekad menghadapi rintangan atau rasa takut.',
                'example_sentence' => 'It takes a lot of courage to speak in public.',
                'pull_energy' => 20,
            ],

            // LEVEL 2: Sungai Biru (Mudah) - Actions & Movements
            [
                'level' => 2,
                'difficulty' => 'mudah',
                'word' => 'PRACTICE',
                'part_of_speech' => 'Verb (Kata Kerja)',
                'meaning' => 'Berlatih / Mempraktikkan',
                'options' => [
                    'Berlatih / Mempraktikkan',
                    'Berhenti Berusaha',
                    'Membatalkan Rencana',
                    'Mengabaikan Tugas',
                ],
                'explanation' => 'Kata kerja mengulang suatu aktivitas secara berkala untuk meningkatkan keahlian.',
                'example_sentence' => 'If you want to speak fluent English, you must practice daily.',
                'pull_energy' => 20,
            ],
            [
                'level' => 2,
                'difficulty' => 'mudah',
                'word' => 'ACHIEVE',
                'part_of_speech' => 'Verb (Kata Kerja)',
                'meaning' => 'Meraih / Mencapai Impian',
                'options' => [
                    'Gagal Total',
                    'Meraih / Mencapai Impian',
                    'Menyerah di Tengah Jalan',
                    'Menunda Pekerjaan',
                ],
                'explanation' => 'Kata kerja keberhasilan menggapai tujuan setelah berusaha keras.',
                'example_sentence' => 'She worked very hard to achieve her dream of becoming a doctor.',
                'pull_energy' => 20,
            ],
            [
                'level' => 2,
                'difficulty' => 'mudah',
                'word' => 'PROTECT',
                'part_of_speech' => 'Verb (Kata Kerja)',
                'meaning' => 'Melindungi / Menjaga Aman',
                'options' => [
                    'Merusak Lingkungan',
                    'Meninggalkan Sendiri',
                    'Melindungi / Menjaga Aman',
                    'Menyerang Musuh',
                ],
                'explanation' => 'Kata kerja menjaga seseorang atau sesuatu dari bahaya atau kerusakan.',
                'example_sentence' => 'We must protect our lake from plastic waste pollution.',
                'pull_energy' => 20,
            ],
            [
                'level' => 2,
                'difficulty' => 'mudah',
                'word' => 'DILIGENT',
                'part_of_speech' => 'Adjective (Kata Sifat)',
                'meaning' => 'Rajin / Tekun Belajar',
                'options' => [
                    'Rajin / Tekun Belajar',
                    'Malas Bekerja',
                    'Lalai / Teledor',
                    'Bosan / Jenuh',
                ],
                'explanation' => 'Kata sifat untuk orang yang sungguh-sungguh, giat, dan tidak mudah menyerah dalam bekerja atau belajar.',
                'example_sentence' => 'A diligent learner always completes assignments on time.',
                'pull_energy' => 20,
            ],
            [
                'level' => 2,
                'difficulty' => 'mudah',
                'word' => 'KINDNESS',
                'part_of_speech' => 'Noun (Kata Benda)',
                'meaning' => 'Kebaikan / Keramahan Hati',
                'options' => [
                    'Kekejaman',
                    'Keserakahan',
                    'Kebaikan / Keramahan Hati',
                    'Ketidakpedulian',
                ],
                'explanation' => 'Kata benda yang merujuk pada sifat atau perbuatan baik dan ramah terhadap sesama makhluk.',
                'example_sentence' => 'A small act of kindness can brighten someone’s whole day.',
                'pull_energy' => 20,
            ],

            // LEVEL 3: Muara Hijau (Mudah) - Daily Environment
            [
                'level' => 3,
                'difficulty' => 'mudah',
                'word' => 'EXPLORE',
                'part_of_speech' => 'Verb (Kata Kerja)',
                'meaning' => 'Menjelajahi / Meneliti',
                'options' => [
                    'Berdiam Diri',
                    'Menjelajahi / Meneliti',
                    'Tersesat di Hutan',
                    'Menghindari Petualangan',
                ],
                'explanation' => 'Kata kerja mengunjungi tempat baru untuk belajar dan mencari pengalaman.',
                'example_sentence' => 'Let us explore the island and discover rare tropical birds.',
                'pull_energy' => 20,
            ],
            [
                'level' => 3,
                'difficulty' => 'mudah',
                'word' => 'BRILLIANT',
                'part_of_speech' => 'Adjective (Kata Sifat)',
                'meaning' => 'Sangat Cerdas / Cemerlang',
                'options' => [
                    'Sangat Cerdas / Cemerlang',
                    'Biasa-Biasa Saja',
                    'Kusam / Gelap',
                    'Bodoh / Ceroboh',
                ],
                'explanation' => 'Kata sifat bermakna luar biasa pintar atau memiliki ide yang sangat cemerlang.',
                'example_sentence' => 'The young student gave a brilliant solution to the math puzzle.',
                'pull_energy' => 20,
            ],
            [
                'level' => 3,
                'difficulty' => 'mudah',
                'word' => 'CHALLENGE',
                'part_of_speech' => 'Noun (Kata Benda)',
                'meaning' => 'Tantangan Menarik',
                'options' => [
                    'Kegagalan Hidup',
                    'Tantangan Menarik',
                    'Kepastian Mutlak',
                    'Kenyamanan Santai',
                ],
                'explanation' => 'Kata benda untuk suatu hal yang menguji kemampuan, keberanian, atau ketahanan seseorang.',
                'example_sentence' => 'Catching the big fish was the greatest challenge of my journey.',
                'pull_energy' => 20,
            ],
            [
                'level' => 3,
                'difficulty' => 'mudah',
                'word' => 'SUPPORT',
                'part_of_speech' => 'Verb (Kata Kerja)',
                'meaning' => 'Mendukung / Membantu',
                'options' => [
                    'Mendukung / Membantu',
                    'Menjatuhkan Lawan',
                    'Mengabaikan Kawan',
                    'Menghalangi Jalan',
                ],
                'explanation' => 'Kata kerja memberi dorongan moril, bantuan, atau semangat kepada orang lain.',
                'example_sentence' => 'True friends always support each other in difficult moments.',
                'pull_energy' => 20,
            ],
            [
                'level' => 3,
                'difficulty' => 'mudah',
                'word' => 'VICTORY',
                'part_of_speech' => 'Noun (Kata Benda)',
                'meaning' => 'Kemenangan / Kejayaan',
                'options' => [
                    'Kekalahan Pahit',
                    'Kemenangan / Kejayaan',
                    'Kehancuran Bersama',
                    'Perpisahan Sedih',
                ],
                'explanation' => 'Kata benda keberhasilan memenangkan sebuah kompetisi atau pertandingan.',
                'example_sentence' => 'The team celebrated their glorious victory with pride.',
                'pull_energy' => 20,
            ],
        ];

        foreach ($soals as $item) {
            FishingSoal::updateOrCreate(
                [
                    'level' => $item['level'],
                    'word' => $item['word'],
                ],
                $item
            );
        }
    }
}
