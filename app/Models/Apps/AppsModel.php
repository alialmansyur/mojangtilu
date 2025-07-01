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

    public function getDataPegawai(){
        return $this->db->query("
            SELECT 
                a.*, b.posisi posisiname
            FROM data_member a
            LEFT JOIN data_posisi b ON b.id = a.unit_id
            WHERE nip <> '199707252024211004'
            ORDER BY a.nama ASC
        ")->getResultArray();
    }

    public function getUnitList(){
        return $this->db->query("
            SELECT *
            FROM data_posisi 
            ORDER BY posisi ASC
        ")->getResultArray();
    }

    public function getDataMovable(){
        return $this->db->query("
            SELECT 
                a.*, b.nama, c.posisi posisiname
            FROM data_movable a
            LEFT JOIN data_member b ON b.nip = a.nip 
            LEFT JOIN data_posisi c ON c.id = b.unit_id
            WHERE month(presence_date) = month(curdate())
            ORDER BY a.presence_date DESC
        ")->getResultArray();
    }

    public function getSetupBase(){
        return $this->db->query("
            SELECT *
            FROM presence_setting a
            ORDER BY a.id ASC
        ")->getResultArray();
    }

    public function getAvatar($user){
        $data = $this->db->query("
            SELECT * FROM auth_users WHERE id = '$user'
        ")->getRow();

        return $data->userimage;
    }

    public function getInfoProfil($user){
        return $this->db->query("
            SELECT * FROM data_member WHERE nip = '$user'
        ")->getRow();
    }
}
