<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Pelang','pl');
		$this->load->model('BuktiPelang','bp');
		$this->load->model('Kend','k');
		$this->load->model('Kpk','kpk');
	}
	

	public function index()
	{
		echo "Hallo :)";
	}

	// Login

	public function auth()
	{
		$this->load->model('Auth','auth');

		$this->api->resp('POST');		
		if ($this->api->req) {
			$d = json_decode(file_get_contents("php://input"));
			
			$this->auth->see = 'u.petugas_id,nama,level,u.status';
			echo json_encode($this->auth->login(@$d->username,@$d->password));
		}else{
			echo json_encode(rsp());
		}
	}

	
	// Konfirmasi Pelanggaran
	public function konfpelang()
	{
		$this->api->resp('POST');
		if ($this->api->req) {

			$d = json_decode(file_get_contents("php://input"));
			
			echo json_encode($this->pl->konfpelang(@$d->kpelang,@$d->nopolisi));
		}else{
			echo json_encode(rsp());
		}
	}

	// Data Pelanggaran
	public function datapelang($kpelang='')
	{
		$this->api->resp('GET');
		if ($this->api->req && ($kpelang != '')) {
		$dp = [];
			$pl = $this->pl->pelang($kpelang);
			if ($pl['status']) {
				
				$k = $this->k->kend(@$pl['data']->kend_id);
				$bp = $this->bp->buktipelang($kpelang);
				$tp = $this->pl->tipe_pelang($pl['data']->tipe_pelang);

				$dp = [
					'tipe_pelang' => $tp['data'],
					'pelang' => $pl,
					'kend' => $k,
					'buktipelang' => $bp
				];

				echo json_encode($dp);

			}else{
				echo json_encode(rsp('',false,'Data Tidak ditemukan'));
			}

		}else{
			echo json_encode(rsp());
		}
	}

	// Datatables data pelanggaran validasi
	public function dt_list_dpValidasi()
	{
		$this->api->resp('POST');
		if ($this->api->req) {
			echo $this->pl->dtDpValidasi();
		}else{
			echo json_encode(rsp());
		}
	}

	// Datatables data pelanggaran
	public function dt_list_dp()
	{
		$this->api->resp('POST');
		if ($this->api->req) {
			$tanggal = $this->input->post('tanggal');
			$status = $this->input->post('status');
			
			echo $this->pl->dtDp($status,$tanggal);
		}else{
			echo json_encode(rsp());
		}
	}

	// Menambahkan data Konfirmasi Pelanggaran Kendaaraan
	public function kpk()
	{
		$this->api->resp($_SERVER['REQUEST_METHOD']);
		switch ($_SERVER['REQUEST_METHOD']) {
			case 'GET':
				# code...
			break;
			case 'POST':
				$d = json_decode(file_get_contents("php://input"));

				if (@$d->kend_tny == 1) {
					$data = [ 
						"pelang_id" => @$d->pelang_id,
						"kend_tny" => @$d->kend_tny,
						"no_pol" => @$d->no_pol,
						"jenis_id" => @$d->jenis_id,
						"tipe" => @$d->tipe,
						"tmp_lahir" => @$d->tmp_lahir,
						"merk" => @$d->merk,
						"warna" => @$d->warna,
						"stnk_ats_nma" => @$d->stnk_ats_nma,
						"stnk_expired" => @$d->stnk_expired,
						"samsat_penerbit" => @$d->samsat_penerbit,
						"no_rangka" => @$d->no_rangka,
						"no_mesin" => @$d->no_mesin,
						"mengendarai_tny" => @$d->mengendarai_tny,
						"nama_pengen" => @$d->nama_pengen,
						"alamat_pengen" => @$d->alamat_pengen,
						"tgl_pengen" => @$d->tgl_pengen,
						"pendik_pengen" => @$d->pendik_pengen,
						"pekerj_pengen" => @$d->pekerj_pengen,
						"gol_sim_pengen" => @$d->gol_sim_pengen,
						"no_sim" => @$d->no_sim,
						"expired_sim_pengen" => @$d->expired_sim_pengen,
						"satpas_penerb" => @$d->satpas_penerb,
						"no_hp_pengen" => @$d->no_hp_pengen,
						"nama_pengisi" => @$d->nama_pengisi,
						"no_hp" => @$d->no_hp,
						"created_date" => date('Y-m-d'),
					];

					if (@$d->pelang_id != '') {
						// Insert ke history sukses
						echo json_encode($this->kpk->inkpk($data));
						// $this->pl->inHPelang(@$d->pelang_id,$this->db->insert_id(),'2');
						$this->pl->inHPelang(@$d->pelang_id,$this->db->insert_id(),5,'Pelanggar sudah mengkonfirmasi','',json_encode($data));
					}else{
						echo json_encode(rsp());
					}

				}else if(@$d->kend_tny == '0'){
					$data = [
						"pelang_id" => @$d->pelang_id,
						"kend_tny" => @$d->kend_tny,
						"no_pol" => @$d->no_pol,
						"nama_baru" => $d->nama_baru,
						"email_baru" => $d->email_baru,
						"hp_baru" => $d->hp_baru,
						"tgl_pemb" => $d->tgl_pemb,
						"created_date" => date('Y-m-d')
					];

					echo json_encode($this->kpk->inkpk($data));
					// Insert ke history dilanjutkan
					$this->pl->inHPelang(@$d->pelang_id,$this->db->insert_id(),5,'Melanjutkan tindak pelanggaran','',json_encode($data));
				}else{
					echo json_encode(rsp());
				}

			break;
			
			default:
				echo json_encode(rsp());
			break;
		}
	}

	// Jenis Kendaraan
	public function jeniskend()
	{
		$this->load->model('JenisKend','jk');

		$this->api->resp($_SERVER['REQUEST_METHOD']);
		switch ($_SERVER['REQUEST_METHOD']) {
			case 'GET':
				echo json_encode($this->jk->all());
			break;
			case 'POST':
				echo "belum ada";
			break;
			
			default:
				echo json_encode(rsp());
			break;
		}
	}

	// Set Validasi
	public function setValidPelang()
	{
		$this->api->resp('POST');
		if ($this->api->req) {

			$d = json_decode(file_get_contents("php://input"));
			
			echo json_encode($this->pl->setValidPelang(@$d->uuid,@$d->valid,$d->kpelang));
		}else{
			echo json_encode(rsp());
		}
	}

	// Set Status Pelanggaran

	public function setStatusPelang()
	{
		$this->api->resp('PUT');
		if ($this->api->req) {

			$d = json_decode(file_get_contents("php://input"));
			
			echo json_encode($this->pl->setStatusPelang(@$d->uuid,@$d->status,@$d->no_pelang));
		}else{
			echo json_encode(rsp());
		}
	}

	// menjumlahkan data pelanggaran berdasarkan status/tanggal
	public function nPelang($s='',$tgl='',$dt='')
	{
		$this->api->resp('GET');
		if ($this->api->req) {
			echo json_encode($this->pl->nPelang($s,$tgl,$dt));
		}else{
			echo json_encode(rsp());
		}
	}

	// menjumlahkan grafik tipe pelangagran
	public function grafikTipePelang($date = '',$dt='m')
	{
		$label = [];
		$val = [];
		$warna = ['#f10075', '#10b759', '#ffc107','#555'];
		$data = [];

		if ($date == '') {
			$date = date('Y-m-d');
		}

		$ntp = $this->pl->nTipePelang($date,$dt);
		if ($ntp['status']) {
			foreach ($ntp['data'] as $v) {
				array_push($label, $v->pelang);
				array_push($val, (int) $v->jml);
			}

			$data = [
				'status' => true,
				'label' => $label,
				'val' => $val,
				'warna' => $warna
			];
			echo json_encode($data);
		}else{
			echo json_encode(rsp());
		}
	}

	// grafik menjumlahkan  data pelanggaran
	public function grafikJmlPelang($dt='m')
	{
		$label = [];
		$val = [];
		$warna = ['#f10075', '#10b759', '#ffc107','#555'];
		$data = [];

		$minggu = [
			['key' => 6, 'val' => 0],
			['key' => 0, 'val' => 0],
			['key' => 1, 'val' => 0],
			['key' => 2, 'val' => 0],
			['key' => 3, 'val' => 0],
			['key' => 4, 'val' => 0],
			['key' => 5, 'val' => 0],
		];


		$ntp = $this->pl->nPelangNew($dt);
		if ($ntp['status']) {
			
			$myData = [];
			$max = 0;

			if ($dt == 'w') {

				foreach ($ntp['data'] as $k => $v) {
					foreach ($minggu as $km => $m) {
						if ($m['key'] == $v->k) {
							$minggu[$km]['val'] = (int)$v->jml;
							if ($v->jml > $max) {
								$max = (int) $v->jml+3;
							}
						}
					}
				}
	
				foreach ($minggu as $v) {
					array_push($myData,$v['val']);
				}
	
				$warna = ["#1b2e4b"];
			
			}else if($dt == 'm'){
				$tgl = [];
				$last_date =  date("t", strtotime(date('Y-m-d')));
				for ($i=1; $i <= $last_date ; $i++) { 
					array_push($tgl,['key' => $i,'val' => 0]);
				}
				
				foreach ($ntp['data'] as $k => $v) {
					foreach ($tgl as $km => $m) {
						if ($m['key'] == $v->k) {
							$tgl[$km]['val'] = (int)$v->jml;
							if ($v->jml > $max) {
								$max = (int) $v->jml+3;
							}
						}
					}
				}
	
				foreach ($tgl as $v) {
					array_push($label,"Tanggal ".$v['key']);
					array_push($myData,$v['val']);
				}
				
			}else if($dt == 'y'){
				$bulan = [
					['key' => 1,'name' => "Jan", 'val' => 0],
					['key' => 2,'name' => "Feb", 'val' => 0],
					['key' => 3,'name' => "Mar", 'val' => 0],
					['key' => 4,'name' => "Apr", 'val' => 0],
					['key' => 5,'name' => "Mei", 'val' => 0],
					['key' => 6,'name' => "Jun", 'val' => 0],
					['key' => 7,'name' => "Jul", 'val' => 0],
					['key' => 8,'name' => "Agu", 'val' => 0],
					['key' => 9,'name' => "Sep", 'val' => 0],
					['key' => 10,'name' => "Okt", 'val' => 0],
					['key' => 11,'name' => "Nov", 'val' => 0],
					['key' => 12,'name' => "Des", 'val' => 0],
				];

				
				foreach ($ntp['data'] as $k => $v) {
					foreach ($bulan as $km => $m) {
						if ($m['key'] == $v->k) {
							$bulan[$km]['val'] = (int)$v->jml;
							if ($v->jml > $max) {
								$max = (int) $v->jml+3;
							}
						}
					}
				}
	
				foreach ($bulan as $v) {
					array_push($label,$v['name']);
					array_push($myData,$v['val']);
				}
				
			}
			

			$data = [
				'max' => $max,
				'status' => true,
				'label' => $label,
				'val' => $myData,
				'warna' => $warna
			];
			echo json_encode($data);
		}else{
			echo json_encode(rsp());
		}
	}
}
