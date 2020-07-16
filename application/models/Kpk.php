<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Kpk extends CI_Model {

    public function __construct() {
        
    }

    public function inkpk($val='')
    {
        if ($val != '') {
            $this->db->insert('kpk', $val);
            $af = $this->db->affected_rows();
            if ($af > 0) {
                return rsp($val,true,'Berhasil menambahkan data',$af);
            }else{
                return rsp($val,false,'Gagal menambahkan data',0);
            }
        }

        return rsp('',false,'Data Tidak ditemukan');
    }
    

}

/* End of file Kend.php */
