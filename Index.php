<?php
$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "akademik";

$koneksi    = mysqli_connect($host,$user,$pass,$db);
if(!$koneksi){
    //cek koneksi
    die("Tidak bisa terkoneksi ke database"); 
}
$nim        ="";
$nama       ="";
$alamat     ="";
$jurusan    ="";
$sukses     ="";
$error      ="";

if(isset($_GET['op'])){
    $op = $_GET['op'];
}else{
    $op = "";
}

if($op == 'delete'){
    $id     = $_GET['id'];
    $sql1   = "delete from mahasisiwa where id = '$id'";
    $q1     = mysqli_query($koneksi, $sql1);
    if($q1){
        $sukses = "Data berhasil di hapus";
    }else{
        $error  = "Gagal menghapus data";
    }
}

if($op == 'edit'){
    $id         =$_GET['id'];
    $sql1       = "select * from mahasisiwa where id = '$id'";
    $q1         = mysqli_query($koneksi,$sql1);
    $r1         = mysqli_fetch_array($q1);
    $nim        =$r1['nim'];
    $nama       =$r1['nama'];
    $alamat     =$r1['alamat'];
    $jurusan    =$r1['jurusan'];

    if($nim == ''){
        $error = "Data tidak Ditemukan";
    }
}

if(isset($_POST['simpan'])){//untuk Creat
    $nim        = $_POST['nim'];
    $nama       = $_POST['nama'];
    $alamat     = $_POST['alamat'];
    $jurusan    = $_POST['jurusan'];

    if ($nim && $nama && $alamat && $jurusan) {
        if ($op == 'edit') { //untuk update
            $sql1       = "update mahasisiwa set nim = '$nim',nama='$nama',alamat = '$alamat',jurusan='$jurusan' where id = '$id'";
            $q1         = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Data berhasil diupdate";
            } else {
                $error  = "Data gagal diupdate";
            }
        } else { //untuk insert
            $sql1   = "insert into mahasisiwa(nim,nama,alamat,jurusan) values ('$nim','$nama','$alamat','$jurusan')";
            $q1     = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses     = "Berhasil memasukkan data baru";
            } else {
                $error      = "Gagal memasukkan data";
            }
        }
    } else {
        $error = "Silakan masukkan semua data";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akademik Sistem Informasi Kelautan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
        .mx-auto{width: 800px ;}
        .card{margin-top: 10px; }
    </style>
</head>
<body>
    <div class="mx-auto">
    <!--Untuk Memasukan Data-->
    <div class="card">
        <div class="card-header">
            Creat/ Edit Data
        </div>
        <div class="card-body">
            <?php 
            if($error){
            ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error ?>
                </div>
            <?php
            }
            ?>

            <?php 
            if($sukses){
            ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $sukses ?>
                </div>
            <?php
            }
            ?>
           <form action="" method="POST">
                <div class="mb-3">
                    <label for="nim" class="col-sm-2 col-form-label">NIM</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nim" name="nim" value="<?php echo $nim ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="alamat"  class="col-sm-2 col-form-label">Alamat</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $alamat ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="jurusan"  class="col-sm-2 col-form-label">Matakuliah</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="jurusan" name="jurusan">
                            <option value="">-Pilih MK Favorit-</option>
                            <option value="INDRAJA"<?php if($jurusan =="INDRAJA") echo "selected"?>>INDRAJA</option>
                            <option value="EKOLOGI LAUT"<?php if($jurusan =="EKOLOGI LAUT") echo "selected"?>>EKOLOGILAUT</option>
                            <option value="OCEANOGRAFI"<?php if($jurusan =="OCEANOGRAFI") echo "selected"?>>OCEANOGRAFI</option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <input type="submit" name="simpan" id="simpan" value="simpan data" class="btn btn-primary">
                </div>
           </form>
        </div>  
        <!--Untuk Mengeluarkan Data-->
        <div class="card">
        <div class="card-header text-white bg-secondary">
            Data Mahasiswa
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">NIM</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Alamat</th>
                        <th scope="col">Matakuliah</th>
                        <th scope="col">Aksi</th>
                    </tr>
                    <tbody>
                        <?php 
                        $sql2   = "select * from mahasisiwa order by id desc";
                        $q2     = mysqli_query($koneksi, $sql2);
                        $urut   = 1;
                        while($r2 =  mysqli_fetch_array($q2)){
                            $id         = $r2['id'];
                            $nim        = $r2['nim'];
                            $nama       = $r2['nama'];
                            $alamat     = $r2['alamat'];
                            $jurusan    = $r2['jurusan'];
                            ?>
                            <tr>
                                <th scope="row"><?php echo $urut++ ?>
                                </th>
                                <td scope="row"><?php echo $nim ?></td>
                                <td scope="row"><?php echo $nama ?></td>
                                <td scope="row"><?php echo $alamat ?></td>
                                <td scope="row"><?php echo $jurusan ?></td>
                                <td scope="row">
                                    <a href="index.php?op=edit&id=<?php echo $id?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                    <a href="index.php?op=delete&id=<?php echo $id?>" onclick="return confirm('Yakin ingin menghapus data?')"> <button type="button" class="btn btn-danger">Delete</button></a>
                                   
                                </td>

                            </tr>
                            <?php


                        }
                        ?>  
                </thead>
            </table>
        </div>
    </div>
</body>
</html>