<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
mysql_connect("localhost","root","");
mysql_select_db("db_psbnew");
	$sql="select * from  tbl_psbsetup ";
	$hasil=mysql_query($sql);
	$d=mysql_fetch_array($hasil);
		$thajaran=$d["f_angkatanpsb"];
		$ketuapanitia = $d["f_ketuapanitia"];
	
	#total biaya form
	$sql="select sum(f_biayaform) as f_totalbiayaform from tbl_calonsiswa where f_tglbayarform != '' ";
	$hasil=mysql_query($sql);
	$d=mysql_fetch_array($hasil);
		$totalbiayaform=$d["f_totalbiayaform"];

require('fpdf.php');
class PDF extends FPDF{
	function Header(){
		$this->Image('logosmea.jpg',5,5,-650);
		$this->SetFont('Arial','B',20);
		$this->text(25,13,'SMK NEGERI 1 AMPANA KOTA');
		$this->setFont('Arial','',9);
		$this->text(25,20,'Jl. Tanjung Api, No. 26, Desa Labuan, Kec. Ratolindo Ampana, Kab. Tojo Una-una 94683.');
		$this->text(5,25,'============================================================================================================');
	}
	function Footer(){
		// Position at 1.5 cm from bottom
		$q=mysql_query('select * from tbl_psbsetup ');
		$d=mysql_fetch_array($q);
		$this->SetY(-15);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,10,'Panitia Penerimaan Siswa/siswi Baru SMK negeri 1 Ampana Kota.',0,0,'C');
		$this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
	}
}

$tgl = date('d M Y');
$pdf = new PDF();
$pdf->Open();
$pdf->addPage();
$pdf->setAutoPageBreak(false);

$pdf->setFont('Arial','',12);
$pdf->text(27,31,'DAFTAR PEMBAYARAN FORMULIR CALON SISWA BARU TAHUN AJARAN '.$thajaran);

$yi = 41;
$ya = 35;
$pdf->setFont('Arial','',9);
$pdf->setFillColor(222,222,222);
$pdf->setXY(5,$ya);
$pdf->CELL(6,6,'NO',1,0,'C',1);
$pdf->CELL(17,6,'NOPEND',1,0,'C',1);
$pdf->CELL(70,6,'NAMA SISWA',1,0,'C',1);
$pdf->CELL(50,6,'TGL BAYAR FORMULIR',1,0,'C',1);
$pdf->CELL(30,6,'BIAYA FORMULIR',1,0,'C',1);
$pdf->CELL(27,6,'NOMOR TELP',1,0,'C',1);
$ya = $yi + $row;
$sql = mysql_query("select * from  tbl_calonsiswa, tbl_jurusan, tbl_psbsetup where tbl_calonsiswa.f_jurusanid = tbl_jurusan.f_jurusanid and f_tglbayarform !='' ");
$i = 1;
$no = 1;
$max = 31;
$row = 6;
while($data = mysql_fetch_array($sql)){
$pdf->setXY(5,$ya);
$pdf->setFont('arial','',9);
$pdf->setFillColor(255,255,255);
$pdf->cell(6,6,$no,1,0,'C',1);
$pdf->cell(17,6,$data[f_nopendaftaran],1,0,'L',1);
$pdf->cell(70,6,$data[f_namalengkap],1,0,'L',1);
$pdf->CELL(50,6,$data[f_tglbayarform],1,0,'C',1);
$pdf->cell(30,6,"Rp. ".$data[f_biayaform],1,0,'C',1);
$pdf->CELL(27,6,$data[f_nohp],1,0,'C',1);
$ya = $ya+$row;
$no++;
$i++;
}
$pdf->text(140,$ya+6,"TOTAL: Rp. ".$totalbiayaform);
$pdf->text(140,$ya+15,"Ampana Kota , ".$tgl);
$pdf->text(140,$ya+30,$ketuapanitia);
$pdf->output();
?>