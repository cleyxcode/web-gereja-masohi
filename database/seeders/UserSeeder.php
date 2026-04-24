<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // ─── Admin ───────────────────────────────────────────────────────────
        User::updateOrCreate(['email' => 'admin@gerejabethesda.com'], [
            'name'          => 'Administrator',
            'password'      => Hash::make('Admin@1234'),
            'role'          => 'admin',
            'no_hp'         => '081200000001',
            'alamat'        => 'Gereja Bethesda, Jl. Harapan Indah No. 123, Ambon',
            'jenis_kelamin' => 'Laki-laki',
            'sektor'        => 'Sektor 1',
            'unit'          => 'Unit 1',
            'is_approved'   => true,
        ]);

        $sektors = ['Sektor 1', 'Sektor 2', 'Sektor 3', 'Sektor 4', 'Sektor 5'];
        $units   = ['Unit A', 'Unit B', 'Unit C', 'Unit D'];

        // ─── Data Lama Jemaat (50) ───────────────────────────────────────────
        $jemaatLama = [
            ['name' => 'Budi Santoso',           'email' => 'budi@email.com',       'no_hp' => '081234567890', 'alamat' => 'Jl. Pattimura No. 45, Ambon'],
            ['name' => 'Sari Widya Ningrum',     'email' => 'sari@email.com',       'no_hp' => '082345678901', 'alamat' => 'Jl. Sirimau No. 12, Ambon'],
            ['name' => 'Hendra Kusuma',          'email' => 'hendra@email.com',     'no_hp' => '083456789012', 'alamat' => 'Jl. Batu Merah No. 7, Ambon'],
            ['name' => 'Dewi Lestari',           'email' => 'dewi@email.com',       'no_hp' => '084567890123', 'alamat' => 'Jl. Rijali No. 3, Ambon'],
            ['name' => 'Agus Prasetyo',          'email' => 'agus@email.com',       'no_hp' => '085678901234', 'alamat' => 'Jl. A. Y. Patty No. 22, Ambon'],
            ['name' => 'Rina Marlina',           'email' => 'rina@email.com',       'no_hp' => '086789012345', 'alamat' => 'Jl. Nusaniwe No. 8, Ambon'],
            ['name' => 'Joko Widodo',            'email' => 'joko@email.com',       'no_hp' => '087890123456', 'alamat' => 'Jl. Waihaong No. 17, Ambon'],
            ['name' => 'Yuliana Putri',          'email' => 'yuliana@email.com',    'no_hp' => '088901234567', 'alamat' => 'Jl. Wainitu No. 5, Ambon'],
            ['name' => 'Samuel Pattikawa',       'email' => 'samuel@email.com',     'no_hp' => '089012345678', 'alamat' => 'Jl. Pattimura No. 9, Ambon'],
            ['name' => 'Maria Tutuarima',        'email' => 'maria@email.com',      'no_hp' => '081123456789', 'alamat' => 'Jl. Sirimau No. 14, Ambon'],
            ['name' => 'Petrus Latumahina',      'email' => 'petrus@email.com',     'no_hp' => '082234567890', 'alamat' => 'Jl. Batu Merah No. 21, Ambon'],
            ['name' => 'Yohana Sipahelut',       'email' => 'yohana@email.com',     'no_hp' => '083345678901', 'alamat' => 'Jl. Halong No. 6, Ambon'],
            ['name' => 'Daniel Souissa',         'email' => 'daniel@email.com',     'no_hp' => '084456789012', 'alamat' => 'Jl. Nusaniwe No. 33, Ambon'],
            ['name' => 'Esther Wattimena',       'email' => 'esther@email.com',     'no_hp' => '085567890123', 'alamat' => 'Jl. Karang Panjang No. 2, Ambon'],
            ['name' => 'Abraham Matulessy',      'email' => 'abraham@email.com',    'no_hp' => '086678901234', 'alamat' => 'Jl. Tulehu No. 11, Ambon'],
            ['name' => 'Naomi Lekahena',         'email' => 'naomi@email.com',      'no_hp' => '087789012345', 'alamat' => 'Jl. Hitu No. 4, Ambon'],
            ['name' => 'Yusuf Pattiasina',       'email' => 'yusuf@email.com',      'no_hp' => '088890123456', 'alamat' => 'Jl. Galala No. 18, Ambon'],
            ['name' => 'Ruth Persulessy',        'email' => 'ruth@email.com',       'no_hp' => '089901234567', 'alamat' => 'Jl. Passo No. 7, Ambon'],
            ['name' => 'Barnabas Talakua',       'email' => 'barnabas@email.com',   'no_hp' => '081012345678', 'alamat' => 'Jl. Benteng No. 25, Ambon'],
            ['name' => 'Deborah Picauly',        'email' => 'deborah@email.com',    'no_hp' => '082123456789', 'alamat' => 'Jl. Laha No. 13, Ambon'],
            ['name' => 'Kristian Lewerissa',     'email' => 'kristian@email.com',   'no_hp' => '083234567891', 'alamat' => 'Jl. Latuhalat No. 3, Ambon'],
            ['name' => 'Felicia Manuhuttu',      'email' => 'felicia@email.com',    'no_hp' => '084345678902', 'alamat' => 'Jl. Namalatu No. 10, Ambon'],
            ['name' => 'Andreas Huwae',          'email' => 'andreas@email.com',    'no_hp' => '085456789013', 'alamat' => 'Jl. Wayame No. 16, Ambon'],
            ['name' => 'Theresia Uneputty',      'email' => 'theresia@email.com',   'no_hp' => '086567890124', 'alamat' => 'Jl. Rumah Tiga No. 28, Ambon'],
            ['name' => 'Marthen Noya',           'email' => 'marthen@email.com',    'no_hp' => '087678901235', 'alamat' => 'Jl. Poka No. 19, Ambon'],
            ['name' => 'Susana Pesiwarissa',     'email' => 'susana@email.com',     'no_hp' => '088789012346', 'alamat' => 'Jl. Waiheru No. 30, Ambon'],
            ['name' => 'Hendrik Rehatta',        'email' => 'hendrik@email.com',    'no_hp' => '089890123457', 'alamat' => 'Jl. Uritetu No. 24, Ambon'],
            ['name' => 'Magdalena Siahaya',      'email' => 'magdalena@email.com',  'no_hp' => '081234509876', 'alamat' => 'Jl. Kudamati No. 15, Ambon'],
            ['name' => 'Timotius Soulissa',      'email' => 'timotius@email.com',   'no_hp' => '082345610987', 'alamat' => 'Jl. Diponegoro No. 20, Ambon'],
            ['name' => 'Hana Tuasikal',          'email' => 'hana@email.com',       'no_hp' => '083456721098', 'alamat' => 'Jl. Baguala No. 8, Ambon'],
            ['name' => 'Markus Silawane',        'email' => 'markus@email.com',     'no_hp' => '084567832109', 'alamat' => 'Jl. Waihoka No. 12, Ambon'],
            ['name' => 'Ester Lesnussa',         'email' => 'ester@email.com',      'no_hp' => '085678943210', 'alamat' => 'Jl. Amahusu No. 6, Ambon'],
            ['name' => 'Paulus Sahalessy',       'email' => 'paulus@email.com',     'no_hp' => '086789054321', 'alamat' => 'Jl. Seilale No. 9, Ambon'],
            ['name' => 'Lydia Manuhutu',         'email' => 'lydia@email.com',      'no_hp' => '087890165432', 'alamat' => 'Jl. Tawiri No. 27, Ambon'],
            ['name' => 'Stefanus Rahanra',       'email' => 'stefanus@email.com',   'no_hp' => '088901276543', 'alamat' => 'Jl. Liang No. 31, Ambon'],
            ['name' => 'Cornelia Papilaya',      'email' => 'cornelia@email.com',   'no_hp' => '089012387654', 'alamat' => 'Jl. Waai No. 22, Ambon'],
            ['name' => 'Yonatan Loupatty',       'email' => 'yonatan@email.com',    'no_hp' => '081123498765', 'alamat' => 'Jl. Tulehu No. 5, Ambon'],
            ['name' => 'Ribka Kakiay',           'email' => 'ribka@email.com',      'no_hp' => '082234509876', 'alamat' => 'Jl. Morela No. 17, Ambon'],
            ['name' => 'Ezra Matitaputty',       'email' => 'ezra@email.com',       'no_hp' => '083345610987', 'alamat' => 'Jl. Hatu No. 38, Ambon'],
            ['name' => 'Priscilla Soumokil',     'email' => 'priscilla@email.com',  'no_hp' => '084456721098', 'alamat' => 'Jl. Hative Besar No. 14, Ambon'],
            ['name' => 'Nikolaus Mailoa',        'email' => 'nikolaus@email.com',   'no_hp' => '085567832109', 'alamat' => 'Jl. Hative Kecil No. 26, Ambon'],
            ['name' => 'Gloria Kissya',          'email' => 'gloria@email.com',     'no_hp' => '086678943210', 'alamat' => 'Jl. Hunimua No. 11, Ambon'],
            ['name' => 'Otniel Laturette',       'email' => 'otniel@email.com',     'no_hp' => '087789054321', 'alamat' => 'Jl. Liliboi No. 34, Ambon'],
            ['name' => 'Welmince Pattipeilohy',  'email' => 'welmince@email.com',   'no_hp' => '088890165432', 'alamat' => 'Jl. Rutong No. 7, Ambon'],
            ['name' => 'Alpius Tomasoa',         'email' => 'alpius@email.com',     'no_hp' => '089901276543', 'alamat' => 'Jl. Leahari No. 42, Ambon'],
            ['name' => 'Melkias Tuaperussy',     'email' => 'melkias@email.com',    'no_hp' => '081012387654', 'alamat' => 'Jl. Hutumuri No. 29, Ambon'],
            ['name' => 'Selvia Hehanusa',        'email' => 'selvia@email.com',     'no_hp' => '082123498765', 'alamat' => 'Jl. Hukurila No. 16, Ambon'],
            ['name' => 'Reimond Pattiruhu',      'email' => 'reimond@email.com',    'no_hp' => '083234509871', 'alamat' => 'Jl. Soya No. 23, Ambon'],
            ['name' => 'Anastasia Wairata',      'email' => 'anastasia@email.com',  'no_hp' => '084345610982', 'alamat' => 'Jl. Urimessing No. 35, Ambon'],
            ['name' => 'Ferdinan Tamaela',       'email' => 'ferdinan@email.com',   'no_hp' => '085456721093', 'alamat' => 'Jl. Wailette No. 18, Ambon'],
        ];

        // ─── Data Baru 100 Jemaat (Ambon) ─────────────────────────────────────
        $jemaatBaru = [
            ['name' => 'Yohanes Matulessy',      'email' => 'yohanes.mat@email.com',     'no_hp' => '082312340001', 'alamat' => 'Jl. Pattimura No. 1, Ambon',           'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Anastasya Tuasikal',     'email' => 'anastasya.tua@email.com',   'no_hp' => '082312340002', 'alamat' => 'Jl. Sirimau No. 15, Ambon',            'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Ferdy Lewerissa',        'email' => 'ferdy.lew@email.com',       'no_hp' => '082312340003', 'alamat' => 'Jl. A. Y. Patty No. 7, Ambon',         'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Rosalinda Souissa',      'email' => 'rosalinda.sou@email.com',   'no_hp' => '082312340004', 'alamat' => 'Jl. Batu Merah No. 22, Ambon',         'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Reinhardt Sipahelut',    'email' => 'reinhardt.sip@email.com',   'no_hp' => '082312340005', 'alamat' => 'Jl. Waihaong No. 3, Ambon',            'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Marlena Talakua',        'email' => 'marlena.tal@email.com',     'no_hp' => '082312340006', 'alamat' => 'Jl. Rijali No. 18, Ambon',             'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Stefanus Sahalessy',     'email' => 'stefanus.sah@email.com',    'no_hp' => '082312340007', 'alamat' => 'Jl. Nusaniwe No. 44, Ambon',           'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Kristina Pattiasina',    'email' => 'kristina.pat@email.com',    'no_hp' => '082312340008', 'alamat' => 'Jl. Karang Panjang No. 9, Ambon',      'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Rudi Mailoa',            'email' => 'rudi.mai@email.com',        'no_hp' => '082312340009', 'alamat' => 'Jl. Diponegoro No. 12, Ambon',         'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Yolanda Lesnussa',       'email' => 'yolanda.les@email.com',     'no_hp' => '082312340010', 'alamat' => 'Jl. Hatu No. 5, Ambon',                'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Barnabas Rehatta',       'email' => 'barnabas.reh@email.com',    'no_hp' => '082312340011', 'alamat' => 'Jl. Galala No. 33, Ambon',             'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Novita Huwae',           'email' => 'novita.huw@email.com',      'no_hp' => '082312340012', 'alamat' => 'Jl. Batu Gajah No. 11, Ambon',         'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Otniel Soulissa',        'email' => 'otniel.sou@email.com',      'no_hp' => '082312340013', 'alamat' => 'Jl. Waihoka No. 6, Ambon',             'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Deborah Laturette',      'email' => 'deborah.lat@email.com',     'no_hp' => '082312340014', 'alamat' => 'Jl. Benteng No. 27, Ambon',            'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Alpius Uneputty',        'email' => 'alpius.une@email.com',      'no_hp' => '082312340015', 'alamat' => 'Jl. Uritetu No. 2, Ambon',             'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Pricilia Wattimena',     'email' => 'pricilia.wat@email.com',    'no_hp' => '082312340016', 'alamat' => 'Jl. Halong No. 38, Ambon',             'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Timotius Kissya',        'email' => 'timotius.kis@email.com',    'no_hp' => '082312340017', 'alamat' => 'Jl. Passo No. 14, Ambon',              'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Suryani Pattipeilohy',   'email' => 'suryani.pat@email.com',     'no_hp' => '082312340018', 'alamat' => 'Jl. Laha No. 21, Ambon',               'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Nikolaus Tomasoa',       'email' => 'nikolaus.tom@email.com',    'no_hp' => '082312340019', 'alamat' => 'Jl. Tawiri No. 8, Ambon',              'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Helena Siahaya',         'email' => 'helena.sia@email.com',      'no_hp' => '082312340020', 'alamat' => 'Jl. Latuhalat No. 30, Ambon',          'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Reimond Papilaya',       'email' => 'reimond.pap@email.com',     'no_hp' => '082312340021', 'alamat' => 'Jl. Namalatu No. 4, Ambon',            'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Florentina Tuaperussy',  'email' => 'florentina.tua@email.com',  'no_hp' => '082312340022', 'alamat' => 'Jl. Amahusu No. 17, Ambon',            'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Ezra Hehanusa',          'email' => 'ezra.heh@email.com',        'no_hp' => '082312340023', 'alamat' => 'Jl. Seilale No. 26, Ambon',            'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Melinda Manuhuttu',      'email' => 'melinda.man@email.com',     'no_hp' => '082312340024', 'alamat' => 'Jl. Wayame No. 13, Ambon',             'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Gregorius Lekahena',     'email' => 'gregorius.lek@email.com',   'no_hp' => '082312340025', 'alamat' => 'Jl. Hutumuri No. 19, Ambon',           'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Wendy Picauly',          'email' => 'wendy.pic@email.com',       'no_hp' => '082312340026', 'alamat' => 'Jl. Leahari No. 7, Ambon',             'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Agustina Matitaputty',   'email' => 'agustina.mat@email.com',    'no_hp' => '082312340027', 'alamat' => 'Jl. Rutong No. 5, Ambon',              'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Darius Latumahina',      'email' => 'darius.lat@email.com',      'no_hp' => '082312340028', 'alamat' => 'Jl. Kilang No. 41, Ambon',             'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Kornelia Pesiwarissa',   'email' => 'kornelia.pes@email.com',    'no_hp' => '082312340029', 'alamat' => 'Jl. Hukurila No. 10, Ambon',           'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Abdiel Noya',            'email' => 'abdiel.noy@email.com',      'no_hp' => '082312340030', 'alamat' => 'Jl. Soya No. 28, Ambon',               'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Valentina Persulessy',   'email' => 'valentina.per@email.com',   'no_hp' => '082312340031', 'alamat' => 'Jl. Batumerah Atas No. 3, Ambon',      'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Jenius Rahanra',         'email' => 'jenius.rah@email.com',      'no_hp' => '082312340032', 'alamat' => 'Jl. Batu Merah Bawah No. 16, Ambon',   'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Yuni Soumokil',          'email' => 'yuni.sou@email.com',        'no_hp' => '082312340033', 'alamat' => 'Jl. Tantui No. 24, Ambon',             'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Leonardus Manuhutu',     'email' => 'leonardus.man@email.com',   'no_hp' => '082312340034', 'alamat' => 'Jl. Wainitu No. 35, Ambon',            'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Aprilia Tamaela',        'email' => 'aprilia.tam@email.com',     'no_hp' => '082312340035', 'alamat' => 'Jl. Kudamati No. 6, Ambon',            'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Paulus Pattiruhu',       'email' => 'paulus.pat@email.com',      'no_hp' => '082312340036', 'alamat' => 'Jl. Baguala No. 9, Ambon',             'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Nurlaila Wairata',       'email' => 'nurlaila.wai@email.com',    'no_hp' => '082312340037', 'alamat' => 'Jl. Poka No. 20, Ambon',               'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Sixtus Loupatty',        'email' => 'sixtus.lou@email.com',      'no_hp' => '082312340038', 'alamat' => 'Jl. Rumah Tiga No. 45, Ambon',         'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Oktaviana Silawane',     'email' => 'oktaviana.sil@email.com',   'no_hp' => '082312340039', 'alamat' => 'Jl. Waelo No. 11, Ambon',              'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Reiner Kakiay',          'email' => 'reiner.kak@email.com',      'no_hp' => '082312340040', 'alamat' => 'Jl. Waeheru No. 32, Ambon',            'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Lidwina Tuasikal',       'email' => 'lidwina.tua@email.com',     'no_hp' => '082312340041', 'alamat' => 'Jl. Liang No. 14, Ambon',              'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Benediktus Huwae',       'email' => 'benediktus.huw@email.com',  'no_hp' => '082312340042', 'alamat' => 'Jl. Tulehu No. 8, Ambon',              'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Susanti Souissa',        'email' => 'susanti.sou@email.com',     'no_hp' => '082312340043', 'alamat' => 'Jl. Waai No. 23, Ambon',               'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Renaldi Sipahelut',      'email' => 'renaldi.sip@email.com',     'no_hp' => '082312340044', 'alamat' => 'Jl. Hitu Lama No. 37, Ambon',          'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Mariana Soulissa',       'email' => 'mariana.soul@email.com',    'no_hp' => '082312340045', 'alamat' => 'Jl. Hative Kecil No. 5, Ambon',        'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Viktor Matulessy',       'email' => 'viktor.mat@email.com',      'no_hp' => '082312340046', 'alamat' => 'Jl. Hative Besar No. 29, Ambon',       'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Rosita Laturette',       'email' => 'rosita.lat@email.com',      'no_hp' => '082312340047', 'alamat' => 'Jl. Tihu No. 18, Ambon',               'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Ariel Mailoa',           'email' => 'ariel.mai@email.com',       'no_hp' => '082312340048', 'alamat' => 'Jl. Mamala No. 12, Ambon',             'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Olivia Lewerissa',       'email' => 'olivia.lew@email.com',      'no_hp' => '082312340049', 'alamat' => 'Jl. Morela No. 40, Ambon',             'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Daud Rehatta',           'email' => 'daud.reh@email.com',        'no_hp' => '082312340050', 'alamat' => 'Jl. Ureng No. 16, Ambon',              'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Elisabet Kissya',        'email' => 'elisabet.kis@email.com',    'no_hp' => '082312340051', 'alamat' => 'Jl. Seith No. 7, Ambon',               'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Marsel Sahalessy',       'email' => 'marsel.sah@email.com',      'no_hp' => '082312340052', 'alamat' => 'Jl. Kaitetu No. 31, Ambon',            'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Natasya Hehanusa',       'email' => 'natasya.heh@email.com',     'no_hp' => '082312340053', 'alamat' => 'Jl. Hila No. 4, Ambon',                'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Zakheus Talakua',        'email' => 'zakheus.tal@email.com',     'no_hp' => '082312340054', 'alamat' => 'Jl. Allang No. 43, Ambon',             'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Fransisca Tomasoa',      'email' => 'fransisca.tom@email.com',   'no_hp' => '082312340055', 'alamat' => 'Jl. Asahude No. 25, Ambon',            'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Ronal Noya',             'email' => 'ronal.noy@email.com',       'no_hp' => '082312340056', 'alamat' => 'Jl. Hunimua No. 9, Ambon',             'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Merlin Picauly',         'email' => 'merlin.pic@email.com',      'no_hp' => '082312340057', 'alamat' => 'Jl. Liliboi No. 13, Ambon',            'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Gilbertus Persulessy',   'email' => 'gilbertus.per@email.com',   'no_hp' => '082312340058', 'alamat' => 'Jl. Halong Atas No. 36, Ambon',        'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Sinta Silawane',         'email' => 'sinta.sil@email.com',       'no_hp' => '082312340059', 'alamat' => 'Jl. Waeheru No. 2, Ambon',             'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Amos Rahanra',           'email' => 'amos.rah@email.com',        'no_hp' => '082312340060', 'alamat' => 'Jl. Wailette No. 28, Ambon',           'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Yohana Papilaya',        'email' => 'yohana.pap@email.com',      'no_hp' => '082312340061', 'alamat' => 'Jl. Batu Gajah No. 17, Ambon',         'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Kristoforus Wairata',    'email' => 'kristoforus.wai@email.com', 'no_hp' => '082312340062', 'alamat' => 'Jl. Negeri Lama No. 39, Ambon',        'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Ira Manuhuttu',          'email' => 'ira.man@email.com',         'no_hp' => '082312340063', 'alamat' => 'Jl. Amahusu No. 21, Ambon',            'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Benedik Pattiasina',     'email' => 'benedik.pat@email.com',     'no_hp' => '082312340064', 'alamat' => 'Jl. Latuhalat No. 10, Ambon',          'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Wulandari Lesnussa',     'email' => 'wulandari.les@email.com',   'no_hp' => '082312340065', 'alamat' => 'Jl. Namalatu No. 5, Ambon',            'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Immanuel Soumokil',      'email' => 'immanuel.sou@email.com',    'no_hp' => '082312340066', 'alamat' => 'Jl. Nusaniwe No. 33, Ambon',           'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Diana Siahaya',          'email' => 'diana.sia@email.com',       'no_hp' => '082312340067', 'alamat' => 'Jl. Seilale No. 15, Ambon',            'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Yefta Tamaela',          'email' => 'yefta.tam@email.com',       'no_hp' => '082312340068', 'alamat' => 'Jl. Passo Raya No. 46, Ambon',         'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Selfina Tuaperussy',     'email' => 'selfina.tua@email.com',     'no_hp' => '082312340069', 'alamat' => 'Jl. Waiheru No. 8, Ambon',             'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Lazarus Kakiay',         'email' => 'lazarus.kak@email.com',     'no_hp' => '082312340070', 'alamat' => 'Jl. Waiap No. 22, Ambon',              'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Yesica Matitaputty',     'email' => 'yesica.mat@email.com',      'no_hp' => '082312340071', 'alamat' => 'Jl. Latta No. 27, Ambon',              'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Cornelis Huwae',         'email' => 'cornelis.huw@email.com',    'no_hp' => '082312340072', 'alamat' => 'Jl. Hative Kecil No. 44, Ambon',       'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Mersia Manuhutu',        'email' => 'mersia.man@email.com',      'no_hp' => '082312340073', 'alamat' => 'Jl. Urimessing No. 19, Ambon',         'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Yulianus Soulissa',      'email' => 'yulianus.sou@email.com',    'no_hp' => '082312340074', 'alamat' => 'Jl. Waiame No. 6, Ambon',              'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Petronela Pattiruhu',    'email' => 'petronela.pat@email.com',   'no_hp' => '082312340075', 'alamat' => 'Jl. Karang Panjang No. 34, Ambon',     'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Adryan Loupatty',        'email' => 'adryan.lou@email.com',      'no_hp' => '082312340076', 'alamat' => 'Jl. Galala No. 11, Ambon',             'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Martina Wattimena',      'email' => 'martina.wat@email.com',     'no_hp' => '082312340077', 'alamat' => 'Jl. Wayame No. 38, Ambon',             'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Dominggus Lekahena',     'email' => 'dominggus.lek@email.com',   'no_hp' => '082312340078', 'alamat' => 'Jl. Poka No. 23, Ambon',               'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Anastasya Uneputty',     'email' => 'anastasya.une@email.com',   'no_hp' => '082312340079', 'alamat' => 'Jl. Rumahtiga No. 4, Ambon',           'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Imanuel Latumahina',     'email' => 'imanuel.lat@email.com',     'no_hp' => '082312340080', 'alamat' => 'Jl. Waihoka No. 30, Ambon',            'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Yosintha Kissya',        'email' => 'yosintha.kis@email.com',    'no_hp' => '082312340081', 'alamat' => 'Jl. Laha No. 12, Ambon',               'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Frederikus Sahalessy',   'email' => 'frederikus.sah@email.com',  'no_hp' => '082312340082', 'alamat' => 'Jl. Tawiri No. 42, Ambon',             'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Leni Souissa',           'email' => 'leni.sou@email.com',        'no_hp' => '082312340083', 'alamat' => 'Jl. Batugantung No. 7, Ambon',         'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Yonathan Mailoa',        'email' => 'yonathan.mai@email.com',    'no_hp' => '082312340084', 'alamat' => 'Jl. Benteng No. 25, Ambon',            'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Karolin Hehanusa',       'email' => 'karolin.heh@email.com',     'no_hp' => '082312340085', 'alamat' => 'Jl. Uritetu No. 9, Ambon',             'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Arnold Sipahelut',       'email' => 'arnold.sip@email.com',      'no_hp' => '082312340086', 'alamat' => 'Jl. Wainitu No. 47, Ambon',            'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Emilia Noya',            'email' => 'emilia.noy@email.com',      'no_hp' => '082312340087', 'alamat' => 'Jl. Kudamati No. 16, Ambon',           'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Gratianus Silawane',     'email' => 'gratianus.sil@email.com',   'no_hp' => '082312340088', 'alamat' => 'Jl. Rijali No. 3, Ambon',              'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Yemima Rahanra',         'email' => 'yemima.rah@email.com',      'no_hp' => '082312340089', 'alamat' => 'Jl. Diponegoro No. 34, Ambon',         'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Yermias Loupatty',       'email' => 'yermias.lou@email.com',     'no_hp' => '082312340090', 'alamat' => 'Jl. Waihaong No. 20, Ambon',           'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Selina Talakua',         'email' => 'selina.tal@email.com',      'no_hp' => '082312340091', 'alamat' => 'Jl. A. Y. Patty No. 8, Ambon',         'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Reinhard Papilaya',      'email' => 'reinhard.pap@email.com',    'no_hp' => '082312340092', 'alamat' => 'Jl. Halong Atas No. 41, Ambon',        'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Yenni Matitaputty',      'email' => 'yenni.mat@email.com',       'no_hp' => '082312340093', 'alamat' => 'Jl. Sirimau No. 15, Ambon',            'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Hiskia Tomasoa',         'email' => 'hiskia.tom@email.com',      'no_hp' => '082312340094', 'alamat' => 'Jl. Pattimura No. 29, Ambon',          'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Ribkah Pesiwarissa',     'email' => 'ribkah.pes@email.com',      'no_hp' => '082312340095', 'alamat' => 'Jl. Nusaniwe No. 6, Ambon',            'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Ananias Lewerissa',      'email' => 'ananias.lew@email.com',     'no_hp' => '082312340096', 'alamat' => 'Jl. Halong No. 38, Ambon',             'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Marselina Matulessy',    'email' => 'marselina.mat@email.com',   'no_hp' => '082312340097', 'alamat' => 'Jl. Batu Merah No. 14, Ambon',         'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Obaja Wairata',          'email' => 'obaja.wai@email.com',       'no_hp' => '082312340098', 'alamat' => 'Jl. Galala No. 22, Ambon',             'jenis_kelamin' => 'Laki-laki'],
            ['name' => 'Theresiana Laturette',   'email' => 'theresiana.lat@email.com',  'no_hp' => '082312340099', 'alamat' => 'Jl. Passo No. 10, Ambon',              'jenis_kelamin' => 'Perempuan'],
            ['name' => 'Obediel Tuasikal',       'email' => 'obediel.tua@email.com',     'no_hp' => '082312340100', 'alamat' => 'Jl. Hitu No. 48, Ambon',               'jenis_kelamin' => 'Laki-laki'],
        ];

        // ─── Gabungkan semua jemaat ke $allUsers ─────────────────────────────
        $allUsers = [];

        foreach ($jemaatLama as $data) {
            $allUsers[] = [
                'name'          => $data['name'],
                'email'         => $data['email'],
                'no_hp'         => $data['no_hp'],
                'alamat'        => $data['alamat'],
                'jenis_kelamin' => null,
            ];
        }

        foreach ($jemaatBaru as $data) {
            $allUsers[] = [
                'name'          => $data['name'],
                'email'         => $data['email'],
                'no_hp'         => $data['no_hp'],
                'alamat'        => $data['alamat'],
                'jenis_kelamin' => $data['jenis_kelamin'],
            ];
        }

        // ─── Tambah 50 Data Faker ──────────────────────────────────────────────
        for ($i = 0; $i < 50; $i++) {
            $allUsers[] = [
                'name'          => $faker->name,
                'email'         => $faker->unique()->safeEmail,
                'no_hp'         => $faker->phoneNumber,
                'alamat'        => $faker->address,
                'jenis_kelamin' => null,
            ];
        }

        // ─── Simpan ke Database ────────────────────────────────────────────────
        $count = 0;
        foreach ($allUsers as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name'          => $u['name'],
                    'password'      => Hash::make('password123'),
                    'role'          => 'jemaat',
                    'no_hp'         => $u['no_hp'],
                    'alamat'        => $u['alamat'],
                    'jenis_kelamin' => $u['jenis_kelamin'] ?? $faker->randomElement(['Laki-laki', 'Perempuan']),
                    'sektor'        => $faker->randomElement($sektors),
                    'unit'          => $faker->randomElement($units),
                    'is_approved'   => true,
                ]
            );
            $count++;
        }

        $this->command->info("✅ UserSeeder: 1 admin + {$count} jemaat berhasil di-seed dengan data lengkap dan status disetujui.");
    }
}
