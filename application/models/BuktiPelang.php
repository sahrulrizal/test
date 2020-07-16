<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class BuktiPelang extends CI_Model {

    public function __construct() {
        
    }

    public function buktipelang($pelang_id='')
    {
        if ($pelang_id != '') {
            $q = $this->db->get_where('pelang_bukti', ['no_pelang' => $pelang_id]);
            if ($q->num_rows() > 0) {
                return rsp($q->result(),true,'Data ditemukan',$q->num_rows());
            }
        }
        
        return rsp('',false,'Data Tidak ditemukan');
        
    }
    

}

/* End of file BuktiPelang.php */
