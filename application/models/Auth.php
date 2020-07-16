<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Model {
    
    public $see = '*';

    public function __construct() {
        
    }
    
    public function login($username='',$password='')
    {
        if ($username != '' && $password != '') {
            $this->db->select($this->see);
            $this->db->join('petugas p', 'p.id = u.petugas_id', 'inner');
            $q = $this->db->get_where('users u', ['username' => $username,'password' => md5($password)]);
            if ($q->num_rows() > 0) {
                return rsp($q->row(),true,'Data ditemukan',$q->num_rows());
            }
        }
        
        return rsp('',false,'Data Tidak ditemukan',0);
        
    }
    
    
}

/* End of file Auth.php */
