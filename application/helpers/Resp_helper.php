<?php
if (!function_exists('ctojson')) {

    function rsp($data=null,$status=false,$msg='Tidak Diketahui',$count=null){
        
        $data = [
			'data' => $data,
			'status' => $status,
			'msg' => $msg,
			'count' => $count 
		];

		return $data;
	}
	
	function rspAuth(){
        
        $data = [
			'status' => false,
			'msg' => "Anda tidak mempunyai hak akses penuh untuk API ini, silahkan hubungi penyedia, terimakasih.",
		];

		return $data;
    }

}