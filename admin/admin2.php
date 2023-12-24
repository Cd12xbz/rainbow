<?php
	include 'cek_login.php';
	include '../class_db.php';
	$db = new database();

	// Tentukan jumlah data per halaman
	$per_page = 25;

	if (isset($_POST['cari'])) {
		$nama = $_POST['nama'];
		$id = $_POST['id'];

	} else {
		$nama = '';
		$id = '';
	}

	// Ambil nilai halaman dari parameter URL, atau set default ke 1
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
?>

<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="../dist/css/admin.css">
		<title>Data Orders</title>
	</head>
	<body>
		<h2>Data ORDER</h2> 
			<?php
				include 'menu.php';
			?><br><br>
		User: <?= $_SESSION['username'] ?><br>
		<form method="post" action=""> 
			<input type="text" name="nama" value="<?= $nama ?>" placeholder="nama">
			<input type="text" name="id" value="<?= $id ?>" placeholder="id">
			<input type="submit" name="cari" value="Cari"><br><br>
		</form>

		<table width="100%" align="center" border="1">
			<thead>
				<tr>
					<th width="5%">#</th>
					<th width="3%">No.</th>
					<th width="3%">ID</th>
					<th width="15%">Nama</th>
					<th width="9%">Email</th>
					<th width="10%">No_Hp</th>
					<th width="15%">Alamat</th>
					<th width="6%">Metode</th>
					<th width="15%">Produk</th>
					<th width="8%">Total Bayar</th>
					<th width="8%">Tanggal Beli</th>
					<th width="7%">Tanggal Pesanan</th>
					<th width="5%">Cetak</th>
				</tr>
			</thead>
			<tbody>
				<?php
				// Ubah query SQL untuk mengambil data pesanan pada hari ini, diurutkan dari yang terbaru
				$sql = "SELECT *
						FROM orders
						WHERE nama LIKE '%$nama%'
						AND id LIKE '%$id%'
						ORDER BY id DESC
						LIMIT $per_page";
				$data = $db->fetchdata($sql);
				$i = ($page - 1) * $per_page + 1;
				foreach ($data as $dat) {
					$tanggal_beli = date_format(date_create($dat['tanggal_beli']), 'd-m-Y');
					$tanggal_pesanan = date_format(date_create($dat['tanggal_pesanan']), 'd-m-Y');

					echo "<tr>
							<td><a href='detail.php?id=".$dat['id']."'>Edit</a></td>
							<td>$i</td>
							<td>" . $dat['id'] . "</td>
							<td>" . $dat['nama'] . "</td>
							<td>" . $dat['email'] . "</td>
							<td>" . $dat['phone'] . "</td>
							<td>" . $dat['address'] . "</td>
							<td>" . $dat['pmode'] . "</td>
							<td>" . $dat['products'] . "</td>
							<td>Rp " . number_format($dat['amount_paid'], 2) . "</td>
							<td>$tanggal_beli</td>
							<td>$tanggal_pesanan</td>
							<td><button onclick=\"cetakData('" . $dat['id'] . "')\">Cetak</button></td>
						</tr>";
					$i++;
				}
				?>
			</tbody>
		</table><br><br>

		<!-- Tombol Print PDF Hari Ini -->
		<a href="print_all.php?today=true" target="_blank"><button>Print Data Orders</button></a>

		<!-- Tombol Pagination -->
		<div style='text-align: center; margin-top: 10px;'>
			<?php
			$total_pages = ceil(count($data) / $per_page);
			for ($i = 1; $i <= $total_pages; $i++) {
				echo "<a href='?page=$i'>$i</a> ";
			}
			?>
		</div>

		<script>
			function cetakData(orderId) {
				window.location.href = 'cetak.php?id=' + orderId;
			}
		</script>
	</body>
</html>
