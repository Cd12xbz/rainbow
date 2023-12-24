<?php
	session_start();
	require 'config.php';
	require_once 'tcpdf/tcpdf.php';

	if (isset($_POST['pid'])) {
		$pid = $_POST['pid'];
		$pnama = $_POST['pnama'];
		$pprice = $_POST['pprice'];
		$pimage = $_POST['pimage'];
		$pcode = $_POST['pcode'];
		$pqty = $_POST['pqty'];
		$total_price = $pprice * $pqty;

		$stmt = $conn->prepare('SELECT product_code FROM cart WHERE product_code=?');
		$stmt->bind_param('s', $pcode);
		$stmt->execute();
		$res = $stmt->get_result();
		$r = $res->fetch_assoc();
		$code = $r['product_code'] ?? '';

		if (!$code) {
			$query = $conn->prepare('INSERT INTO cart (product_nama,product_price,product_image,qty,total_price,product_code) VALUES (?,?,?,?,?,?)');
			$query->bind_param('ssssss', $pnama, $pprice, $pimage, $pqty, $total_price, $pcode);
			$query->execute();

			echo '<div class="alert alert-success alert-dismissible mt-2">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong>Item Ditambahkan!</strong>
			</div>';
		} else {
			echo '<div class="alert alert-danger alert-dismissible mt-2">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong>Item Sudah Ditambahkan!</strong>
			</div>';
		}
	}

	if (isset($_GET['cartItem']) && $_GET['cartItem'] == 'cart_item') {
		$stmt = $conn->prepare('SELECT * FROM cart');
		$stmt->execute();
		$stmt->store_result();
		$rows = $stmt->num_rows;

		echo $rows;
	}

	if (isset($_GET['remove'])) {
		$id = $_GET['remove'];

		$stmt = $conn->prepare('DELETE FROM cart WHERE id=?');
		$stmt->bind_param('i', $id);
		$stmt->execute();

		$_SESSION['showAlert'] = 'block';
		$_SESSION['message'] = 'Item dihapus dari keranjang!';
		header('location:cart.php');
	}

	if (isset($_GET['clear'])) {
		$stmt = $conn->prepare('DELETE FROM cart');
		$stmt->execute();
		$_SESSION['showAlert'] = 'block';
		$_SESSION['message'] = 'Semua item dihapus dari keranjang!';
		header('location:cart.php');
	}

	if (isset($_POST['qty'])) {
		$qty = $_POST['qty'];
		$pid = $_POST['pid'];
		$pprice = $_POST['pprice'];

		$tprice = $qty * $pprice;

		$stmt = $conn->prepare('UPDATE cart SET qty=?, total_price=? WHERE id=?');
		$stmt->bind_param('isi', $qty, $tprice, $pid);
		$stmt->execute();
	}

	if (isset($_POST['action']) && $_POST['action'] == 'order') {
		$nama = $_POST['nama'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$products = $_POST['products'];
		$grand_total = $_POST['grand_total'];
		$address = $_POST['address'];
		$pmode = $_POST['pmode'];
		$tgl_pesanan = $_POST['tanggal_pesanan'];

		$data = '';

		$stmt = $conn->prepare('INSERT INTO orders (nama,email,phone,address,pmode,products,amount_paid, tanggal_pesanan)VALUES(?,?,?,?,?,?,?,?)');
		$stmt->bind_param('ssssssss', $nama, $email, $phone, $address, $pmode, $products, $grand_total,$tgl_pesanan);
		$stmt->execute();
		$stmt2 = $conn->prepare('DELETE FROM cart');
		$stmt2->execute();
		$data .= '<div class="text-center">
			<h1 class="display-4 mt-2 text-danger">Terima Kasih!</h1>
			<h2 class="text-success">Pesanan Anda Berhasil!</h2>
			<h4 class="bg-danger text-light rounded p-2">Item : ' . $products . '</h4>
			<h4>Nama : ' . $nama . '</h4>
			<h4>E-mail : ' . $email . '</h4>
			<h4>Telepon : ' . $phone . '</h4>
			<h4>Tanggal Pesanan : ' . $tgl_pesanan . '</h4>
			<h4>Jumlah Total Pembayaran : ' . number_format($grand_total, 2) . '</h4>
			<h4>Metode Pembayaran : ' . $pmode . '</h4>
			<img src="image/Qris.jpeg"  width= "250px">
			<h5 class="text-success">SCREENSHOT BAGIAN INI DAN KIRIMKAN KE NOMOR WA YANG TERSEDIA</h5>
			<h2 class="text-success"><a href="https://wa.me/6288901846686" target="_blank">0889-0184-6686</a></h2>
		</div>';
		echo $data;
	}
?>
