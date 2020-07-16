<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Err extends CI_Controller {

    public function index()
    {
        echo json_encode(rspAuth());
    }

}

/* End of file Err.php */
