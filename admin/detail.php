<?php
    include '../class_db.php';
    $db = new database();

    $id = $_GET['id'];

    $sql = "SELECT * FROM orders WHERE id = '$id'";
    $dat = $db->datasql($sql);
?>

<html>
    <head>
        <title>Detail Data Orders</title>
        <link rel="stylesheet" href="../dist/css/edit.css">
    </head>
    <body>
        <h2>Detail Data Orders</h2>
        
        <?php
            include 'menu.php';
        ?><br><br>

        <form action="delete.php" method="POST">

            <div class="form-group">
                <label for="nama">Nama</label><br>
                <input type="text" name="nama" id="nama" value="<?= $dat['nama'] ?>"><br><br>
            </div>

            <div class="form-group">
                <label for="No_Hp">No_Hp</label><br>
                <input type="text" name="phone" id="phone" value="<?= $dat['phone'] ?>"><br><br>
            </div>

            <div class="form-group">
                <label for="Alamat">Alamat</label><br>
                <textarea name="address" id="address" rows="4" cols="50"><?= $dat['address'] ?></textarea><br><br>
            </div>

            <div class="form-group">
                <label for="tangal">Tanggal Pesanan</label><br>
                <input type="date" name="tanggal_pesanan" id="tanggal_pesanan" value="<?= $dat['tanggal_pesanan'] ?>"><br><br>
            </div>

            <div class="form-group">
                <input type="submit" name="submit_delete" value="HAPUS" onclick="return confirm('Yakin Hapus Data?')">
                <input type="submit" name="submit_edit" value="SIMPAN" onclick="return confirm('Yakin Simpan Data?')">
                <input type="hidden" name="id_old" value="<?= $dat['id'] ?>">
            </div>
        </form>
    </body>
</html>
