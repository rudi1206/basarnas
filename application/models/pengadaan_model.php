<?php
class Pengadaan_Model extends MY_Model{
	
	function __construct(){
		parent::__construct();
                $this->viewTable = 'view_pengadaan';
		$this->extTable = 'pengadaan';
                
                $this->selectColumn = "SELECT id, kd_lokasi, kd_brg, no_aset, kode_unor, part_no, merek, model, 
                            nama_unker, nama_unor, id_vendor, 
                            tahun_angaran, perolehan_sumber, perolehan_bmn, no_sppa, 
                            asal_pengadaan, harga_total, deskripsi, perolehan_tanggal, 
                            faktur_no, faktur_tanggal, kuitansi_no, kuitansi_tanggal, 
                            sp2d_no, sp2d_tanggal, mutasi_no, mutasi_tanggal, 
                            garansi_berlaku, garansi_keterangan, pelihara_berlaku, pelihara_keterangan, 
                            spk_no, spk_jenis, spk_berlaku, spk_keterangan, 
                            is_terpelihara, is_garansi, is_spk, data_kontrak,image_url,document_url";
	}
        
	
	function get_AllData(){
		$query = "$this->selectColumn FROM $this->viewTable";

		return $this->Get_By_Query($query);	
	}
	
	function get_ByID($id)
	{
		$query = "$this->selectColumn FROM $this->viewTable
							where id = $id";
		return $this->Get_By_Query($query);	
	}
        
        function get_ByKode($kd_lokasi,$kd_brg,$no_aset)
	{
		$query = "$this->selectColumn FROM $this->viewTable
                                where kd_lokasi = '$kd_lokasi' AND kd_brg = '$kd_brg' AND no_aset = $no_aset";
                
		return $this->Get_By_Query($query);	
	}
	
}
?>