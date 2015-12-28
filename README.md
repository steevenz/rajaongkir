# RajaOngkir
Ini adalah Advanced [RajaOngkir][11] API PHP Class, yang berfungsi untuk melakukan request API [RajaOngkir][11].

Instalasi
---------
Cara terbaik untuk melakukan instalasi library ini adalah dengan menggunakan [Composer][7]
```
composer require steevenz/rajaongkir
```

Penggunaan
----------
```php
use Steevenz\Rajaongkir;

/*
 * --------------------------------------------------------------
 * Inisiasi Class RajaOngkir
 *
 * Tipe account yang tersedia di RajaOngkir:
 * - starter (tidak support international dan metode waybill)
 * - basic
 * - pro
 *
 * @param string API Key
 * @param string Account Type (lowercase)
 * --------------------------------------------------------------
 */
 // inisiasi untuk tipe starter
 $rajaongkir = new Rajaongkir();
 
 // inisiasi untuk tipe account basic / pro
 $rajaongkir = new Rajaongkir('API_KEY_ANDA', 'basic');

/*
 * --------------------------------------------------------------
 * Mendapatkan list seluruh propinsi
 * --------------------------------------------------------------
 */
$provinces = $rajaongkir->get_provinces();

/*
 * --------------------------------------------------------------
 * Mendapatkan detail propinsi
 *
 * @param int Province ID
 * --------------------------------------------------------------
 */
$province = $rajaongkir->get_province(1);

/*
 * --------------------------------------------------------------
 * Mendapatkan list seluruh kota
 * --------------------------------------------------------------
 */
$cities = $rajaongkir->get_cities();

/*
 * --------------------------------------------------------------
 * Mendapatkan list seluruh kota di propinsi tertentu
 *
 * @param int Province ID
 * --------------------------------------------------------------
 */
$cities = $rajaongkir->get_cities(1);

/*
 * --------------------------------------------------------------
 * Mendapatkan detail kota
 *
 * @param int City ID
 * --------------------------------------------------------------
 */
$city = $rajaongkir->get_city(1);

/*
 * --------------------------------------------------------------
 * Mendapatkan list seluruh subdistrict dari kota tertentu
 *
 * @param int City ID
 * --------------------------------------------------------------
 */
$subdistricts = $rajaongkir->get_subdistricts(1);

/*
 * --------------------------------------------------------------
 * Mendapatkan detail subdistrict
 *
 * @param int Subdistrict ID
 * --------------------------------------------------------------
 */
$subdistrict = $rajaongkir->get_subdistrict(1);

/*
 * --------------------------------------------------------------
 * Mendapatkan list seluruh kota yang mendukung pengiriman
 * ke Internasional
 * (tidak tersedia untuk tipe account starter)
 * --------------------------------------------------------------
 */
$international_origins = $rajaongkir->get_international_origins();

/*
 * --------------------------------------------------------------
 * Mendapatkan list seluruh kota yang mendukung pengiriman
 * ke Internasional di propinsi tertentu
 * (tidak tersedia untuk tipe account starter)
 *
 * @param int Province ID
 * --------------------------------------------------------------
 */
$international_origins = $rajaongkir->get_international_origins(6);

/*
 * --------------------------------------------------------------
 * Mendapatkan detail Origin Internasional
 * (tidak tersedia untuk tipe account starter)
 *
 * @param int City ID
 * --------------------------------------------------------------
 */
$international_origin = $rajaongkir->get_international_origin(152);

/*
 * --------------------------------------------------------------
 * Mendapatkan list seluruh negara tujuan Internasional
 * (tidak tersedia untuk tipe account starter)
 * --------------------------------------------------------------
 */
$international_destinations = $rajaongkir->get_international_destinations();

/*
 * --------------------------------------------------------------
 * Mendapatkan detail tujuan Internasional
 * (tidak tersedia untuk tipe account starter)
 *
 * @param int Country ID
 * --------------------------------------------------------------
 */
$international_destination = $rajaongkir->get_international_destination(108);

/*
 * --------------------------------------------------------------
 * Mendapatkan harga ongkos kirim berdasarkan berat dalam gram
 *
 * @param array Origin 
 * @param array Destination
 * @param int|array Weight|Metrics
 * @param string Courier
 * --------------------------------------------------------------
 */
$cost = $rajaongkir->get_cost(['city' => 501], ['subdistrict' => 574], 1000, 'jne');

/*
 * --------------------------------------------------------------
 * Mendapatkan harga ongkos kirim berdasarkan volume metrics
 * atau berdasarkan ukuran panjang x lebar x tinggi
 *
 * Catatan: 
 * Berat akan otomatis dihitung berdasarkan volume metrics.
 *
 * @param array Origin 
 * @param array Destination
 * @param int|array Weight|Metrics
 * @param string Courier
 * --------------------------------------------------------------
 */
$cost = $rajaongkir->get_cost(['city' => 501], ['subdistrict' => 574], 
                                        array( 'length' => 50, 
                                               'width' => 50, 
                                               'height' => 50
                                        ), 'jne');

/*
 * --------------------------------------------------------------
 * Mendapatkan harga ongkos kirim berdasarkan berat dalam gram
 * atau berdasarkan ukuran panjang x lebar x tinggi
 *
 * Catatan: 
 * Jika ukuran menghasilkan berat yang lebih besar dari
 * berat yang didefinisikan, berat yang akan dipakai sebagai
 * kalkulasi ongkos kirim adalah berat berdasarkan volume metrics
 *
 * @param array Origin 
 * @param array Destination
 * @param int|array Weight|Metrics
 * @param string Courier
 * --------------------------------------------------------------
 */
 $cost = $rajaongkir->get_cost(['city' => 501], ['subdistrict' => 574], 
                                        array( 'weight' => 1000,
                                                'length' => 50, 
                                                'width' => 50, 
                                                'height' => 50
                                        ), 'jne');
                                        
/*
 * --------------------------------------------------------------
 * Mendapatkan harga ongkos kirim international berdasarkan berat 
 * dalam gram (tidak tersedia untuk tipe account starter)
 *
 * @param array Origin 
 * @param array Destination
 * @param int|array Weight|Metrics
 * @param string Courier
 * --------------------------------------------------------------
 */
$cost = $rajaongkir->get_cost(['city' => 152], ['country' => 108], 1400, 'pos');                                        

/*
 * --------------------------------------------------------------
 * Melacak status pengiriman
 *
 * @param string Receipt ID (Nomor Resi Pengiriman)
 * @param string Courier
 * --------------------------------------------------------------
 */
 $waybill = $rajaongkir->get_waybill('SOCAG00183235715', 'jne');
```

Ide, Kritik dan Saran
---------------------
Jika anda memiliki ide, kritik ataupun saran, anda dapat mengirimkan email ke [steevenz@stevenz.com][3]. 
Anda juga dapat mengunjungi situs pribadi saya di [steevenz.com][1]

Bugs and Issues
---------------
Jika anda menemukan bugs atau issue, anda dapat mempostingnya di [Github Issues][6].

Requirements
------------
- PHP 5.4+
- [Composer][9]
- [O2System CURL (O2CURL)][10]

Referensi
---------
Untuk mengetahui lebih lanjut mengenai RajaOngkir API, lihat di [Dokumentasi RajaOngkir][12].

[1]: http://steevenz.com
[2]: http://steevenz.com/blog/rajaongkir-api
[3]: mailto:steevenz@steevenz.com
[4]: http://github.com/steevenz/rajaongkir
[5]: http://github.com/steevenz/rajaongkir/wiki
[6]: http://github.com/steevenz/rajaongkir/issues
[7]: https://packagist.org/packages/steevenz/rajaongkir
[9]: https://getcomposer.org
[10]: http://github.com/o2system/o2curl
[11]: http://rajaongkir.com
[12]: http://rajaongkir.com/dokumentasi
