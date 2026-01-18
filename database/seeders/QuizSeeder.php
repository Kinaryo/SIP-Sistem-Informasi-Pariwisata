<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Support\Str;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // Quiz 1: Hewan & Tumbuhan Papua & Maluku
        // =========================
        $quiz1 = Quiz::create([
            'title' => 'Hewan dan Tumbuhan Endemik',
            'slug' => Str::slug('Hewan dan Tumbuhan Endemik'),
            'description' => 'Tes pengetahuanmu tentang hewan dan tumbuhan.',
            'is_active' => true,
        ]);

        $questions1 = [
            [
                'question' => 'Burung cenderawasih yang terkenal di Papua dikenal dengan sebutan?',
                'options' => ['Cendrawasih Merah', 'Kakatua', 'Merpati', 'Elang Jawa'],
                'correct_answer' => 'Cendrawasih Merah',
            ],
            [
                'question' => 'Tumbuhan pala, salah satu komoditas terkenal Maluku, termasuk dalam genus?',
                'options' => ['Myristica', 'Mangifera', 'Coffea', 'Citrus'],
                'correct_answer' => 'Myristica',
            ],
            [
                'question' => 'Kuskus adalah hewan marsupial yang banyak ditemukan di?',
                'options' => ['Papua', 'Sumatra', 'Bali', 'Kalimantan'],
                'correct_answer' => 'Papua',
            ],
            [
                'question' => 'Tumbuhan sagu merupakan sumber makanan pokok di Papua. Bagian yang dimanfaatkan adalah?',
                'options' => ['Pohon', 'Daun', 'Umbi', 'Buah'],
                'correct_answer' => 'Umbi',
            ],
            [
                'question' => 'Burung Maleo yang endemik di Sulawesi memiliki ciri khas?',
                'options' => ['Telur dikubur di pasir panas', 'Bersarang di pohon tinggi', 'Terbang malam', 'Berenang di sungai'],
                'correct_answer' => 'Telur dikubur di pasir panas',
            ],
            [
                'question' => 'Burung paruh bengkok berwarna merah dan hijau yang banyak di Maluku disebut?',
                'options' => ['Nuri Maluku', 'Cenderawasih', 'Kakatua', 'Merpati Hias'],
                'correct_answer' => 'Nuri Maluku',
            ],
            [
                'question' => 'Kopi Papua termasuk varietas?',
                'options' => ['Arabika', 'Robusta', 'Liberika', 'Excelsa'],
                'correct_answer' => 'Arabika',
            ],
            [
                'question' => 'Kayu gaharu banyak ditemukan di Maluku dan Papua. Gaharu digunakan untuk?',
                'options' => ['Minyak wangi', 'Makanan', 'Pupuk', 'Obat sakit kepala'],
                'correct_answer' => 'Minyak wangi',
            ],
            [
                'question' => 'Ular sanca terbesar di Papua disebut?',
                'options' => ['Sanca Papua', 'Python Reticulatus', 'Anaconda', 'Cobra'],
                'correct_answer' => 'Sanca Papua',
            ],
            [
                'question' => 'Buah merah atau pandan merah yang endemik Papua memiliki kandungan?',
                'options' => ['Beta-karoten tinggi', 'Protein tinggi', 'Karbohidrat tinggi', 'Kalsium tinggi'],
                'correct_answer' => 'Beta-karoten tinggi',
            ],
        ];

        foreach($questions1 as $q){
            QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'question' => $q['question'],
                'options' => $q['options'],
                'correct_answer' => $q['correct_answer'],
            ]);
        }

        // =========================
        // Quiz 2: Lagu-lagu Nasional Indonesia
        // =========================
        $quiz2 = Quiz::create([
            'title' => 'Lagu-lagu Nasional Indonesia',
            'slug' => Str::slug('Lagu-lagu Nasional Indonesia'),
            'description' => 'Uji pengetahuanmu tentang lagu-lagu nasional dan penciptanya.',
            'is_active' => true,
        ]);

        $questions2 = [
            [
                'question' => 'Lagu kebangsaan Indonesia adalah?',
                'options' => ['Indonesia Raya', 'Halo-Halo Bandung', 'Bagimu Negeri', 'Satu Nusa Satu Bangsa'],
                'correct_answer' => 'Indonesia Raya',
            ],
            [
                'question' => 'Pencipta lagu Indonesia Raya adalah?',
                'options' => ['Wage Rudolf Supratman', 'Ismail Marzuki', 'Cornel Simanjuntak', 'Ibu Soed'],
                'correct_answer' => 'Wage Rudolf Supratman',
            ],
            [
                'question' => 'Lagu “Bagimu Negeri” diciptakan oleh?',
                'options' => ['R. Kusbini', 'Wage Rudolf Supratman', 'Ibu Soed', 'Ismail Marzuki'],
                'correct_answer' => 'R. Kusbini',
            ],
            [
                'question' => 'Lagu “Halo-Halo Bandung” menceritakan?',
                'options' => ['Rindu pada Bandung setelah perang', 'Kemerdekaan Indonesia', 'Keindahan alam Indonesia', 'Perjuangan Pahlawan Nasional'],
                'correct_answer' => 'Rindu pada Bandung setelah perang',
            ],
            [
                'question' => 'Lagu “Tanah Airku” liriknya menceritakan?',
                'options' => ['Cinta tanah air Indonesia', 'Perjuangan kemerdekaan', 'Kehidupan petani', 'Perjalanan laut Nusantara'],
                'correct_answer' => 'Cinta tanah air Indonesia',
            ],
            [
                'question' => 'Pencipta lagu “Indonesia Pusaka” adalah?',
                'options' => ['Ismail Marzuki', 'Wage Rudolf Supratman', 'Cornel Simanjuntak', 'R. Kusbini'],
                'correct_answer' => 'Ismail Marzuki',
            ],
            [
                'question' => 'Lagu “Satu Nusa Satu Bangsa” memiliki tema?',
                'options' => ['Persatuan Indonesia', 'Kemerdekaan Indonesia', 'Kehidupan nelayan', 'Pahlawan Nasional'],
                'correct_answer' => 'Persatuan Indonesia',
            ],
            [
                'question' => 'Lagu “Indonesia Pusaka” biasanya dinyanyikan saat?',
                'options' => ['Upacara bendera', 'Acara pernikahan', 'Pertemuan keluarga', 'Festival musik'],
                'correct_answer' => 'Upacara bendera',
            ],
            [
                'question' => 'Lagu “Halo-Halo Bandung” dipopulerkan saat peristiwa?',
                'options' => ['Perang kemerdekaan 1945', 'Proklamasi kemerdekaan 1945', 'Revolusi industri', 'Peristiwa Tragedi 1965'],
                'correct_answer' => 'Perang kemerdekaan 1945',
            ],
            [
                'question' => 'Lagu “Bagimu Negeri” sering dinyanyikan pada?',
                'options' => ['Hari kemerdekaan', 'Hari libur nasional', 'Hari pendidikan', 'Hari anak-anak'],
                'correct_answer' => 'Hari kemerdekaan',
            ],
        ];

        foreach($questions2 as $q){
            QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'question' => $q['question'],
                'options' => $q['options'],
                'correct_answer' => $q['correct_answer'],
            ]);
        }
    }
}
