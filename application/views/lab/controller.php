<%php
defined('BASEPATH') OR exit('No direct script access allowed');

class <?=ucfirst($nama_controller);?> extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('<?=ucfirst($nama_model);?>','<?=strtolower(substr($nama_model,0,2))?>');
	}

	public function dt()
	{
		echo $this-><?=strtolower(substr($nama_model,0,2))?>->dt();
	}
    
    public function list_<?=$nama_controller;?>()
	{
		$d = [
			'title' => 'List <?=ucfirst($nama_controller);?>',
			'linkView' => 'page/<?=$nama_controller;?>s/list_<?=$nama_controller;?>',
			'fileScript' => 'list_<?=$nama_controller;?>.js',
			'bread' => [
				'nama' => 'List <?=ucfirst($nama_controller);?>',
				'data' => [
					['nama' => 'List <?=ucfirst($nama_controller);?>','link' => site_url('<?=$nama_controller;?>s/list_<?=$nama_controller;?>'),'active' => 'active'],
				]
			],
		];
		$this->load->view('_main',$d);
	}

	public function add_<?=$nama_controller;?>()
	{
		$d = [
			'title' => 'List <?=ucfirst($nama_controller);?>',
			'linkView' => 'page/<?=$nama_controller;?>s/add_<?=$nama_controller;?>',
			'fileScript' => 'add_<?=$nama_controller;?>.js',
			'bread' => [
				'nama' => 'Add <?=ucfirst($nama_controller);?>',
				'data' => [
					['nama' => 'List <?=ucfirst($nama_controller);?>','link' => site_url('<?=$nama_controller;?>s/list_<?=$nama_controller;?>'),'active' => ''],
					['nama' => 'Add <?=ucfirst($nama_controller);?>','link' => '','active' => 'active'],
				]
			],
			'segment' => $this->msg->get()->result()

		];
		$this->load->view('_main',$d);
	}

	public function in<?=ucfirst($nama_controller);?>()
	{
		$cost = [
			<?php foreach ($field as $v) { 
			echo '"'.$v.'" => $this->input->get("'.$v.'"),'."\n";
			} ?>
		];
		
		$inCost = $this-><?=strtolower(substr($nama_model,0,2))?>->in($cost);
		if ($inCost[1] == 1) {
			$this->session->set_flashdata('success', 'Success to added <?=$nama_controller;?>');
			redirect('<?=$nama_controller;?>s/list_<?=$nama_controller;?>');
		}else{
			$this->session->set_flashdata('error', 'Failed to added <?=$nama_controller;?>');
			redirect('<?=$nama_controller;?>s/add_<?=$nama_controller;?>');
		}
	}

}
