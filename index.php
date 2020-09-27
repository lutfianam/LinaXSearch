<?php
/*
 Hallo, perkenalkan nama saya Lutfi Anam. saya sekarang masih menjadi pelajar di salah satu sekolah swasta Temanggung.

 Saya sengaja mempublikasikan project ini karena saya ingin mendukung gerakan #IndonesiaOpenSoure dan juga karena saya ingin membuat website ini ke yang lebih baik lagi dengan cara menjadikannya open source, agar dapat di kembangkan bersama-sama.

 Dalam kasus ini, aplikasinya digunakan sebagai tempat sharing tema blogger dan wordpress. ( Demo : https://temaku.nasihosting.com )

 [!] *Jangan hapus footer ( kecuali sudah melakukan donasi )

 [ Tambahan sedikit : Kalau ada pekerjaan bagi infonya kang, hehe. saya lagi butuh pekerjaan soalnya (skill:php,mysql,html,pentest) ]

 ***

 Donasi : https://trakteer.id/lutfianam/showcase/linaxsearch-tools-search-engine-fOHhK

 ***

 Ikuti saya :
 Blog : https://wegihngetik.blogspot.com
 Email : lutfianamart@gmail.com
 Facebook : https://fb.me/lutf1anam
 Github : https://github.com/lutfianam
 Instagram : https://instagram.com/lutf1anam
 Telegram : ( silahkan minta lewat email )
 Twitter : https://twitter.com/lutf1anam
 Whatsapp : +62 .......... (minta lewat email aja)
 YouTube : Lutfi Anam
 ( Budayakan saat chatting, menggunakan bahasa yang sopan )
*/
// Configuration
define('DB_H', 'localhost'); // Host
define('DB_U', 'root'); // Database username
define('DB_P', ''); // Database password
define('DB_N', 'linaxsearch'); // Database Nama

$db_kon = mysqli_connect(DB_H, DB_U, DB_P, DB_N);

if (mysqli_connect_errno()) {
  echo 'Tidak dapat terhubung ke database : ' . mysqli_connect_error();
  exit();
}
$app_nama = 'LinaXSearch'; // Ganti dengan nama aplikasi
$base_url = "http://localhost/project/opensource/LinaXSearch"; // Ganti dengan url aplikasi
// end of configuration

// hide error
// Turn off error reporting
error_reporting(0);
// Report runtime errors
error_reporting(E_ERROR | E_WARNING | E_PARSE);
// Report all errors
error_reporting(E_ALL);
// Same as error_reporting(E_ALL);
ini_set("error_reporting", E_ALL);
// Report all errors except E_NOTICE
error_reporting(E_ALL & ~E_NOTICE);
// end hide all error

// session admin dan pengunjung
session_start();
// session pengunjung
if (!isset($_SESSION['adm1n'])) {
  $_SESSION['p3ngunjung'] = $_SERVER['REMOTE_ADDR'];
} else {
  unset($_SESSION['p3ngunjung']);
}
// end of session pengunjung
// Login Process dan session admin
if (isset($_GET["l0g1n"])){
  // cara login admin = tambahkan ?l0g1n=password di akhir url
    if ($_GET["password"]=="password"){ // silahkan ganti password
        session_start();
        $_SESSION['adm1n'] = 'Lutfianam'; // Ganti dengan nama kamu
        header("Location: index.php");    
    }else{
        echo "Maaf Username Atau Password Salah !";
    }
}
// end of login
// menghapus session
// Logut Process
if (isset($_GET['logout'])) {
  unset($_SESSION['adm1n']);
  session_destroy();
}
// end of log out
// end of menghapus session
// end of session

// keamanan aplikasi
// santization of all fileds and requests
$_GET  = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
$string = strtolower($_SERVER['QUERY_STRING']);
$crlf = strpos($string,'%0a%0d');
if ($crlf !== FALSE){
  echo '<script type="text/javascript">window.location = "?error=attacker&pesan=maunyobacrlfya"</script>';
  exit();
}
// end of santization
// csrf token
if (empty($_SESSION['token'])) {
  $binhex = bin2hex(random_bytes(10));
  $ip = md5($_SERVER['REMOTE_ADDR']);
    $_SESSION['token'] = $binhex.$ip.$binhex."==";
}
$token = $_SESSION['token'];
// end of csrf token
// end of keamanan aplikasi

