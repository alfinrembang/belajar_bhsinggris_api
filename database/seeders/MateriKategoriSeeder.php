<?php

namespace Database\Seeders;

use App\Models\Materi;
use Illuminate\Database\Seeder;

class MateriKategoriSeeder extends Seeder
{
    /**
     * Run the database seeds for Grammar, Conversation, and Vocabulary.
     */
    public function run(): void
    {
        // =====================================================================
        // 1. MATERI KATEGORI: GRAMMAR
        // =====================================================================
        $materiGrammar = Materi::updateOrCreate(
            ['judul' => 'Mastering Present Tenses: Simple Present vs Present Continuous'],
            [
                'kategori' => 'Grammar',
                'tingkat_kelas' => 'Semua Kelas',
                'deskripsi_singkat' => 'Membedakan aksi kebiasaan/fakta umum dengan kejadian yang sedang berlangsung saat ini.',
                'penjelasan' => "Present Tenses digunakan untuk membicarakan kejadian masa kini, namun terbagi menjadi dua fokus utama:\n\n1. Simple Present Tense (Kebiasaan, Rutinitas, & Fakta Umum)\nRumus Verbal:\n(+) Subject + Verb 1 (s/es untuk He/She/It) + Object\n(-) Subject + do/does not + Verb 1 + Object\n(?) Do/Does + Subject + Verb 1?\nContoh: She drinks coffee every morning. / Water boils at 100°C.\nTime Signals: always, usually, often, sometimes, everyday, every week.\n\n2. Present Continuous Tense (Aktivitas yang Sedang Berlangsung Sekarang)\nRumus:\n(+) Subject + to be (am/is/are) + Verb-ing + Object\n(-) Subject + to be + not + Verb-ing + Object\n(?) To be + Subject + Verb-ing?\nContoh: They are studying in the library right now.\nTime Signals: now, right now, at the moment, currently, Look!, Listen!\n\nCatatan Penting (Stative Verbs):\nKata kerja yang menyatakan perasaan, kepemilikan, atau pemikiran (seperti: love, like, know, understand, believe, have) umumnya TIDAK memakai bentuk Verb-ing.\nContoh Benar: \"I understand the lesson.\" (Bukan: \"I am understanding the lesson.\")",
                'contoh_teks' => 'Sarah works as a graphic designer at a creative agency. Every weekday, she wakes up at 6:00 AM and drinks green tea before heading to the office. Usually, she designs branding logos on her computer. However, right now at this exact moment, Sarah is not designing; she is attending a project kickoff meeting with her international clients via Zoom. Her colleagues are taking notes while the team leader is presenting the project timeline.',
                'gambar_materi' => null,
                'audio_materi' => null,
                'xp_reward' => 50,
                'status' => 'aktif',
            ]
        );

        // Hapus soal lama untuk id materi ini jika ada (agar tidak duplikat saat re-seed)
        $materiGrammar->soals()->delete();

        $soalGrammar = [
            [
                'tipe_soal' => 'pilgan',
                'pertanyaan' => 'Listen! The baby ... in the next room right now.',
                'audio_soal' => null,
                'pilihan_a' => 'cries',
                'pilihan_b' => 'is crying',
                'pilihan_c' => 'cried',
                'pilihan_d' => 'was crying',
                'kunci_jawaban' => 'B',
                'pembahasan' => "Keterangan waktu 'right now' dan seruan 'Listen!' menandakan aktivitas sedang berlangsung saat ini, sehingga memakai Present Continuous Tense (is crying).",
            ],
            [
                'tipe_soal' => 'pilgan',
                'pertanyaan' => 'My father always ... the morning newspaper before going to work.',
                'audio_soal' => null,
                'pilihan_a' => 'read',
                'pilihan_b' => 'reads',
                'pilihan_c' => 'reading',
                'pilihan_d' => 'is reading',
                'kunci_jawaban' => 'B',
                'pembahasan' => "Subjek tunggal orang ketiga 'My father' (He) dengan frekuensi kebiasaan 'always' memerlukan kata kerja berakhiran -s (reads).",
            ],
            [
                'tipe_soal' => 'pilgan',
                'pertanyaan' => 'Why is the sentence "I am knowing the answer" grammatically incorrect?',
                'audio_soal' => null,
                'pilihan_a' => '"Know" is a stative verb that does not typically take continuous aspect',
                'pilihan_b' => 'The subject "I" cannot use auxiliary "am"',
                'pilihan_c' => 'The main verb should be placed in the past tense',
                'pilihan_d' => 'It requires the negative particle "does not"',
                'kunci_jawaban' => 'A',
                'pembahasan' => "'Know' adalah kata kerja keadaan (stative verb) yang menyatakan pemikiran, sehingga tidak lazim menggunakan bentuk -ing. Kalimat yang tepat: 'I know the answer'.",
            ],
            [
                'tipe_soal' => 'pilgan',
                'pertanyaan' => 'At the moment, our English teacher ... the grammar rules on the whiteboard.',
                'audio_soal' => null,
                'pilihan_a' => 'explains',
                'pilihan_b' => 'is explaining',
                'pilihan_c' => 'explained',
                'pilihan_d' => 'has explained',
                'kunci_jawaban' => 'B',
                'pembahasan' => "'At the moment' merupakan time signal khas Present Continuous Tense, yang dibentuk dengan to be (is) + Verb-ing (explaining).",
            ],
            [
                'tipe_soal' => 'pilgan',
                'pertanyaan' => 'Which sentence correctly demonstrates a general truth or scientific fact?',
                'audio_soal' => null,
                'pilihan_a' => 'The sun is rising in the east every morning',
                'pilihan_b' => 'The sun rises in the east',
                'pilihan_c' => 'The sun was rising in the east yesterday',
                'pilihan_d' => 'The sun will rise in the east right now',
                'kunci_jawaban' => 'B',
                'pembahasan' => "Fakta ilmiah atau kebenaran umum abadi selalu diungkapkan menggunakan Simple Present Tense: 'The sun rises in the east'.",
            ],
        ];

        foreach ($soalGrammar as $s) {
            $materiGrammar->soals()->create($s);
        }

        // =====================================================================
        // 2. MATERI KATEGORI: CONVERSATION
        // =====================================================================
        $materiConversation = Materi::updateOrCreate(
            ['judul' => 'Expressing Opinions, Agreement, and Polite Disagreement'],
            [
                'kategori' => 'Conversation',
                'tingkat_kelas' => 'Semua Kelas',
                'deskripsi_singkat' => 'Keahlian menyampaikan pendapat pribadi, menyetujui, dan menolak gagasan secara santun dan profesional.',
                'penjelasan' => "Dalam komunikasi bahasa Inggris, menyampaikan opini dan menanggapi ide orang lain membutuhkan frasa yang tepat agar percakapan tetap sopan (polite) dan efektif:\n\n1. Frasa Memberikan Pendapat (Giving Opinion):\n- \"In my opinion, ...\" (Menurut pendapat saya, ...)\n- \"From my point of view, ...\" (Dari sudut pandang saya, ...)\n- \"I strongly believe that ...\" (Saya sangat meyakini bahwa ...)\n- \"As far as I'm concerned, ...\" (Sejauh yang saya ketahui, ...)\n\n2. Frasa Menyetujui Pendapat (Expressing Agreement):\n- \"I completely agree with you.\" (Saya sangat setuju denganmu.)\n- \"You have a point there.\" (Kamu ada benarnya.)\n- \"That's exactly what I was thinking.\" (Tepat seperti yang saya pikirkan.)\n- \"No doubt about it.\" (Tidak diragukan lagi.)\n\n3. Frasa Menolak Pendapat Secara Santun (Polite Disagreement):\nHindari mengatakan kata kasar seperti \"You are wrong!\". Gunakan frasa diplomatis:\n- \"I see your point, but don't you think ...?\" (Saya paham poinmu, tapi tidakkah kamu rasa ...?)\n- \"I'm afraid I have a slightly different view.\" (Sepertinya saya memiliki pandangan yang sedikit berbeda.)\n- \"That might be true, however ...\" (Itu mungkin benar, namun ...)\n- \"I respectfully disagree because ...\" (Saya dengan hormat kurang sependapat karena ...)",
                'contoh_teks' => "Maya: \"Alex, have you seen the proposal for our school's green campus program? In my opinion, banning single-use plastic bottles in the canteen is the most effective first step.\"\nAlex: \"You have a good point there, Maya. It definitely reduces daily waste. However, from my perspective, we should also provide clean water refill stations first so students won't have trouble staying hydrated.\"\nMaya: \"I totally agree with you on that! If we provide refill stations alongside the policy, everyone will gladly support it.\"",
                'gambar_materi' => null,
                'audio_materi' => null,
                'xp_reward' => 50,
                'status' => 'aktif',
            ]
        );

        $materiConversation->soals()->delete();

        $soalConversation = [
            [
                'tipe_soal' => 'pilgan',
                'pertanyaan' => 'Maya says: "In my opinion, banning single-use plastic is the best step." What communicative function is Maya expressing?',
                'audio_soal' => null,
                'pilihan_a' => 'Asking for someone else\'s permission',
                'pilihan_b' => 'Giving a personal opinion',
                'pilihan_c' => 'Refusing a polite invitation',
                'pilihan_d' => 'Offering an apology',
                'kunci_jawaban' => 'B',
                'pembahasan' => "Frasa 'In my opinion' secara lugas digunakan untuk mengawali dan menyampaikan opini pribadi (giving a personal opinion).",
            ],
            [
                'tipe_soal' => 'pilgan',
                'pertanyaan' => 'Which phrase represents the MOST polite and professional way to express disagreement in a formal meeting?',
                'audio_soal' => null,
                'pilihan_a' => 'You are totally wrong!',
                'pilihan_b' => 'That makes no sense at all!',
                'pilihan_c' => 'I see your point, but I have a slightly different perspective.',
                'pilihan_d' => 'Be quiet, that idea is completely unusable.',
                'kunci_jawaban' => 'C',
                'pembahasan' => "'I see your point, but I have a slightly different perspective' adalah ungkapan diplomatis yang mengakui pandangan lawan bicara sebelum menyampaikan ketidaksetujuan secara santun.",
            ],
            [
                'tipe_soal' => 'pilgan',
                'pertanyaan' => "Rian: \"I believe online learning provides greater flexibility for modern students.\"\nDina: \"... It allows us to manage our study time and pace much better.\"\nWhich expression best completes Dina's strong agreement?",
                'audio_soal' => null,
                'pilihan_a' => 'I am afraid I disagree with you',
                'pilihan_b' => 'I couldn\'t agree more',
                'pilihan_c' => 'I don\'t think that is true',
                'pilihan_d' => 'Are you totally sure about that?',
                'kunci_jawaban' => 'B',
                'pembahasan' => "Dina mendukung pendapat Rian dengan penjelasan positif. Ungkapan 'I couldn't agree more' berarti 'Saya sangat setuju sekali'.",
            ],
            [
                'tipe_soal' => 'pilgan',
                'pertanyaan' => 'When a speaker in a conversation says "No doubt about it", what does it signify?',
                'audio_soal' => null,
                'pilihan_a' => 'They fully and confidently agree with the statement',
                'pilihan_b' => 'They are feeling very skeptical and doubtful',
                'pilihan_c' => 'They wish to change the discussion topic',
                'pilihan_d' => 'They did not hear the previous statement clearly',
                'kunci_jawaban' => 'A',
                'pembahasan' => "Ungkapan 'No doubt about it' berarti tidak ada keraguan sedikit pun, menandakan persetujuan penuh terhadap apa yang disampaikan.",
            ],
            [
                'tipe_soal' => 'listening',
                'pertanyaan' => 'According to the conversation between Maya and Alex, what essential facility does Alex suggest providing before banning plastic bottles?',
                'audio_soal' => null,
                'pilihan_a' => 'Closing all campus canteen stalls',
                'pilihan_b' => 'Providing clean drinking water refill stations',
                'pilihan_c' => 'Distributing free plastic bottles to everyone',
                'pilihan_d' => 'Increasing the prices of all sweetened beverages',
                'kunci_jawaban' => 'B',
                'pembahasan' => "Dalam dialog, Alex menyarankan: 'we should also provide clean water refill stations first so students won\'t have trouble staying hydrated'.",
            ],
        ];

        foreach ($soalConversation as $s) {
            $materiConversation->soals()->create($s);
        }

        // =====================================================================
        // 3. MATERI KATEGORI: VOCABULARY
        // =====================================================================
        $materiVocabulary = Materi::updateOrCreate(
            ['judul' => 'Essential Vocabulary: 21st Century Workplace & Career Skills'],
            [
                'kategori' => 'Vocabulary',
                'tingkat_kelas' => 'Semua Kelas',
                'deskripsi_singkat' => 'Kumpulan kosakata esensial dunia kerja, kolaborasi tim, dan keterampilan profesional abad ke-21.',
                'penjelasan' => "Menguasai kosakata profesional (professional & career vocabulary) sangat penting untuk mempersiapkan diri menghadapi wawancara kerja, komunikasi profesional, dan kolaborasi global:\n\n1. Core Workplace Competencies:\n- Collaboration (n): Kerja sama dan kolaborasi tim untuk mencapai tujuan bersama.\n- Adaptability (n): Kemampuan beradaptasi dengan cepat terhadap perubahan lingkungan/teknologi.\n- Deadline (n): Batas waktu akhir pengumpulan tugas atau proyek.\n- Problem-solving (n): Kemampuan menganalisis masalah dan menemukan solusi efektif.\n- Initiative (n): Prakarsa atau kemauan mengambil langkah mandiri tanpa harus selalu disuruh.\n\n2. Professional Verbs & Action Words:\n- Implement (v): Menerapkan atau mengeksekusi rencana menjadi tindakan nyata.\n- Negotiate (v): Berunding atau bernegosiasi untuk mencapai kesepakatan bersama.\n- Streamline (v): Menyederhanakan alur kerja agar lebih efisien dan hemat waktu.\n- Prioritize (v): Mengurutkan dan mengerjakan tugas berdasarkan tingkat kepentingannya.\n\n3. Useful Collocations:\n- \"Meet a deadline\" (Menepati tenggat waktu)\n- \"Overcome obstacles\" (Mengatasi rintangan/kendala)\n- \"Drive innovation\" (Mendorong terciptanya inovasi baru)\n- \"Constructive feedback\" (Masukan yang membangun untuk perbaikan diri)",
                'contoh_teks' => "In today's fast-paced digital era, employers value individuals who demonstrate strong adaptability and creative problem-solving. During an interview, Kevin explained how his team managed to streamline their workflow and meet a tight deadline on their mobile app development. By taking initiative and fostering open collaboration among software engineers and UI designers, the team successfully launched the application two weeks ahead of schedule and received overwhelming constructive feedback from early users.",
                'gambar_materi' => null,
                'audio_materi' => null,
                'xp_reward' => 50,
                'status' => 'aktif',
            ]
        );

        $materiVocabulary->soals()->delete();

        $soalVocabulary = [
            [
                'tipe_soal' => 'pilgan',
                'pertanyaan' => 'The word "streamline" in a workplace and business context means to ...',
                'audio_soal' => null,
                'pilihan_a' => 'make a process simpler, faster, and more efficient',
                'pilihan_b' => 'make a procedure complicated and hard to understand',
                'pilihan_c' => 'cancel an ongoing project due to lack of budget',
                'pilihan_d' => 'postpone important work until the next quarterly review',
                'kunci_jawaban' => 'A',
                'pembahasan' => "'Streamline' berarti menyederhanakan suatu alur proses kerja agar berjalan lebih cepat, terstruktur, dan efisien tanpa pemborosan waktu.",
            ],
            [
                'tipe_soal' => 'pilgan',
                'pertanyaan' => 'Which word best describes the readiness and ability to embark on new tasks independently without waiting for instructions?',
                'audio_soal' => null,
                'pilihan_a' => 'Hesitation',
                'pilihan_b' => 'Initiative',
                'pilihan_c' => 'Procrastination',
                'pilihan_d' => 'Reluctance',
                'kunci_jawaban' => 'B',
                'pembahasan' => "'Initiative' (inisiatif) adalah kemampuan mengambil langkah proaktif secara mandiri untuk menyelesaikan masalah atau memulai pekerjaan.",
            ],
            [
                'tipe_soal' => 'pilgan',
                'pertanyaan' => 'The project manager reminded the team that the strict ... for submitting the final proposal is Friday at 5:00 PM.',
                'audio_soal' => null,
                'pilihan_a' => 'obstacle',
                'pilihan_b' => 'deadline',
                'pilihan_c' => 'hesitation',
                'pilihan_d' => 'feedback',
                'kunci_jawaban' => 'B',
                'pembahasan' => "Batas tenggat waktu terakhir penyelesaian sebuah tugas dalam konteks profesional disebut 'deadline'.",
            ],
            [
                'tipe_soal' => 'pilgan',
                'pertanyaan' => 'What is the closest synonym of the noun "Adaptability"?',
                'audio_soal' => null,
                'pilihan_a' => 'Rigidity',
                'pilihan_b' => 'Inflexibility',
                'pilihan_c' => 'Flexibility',
                'pilihan_d' => 'Stubbornness',
                'kunci_jawaban' => 'C',
                'pembahasan' => "'Adaptability' (kemampuan beradaptasi/menyesuaikan diri) bersinonim dengan 'Flexibility' (fleksibilitas/kelenturan).",
            ],
            [
                'tipe_soal' => 'pilgan',
                'pertanyaan' => 'Helpful criticism or advice that is intended to facilitate improvement rather than just pointing out flaws is known as ...',
                'audio_soal' => null,
                'pilihan_a' => 'Destructive criticism',
                'pilihan_b' => 'Negative feedback',
                'pilihan_c' => 'Constructive feedback',
                'pilihan_d' => 'Hostile evaluation',
                'kunci_jawaban' => 'C',
                'pembahasan' => "Umpan balik yang bernilai membangun demi pengembangan dan perbaikan performa disebut 'Constructive feedback'.",
            ],
        ];

        foreach ($soalVocabulary as $s) {
            $materiVocabulary->soals()->create($s);
        }
    }
}
