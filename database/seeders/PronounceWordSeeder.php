<?php

namespace Database\Seeders;

use App\Models\PronounceWord;
use Illuminate\Database\Seeder;

class PronounceWordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $words = [
            // ==================== TINGKAT MUDAH ====================
            [
                'word' => 'Wednesday',
                'phonetic' => '/ˈwɛnzdeɪ/',
                'part_of_speech' => 'Noun (Kata Benda)',
                'meaning' => 'Hari Rabu',
                'difficulty' => 'mudah',
                'tips' => "Huruf 'd' pertama dan 'e' kedua bersifat silent (tidak dibaca). Lafalkan: 'WENZ-day'.",
                'example_sentence' => 'We have an English quiz this Wednesday.',
            ],
            [
                'word' => 'Comfortable',
                'phonetic' => '/ˈkʌmftəbl/',
                'part_of_speech' => 'Adjective (Kata Sifat)',
                'meaning' => 'Nyaman',
                'difficulty' => 'mudah',
                'tips' => "Jangan dibaca 4 suku kata 'com-for-ta-ble'. Lafalkan 3 suku kata: 'KUMF-tuh-bl'.",
                'example_sentence' => 'This chair is very comfortable to sit on.',
            ],
            [
                'word' => 'Island',
                'phonetic' => '/ˈaɪlənd/',
                'part_of_speech' => 'Noun (Kata Benda)',
                'meaning' => 'Pulau',
                'difficulty' => 'mudah',
                'tips' => "Huruf 's' adalah silent letter. Jangan sebut 'is-land', melainkan 'EYE-luhnd'.",
                'example_sentence' => 'Bali is a famous tropical island in Indonesia.',
            ],
            [
                'word' => 'Muscle',
                'phonetic' => '/ˈmʌsəl/',
                'part_of_speech' => 'Noun (Kata Benda)',
                'meaning' => 'Otot',
                'difficulty' => 'mudah',
                'tips' => "Huruf 'c' tidak dibaca. Lafalkan persis seperti kata kerang 'mussel': 'MUH-sl'.",
                'example_sentence' => 'Drinking milk helps build strong muscle.',
            ],
            [
                'word' => 'Chocolate',
                'phonetic' => '/ˈtʃɒklət/',
                'part_of_speech' => 'Noun (Kata Benda)',
                'meaning' => 'Cokelat',
                'difficulty' => 'mudah',
                'tips' => "Hanya 2 suku kata: 'CHOK-lit', bukan 'cho-co-late'.",
                'example_sentence' => 'She loves hot chocolate on rainy days.',
            ],
            [
                'word' => 'Listen',
                'phonetic' => '/ˈlɪsən/',
                'part_of_speech' => 'Verb (Kata Kerja)',
                'meaning' => 'Mendengarkan',
                'difficulty' => 'mudah',
                'tips' => "Huruf 't' tidak dibunyikan sama sekali. Lafalkan: 'LIH-sn'.",
                'example_sentence' => 'Please listen carefully to the speaker.',
            ],

            // ==================== TINGKAT SEDANG ====================
            [
                'word' => 'Vehicle',
                'phonetic' => '/ˈviːəkl/',
                'part_of_speech' => 'Noun (Kata Benda)',
                'meaning' => 'Kendaraan',
                'difficulty' => 'sedang',
                'tips' => "Huruf 'h' tidak dibaca. Lafalkan: 'VEE-uh-kl', bukan 've-hi-kel'.",
                'example_sentence' => 'Electric vehicle is good for the environment.',
            ],
            [
                'word' => 'Receipt',
                'phonetic' => '/rɪˈsiːt/',
                'part_of_speech' => 'Noun (Kata Benda)',
                'meaning' => 'Struk Pembayaran / Bukti Kasir',
                'difficulty' => 'sedang',
                'tips' => "Huruf 'p' adalah silent letter. Lafalkan: 'rih-SEET'.",
                'example_sentence' => 'Always keep your purchase receipt as proof.',
            ],
            [
                'word' => 'Doubt',
                'phonetic' => '/daʊt/',
                'part_of_speech' => 'Noun / Verb',
                'meaning' => 'Ragu / Keraguan',
                'difficulty' => 'sedang',
                'tips' => "Huruf 'b' tidak dibaca. Lafalkan berima dengan 'out': 'DOWT'.",
                'example_sentence' => 'There is no doubt that you will succeed.',
            ],
            [
                'word' => 'Recipe',
                'phonetic' => '/ˈrɛsəpi/',
                'part_of_speech' => 'Noun (Kata Benda)',
                'meaning' => 'Resep Masakan',
                'difficulty' => 'sedang',
                'tips' => "Huruf 'e' terakhir dibaca 'ee'. Lafalkan 3 suku kata: 'REH-suh-pee'.",
                'example_sentence' => 'My grandmother shared her secret pancake recipe.',
            ],
            [
                'word' => 'Chaos',
                'phonetic' => '/ˈkeɪɒs/',
                'part_of_speech' => 'Noun (Kata Benda)',
                'meaning' => 'Kekacauan Total',
                'difficulty' => 'sedang',
                'tips' => "Huruf 'ch' dibaca seperti huruf 'k'. Lafalkan: 'KAY-oss'.",
                'example_sentence' => 'The sudden rain caused chaos in the traffic.',
            ],
            [
                'word' => 'Subtle',
                'phonetic' => '/ˈsʌtl/',
                'part_of_speech' => 'Adjective (Kata Sifat)',
                'meaning' => 'Halus / Samar',
                'difficulty' => 'sedang',
                'tips' => "Huruf 'b' tidak dibaca. Lafalkan mirip 'settle': 'SUH-tl'.",
                'example_sentence' => 'There was a subtle smile on her face.',
            ],

            // ==================== TINGKAT SULIT ====================
            [
                'word' => 'Queue',
                'phonetic' => '/kjuː/',
                'part_of_speech' => 'Noun / Verb',
                'meaning' => 'Antrean / Mengantre',
                'difficulty' => 'sulit',
                'tips' => "Meskipun terdiri dari 5 huruf, pelafalannya persis seperti menyebut huruf 'Q'.",
                'example_sentence' => 'Please stand in a queue politely.',
            ],
            [
                'word' => 'Choir',
                'phonetic' => '/ˈkwaɪər/',
                'part_of_speech' => 'Noun (Kata Benda)',
                'meaning' => 'Paduan Suara',
                'difficulty' => 'sulit',
                'tips' => "Huruf 'ch' dilafalkan seperti 'kw'. Lafalkan: 'KWY-er'.",
                'example_sentence' => 'The school choir sang beautifully on graduation day.',
            ],
            [
                'word' => 'Entrepreneur',
                'phonetic' => '/ˌɒntrəprəˈnɜːr/',
                'part_of_speech' => 'Noun (Kata Benda)',
                'meaning' => 'Wirausahawan / Pengusaha',
                'difficulty' => 'sulit',
                'tips' => "Berasal dari bahasa Perancis. Lafalkan: 'on-truh-pruh-NUR'.",
                'example_sentence' => 'He became a successful tech entrepreneur at young age.',
            ],
            [
                'word' => 'Genre',
                'phonetic' => '/ˈʒɑːnrə/',
                'part_of_speech' => 'Noun (Kata Benda)',
                'meaning' => 'Aliran / Kategori Seni',
                'difficulty' => 'sulit',
                'tips' => "Awalannya berbunyi desis 'zh' lembut. Lafalkan: 'ZHON-ruh'.",
                'example_sentence' => 'Science fiction is my favorite book genre.',
            ],
            [
                'word' => 'Colonel',
                'phonetic' => '/ˈkɜːrnəl/',
                'part_of_speech' => 'Noun (Kata Benda)',
                'meaning' => 'Kolonel (Pangkat Militer)',
                'difficulty' => 'sulit',
                'tips' => "Jangan baca 'co-lo-nel'! Lafalkan persis seperti biji jagung 'kernel': 'KUR-nl'.",
                'example_sentence' => 'The colonel gave instructions to the platoon.',
            ],
            [
                'word' => 'Vocabulary',
                'phonetic' => '/vəˈkæbjələri/',
                'part_of_speech' => 'Noun (Kata Benda)',
                'meaning' => 'Perbendaharaan Kosakata',
                'difficulty' => 'sulit',
                'tips' => "Tekankan pada suku kata kedua: 'vuh-KAB-yuh-lair-ee'.",
                'example_sentence' => 'Reading books regularly expands your English vocabulary.',
            ],
        ];

        foreach ($words as $w) {
            PronounceWord::updateOrCreate(['word' => $w['word']], $w);
        }
    }
}
