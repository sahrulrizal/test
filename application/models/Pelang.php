<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Pelang extends CI_Model {

    public function __construct() {
        
    }

    // Konfirmasi data pelanggaran
    public function konfpelang($kpelang='')
    {
        if ($kpelang != '') {
            $q = $this->db->get_where('pelang',['no_pelang' => $kpelang]);
            if ($q->num_rows() > 0) {
                return rsp($q->row(),true,'Data ditemukan',$q->num_rows());
            }else{
                return rsp('',false,'Data Tidak ditemukan');
            }
        }

        return rsp('',false,'Kode Pelanggaran dan Nomor Polisi harus diisi');
    }

    // Menampilkan data pelanggaran
    public function pelang($kpelang='')
    {
        if ($this->konfpelang($kpelang)['status']) {
            return $this->konfpelang($kpelang);
        }
        
        return rsp('',false,'Data Tidak ditemukan');
        
    }

      // Datatable list pelanggaran validasi
      public function dtDpValidasi($valid='0',$tanggal='')
      {
          // Definisi
          $condition = [];
          $data = [];
          
          $CI = &get_instance();
          $CI->load->model('DataTable', 'dt');
          
          // Set table name
          $CI->dt->table = 'pelang p';
          // Set orderable column fields
          $CI->dt->column_order = [null, 'p.no_pelang','p.no_pol','pt.pelang','p.created_date','p.lokasi'];
          // Set searchable column fields
          $CI->dt->column_search = ['p.no_pelang','p.no_pol','pt.pelang','p.created_date','p.lokasi'];
          // Set select column fields
          $CI->dt->select = 'p.no_pelang,p.no_pol,pt.pelang,p.created_date,p.lokasi';
          // Set default order
          $CI->dt->order = ['p.id' => 'desc'];
  
          $con = ['join','pelang_tipe pt','pt.id = p.tipe_pelang','inner'];
          array_push($condition,$con);
          
     

          if ($valid != '') {
            $con1 = ['where','valid',$valid];
            array_push($condition,$con1);
           }
           

          if ($tanggal != '') {
            $con1t = ['where','date(created_date)',$tanggal];
            array_push($condition,$con1t);
           }
          
          // Fetch member's records
          $dataTabel = $this->dt->getRows($_POST, $condition);
          
          $i = $this->input->post('start');
          foreach ($dataTabel as $dt) {
              $i++;
              $data[] = array(
                  $dt->no_pelang,
                  $dt->no_pol,
                  $dt->pelang,
                  $dt->created_date,
                  $dt->lokasi,
                  '<a href="detail_validasi/'.$dt->no_pelang.'/'.$dt->no_pol.'" class="btn btn-dark btn-xs"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M20 14.66V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5.34"></path><polygon points="18 2 22 6 12 16 8 16 8 12 18 2"></polygon></svg> Update</a>',
              );
          }
          
          
          $output = array(
              "draw" =>  $this->input->post('draw'),
              "recordsTotal" => $this->dt->countAll($condition),
              "recordsFiltered" => $this->dt->countFiltered($_POST, $condition),
              "data" => $data,
          );
          
          // Output to JSON format
          return json_encode($output);
      }

    // Datatable list pelanggaran
    public function dtDp($status='',$tanggal='')
    {
        // Definisi
        $condition = [];
        $data = [];
        
        $CI = &get_instance();
        $CI->load->model('DataTable', 'dt');
        
        // Set table name
        $CI->dt->table = 'pelang p';
        // Set orderable column fields
        $CI->dt->column_order = [null, 'p.no_pelang','p.no_pol','pt.pelang','p.created_date','p.lokasi','p.status'];
        // Set searchable column fields
        $CI->dt->column_search = ['p.no_pelang','p.no_pol','pt.pelang','p.created_date','p.lokasi','p.status'];
        // Set select column fields
        $CI->dt->select = 'p.no_pelang,p.no_pol,pt.pelang,p.created_date,p.lokasi,p.status';
        // Set default order
        $CI->dt->order = ['p.id' => 'desc'];

        $con = ['join','pelang_tipe pt','pt.id = p.tipe_pelang','inner'];
        array_push($condition,$con);
        
        // Validasi 1 =>  Pelanggar
        $con1 = ['where','valid',1];
        array_push($condition,$con1);
        
        if ($status != '' && $status != 'all') {
            $con1s = ['where','status',$status];
            array_push($condition,$con1s);
        }

        if ($tanggal != '') {
            $con1t = ['where','date(created_date)',$tanggal];
            array_push($condition,$con1t);
        }
        
        // Fetch member's records
        $dataTabel = $this->dt->getRows($_POST, $condition);
        
        $i = $this->input->post('start');
        foreach ($dataTabel as $dt) {
            $i++;
            $dtp = "'".$dt->no_pelang."','".$dt->no_pol."'";
            $data[] = array(
                '<a href="#" onclick="to_detail_pelang('.$dtp.')">'.$dt->no_pelang.'</a>',
                $dt->no_pol,
                $dt->pelang,
                $dt->created_date,
                $dt->lokasi,
                '<span class="badge badge-light">'.$this->statusPelang($dt->status).'</span>',
                $this->actPelang($dt->status,$dt->no_pelang)
            );
        }
        
        
        $output = array(
            "draw" =>  $this->input->post('draw'),
            "recordsTotal" => $this->dt->countAll($condition),
            "recordsFiltered" => $this->dt->countFiltered($_POST, $condition),
            "data" => $data,
        );
        
        // Output to JSON format
        return json_encode($output);
    }
    

    // Insert History Pelanggaran
    public function inHPelang($no_pelang='',$kpk_id='',$status='',$pesan='',$crtdby='',$obj_option='')
    {
        if ($no_pelang != ''  && $status != '') {
            $obj = [
                'no_pelang' => $no_pelang,
                'kpk_id' => $kpk_id,
                'status' => $status,
                'pesan' => $pesan,
                'obj_option' => $obj_option,
                'created_by' => $crtdby,
                'created_date' => date('Y-m-d H:i:s')
            ];

            $this->db->insert('pelang_h', $obj);
            $af = $this->db->affected_rows();
            
            return rsp($obj,true,'Data ditemukan',$af);

        }else{
            return rsp('',false,'Tidak dapat menambahkan pelangagran history');
        }

        
    }

    // Tipe Pelangggaran
    public function tipe_pelang($id="")
    {
        if ($id != '') {
            $q = $this->db->get_where('pelang_tipe',['id' => $id]);
            if ($q->num_rows() > 0) {
                return rsp($q->row(),true,'Data ditemukan',$q->num_rows());
            }else{
                return rsp('',false,'Data Tidak ditemukan');
            }
        }

        return rsp('',false,'Kode Pelanggaran dan Nomor Polisi harus diisi');
    }
    
    // Konfirmasi ke validasi

    public function setValidPelang($uuid='',$valid='',$kpelang='')
    {
        $status = 0;
        if ($kpelang != '') {
            if ($valid == 1) {
                $status = 1;
            }

            $obj_option = json_encode([
                'valid' => $valid,
            ]);

            $this->inHPelang($kpelang,'',$status,'Berhasil divalidasi',$uuid,$obj_option);
            $this->db->update('pelang', ['valid' => $valid,'status' => $status], ['no_pelang' => $kpelang]);
            $x = $this->db->affected_rows();
            return rsp('',true,'Berhasil diubah',$x);
        }else{
            return rsp('',false,'Gagal diubah');
        }
    }

    // Set Status Pelanggaran

    public function setStatusPelang($uuid='',$status='',$no_pelang='')
    {
        if ($no_pelang != '' && $status != '') {
            $this->db->update('pelang', ['status' => $status], ['no_pelang' => $no_pelang]);
            $x = $this->db->affected_rows();

            $msg = '-';
            if ($status != '') {
                switch ($status) {
                    case '1': //tervalidasi
                        $msg = "Dokumen Tervalidasi";
                    break;
                    case '2': //terberkas
                        $msg = "Sudah terberkaskan dalam bentuk dokumen";
                    break;
                    case '3': //dikirim
                        $msg = "Dokumen sudah dikirimkan";
                    break;
                    case '4': //diterima
                        $msg = "Dokumen sudah diterima oleh pelanggar";
                    break;
                    case '5': //terkonfirmasi & belum bayar
                        $msg = "Sudah terkonfirmasi & tertagih";
                    break;
                    case '6': //expired
                        $msg = "Expired";
                    break;
                    case '7': //lunas
                        $msg = "Sudah Terbayar oleh pelanggar";
                    break;
                }
            }

            $this->inHPelang($no_pelang,'',$status,$msg,$uuid,$ops='');

            return rsp('',true,'Berhasil diubah',$x);
        }else{
            return rsp('',false,'Gagal diubah');
        }
    }


    // Status Pelanggaran
    public function statusPelang($status='')
    {
        $hasil = '-';
        if ($status != '') {
            switch ($status) {
                case '1':
                    $hasil = "tervalidasi";
                break;
                case '2':
                    $hasil = "terberkas";
                break;
                case '3':
                    $hasil = "dikirim";
                break;
                case '4':
                    $hasil = "diterima";
                break;
                case '5':
                    $hasil = "terkonfirmasi & belum bayar";
                break;
                case '6':
                    $hasil = "expired";
                break;
                case '7':
                    $hasil = "lunas";
                break;
            }
        }

        return $hasil;
    }

    // Tombol Action Pelanggaran
    public function actPelang($status='',$no_pelang='')
    {
        $n = 0;
        $btnLink = '-';
        
        if ($status != '') {
            switch ($status) {
                case '1':
                    $n = 2;
                    $btnName = "Berkaskan";
                break;
                case '2':
                    $n = 3;
                    $btnName = "Kirim";
                break;
                case '3':
                    $n = 4;
                    $btnName = "diterima";
                break;
                case '4':
                    $n = 5;
                    $btnName = "terkonfirmasi & belum bayar";
                break;
                case '5':
                    $n = 7;
                    $btnName = "Lunas";
                break;
            }
        }
        
        if ($n != 0) {
            $vact = "'".$no_pelang."',".$n;
            $btnLink = '<a href="#" onclick="actUpdate('.$vact.')" class="btn btn-secondary btn-xs">'.strtoupper($btnName).'</a>';
        }

        return $btnLink;
    }

    // Menghitung jumlah status masing-masing, n <- artinya nilai/jumlah
    public function nPelang($status='',$date='',$dateType='')
    {
            $dt = 'DATE(created_date) = DATE("'.$date.'")';
            $this->db->select('count(id) as jml,created_date');

            if ($status != '' && $status != 'all') { //kalau nilainya all gak bisa masuk ke if ini
                $this->db->where('status', $status);
            }

            // datetype : w => week,month,year
            if ($date != '') {
                if ($dateType != '') {
                    switch ($dateType) {
                        case 'w':
                            $dt = 'WEEK(created_date) = WEEK("'.$date.'")'; //minggu ini
                        break;
                        case 'm':
                            $dt = 'MONTH(created_date) = MONTH("'.$date.'")'; //bulan ini
                        break;
                        case 'y':
                            $dt = 'YEAR(created_date) =  YEAR("'.$date.'")'; //tahun ini
                        break;
                    }
                }
              
                $this->db->where($dt);
            }
          
            // Harus Valid
            $this->db->where('valid', '1');

            $this->db->group_by('id');

            $q = $this->db->get('pelang');
            if ($q->num_rows() > 0) {
                return rsp('',true,'Data ditemukan',$q->num_rows());
            }else{
                return rsp('',false,'Data Tidak ditemukan',0);
            }
    }

    // Jumlah Tipe Pelanggaran

    public function nTipePelang($date='',$dateType='m')
    {
        if ($dateType != '') {
            switch ($dateType) {
                case 'w':
                    $dt = 'AND WEEK(p.created_date) = WEEK("'.$date.'")'; //minggu ini
                break;
                case 'm':
                    $dt = 'AND MONTH(p.created_date) = MONTH("'.$date.'")'; //bulan ini
                break;
                case 'y':
                    $dt = 'AND YEAR(p.created_date) =  YEAR("'.$date.'")'; //tahun ini
                break;
            }
        }
        
        $q = $this->db->query("SELECT pt.pelang, count(p.id) as jml FROM `pelang` p INNER JOIN pelang_tipe pt ON pt.id = p.tipe_pelang WHERE p.valid = '1' ".$dt."  GROUP BY p.tipe_pelang");
        if ($q->num_rows() > 0) {
            return rsp($q->result(),true,'Data ditemukan',$q->num_rows());
        }else{
            return rsp('',false,'Data Tidak ditemukan',0);
        }
    }

    // Jumlah Pelanggaran
    public function nPelangNew($dateType='m')
    {
        if ($dateType != '') {
            switch ($dateType) {
                case 'w':
                    $se = "WEEKDAY(created_date) as k"; // menampilkan hari senin = 0, selasa = 1, dll
                    $ob = 'DATE(created_date)'; //minggu ini
                    $we = " AND WEEK(created_date) = WEEK(NOW())";
                break;
                case 'm':
                    $se = "DATE_FORMAT(created_date,'%e') as k"; //menampilkan tanggal 1,2,3,.., 31 dll
                    $we = "AND MONTH(created_date) = MONTH(NOW())";
                    $ob = 'DATE(created_date)'; //bulan ini
                break;
                case 'y':
                    $se = "MONTH(created_date) as k"; //menampilkab bulan 1,2,3 dll
                    $we = "AND YEAR(created_date) = YEAR(NOW())";
                    $ob = 'MONTH(created_date)'; //tahun ini
                break;
            }
        }
        
        $q = $this->db->query("SELECT count(id) as jml,".$se.",created_date as date FROM `pelang` WHERE valid = '1' ".$we." GROUP BY ".$ob);
        if ($q->num_rows() > 0) {
            return rsp($q->result(),true,$this->db->last_query(),$q->num_rows());
        }else{
            return rsp('',false,'Data Tidak ditemukan',0);
        }
    }


}

/* End of file Pelang.php */
