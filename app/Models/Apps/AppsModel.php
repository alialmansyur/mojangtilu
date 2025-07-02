<?php

namespace App\Models\Apps;

use CodeIgniter\Model;

class AppsModel extends Model
{
    protected $table = 'data_pegwai';

    public function __construct()
    {
        parent::__construct();
    }

    public function storeData($data, $table)
    {
        $this->db->table($table)->insert($data);
        return $this->db->insertID();
    }

    public function updateData($data, $id, $table)
    {
        return $this->db->table($table)->where('id', $id)->update($data);
    }

    public function removeData($id, $table)
    {
        return $this->db->table($table)->where('id', $id)->delete();
    }

    public function insertBatchData($data,$table)
    {
        $this->table = $table;
        return $this->db->table($this->table)->insertBatch($data);
    }

    public function getAvatar($user){
        $data = $this->db->query("
            SELECT * FROM auth_users WHERE id = '$user'
        ")->getRow();
        return $data->userimage;
    }

    public function getLayananData($param, $keyword = '', $unit = '0')
    {
        $builder = $this->db->table('data_layanan')->orderBy('alias', 'ASC');

        if ($unit !== '0') {
            $builder->where('bidang_id', $unit);
        }

        if ($keyword !== '') {
            $builder->groupStart()
                ->like('nama_layanan', $keyword)
                ->orLike('alias', $keyword)
                ->orLike('kategori', $keyword)
                ->groupEnd();
        }

        return $builder->get()->getResultArray();
    }

    public function validateEnrolled($param,$enroll){
        $builder = $this->db->table('trx_enroll');
        $builder->where('nip', $param);
        $builder->where('layanan_id', $enroll);
        return $builder->get()->getResultArray();
    }

    public function getLayananEnrolledData($user){
        return $this->db->query("
            SELECT 
                b.*, a.created_at enrolled_at 
            FROM trx_enroll a 
            LEFT JOIN data_layanan b ON b.id = a.layanan_id
            WHERE a.nip = '$user'
        ")->getResultArray();
    }

}
