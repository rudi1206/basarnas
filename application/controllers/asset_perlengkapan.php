<?php
class Asset_Perlengkapan extends MY_Controller {

	function __construct() {
		parent::__construct();
 		if ($this->my_usession->logged_in == FALSE){
 			echo "window.location = '".base_url()."user/index';";
 			exit;
    	}
		$this->load->model('Asset_Perlengkapan_Model','',TRUE);
		$this->model = $this->Asset_Perlengkapan_Model;		
	}
	
	function perlengkapan(){
		if($this->input->post("id_open")){
			$data['jsscript'] = TRUE;
			$this->load->view('pengelolaan_asset/perlengkapan_view',$data);
		}else{
			$this->load->view('pengelolaan_asset/perlengkapan_view');
		}
	}
	
	function modifyPerlengkapan(){

                $dataSimak = array();
                $dataKlasifikasiAset = array();
                
                $klasifikasiAsetFields = array(
                    'kd_lvl1','kd_lvl2','kd_lvl3'
                );
                //$dataExt = array();
//                $dataKode = array();
                
//                $kodeFields = array(
//                        'kd_gol','kd_bid','kd_kelompok','kd_skel','kd_sskel'
//                );
                
	  	$simakFields = array(
			'id','warehouse_id','ruang_id','rak_id',
                        'serial_number', 'part_number','kd_brg','kd_lokasi',
                        'no_aset','kondisi', 'kuantitas', 'dari',
                        'tanggal_perolehan','no_dana','penggunaan_waktu',
                        'penggunaan_freq','unit_waktu','unit_freq','disimpan', 
                        'dihapus','image_url','document_url','kd_klasifikasi_aset');
                
//                $extFields = array(
//                        'kd_lokasi', 'kd_brg', 'no_aset', 'id',
//                        'kode_unor','image_url','document_url'
//                );
//		

		foreach ($simakFields as $field) {
			$dataSimak[$field] = $this->input->post($field);
		}
                
                if(!isset($dataSimak['disimpan']))
                {
                    $dataSimak['disimpan'] = 0;
                }
                
                if(!isset($dataSimak['dihapus']))
                {
                    $dataSimak['dihapus'] = 0;
                }
                
                $partNumberDetails = $this->model->get_partNumberDetails($dataSimak['part_number']);
                $dataSimak['kd_brg'] = $partNumberDetails->kd_brg;
                
                foreach($klasifikasiAsetFields as $field)
                {
                    $dataKlasifikasiAset[$field] =  $this->input->post($field);
                }
                
                $dataSimak['kd_klasifikasi_aset'] = $this->kodeKlasifikasiAsetGenerator($dataKlasifikasiAset);
                
                //GENERATE NO ASET
                if($dataSimak['no_aset'] == null || $dataSimak['no_aset'] == "")
                {
                    $this->db->where('id',$dataSimak['id']);
                    $query = $this->db->get('asset_perlengkapan');
                    $result = $query->row();
                    if($query->num_rows === 0)
                    {
                        $dataSimak['no_aset'] = $this->noAssetGenerator($dataSimak['kd_brg'], $dataSimak['kd_lokasi']);
                    }
                    else
                    {
                        $dataSimak['no_aset'] = $result->no_aset;
                    }
                }
                
//                $dataSimak['part_number'] = $partNumberDetails->part_number;
//                $dataSimak['no_aset'] = $this->noAssetGenerator($dataSimak['kd_brg'],$dataSimak['kd_lokasi']);
                
//                foreach ($extFields as $field) {
//			$dataExt[$field] = $this->input->post($field);
//		} 
//                
//                $dataExt['kd_brg'] = $kd_brg;	
                
		$this->modifyData($dataSimak, null);
	}
	
	function deletePerlengkapan()
	{
            /*
             * NOTES:
             * The current built in function generates an error due to not having an ext table
             * that's why i made a custom one
             */
		$data = $this->input->post('data');
                $fail = array();
		$success = true;
		
		foreach($data as $keys)
		{                        
			if($this->model->deleteData($keys['id']) == FALSE)
			{
				$success = false;
			}
		}
		
		$result = array('fail' => $fail,
                                'success'=>$success);
						
		echo json_encode($result);
	}
}
?>