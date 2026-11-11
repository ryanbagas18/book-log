<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\LibraryBook;
use Illuminate\Database\Seeder;

class LibraryCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $genres = collect([
            'Fiction',
            'Non-Fiction',
            'Self-Improvement',
            'Children',
            'Comics',
            'Religion & Spirituality',
        ])->mapWithKeys(fn ($name) => [$name => Genre::firstOrCreate(['name' => $name])]);

        $books = [
            ['Laskar Pelangi', 'Andrea Hirata', 'Fiction', 'Indonesian', 529, 100000, 5000, 3,
                'Kisah sepuluh anak di SD Muhamadiyah Belitung Timur yang berjuang mengejar pendidikan di bawah gurunya yang luar biasa, pak Maeda, dengan semangat "Laskar Pelangi".'],
            ['Bumi Manusia', 'Pramoedya Ananta Toer', 'Fiction', 'Indonesian', 535, 120000, 6000, 2,
                'Minke, pemuda pribumi terpelajar di Hindia Belanda, bertemu Annelies dan membuka mata terhadap feodalisme serta kolonialisme di abad ke-19.'],
            ['Negeri 5 Menara', 'A. Fuadi', 'Fiction', 'Indonesian', 403, 90000, 4500, 4,
                'Enam santri dari Pondok Pesantren Al-Ma\'hadul Huda di Jawa Timur menempuh pendidikan Islam dan bercita-cita masuk kampus ternama di Tanah Air.'],
            ['Saman', 'Ayu Utami', 'Fiction', 'Indonesian', 352, 80000, 4000, 1,
                'Empat tokoh di pesisir Sumatera — Saman si pemimpin agama, Cok Sakti sang seniman, Syarif sang intelektual, dan Arni si perempuan tegar — dalam satu generasi yang memberontak.'],
            ['Supernova', 'Dee Lestari', 'Fiction', 'Indonesian', 448, 95000, 5000, 0,
                'Kisah cinta dan pencarian jati diri Yuda, Diva, dan Abe yang saling terhubung oleh mimpi, spiritualitas, dan sebuah gerhana supernova.'],
            ['Filosofi Teras', 'Henry Manampiring', 'Self-Improvement', 'Indonesian', 280, 75000, 3500, 5,
                'Panduan membangun mental tangguh ala filsuf Stoik Marcus Aurelius: cara menghadapi masalah dan mempertahankan ketenangan dalam hidup modern.'],
            ['Atomic Habits', 'James Clear', 'Self-Improvement', 'English', 320, 110000, 5000, 3,
                'Sistem praktis untuk membangun kebiasaan baik dan menyingkirkan yang buruk melalui perubahan kecil yang berlipat ganda.'],
            ['The Psychology of Money', 'Morgan Housel', 'Non-Fiction', 'English', 256, 105000, 5000, 2,
                'Pelajaran keuangan yang tidak diajarkan di sekolah: mengapa perilaku kita terhadap uang lebih menentukan hasil daripada intelek.'],
            ['Sapiens', 'Yuval Noah Harari', 'Non-Fiction', 'English', 443, 130000, 6000, 1,
                'Sejarah singkat umat manusia: bagaimana Homo sapiens menguasai planet melalui fiksi bersama, pertanian, dan imajinasi kolektif.'],
            ['Rich Dad Poor Dad', 'Robert Kiyosaki', 'Non-Fiction', 'English', 336, 100000, 4500, 4,
                'Perbandingan pola pikir dua figur ayah — si kaya dan si miskin — tentang uang, aset, utang, dan cara berpikir untuk meraih kebebasan finansial.'],
            ['Mangga Kecil', 'Candra Rimbun', 'Children', 'Indonesian', 48, 50000, 2000, 6,
                'Dongeng rakyat tentang kancil cerdik dan persahabatan di hutan Mangga Kecil, penuh pesan moral untuk anak-anak.'],
            ['Sang Pemimpi', 'Andrea Hirata', 'Children', 'Indonesian', 288, 85000, 4000, 2,
                'Lanjutan kisah Andre, sang pemimpi dari Laskar Pelangi, yang tetap berjuang meraih cita-cita dan cinta di Belitung.'],
            ['One Piece Vol. 1', 'Eiichiro Oda', 'Comics', 'Japanese', 192, 60000, 3000, 5,
                'Monkey D. Luffy dan Bajak Laut Topi Jerami memulai petualangan mencari One Piece, harta karun legendaris di Grand Line.'],
            ['Naruto Vol. 1', 'Masashi Kishimoto', 'Comics', 'Japanese', 192, 55000, 3000, 3,
                'Naruto Uzumaki, jinchuriki rubah ekor yang dikucilkan desa, bermimpi menjadi Hokage paling hebat di Konoha.'],
            ['The Alchemist', 'Paulo Coelho', 'Religion & Spirituality', 'English', 208, 90000, 4000, 2,
                'Petualangan Santiago, seorang penggembala Spanyol, melintasi gurun mencari harta karunnya dan menemukan makna hidup sesungguhnya.'],
        ];

        foreach ($books as [$title, $author, $genre, $language, $pages, $deposit, $daily, $stock, $synopsis]) {
            LibraryBook::updateOrCreate(
                ['title' => $title],
                [
                    'author' => $author,
                    'genre_id' => $genres[$genre]->id,
                    'language' => $language,
                    'pages' => $pages,
                    'synopsis' => $synopsis,
                    'goodreads_url' => 'https://www.goodreads.com/search?q=' . urlencode($title . ' ' . $author),
                    'deposit_price' => $deposit,
                    'daily_price' => $daily,
                    'stock' => $stock,
                    'is_active' => $title !== 'Naruto Vol. 1',
                ]
            );
        }
    }
}
