<?php

namespace Database\Seeders;

use App\Models\SentenceSoal;
use Illuminate\Database\Seeder;

class SentenceSoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $soals = [
            // Level 1 / Mudah
            [
                'level' => 1,
                'difficulty' => 'mudah',
                'sentence' => 'I drink fresh milk every morning.',
                'meaning' => 'Saya minum susu segar setiap pagi.',
                'tenses_category' => 'Simple Present Tense',
                'grammar_tips' => 'Gunakan Verb 1 (drink) untuk kebiasaan sehari-hari.',
                'points' => 100,
            ],
            [
                'level' => 1,
                'difficulty' => 'mudah',
                'sentence' => 'She reads an interesting English book.',
                'meaning' => 'Dia membaca buku bahasa Inggris yang menarik.',
                'tenses_category' => 'Simple Present Tense',
                'grammar_tips' => 'Tambahkan akhiran -s pada kata kerja (reads) untuk subjek tunggal She.',
                'points' => 100,
            ],
            [
                'level' => 1,
                'difficulty' => 'mudah',
                'sentence' => 'We play football in the afternoon.',
                'meaning' => 'Kami bermain sepak bola di sore hari.',
                'tenses_category' => 'Simple Present Tense',
                'grammar_tips' => 'Subjek jamak We menggunakan kata kerja bentuk dasar tanpa akhiran -s.',
                'points' => 100,
            ],
            [
                'level' => 1,
                'difficulty' => 'mudah',
                'sentence' => 'The sun rises in the east.',
                'meaning' => 'Matahari terbit di sebelah timur.',
                'tenses_category' => 'Simple Present Tense',
                'grammar_tips' => 'Fakta umum (General Truth) selalu menggunakan Simple Present Tense.',
                'points' => 100,
            ],
            [
                'level' => 1,
                'difficulty' => 'mudah',
                'sentence' => 'My mother cooks delicious chicken soup.',
                'meaning' => 'Ibu saya memasak sup ayam yang lezat.',
                'tenses_category' => 'Simple Present Tense',
                'grammar_tips' => 'Subjek tunggal My mother menggunakan akhiran -s pada kata kerja (cooks).',
                'points' => 100,
            ],
            [
                'level' => 1,
                'difficulty' => 'mudah',
                'sentence' => 'Cats like to sleep all day.',
                'meaning' => 'Kucing suka tidur sepanjang hari.',
                'tenses_category' => 'Simple Present Tense',
                'grammar_tips' => 'Kata like diikuti to + infinitive (to sleep).',
                'points' => 100,
            ],
            [
                'level' => 1,
                'difficulty' => 'mudah',
                'sentence' => 'He speaks English very fluently.',
                'meaning' => 'Dia berbicara bahasa Inggris dengan sangat lancar.',
                'tenses_category' => 'Simple Present Tense',
                'grammar_tips' => 'Fluently adalah adverb (kata keterangan cara) yang menjelaskan kata kerja speaks.',
                'points' => 100,
            ],
            [
                'level' => 1,
                'difficulty' => 'mudah',
                'sentence' => 'Birds sing sweetly in the morning.',
                'meaning' => 'Burung-burung bernyanyi merdu di pagi hari.',
                'tenses_category' => 'Simple Present Tense',
                'grammar_tips' => 'Subjek jamak Birds berpasangan dengan Verb 1 dasar (sing).',
                'points' => 100,
            ],
            [
                'level' => 1,
                'difficulty' => 'mudah',
                'sentence' => 'They live in a beautiful house.',
                'meaning' => 'Mereka tinggal di rumah yang indah.',
                'tenses_category' => 'Simple Present Tense',
                'grammar_tips' => 'Kata sifat beautiful diletakkan sebelum kata benda house.',
                'points' => 100,
            ],
            [
                'level' => 1,
                'difficulty' => 'mudah',
                'sentence' => 'The train arrives on time.',
                'meaning' => 'Kereta api tiba tepat waktu.',
                'tenses_category' => 'Simple Present Tense',
                'grammar_tips' => 'On time adalah ungkapan yang berarti tepat pada waktunya.',
                'points' => 100,
            ],

            // Level 2 / Sedang
            [
                'level' => 2,
                'difficulty' => 'sedang',
                'sentence' => 'They are studying English in the classroom.',
                'meaning' => 'Mereka sedang belajar bahasa Inggris di dalam kelas.',
                'tenses_category' => 'Present Continuous',
                'grammar_tips' => 'Gunakan to be (are) + Verb-ing untuk kegiatan yang sedang berlangsung saat ini.',
                'points' => 120,
            ],
            [
                'level' => 2,
                'difficulty' => 'sedang',
                'sentence' => 'He is writing a letter to his friend.',
                'meaning' => 'Dia sedang menulis surat untuk temannya.',
                'tenses_category' => 'Present Continuous',
                'grammar_tips' => 'Subjek tunggal He berpasangan dengan to be is sebelum Verb-ing.',
                'points' => 120,
            ],
            [
                'level' => 2,
                'difficulty' => 'sedang',
                'sentence' => 'We went to the beach yesterday.',
                'meaning' => 'Kami pergi ke pantai kemarin.',
                'tenses_category' => 'Simple Past Tense',
                'grammar_tips' => 'Gunakan Verb 2 (went) untuk peristiwa yang sudah selesai di masa lampau.',
                'points' => 120,
            ],
            [
                'level' => 2,
                'difficulty' => 'sedang',
                'sentence' => 'She was listening to music while cooking.',
                'meaning' => 'Dia sedang mendengarkan musik saat memasak.',
                'tenses_category' => 'Past Continuous',
                'grammar_tips' => 'Kata penghubung while sering menghubungkan dua kegiatan yang berlangsung bersamaan.',
                'points' => 120,
            ],
            [
                'level' => 2,
                'difficulty' => 'sedang',
                'sentence' => 'My teacher gave us a helpful assignment.',
                'meaning' => 'Guru saya memberi kami tugas yang bermanfaat.',
                'tenses_category' => 'Simple Past Tense',
                'grammar_tips' => 'Gave adalah bentuk lampau (V2) dari kata kerja give.',
                'points' => 120,
            ],
            [
                'level' => 2,
                'difficulty' => 'sedang',
                'sentence' => 'You must submit the homework by tomorrow.',
                'meaning' => 'Kamu harus mengumpulkan pekerjaan rumah sebelum besok.',
                'tenses_category' => 'Modal Verbs',
                'grammar_tips' => 'Modal verb must selalu diikuti kata kerja bentuk pertama murni (submit).',
                'points' => 120,
            ],
            [
                'level' => 2,
                'difficulty' => 'sedang',
                'sentence' => 'The library is closed on public holidays.',
                'meaning' => 'Perpustakaan tutup pada hari libur nasional.',
                'tenses_category' => 'Passive Voice',
                'grammar_tips' => 'Bentuk pasif: is + closed (V3).',
                'points' => 120,
            ],
            [
                'level' => 2,
                'difficulty' => 'sedang',
                'sentence' => 'He has lived in Jakarta for ten years.',
                'meaning' => 'Dia telah tinggal di Jakarta selama sepuluh tahun.',
                'tenses_category' => 'Present Perfect Tense',
                'grammar_tips' => 'Present Perfect menggunakan has + V3 (lived) bersama durasi for.',
                'points' => 120,
            ],
            [
                'level' => 2,
                'difficulty' => 'sedang',
                'sentence' => 'They will visit their grandmother next weekend.',
                'meaning' => 'Mereka akan mengunjungi nenek mereka akhir pekan depan.',
                'tenses_category' => 'Simple Future Tense',
                'grammar_tips' => 'Bentuk masa depan: will + Verb 1 (visit).',
                'points' => 120,
            ],
            [
                'level' => 2,
                'difficulty' => 'sedang',
                'sentence' => 'She bought a new laptop for school.',
                'meaning' => 'Dia membeli sebuah laptop baru untuk sekolah.',
                'tenses_category' => 'Simple Past Tense',
                'grammar_tips' => 'Bought adalah bentuk lampau tidak beraturan (irregular verb) dari buy.',
                'points' => 120,
            ],

            // Level 3 / Sulit
            [
                'level' => 3,
                'difficulty' => 'sulit',
                'sentence' => 'I bought a new motorcycle two days ago.',
                'meaning' => 'Saya membeli sepeda motor baru dua hari yang lalu.',
                'tenses_category' => 'Simple Past Tense',
                'grammar_tips' => 'Keterangan waktu lampau ago selalu berpasangan dengan Simple Past Tense.',
                'points' => 150,
            ],
            [
                'level' => 3,
                'difficulty' => 'sulit',
                'sentence' => 'Although it was raining heavily, we arrived on time.',
                'meaning' => 'Meskipun hujan lebat, kami tiba tepat waktu.',
                'tenses_category' => 'Adverbial Clause',
                'grammar_tips' => 'Konjungsi Although digunakan untuk menyatakan pertentangan kondisi.',
                'points' => 150,
            ],
            [
                'level' => 3,
                'difficulty' => 'sulit',
                'sentence' => 'If you practice speaking English every day, you will improve.',
                'meaning' => 'Jika kamu berlatih berbicara bahasa Inggris setiap hari, kamu akan berkembang.',
                'tenses_category' => 'Conditional Sentence Type 1',
                'grammar_tips' => 'If clause menggunakan Simple Present, klausa utama menggunakan will + V1.',
                'points' => 150,
            ],
            [
                'level' => 3,
                'difficulty' => 'sulit',
                'sentence' => 'The mechanic successfully repaired the car that broke down yesterday.',
                'meaning' => 'Montir berhasil memperbaiki mobil yang rusak kemarin.',
                'tenses_category' => 'Relative Clause',
                'grammar_tips' => 'Klausa penjelas that broke down yesterday menjelaskan objek the car.',
                'points' => 150,
            ],
            [
                'level' => 3,
                'difficulty' => 'sulit',
                'sentence' => 'Students who work diligently will achieve their highest dreams.',
                'meaning' => 'Siswa yang berusaha dengan rajin akan meraih impian tertingginya.',
                'tenses_category' => 'Relative Clause',
                'grammar_tips' => 'Pronoun who merujuk pada subjek manusia (Students).',
                'points' => 150,
            ],
            [
                'level' => 3,
                'difficulty' => 'sulit',
                'sentence' => 'Before leaving the classroom, please make sure to turn off lights.',
                'meaning' => 'Sebelum meninggalkan kelas, tolong pastikan untuk mematikan lampu.',
                'tenses_category' => 'Imperative & Gerund',
                'grammar_tips' => 'Preposisi before diikuti bentuk Gerund (leaving).',
                'points' => 150,
            ],
            [
                'level' => 3,
                'difficulty' => 'sulit',
                'sentence' => 'Technology helps us communicate with people from around the world.',
                'meaning' => 'Teknologi membantu kita berkomunikasi dengan orang-orang dari penjuru dunia.',
                'tenses_category' => 'Complex Sentence',
                'grammar_tips' => 'Kata kerja help dapat diikuti bare infinitive (communicate).',
                'points' => 150,
            ],
            [
                'level' => 3,
                'difficulty' => 'sulit',
                'sentence' => 'The smart student solved the difficult math problem very quickly.',
                'meaning' => 'Siswa pintar itu memecahkan soal matematika sulit tersebut dengan sangat cepat.',
                'tenses_category' => 'Simple Past Tense',
                'grammar_tips' => 'Solved adalah bentuk lampau dari kata kerja beraturan solve.',
                'points' => 150,
            ],
        ];

        foreach ($soals as $item) {
            SentenceSoal::updateOrCreate(
                ['sentence' => $item['sentence']],
                $item
            );
        }
    }
}
