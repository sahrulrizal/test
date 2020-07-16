<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Kend extends CI_Model {

    public function __construct() {
        
    }

    public function kend($id='')
    {
        if ($id != '') {
             $this->db->join('jenis_kend jk', 'jk.id = k.jenis_id', 'inner');
            $q = $this->db->get_where('kend k ', ['k.id' => $id]);
            if ($q->num_rows() > 0) {
                return rsp($q->row(),true,'Data ditemukan',$q->num_rows());
            }
        }
        return rsp('',false,'Data Tidak ditemukan');
    }
    

}

/* End of file Kend.php */
