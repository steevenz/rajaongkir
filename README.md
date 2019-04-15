# RajaOngkir

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/steevenz/rajaongkir/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/steevenz/rajaongkir/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/steevenz/rajaongkir/badges/build.png?b=master)](https://scrutinizer-ci.com/g/steevenz/rajaongkir/build-status/master)
[![Latest Stable Version](https://poser.pugx.org/steevenz/rajaongkir/v/stable)](https://packagist.org/packages/steevenz/rajaongkir)
[![Total Downloads](https://poser.pugx.org/steevenz/rajaongkir/downloads)](https://packagist.org/packages/steevenz/rajaongkir)
[![License](https://poser.pugx.org/steevenz/rajaongkir/license)](https://packagist.org/packages/steevenz/rajaongkir)

[RajaOngkir][11] API PHP Class Library berfungsi untuk melakukan request API [RajaOngkir][11].

Fitur
-----
- Support seluruh tipe akun RajaOngkir (Starter, Basic, Pro).
- Support mendapatkan biaya ongkos kirim berdasarkan berat (gram) dan volume metrics (p x l x t - otomatis akan dikonversi ke satuan gram). 

Instalasi
---------
Cara terbaik untuk melakukan instalasi library ini adalah dengan menggunakan [Composer][7]
```
composer require steevenz/rajaongkir
```
PHP Framework yang mendukung instalasi diatas:
1. O2System Framework
2. Laravel Framework
3. Yii Framework
4. Symfony Framework
5. CodeIgniter Framework

Instalasi pada framework lain atau PHP Native
```php
require_once('path/to/steevenz/rajaongkir/src/autoload.php');
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
 * @param string|array API Key atau konfigurasi dalam array
 * @param string Account Type (lowercase)
 * --------------------------------------------------------------
 */
 $rajaongkir = new Rajaongkir('API_KEY_ANDA', Rajaongkir::ACCOUNT_STARTER);
 
 // inisiasi dengan config array
 $config['api_key'] = 'API_KEY_ANDA';
 $config['account_type'] = 'starter';
 
 $rajaongkir = new Rajaongkir($config);

/*
 * --------------------------------------------------------------
 * Mendapatkan list seluruh propinsi
 * --------------------------------------------------------------
 */
$provinces = $rajaongkir->getProvinces();

/*
 * --------------------------------------------------------------
 * Mendapatkan detail propinsi
 *
 * @param int Province ID
 * --------------------------------------------------------------
 */
$province = $rajaongkir->getProvince(1);

/*
 * --------------------------------------------------------------
 * Mendapatkan list seluruh kota
 * --------------------------------------------------------------
 */
$cities = $rajaongkir->getCities();

/*
 * --------------------------------------------------------------
 * Mendapatkan list seluruh kota di propinsi tertentu
 *
 * @param int Province ID (optional)
 * --------------------------------------------------------------
 */
$cities = $rajaongkir->getCities(1);

/*
 * --------------------------------------------------------------
 * Mendapatkan detail kota
 *
 * @param int City ID
 * --------------------------------------------------------------
 */
$city = $rajaongkir->getCity(1);

/*
 * --------------------------------------------------------------
 * Mendapatkan list seluruh subdistrict dari kota tertentu
 *
 * @param int City ID (optional)
 * --------------------------------------------------------------
 */
$subdistricts = $rajaongkir->getSubdistricts(39);

/*
 * --------------------------------------------------------------
 * Mendapatkan detail subdistrict
 *
 * @param int Subdistrict ID 
 * --------------------------------------------------------------
 */
$subdistrict = $rajaongkir->getSubdistrict(537);

/*
 * --------------------------------------------------------------
 * Mendapatkan list seluruh kota yang mendukung pengiriman
 * ke Internasional
 * (tidak tersedia untuk tipe account starter)
 * --------------------------------------------------------------
 */
$internationalOrigins = $rajaongkir->getInternationalOrigins();

/*
 * --------------------------------------------------------------
 * Mendapatkan list seluruh kota yang mendukung pengiriman
 * ke Internasional di propinsi tertentu
 * (tidak tersedia untuk tipe account starter)
 *
 * @param int Province ID (optional)
 * --------------------------------------------------------------
 */
$internationalOrigins = $rajaongkir->getInternationalOrigins(6);

/*
 * --------------------------------------------------------------
 * Mendapatkan detail Origin Internasional
 * (tidak tersedia untuk tipe account starter)
 *
 * @param int City ID (optional)
 * @param int Province ID (optional)
 * --------------------------------------------------------------
 */
$internationalOrigin = $rajaongkir->getInternationalOrigin(152, 6);

/*
 * --------------------------------------------------------------
 * Mendapatkan list seluruh negara tujuan Internasional
 * (tidak tersedia untuk tipe account starter)
 * --------------------------------------------------------------
 */
$internationalDestinations = $rajaongkir->getInternationalDestinations();

/*
 * --------------------------------------------------------------
 * Mendapatkan detail tujuan Internasional
 * (tidak tersedia untuk tipe account starter)
 *
 * @param int Country ID
 * --------------------------------------------------------------
 */
$internationalDestination = $rajaongkir->getInternationalDestination(108);

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
$cost = $rajaongkir->getCost(['city' => 501], ['subdistrict' => 574], 1000, 'jne');

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
$cost = $rajaongkir->getCost(['city' => 501], ['subdistrict' => 574],
                    [
                        'length' => 50,
                        'width'  => 50,
                        'height' => 50,
                    ], 'jne');

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
 $cost = $rajaongkir->getCost(['city' => 501], ['subdistrict' => 574],
                     [
                         'weight' => 1000,
                         'length' => 50,
                         'width'  => 50,
                         'height' => 50,
                     ], 'jne');
                                        
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
$cost = $rajaongkir->getCost(['city' => 152], ['country' => 108], 1400, 'pos'); 

/*
 * --------------------------------------------------------------
 * Melacak status pengiriman
 *
 * @param string Receipt ID (Nomor Resi Pengiriman)
 * @param string Courier
 * --------------------------------------------------------------
 */
 $waybill = $rajaongkir->getWaybill('SOCAG00183235715', 'jne');
 
/*
 * --------------------------------------------------------------
 * Mendapatkan informasi nilai tukar rupiah terhadap US dollar.
 * --------------------------------------------------------------
 */
 $currency = $rajaongkir->getCurrency();
 
/*
 * --------------------------------------------------------------
 * Melakukan debugging errors.
 * --------------------------------------------------------------
 */
 if(false === ($waybill = $rajaongkir->getWaybill('SOCAG00183235715', 'jne'))) {
    print_out($rajaongkir->getErrors());
 }
 
/*
 * --------------------------------------------------------------
 * Mendapatkan daftar courier yang didukung oleh tipe akun anda
 * --------------------------------------------------------------
 */
 $supportedCouriers = $rajaongkir->getSupportedCouriers();
  
/*
 * --------------------------------------------------------------
 * Mendapatkan daftar way bill courier yang didukung oleh tipe akun anda
 * --------------------------------------------------------------
 */
 $supportedWayBills = $rajaongkir->getSupportedWayBills();
```

Untuk keterangan lebih lengkap dapat dibaca di [Wiki](https://github.com/steevenz/rajaongkir/wiki)

Ide, Kritik dan Saran
---------------------
Jika anda memiliki ide, kritik ataupun saran, anda dapat mengirimkan email ke [steevenz@stevenz.com][3]. 
Anda juga dapat mengunjungi situs pribadi saya di [steevenz.com][1]

Bugs and Issues
---------------
Jika anda menemukan bugs atau issue, anda dapat mempostingnya di [Github Issues][6].

Requirements
------------
- PHP 7.2+
- [Composer][9]
- [O2System Curl][10]

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
[10]: http://github.com/o2system/curl
[11]: http://rajaongkir.com
[12]: http://rajaongkir.com/dokumentasi
