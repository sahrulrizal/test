<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tabel extends CI_Controller {
    
    
    public function __construct()
    {
        parent::__construct();
    }

    public function list_tabel()
	{
        $data['tabel'] = $this->db->list_tables();
        $this->load->view('lab/tabel', $data);

    }
    
    public function getKolom($t)
	{
        if ($this->db->table_exists($t))
        {
            $data['field'] = $this->db->list_fields($t);
        }else{
           echo "gak ada tabel";
        }

        $this->load->view('lab/kolom', $data);
    }

    public function cek($t)
    {
        $data = [
            'nama_controller' => 'cek',
            'nama_model' => 'mCek',
            'field' => $this->db->list_fields($t)
        ];
       $data = $this->load->view('lab/controller', $data,TRUE); 
       
       $data = str_replace('<%php','<?php',$data);

       write_file(APPPATH.'controllers/lab/'.ucfirst('nama_controller').'.php', $data);
       chmod(APPPATH.'controllers/lab/'.ucfirst('nama_controller').'.php',0777);
    }

}
