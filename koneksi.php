<?php
//untuk mendapatkan objek PDO yang mewakili koneksi ke database//
function getConnection(): ?PDO {
    //variabel yang berisi informasi koneksi ke database//
    $host = "localhost";  // Alamat server database
    $port = 3306;         // Port database MySQL (default: 3306)
    $db = "loginform";    // Nama database yang akan digunakan
    $username = "root";   // Nama pengguna database
    $password = "";       // Kata sandi pengguna database (kosong pada contoh ini)

    // untuk menangani pengecualian yang mungkin terjadi selama koneksi ke database//
    try {
        //membuat objek PDO untuk koneksi ke database MySQL//
        $connection = new PDO("mysql:host=$host;port=$port;dbname=$db", $username, $password);
        //Baris ini mengatur atribut PDO untuk menampilkan pengecualian jika terjadi kesalahan selama eksekusi query//
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo '<div id="successMessage">Koneksi Sukses</div>';

         // Mengembalikan objek PDO untuk digunakan dalam operasi database//
        return $connection;
      // Menampilkan pesan kesalahan jika koneksi gagal dan mengembalikan null//
    } catch (PDOException $exception) {
        echo "Gagal Koneksi: " . $exception->getMessage();

        return null;
    }
}
?>
