<?php
class Asset_Bangunan_Model extends MY_Model{
	
	function __construct(){
            parent::__construct();
            $this->table = 'asset_bangunan';
            $this->extTable = 'ext_asset_bangunan';
            
            $this->selectColumn = "SELECT t.kd_lokasi,t.kd_brg,t.no_aset,kuantitas, rph_aset, 
                        no_kib, type, thn_sls, thn_pakai, no_imb, tgl_imb, 
                        kd_prov, kd_kab, kd_kec, t.kd_kel, alamat, 
                        kd_rtrw, no_kibtnh, jns_trn, dari, tgl_prl, 
                        kondisi, rph, dasar_hrg, sumber, no_dana, 
                        tgl_dana, unit_pmk, alm_pmk, catatan, tgl_buku, 
                        rphwajar, rphnjop, status, cad1, luas_dsr, 
                        luas_bdg, jml_lt, 
                        b.id, b.kode_unor, b.image_url, b.document_url,
                        b.nop, b.njkp, b.waktu_pembayaran, b.setoran_pajak, b.keterangan,
                        c.ur_upb as nama_unker, d.nama_unor,
                        e.kd_gol,e.kd_bid,e.kd_kel as kd_kelompok,e.kd_skel, e.kd_sskel
                        ,f.nama as nama_klasifikasi_aset, t.kd_klasifikasi_aset";
	}
	
	function get_AllData(){
            $query = "$this->selectColumn 
                        FROM $this->table AS t 
                        LEFT JOIN $this->extTable AS b ON t.kd_lokasi = b.kd_lokasi AND t.kd_brg = b.kd_brg AND t.no_aset = b.no_aset
                        LEFT JOIN ref_unker AS c ON t.kd_lokasi = c.kdlok
                        LEFT JOIN ref_unor d ON b.kode_unor = d.kode_unor
                        LEFT JOIN ref_subsubkel AS e ON t.kd_brg = e.kd_brg
                        LEFT JOIN ref_klasifikasiaset_lvl3 AS f ON t.kd_klasifikasi_aset = f.kd_klasifikasi_aset
                        LIMIT 0, $this->limit";

            return $this->Get_By_Query($query);	
	}
        
        function get_ExtAllData($kd_lokasi,$kd_brg,$no_aset){
            $query = "SELECT t.nop, t.njkp, t.waktu_pembayaran, t.setoran_pajak, t.keterangan 
                FROM $this.extTable as t WHERE t.kd_lokasi = $kd_lokasi AND t.kd_brg = $kd_brg AND t.no_aset = $no_aset";
            
            return $this->Get_By_Query($query);
        }
	
	function get_byIDs($ids)
	{		
            $query = 'SELECT id, kd_lokasi, kd_brg, no_aset, kuantitas, rph_aset, 
                        no_kib, type, thn_sls, thn_pakai, no_imb, tgl_imb, 
                        kd_prov, kd_kab, kd_kec, kd_kel, alamat, 
                        kd_rtrw, no_kibtnh, jns_trn, dari, tgl_prl, 
                        kondisi, rph, dasar_hrg, sumber, no_dana, 
                        tgl_dana, unit_pmk, alm_pmk, catatan, tgl_buku, 
                        rphwajar, rphnjop, status, cad1, luas_dsr, 
                        luas_bdg, jml_lt FROM ' . $this->table . ' WHERE id IN ('.$this->prepare_Query($ids).')ORDER BY id ASC';

            return $this->Get_By_Query($query);
	}
}
?>