// All function
function RandomKode($panjang)
{
    $karakter= 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789';
    $string = '';
    for ($i = 0; $i < $panjang; $i++) {
    $pos = rand(0, strlen($karakter)-1);
    $string .= $karakter{$pos};
    }
    return $string;
}
// end all function

// all query
// query tambah tema baru
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['token'] == $_POST['token']) {
  // Semua permintaan
  $kodeweb = RandomKode(6);
  $title = addslashes(htmlspecialchars($_POST['title']));
  $url_download = addslashes(htmlspecialchars($_POST['link-download']));
  $url_demo = addslashes(htmlspecialchars($_POST['link-demo']));
  $kategori = addslashes(htmlspecialchars($_POST['category']));
  $deskripsi = addslashes(htmlspecialchars($_POST['deskripsi']));
  $tag = addslashes(htmlspecialchars($_POST['tag']));
  $ip_u = addslashes(htmlspecialchars($_SERVER['REMOTE_ADDR']));
  // Menginsert data
  if (isset($_POST['accept']) == 'on') {
    if (empty($title)) {
      echo '<script type="text/javascript">window.location = "?tambah-tema&pesan=judultidakbolehkosong"</script>';
      exit();
    }
    if(preg_match("/(<|>)/i", $_GET['title'])) {
      echo '<script type="text/javascript">window.location = "?error=attacker&pesan=bandel"</script>';
      exit();
    }
    if (!empty($_POST['link-demo'])) {
      if (!filter_var($url_demo, FILTER_VALIDATE_URL)) {
        echo '<script type="text/javascript">window.location = "?tambah-tema&pesan=urldemotidakvalid"</script>';
        exit();
      }
    }
    if (!filter_var($url_download, FILTER_VALIDATE_URL)) {
      echo '<script type="text/javascript">window.location = "?tambah-tema&pesan=urldownloadtidakvalid"</script>';
      exit();
    }
    if (empty($tag)) {
      echo '<script type="text/javascript">window.location = "?tambah-tema&pesan=harusmenyertakanfitur"</script>';
      exit();
    }
    if (isset($_SESSION['adm1n'])) {
      $author = 1;
    } else {
      $author = '';
    }
    if ($kategori == '1') {
      $kategori = '1';
    } elseif ($kategori == '2') {
      $kategori = '2';
    } elseif ($kategori == '0') {
      echo '<script type="text/javascript">window.location = "?tambah-tema&pesan=andaharusmemilihkategori"</script>';
      exit();
    } else {
      echo '<script type="text/javascript">window.location = "?tambah-tema&pesan=kategoritidakditemukan"</script>';
      exit();
    }
    // echo $kategori;
    $queryvalid = mysqli_query($db_kon, "SELECT * FROM `lxl_link_download` WHERE kode_url='$kodeweb' LIMIT 1");
    $validator  = mysqli_num_rows($queryvalid);
    if ($validator > "0") {
      $kodeweb = RandomKode(6);
    } else {
      $query = mysqli_query($db_kon, "INSERT INTO `lxl_link_download` (kode_url, ip_user, url_download, url_demo, title, author, deskripsi, tag, kategori, download, pengunjung, date) VALUES('$kodeweb', '$ip_u','$url_download', '$url_demo', '$title','$author', '$deskripsi', '$tag', '$kategori', '0', '0', NOW())");
      // var_dump($query);
      echo '<script type="text/javascript">window.location = "?detail='.$kodeweb.'"</script>';
    }
  } else {
    echo '<script type="text/javascript">window.location = "?tambah-tema=0"</script>';
  }
}
// end all query tema baru

// Menampilkan hasil query pencarian
$halaman = 12;
$page = isset($_GET['halaman'])? (int)$_GET["halaman"]:1;
$page = addslashes(htmlspecialchars($page));
$mulai = ($page>1) ? ($page * $halaman) - $halaman : 0;
$mulai = addslashes(htmlspecialchars($mulai));
if(isset($_GET['query'])){
  $cari = addslashes(htmlspecialchars($_GET['query']));
  if (!empty($cari)) {
    $sql = mysqli_query($db_kon,"select * from lxl_link_download where title like '%".$cari."%' OR deskripsi like '%".$cari."%' OR tag like '%".$cari."%' LIMIT $mulai, $halaman");
  } else {
    echo '<script type="text/javascript">window.location = "?error=no-keyword"</script>';
      exit;
    }
}
$query = mysqli_query($db_kon,"select * from lxl_link_download LIMIT $mulai, $halaman");
$counth = mysqli_query($db_kon,"select * from lxl_link_download where title like '%".$cari."%' OR deskripsi like '%".$cari."%' OR tag like '%".$cari."%'");
$total = mysqli_num_rows($counth);
$pages = ceil($total/$halaman);
// var_dump($sql);
// end of hasil query pencarian
// end of all query

