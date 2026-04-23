<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Admin ───────────────────────────────────────────────────────────
        User::updateOrCreate(['email' => 'admin@gerejabethesda.com'], [
            'name'     => 'Administrator',
            'password' => Hash::make('Admin@1234'),
            'role'     => 'admin',
            'no_hp'    => '081200000001',
            'alamat'   => 'Gereja Bethesda, Jl. Harapan Indah No. 123, Jakarta Pusat',
        ]);

        // ─── 50 Jemaat ───────────────────────────────────────────────────────
        $jemaat = [
            ['name' => 'Budi Santoso',           'email' => 'budi@email.com',       'no_hp' => '081234567890', 'alamat' => 'Jl. Mawar No. 45, Jakarta Selatan'],
            ['name' => 'Sari Widya Ningrum',     'email' => 'sari@email.com',       'no_hp' => '082345678901', 'alamat' => 'Jl. Merpati No. 12, Tangerang'],
            ['name' => 'Hendra Kusuma',          'email' => 'hendra@email.com',     'no_hp' => '083456789012', 'alamat' => 'Jl. Kenanga No. 7, Bekasi'],
            ['name' => 'Dewi Lestari',           'email' => 'dewi@email.com',       'no_hp' => '084567890123', 'alamat' => 'Jl. Melati No. 3, Depok'],
            ['name' => 'Agus Prasetyo',          'email' => 'agus@email.com',       'no_hp' => '085678901234', 'alamat' => 'Jl. Anggrek No. 22, Bogor'],
            ['name' => 'Rina Marlina',           'email' => 'rina@email.com',       'no_hp' => '086789012345', 'alamat' => 'Jl. Flamboyan No. 8, Bekasi'],
            ['name' => 'Joko Widodo',            'email' => 'joko@email.com',       'no_hp' => '087890123456', 'alamat' => 'Jl. Cempaka No. 17, Jakarta Timur'],
            ['name' => 'Yuliana Putri',          'email' => 'yuliana@email.com',    'no_hp' => '088901234567', 'alamat' => 'Jl. Dahlia No. 5, Tangerang Selatan'],
            ['name' => 'Samuel Pattikawa',       'email' => 'samuel@email.com',     'no_hp' => '089012345678', 'alamat' => 'Jl. Pattimura No. 9, Ambon'],
            ['name' => 'Maria Tutuarima',        'email' => 'maria@email.com',      'no_hp' => '081123456789', 'alamat' => 'Jl. Sirimau No. 14, Ambon'],
            ['name' => 'Petrus Latumahina',      'email' => 'petrus@email.com',     'no_hp' => '082234567890', 'alamat' => 'Jl. Batu Merah No. 21, Ambon'],
            ['name' => 'Yohana Sipahelut',       'email' => 'yohana@email.com',     'no_hp' => '083345678901', 'alamat' => 'Jl. Waai No. 6, Maluku Tengah'],
            ['name' => 'Daniel Souissa',         'email' => 'daniel@email.com',     'no_hp' => '084456789012', 'alamat' => 'Jl. Nusaniwe No. 33, Ambon'],
            ['name' => 'Esther Wattimena',       'email' => 'esther@email.com',     'no_hp' => '085567890123', 'alamat' => 'Jl. Leitimur No. 2, Ambon'],
            ['name' => 'Abraham Matulessy',      'email' => 'abraham@email.com',    'no_hp' => '086678901234', 'alamat' => 'Jl. Tulehu No. 11, Maluku Tengah'],
            ['name' => 'Naomi Lekahena',         'email' => 'naomi@email.com',      'no_hp' => '087789012345', 'alamat' => 'Jl. Hitu No. 4, Maluku Tengah'],
            ['name' => 'Yusuf Pattiasina',       'email' => 'yusuf@email.com',      'no_hp' => '088890123456', 'alamat' => 'Jl. Masohi No. 18, Maluku Tengah'],
            ['name' => 'Ruth Persulessy',        'email' => 'ruth@email.com',       'no_hp' => '089901234567', 'alamat' => 'Jl. Amahai No. 7, Maluku Tengah'],
            ['name' => 'Barnabas Talakua',       'email' => 'barnabas@email.com',   'no_hp' => '081012345678', 'alamat' => 'Jl. Banda No. 25, Maluku Tengah'],
            ['name' => 'Deborah Picauly',        'email' => 'deborah@email.com',    'no_hp' => '082123456789', 'alamat' => 'Jl. Saparua No. 13, Maluku Tengah'],
            ['name' => 'Kristian Lewerissa',     'email' => 'kristian@email.com',   'no_hp' => '083234567891', 'alamat' => 'Jl. Haruku No. 3, Maluku Tengah'],
            ['name' => 'Felicia Manuhuttu',      'email' => 'felicia@email.com',    'no_hp' => '084345678902', 'alamat' => 'Jl. Seram No. 10, Maluku'],
            ['name' => 'Andreas Huwae',          'email' => 'andreas@email.com',    'no_hp' => '085456789013', 'alamat' => 'Jl. Buru No. 16, Maluku'],
            ['name' => 'Theresia Uneputty',      'email' => 'theresia@email.com',   'no_hp' => '086567890124', 'alamat' => 'Jl. Ternate No. 28, Maluku Utara'],
            ['name' => 'Marthen Noya',           'email' => 'marthen@email.com',    'no_hp' => '087678901235', 'alamat' => 'Jl. Tidore No. 19, Maluku Utara'],
            ['name' => 'Susana Pesiwarissa',     'email' => 'susana@email.com',     'no_hp' => '088789012346', 'alamat' => 'Jl. Bacan No. 30, Maluku Utara'],
            ['name' => 'Hendrik Rehatta',        'email' => 'hendrik@email.com',    'no_hp' => '089890123457', 'alamat' => 'Jl. Tobelo No. 24, Maluku Utara'],
            ['name' => 'Magdalena Siahaya',      'email' => 'magdalena@email.com',  'no_hp' => '081234509876', 'alamat' => 'Jl. Kairatu No. 15, Seram Bagian Barat'],
            ['name' => 'Timotius Soulissa',      'email' => 'timotius@email.com',   'no_hp' => '082345610987', 'alamat' => 'Jl. Piru No. 20, Seram Bagian Barat'],
            ['name' => 'Hana Tuasikal',          'email' => 'hana@email.com',       'no_hp' => '083456721098', 'alamat' => 'Jl. Elpaputih No. 8, Seram Bagian Barat'],
            ['name' => 'Markus Silawane',        'email' => 'markus@email.com',     'no_hp' => '084567832109', 'alamat' => 'Jl. Namlea No. 12, Buru'],
            ['name' => 'Ester Lesnussa',         'email' => 'ester@email.com',      'no_hp' => '085678943210', 'alamat' => 'Jl. Namrole No. 6, Buru Selatan'],
            ['name' => 'Paulus Sahalessy',       'email' => 'paulus@email.com',     'no_hp' => '086789054321', 'alamat' => 'Jl. Dobo No. 9, Kepulauan Aru'],
            ['name' => 'Lydia Manuhutu',         'email' => 'lydia@email.com',      'no_hp' => '087890165432', 'alamat' => 'Jl. Tual No. 27, Maluku Tenggara'],
            ['name' => 'Stefanus Rahanra',       'email' => 'stefanus@email.com',   'no_hp' => '088901276543', 'alamat' => 'Jl. Langgur No. 31, Maluku Tenggara'],
            ['name' => 'Cornelia Papilaya',      'email' => 'cornelia@email.com',   'no_hp' => '089012387654', 'alamat' => 'Jl. Saumlaki No. 22, Maluku Barat Daya'],
            ['name' => 'Yonatan Loupatty',       'email' => 'yonatan@email.com',    'no_hp' => '081123498765', 'alamat' => 'Jl. Tiakur No. 5, Maluku Barat Daya'],
            ['name' => 'Ribka Kakiay',           'email' => 'ribka@email.com',      'no_hp' => '082234509876', 'alamat' => 'Jl. Wonreli No. 17, Maluku Barat Daya'],
            ['name' => 'Ezra Matitaputty',       'email' => 'ezra@email.com',       'no_hp' => '083345610987', 'alamat' => 'Jl. Mako No. 38, Maluku Tengah'],
            ['name' => 'Priscilla Soumokil',     'email' => 'priscilla@email.com',  'no_hp' => '084456721098', 'alamat' => 'Jl. Werinama No. 14, Seram Bagian Timur'],
            ['name' => 'Nikolaus Mailoa',        'email' => 'nikolaus@email.com',   'no_hp' => '085567832109', 'alamat' => 'Jl. Bula No. 26, Seram Bagian Timur'],
            ['name' => 'Gloria Kissya',          'email' => 'gloria@email.com',     'no_hp' => '086678943210', 'alamat' => 'Jl. Geser No. 11, Seram Bagian Timur'],
            ['name' => 'Otniel Laturette',       'email' => 'otniel@email.com',     'no_hp' => '087789054321', 'alamat' => 'Jl. Kobisonta No. 34, Maluku Tengah'],
            ['name' => 'Welmince Pattipeilohy',  'email' => 'welmince@email.com',   'no_hp' => '088890165432', 'alamat' => 'Jl. Tehoru No. 7, Maluku Tengah'],
            ['name' => 'Alpius Tomasoa',         'email' => 'alpius@email.com',     'no_hp' => '089901276543', 'alamat' => 'Jl. Sepa No. 42, Maluku Tengah'],
            ['name' => 'Melkias Tuaperussy',     'email' => 'melkias@email.com',    'no_hp' => '081012387654', 'alamat' => 'Jl. Teon No. 29, Maluku Tengah'],
            ['name' => 'Selvia Hehanusa',        'email' => 'selvia@email.com',     'no_hp' => '082123498765', 'alamat' => 'Jl. Nila No. 16, Maluku Tengah'],
            ['name' => 'Reimond Pattiruhu',      'email' => 'reimond@email.com',    'no_hp' => '083234509871', 'alamat' => 'Jl. Serua No. 23, Maluku Tengah'],
            ['name' => 'Anastasia Wairata',      'email' => 'anastasia@email.com',  'no_hp' => '084345610982', 'alamat' => 'Jl. Manipa No. 35, Maluku Tengah'],
            ['name' => 'Ferdinan Tamaela',       'email' => 'ferdinan@email.com',   'no_hp' => '085456721093', 'alamat' => 'Jl. Kelang No. 18, Maluku Tengah'],
        ];

        foreach ($jemaat as $data) {
            User::updateOrCreate(['email' => $data['email']], [
                'name'     => $data['name'],
                'password' => Hash::make('password123'),
                'role'     => 'jemaat',
                'no_hp'    => $data['no_hp'],
                'alamat'   => $data['alamat'],
            ]);
        }

        $this->command->info('✅ UserSeeder: 1 admin + ' . count($jemaat) . ' jemaat selesai.');
    }
}