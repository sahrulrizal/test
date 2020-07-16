<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class JenisKend extends CI_Model {
    
    public function __construct() {
        
    }
    
    public function all()
    {
        $q = $this->db->get('jenis_kend');
        if ($q->num_rows() > 0) {
            return rsp($q->result(),true,'Data ditemukan',$q->num_rows());
        }else{
            return rsp('',false,'Data Tidak ditemukan');
        }
        
    }
    
    public function get($id='')
    {
        if ($id != '') {
            $q = $this->db->get_where('jenis_kend', ['id' => $id]);
            if ($q->num_rows() > 0) {
                return rsp($q->result(),true,'Data ditemukan',$q->num_rows());
            }
        }
        
        return rsp('',false,'Data Tidak ditemukan');
        
    }
    
    
}

/* End of file BuktiPelang.php */