// sitemap
if (isset($_GET['sitemap'])) {
  header('Content-Type: application/xml');
  $sql_sitemap = mysqli_query($db_kon, "SELECT * FROM lxl_link_download");
  echo "<?xml version='1.0' encoding='UTF-8'?>"."\n";
  echo "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>"."\n";
  echo "<url>
          <loc>$base_url</loc>
          <lastmod>2020-08-10T18:00:15+00:00</lastmod>
          <changefreq>daily</changefreq>
        </url>";
  while($row=mysqli_fetch_array($sql_sitemap))
  {
   echo "<url>";
   echo "<loc>".$base_url."/?detail=".$row['kode_url']."</loc>";
   echo "<lastmod>".$row['date']."</lastmod>";
   echo "<changefreq>daily</changefreq>";
   echo "</url>";
  }

  echo "</urlset>";
  exit();
}
// end of sitemap

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
  <meta name="description" content="<?= $app_nama; ?> merupakan website untuk mencari template blogger dan wordpress">
  <meta name="keywords" content="Blogger, Wordpress,Template,Tema,Premium,Free">
  <meta name="author" content="Lutfi Anam">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> 
<?php
if (isset($_GET['query'])) {
    $key = addslashes(htmlspecialchars($_GET['query']));
    echo substr(stripslashes($key), 0, '36')." . . . |";
  } elseif (isset($_GET['detail'])) {
    $code = addslashes(htmlspecialchars($_GET['detail']));
    $qdetail = mysqli_query($db_kon, "SELECT * FROM lxl_link_download WHERE kode_url='$code'");
    $fdetail = mysqli_fetch_array($qdetail);
    $jt_pengunjung = $fdetail['pengunjung']+'1';
    $q_jtpengunjung = mysqli_query($db_kon,"UPDATE lxl_link_download SET pengunjung='$jt_pengunjung' WHERE kode_url='$code'");
    echo stripslashes(htmlspecialchars($fdetail['title']))." |";
  } elseif (isset($_GET['redirect']) && $_SESSION['token'] == $_GET['token']) {
    $code = addslashes(htmlspecialchars($_GET['redirect']));
    $qdetail = mysqli_query($db_kon, "SELECT * FROM lxl_link_download WHERE kode_url='$code'");
    $fdetail = mysqli_fetch_array($qdetail);
      if ($_GET['type'] == 'demo') {
        echo "Demo ".addslashes(htmlspecialchars($fdetail['title']))." |";
      } elseif ($_GET['type'] == 'download') {
        $jt_download = $fdetail['download']+'1';
        $q_jtdownloas = mysqli_query($db_kon,"UPDATE lxl_link_download SET download='$jt_download' WHERE kode_url='$code'");
        echo "Download ".addslashes(htmlspecialchars($fdetail['title']))." |";
      }
}
?> <?= $app_nama; ?> - Tools Search Engine </title>

  <!-- CSS  -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <style type="text/css">
    .btn-block {
      width: 100%;
    }
    .wrapper {
      align-items:center;
      margin: 150px auto;
    }
    .spasi {
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <nav class="light-green lighten-1" role="navigation">
    <div class="nav-wrapper container"><a id="logo-container" href="<?= $base_url; ?>" class="brand-logo"><?= $app_nama; ?></a>
      <ul class="right hide-on-med-and-down">
        <li><a href="<?= $base_url; ?>/?tambah-tema">Tambah Tema</a></li>
      </ul>

      <ul id="nav-mobile" class="sidenav">
        <li><a href="<?= $base_url; ?>/?tambah-tema">Tambah Tema</a></li>
      </ul>
      <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
    </div>
  </nav>
<?php
if(isset($_GET['query']) OR isset($_GET['detail']) OR isset($_GET['redirect']) OR isset($_GET['error']) OR isset($_GET['tambah-tema'])){
    $detail = addslashes(htmlspecialchars($_GET['detail']));
    $error = addslashes(htmlspecialchars($_GET['error']));
    $redirect = addslashes(htmlspecialchars($_GET['redirect']));
    $sitemap = addslashes(htmlspecialchars($_GET['sitemap']));
    $query = addslashes(htmlspecialchars($_GET['query']));

    if (isset($_GET['query'])) {
      // pembersihan keyword
      $pencarian = substr($query, 0, '36');
      // end of pembersihan keyword
      // log pencarian
      if(preg_match("/(<|'|>)/i", $_GET['query'])) {
        echo '<script type="text/javascript">window.location = "?error=attacker&pesan=bandel"</script>';
        exit();
      }
      $log_pencarian = mysqli_query($db_kon, "SELECT * FROM `lxl_keyword` WHERE keyword='$pencarian' LIMIT 1");
      $log = mysqli_fetch_array($log_pencarian);
      $validator_log  = mysqli_num_rows($log_pencarian);
      if ($validator_log > "0") {
        $update_log = $log['jumlah']+'1';
        $q_jtpengunjung = mysqli_query($db_kon,"UPDATE lxl_keyword SET jumlah='$update_log' WHERE keyword='$pencarian'");
      } else {
        $log_pencarian = mysqli_query($db_kon,"INSERT INTO `lxl_keyword` (keyword, jumlah) VALUES('$pencarian', '0')");
      }
      // end of log pencarian
?>
  <div class="row">
    <?php
    if ($total == '0') {
?>
<div class="container">
  <blockquote class="wrapper">
    <h2 class="header center red-text">Belum ada tema</h2>
    <p class="center">
      Mohon maaf belum ada tema yang ditemukan dengan kata kunci "<?= stripslashes($pencarian); ?>".
    </p>
  </blockquote>
</div>
<?php
    } else {
    // echo $total;
    ?>
    <h3 class="header center green-text">"<?= stripslashes($pencarian); ?>"</h3>
    <div class="col s12 m12">
      <div class="container">
      <div class="card">
        <div class="card-content green-text">
          <span class="card-title">Hasil Pencarian</span>      
            <table class="striped responsive">
              <thead>
                <tr>
                    <th>No.</th>
                    <th>Type</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Penulis</th>
                    <th>Aksi</th>
                </tr>
              </thead>

              <tbody>
<?php
  $no = 1;
  while($link = mysqli_fetch_array($sql)){
?>
                <tr>
                  <td><?= $no; ?></td>
                  <td>
<?php
/*
if ($link['kategori'] == '1') {
  echo '<img class="materialboxed" width="17" src="icons/blogger-brands.svg">';
} elseif ($link['kategori'] == '2') {
  echo '<img class="materialboxed" width="17" src="icons/wordpress-brands.svg">';
}
*/
if ($link['kategori'] == '1') {
  echo '<img class="materialboxed" width="17" src="https://img.icons8.com/ios-filled/50/000000/blogger.png">';
} elseif ($link['kategori'] == '2') {
  echo '<img class="materialboxed" width="17" src="https://img.icons8.com/windows/32/000000/wordpress.png">';
}
?>  
                  </td>
                  <td><?= stripslashes(htmlspecialchars(substr($link['title'], '0', '26'))); ?>...</td>
                  <td><?= stripslashes(htmlspecialchars(substr($link['deskripsi'], '0', '32'))); ?>...</td>
                  <td>
<?php
  if ($link['author'] == 1) {
    echo "Admin";
  } else {
    echo "Anonim";  
  }
?>
                  </td>
                  <td><a href="?detail=<?= addslashes(htmlspecialchars($link['kode_url'])); ?>" class="green-text"><i class="material-icons prefix">file_download</i></a></td>
                  <?php $no++; ?>
                </tr>
<?php
  }
?>
              </tbody>
            </table>
        </div>
        <div class="card-action">
          <ul class="pagination center">
            <li class="disabled"><a href="#!"><i class="material-icons">chevron_left</i></a></li>
<?php
// pagination
for ($i=1; $i<=$pages ; $i++){
?>
            <li class="waves-effect"><a href="?query=<?= stripslashes(htmlspecialchars($pencarian)); ?>&halaman=<?= $i; ?>"><?= $i; ?></a></li>
<?php 
}
// end of pagination
?>
            <li class="waves-effect"><a href="#!"><i class="material-icons">chevron_right</i></a></li>
          </ul>
        </div>
      </div>
      </div>
    </div>
  </div>
<?php
    }
    }

    if (isset($_GET['tambah-tema'])) {
?>
<br><br>
<div class="container">
    <blockquote class="green-text">
      <h3>Tambah tema</h3>
    </blockquote>
<form action="" method="POST">
  <div class="row">
    <div class="input-field col s12">
      <i class="material-icons prefix">mode_edit</i>
      <input id="icon_prefix" type="text" class="validate" pattern="[^<>]+" title="Jangan gunakan kurung siku" name="title" required>
      <label for="icon_prefix">Judul*</label>
    </div>
    <div class="col s12">
      <div class="row">
        <div class="input-field col s6">
          <i class="material-icons prefix">important_devices</i>
          <input id="icon_prefix" type="url" class="validate" name="link-demo" pattern="https?://.+" title="Sertakan http:// atau https://">
          <label for="icon_prefix">URL Demo</label>
        </div>
        <div class="input-field col s6">
          <i class="material-icons prefix">file_download</i>
          <input id="icon_telephone" type="url" class="validate" name="link-download" pattern="https?://.+" title="Sertakan http:// atau https://" required>
          <label for="icon_telephone">URL Download*</label>
        </div>
      </div>
      <label>Kategori tema*</label>
      <select class="browser-default" name="category" pattern="[0-2]{1}" required>
        <option value="0" disabled selected>Pilih salah satu</option>
        <option value="1">Blogger</option>
        <option value="2">Wordpress</option>
      </select>
      <div class="row">
        <div class="col s12">
          <div class="row">
            <div class="input-field col s12">
              <i class="material-icons prefix">info</i>
              <textarea id="textarea1" class="materialize-textarea" name="deskripsi"></textarea>
              <label for="textarea1">Deskripsi</label>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <i class="material-icons prefix">label</i>
          <input id="email" type="text" class="validate" name="tag" required>
          <label for="email">Fitur*</label>
        </div>
      </div>
      <p>
        <label>
          <input type="checkbox" name="accept" required/>
          <span>Setuju dengan syarat & ketentuan*.</span>
        </label>
        <input type="hidden" name="token" value="<?= $token; ?>">
      </p>
      <div class="right">
        <button class="btn waves-effect waves-light green" type="submit" name="statuse">Tambah
          <i class="material-icons right">add</i>
        </button>
      </div>
    </div>
  </div>
</form>
  <div class="row">
    <div class="col s12">
      <div class="card-panel green">
        <span class="white-text">
          <h6 class="header white-text">Cara menambahkan tema baru :</h6>
          <ul>
            <li>1. Masukkan judul atau nama template pada (<i class="material-icons prefix">mode_edit</i>).</li>
            <li>2. Masukkan url menuju demo template pada (<i class="material-icons prefix">important_devices</i>) usahakan url langsung menuju ke demo (untuk keperluan pengembangan).</li>
            <li>3. Masukkan url menuju download template pada (<i class="material-icons prefix">file_download</i>).</li>
            <li>4. Pilih kategori tema yang akan kalian ingin tambahkan.</li>
            <li>5. Berikan deskripsi template pada (<i class="material-icons prefix">info</i>).</li>
            <li>6. Masukkan tag template pada (<i class="material-icons prefix">mode_edit</i>) pisahkan kata menggunakan tanda koma (contoh responsive,seofriendly).</li>
            <li>7. klik setuju dengan syarat dan ketentuan.</li>
            <li>Tekan atau klik tombol "TAMBAH" jika tema berhasil di tambahkan maka kamu akan langsung di redirect ke halamannya.</li>
            <br>
            <li>(*) = Tidak boleh kosong.</li>
          </ul>
        </span>
      </div>
    </div>
  </div>

</div>
<br><br>
<?php
    }

    if (isset($_GET['detail'])) {
      $code = addslashes(htmlspecialchars($_GET['detail']));
      $qdetail = mysqli_query($db_kon, "SELECT * FROM lxl_link_download WHERE kode_url='$code'");
      $fdetail = mysqli_fetch_array($qdetail);
      $detail = $fdetail;
      $validetail  = mysqli_num_rows($qdetail);
      // echo $validetail;
        if ($validetail == "0") {
          die('<script type="text/javascript">window.location = "?error=no-data"</script>');
        }
?>
<div class="container">
  <blockquote class="green-text">
    <h3 class="header green-text"><?= stripslashes(htmlspecialchars($detail['title'])); ?></h3>
    <p>Dilihat <?= $detail['pengunjung']; ?> Kali | Diunduh <?= $detail['download']; ?> Kali | Diupload Pada <?= $detail['date']; ?> Oleh
<?php
  if ($detail['author'] == 1) {
    echo "Admin";
  } else {
    echo "Anonim";  
  }
?>
    </p>
  </blockquote>
  <div class="row">
    <div class="col s12 m6">
      <div class="col s6">
<?php
  if ($detail['url_demo'] == '') {
?>
        <a class="btn btn-block waves-effect disabled"><i class="material-icons right">important_devices</i>No Demo</a>

<?php
  } else {
?>
        <a class="btn btn-block waves-effect waves-light blue" href="?redirect=<?= stripslashes(htmlspecialchars($detail['kode_url'])); ?>&type=demo&token=<?= $token; ?>" target="_blank"><i class="material-icons right">important_devices</i>Demo</a>
<?php
  }
?>
      </div>
      <div class="col s6">
        <a class="btn btn-block waves-effect waves-light green" href="?redirect=<?= stripslashes(htmlspecialchars($detail['kode_url'])); ?>&type=download&token=<?= $token; ?>" target="_blank"><i class="material-icons right">file_download</i>Unduh</a>
      </div>
      <div class="col s12"><br><?= stripslashes(htmlspecialchars($detail['deskripsi'])); ?></div>
    </div>
    <div class="col s12 m6">
      <ul class="collection with-header">
        <li class="collection-header green"><h6 class="white-text">Fitur</h6></li>
<?php
$tag = explode(',', $detail['tag']);
for($x=0;$x<count($tag);$x++){
  echo '<li class="collection-item">'.stripslashes(htmlspecialchars($tag[$x])).'</li>';
}
?>
      </ul>
    </div>
  </div>
</div>
<?php
    }

    if (isset($_GET['redirect'])) {
      $query_redirect = mysqli_query($db_kon, "SELECT * FROM lxl_link_download WHERE kode_url='$redirect'");
      $sql_redirect = mysqli_fetch_array($query_redirect);
      $type = stripslashes(htmlspecialchars($_GET['type']));
      if (isset($_GET['token']) == $_SESSION['token']) {
        switch ($type) {
          case 'demo':
            die('<script type="text/javascript">window.location = "'.stripslashes(htmlspecialchars($sql_redirect['url_demo'])).'"</script>');
            break;
          case 'download':
            die('<script type="text/javascript">window.location = "'.stripslashes(htmlspecialchars($sql_redirect['url_download'])).'"</script>');
            break;    
          default:
            die('<script type="text/javascript">window.location = "?error=tidakadadata"</script>');
            break;
        }
      } else {
        die('<script type="text/javascript">window.location = "?error=tokensalah"</script>');
      }
    }

    if (isset($_GET['error'])) {
      switch ($error) {
        case 'no-data':
?>
<div class="container">
  <blockquote class="wrapper">
    <h2 class="header center red-text">Tidak ada data</h2>
    <p class="center">
      Mohon maaf tidak ada data yang ditemukan.
    </p>
  </blockquote>
</div>
<?php
          break;
        
        case 'no-keyword':
?>
<div class="container">
  <blockquote class="wrapper">
    <h2 class="header center red-text">Tidak ada keyword</h2>
    <p class="center">
      Anda harus memasukkan keyword pencarian.
    </p>
  </blockquote>
</div>
<?php
          break;

        case 'attacker':
?>
<div class="container">
  <blockquote class="wrapper">
    <h2 class="header center red-text">Waduh heker ya?</h2>
    <p class="center">
      Percuma kang kalo pentest web ini, gak dapet bounty apa2.
    </p>
  </blockquote>
</div>
<?php
          break;

        default:
?>
<div class="container">
  <blockquote class="wrapper">
    <h2 class="header center red-text">Error 404</h2>
    <p class="center">
      Mohon maaf kami tidak menemukan apa2.
    </p>
  </blockquote>
</div>
<?php
          break;
      }
    }

  }else{
?>
  <div class="section no-pad-bot" id="index-banner">
    <div class="container">
      <br><br>
      <h1 class="header center green-text"><?= $app_nama; ?></h1>
      <div class="row center">
        <h5 class="header col s12 light">Alat Pencarian No 1 Di Hati</h5>
      </div>
      <div class="row">
        <form class="col s12" action="" method="get">
          <div class="row">
            <div class="input-field col s12">
              <i class="material-icons prefix">search</i>
              <input id="icon_prefix" type="text" class="validate" name="query" pattern="[^'<>]+" title="Mohon jangan gunakan kutip dan kurung siku" required>
              <label for="icon_prefix">Keyword</label>
            </div>
              <h6 class="left col s12 spasi">Pencarian Terbanyak :</h6>
            <div class="row center">
<?php
  $hot_pencarian = mysqli_query($db_kon,"select keyword from lxl_keyword order by jumlah desc LIMIT 7");
  while ($hot = mysqli_fetch_array($hot_pencarian)) {
    // var_dump($hot);
    echo '<a href="?query='.stripslashes(htmlspecialchars($hot['keyword'])).'" class="chip">'.stripslashes(htmlspecialchars(ucwords($hot['keyword']))).'</a>';
  }
?>
            </div>
          </div>
        </form>
      </div>
      <br><br>

    </div>
  </div>

  <div class="container">
    <div class="section">

      <!--   Icon Section   -->
      <div class="row">
        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center light-green-text"><i class="material-icons">search</i></h2>
            <h5 class="center">Cari</h5>

            <p class="light">Cari apapun yang kamu inginkan disini insyaallah ada ( eksplorasikan kata2 kamu disini sebagai motto dari website kamu ).</p>
          </div>
        </div>

        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center light-green-text"><i class="material-icons">cloud_download</i></h2>
            <h5 class="center">Unduh</h5>

            <p class="light">Unduh file yang kamu mau disini ( eksplorasikan kata2 kamu disini sebagai motto dari website kamu ).</p>
          </div>
        </div>

        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center light-green-text"><i class="material-icons">sync</i></h2>
            <h5 class="center">Berbagi</h5>

            <p class="light">Berbagi dengan sesama disini ( eksplorasikan kata2 kamu disini sebagai motto dari website kamu ).</p>
          </div>
        </div>
      </div>

    </div>
    <br><br>
  </div>
<?php
  }
?>

  <footer class="page-footer green">
    <div class="container">
      <div class="row">
        <div class="col l6 s12">
          <h5 class="white-text">Apa Itu <?= $app_nama; ?>?</h5>
          <p class="grey-text text-lighten-4">Silahkan ganti paragraf ini dengan deskripsi dari web kamu, deskripsikan dengan bahasa yang jelas agar mudah untuk di mengerti dan menambah seo.</p>
        </div>
        <div class="col l3 s12">
          <h5 class="white-text">Settings</h5>
          <ul>
            <li><a class="white-text" href="<?= $base_url; ?>/?sitemap">SiteMap</a></li>
          </ul>
        </div>
        <div class="col l3 s12">
          <h5 class="white-text">Tetap Terhubung</h5>
          <ul>
            <li><a class="white-text" href="https://facebook.com/">FP : TemaKu</a></li>
            <li><a class="white-text" href="https://instagram.com/lutf1anam">IG : @lutf1anam</a></li>
            <li><a class="white-text" href="https://github.com/lutfianam">Github : lutfianam</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="footer-copyright">
      <div class="container">
      <!-- jangan hapus bagian ini hargailah pembuat -->
      Development By <a class="orange-text text-lighten-3" href="<?= $base_url; ?>"><?= $app_nama; ?></a> And Created With Love By <a class="orange-text text-lighten-3" href="https://github.com/lutfianam">LinaX</a>
      <!-- jangan hapus bagian ini hargailah pembuat -->
      </div>
    </div>
  </footer>

  <!--  Scripts-->
  <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script src="js/materialize.js"></script>
  <script src="js/init.js"></script>

  </body>
</html>
