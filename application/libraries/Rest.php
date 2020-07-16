<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
  
  class Rest {
    
    public $req = false;

   
   public function __construct()
   {
     //Do your magic here
   }
   

   public function resp($method="GET")
    {
      header("Content-Type: application/json; charset=UTF-8");
      header('Access-Control-Allow-Origin: *');
     
        // $allowed_domains = [/* Array of allowed domains*/];

        // if (in_array($_SERVER['HTTP_ORIGIN'], $allowed_domains)) {
        //     header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
        // }

        header("Access-Control-Allow-Methods: ".$method);
        header("Access-Control-Max-Age: 8600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

      if ($_SERVER['REQUEST_METHOD'] === $method ){
			  $this->req = true;
		  }
    }

    public function cekLogin()
    {
       $ci = $this->CI = &get_instance();
       if (!$ci->session->userdata('id')) {
          redirect('Err');
       }
    }
  
}