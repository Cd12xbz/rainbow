<?php
include '../class_db.php';
$db = new database();

if (isset($_POST['submit_edit'])) {
    $nama = $_POST['nama'];
    $no_hp = $_POST['phone'];
    $alamat = $_POST['address'];
    $tanggal_pesanan = $_POST['tanggal_pesanan'];

    $id_old = $_POST['id_old'];

    $sql = "UPDATE orders SET nama = '$nama', phone = '$no_hp', address = '$alamat', tanggal_pesanan = '$tanggal_pesanan'
            WHERE id = '$id_old'";
    if (!$db->sqlquery($sql)) {
        die('Update data Gagal' . $sql);
    } else {
        header("Location: admin.php");
        exit();
    }
}

if (isset($_POST['submit_delete'])) {
    $id = $_POST['id_old'];

    $sql = "DELETE FROM orders WHERE id = '$id'";
    if (!$db->sqlquery($sql)) {
        die('Delete data Gagal' . $sql);
    } else {
        header("Location: admin.php");
        exit();
    }
}
?>