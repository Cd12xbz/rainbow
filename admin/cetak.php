<?php
require_once('../tcpdf/tcpdf.php');

if (isset($_GET['id'])) {
    $orderId = $_GET['id'];

    // Ambil data order dari database berdasarkan ID
    include '../class_db.php';
    $db = new database();
    $sql = "SELECT *
            FROM orders
            WHERE id = '$orderId'";
    $orderData = $db->fetchdata($sql);

    // Cetak PDF
    if (!empty($orderData)) {
        // Extend TCPDF untuk menentukan header dan footer
        class MYPDF extends TCPDF {
            private $storeName; // Menyimpan nama toko
            
            public function setStoreName($name) {
                $this->storeName = $name;
            }

            // Header
            public function Header() {
                // Sesuaikan dengan kebutuhan header Anda
                $this->SetFont('helvetica', 'B', 12);
                $this->Cell(0, 10, $this->storeName, 0, false, 'C', 0, '', 0, false, 'M', 'M');
            }

            // Footer
            public function Footer() {
                // Sesuaikan dengan kebutuhan footer Anda
                $this->SetY(-15);
                $this->SetFont('helvetica', 'I', 8);
                $this->Cell(0, 10, 'Terima kasih atas pembelian Anda di ' . $this->storeName, 0, false, 'C', 0, '', 0, false, 'T', 'M');
                $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
            }
        }

        $pdf = new MYPDF();
        $pdf->setStoreName('Rainbow Cake Dapur Mama Sri'); // Atur nama toko di sini
        $pdf->SetMargins(10, 10, 10);
        $pdf->AddPage();

        // Tambahkan konten PDF disini sesuai dengan struktur orderData
        foreach ($orderData as $data) {
            $html = '
                <table>
                    <tr><td colspan="2"><b>Order ID:</b> ' . $data['id'] . '</td></tr>
                    <tr><td colspan="2"><b>Nama:</b> ' . $data['nama'] . '</td></tr>
                    <tr><td colspan="2"><b>Email:</b> ' . $data['email'] . '</td></tr>
                    <tr><td colspan="2"><b>No Hp:</b> ' . $data['phone'] . '</td></tr>
                    <tr><td colspan="2"><b>Alamat:</b> ' . $data['address'] . '</td></tr>
                    <tr><td colspan="2"><b>Produk:</b> ' . $data['products'] . '</td></tr>
                    <tr><td colspan="2"><b>Metode Bayar:</b> ' . $data['pmode'] . '</td></tr>
                    <tr><td colspan="2"><b>Total Bayar:</b> Rp ' . number_format($data['amount_paid'], 2) . '</td></tr>
                    <tr><td colspan="2"><b>Tanggal Order:</b> ' . $data['tanggal_beli'] . '</td></tr>
                    <tr><td colspan="2"><b>Tanggal Pesanan:</b> ' . $data['tanggal_pesanan'] . '</td></tr>
                </table>';
            
            $pdf->writeHTML($html);
        }

        // Simpan atau tampilkan PDF (sesuai kebutuhan)
        // $pdf->Output('cetak_order_'.$orderId.'.pdf', 'D'); // Untuk men-download
        $pdf->Output();
    } else {
        echo 'Order tidak ditemukan.';
    }
} else {
    echo 'ID order tidak diberikan.';
}
?>
