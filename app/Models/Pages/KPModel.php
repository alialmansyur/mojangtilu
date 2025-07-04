<?php

namespace App\Models\Pages;

use CodeIgniter\Model;

class KPModel extends Model
{
    protected $table            = 'txn_layanan_kp';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getDataUploaded(){
        $builder = $this->db->table('txn_layanan_kp');
        $builder->where('DATE(created_at) = CURDATE()');
        return $builder->get()->getResultArray();
    }

    public function getCurrentData($user){
        $builder = $this->db->table('txn_layanan_kp');
        $builder->where('verified_by', $user);
        $builder->orderBy('nama', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function getAvaData() {
        return $this->db->query("
            SELECT 
            COUNT(*) AS total, DATE(created_at) taskdate
            FROM txn_layanan_kp 
            WHERE verified_by IS NULL AND DATE(created_at) = CURDATE()
        ")->getRow();
    }

    public function getEnrolledTask(){
        return $this->db->query("
            SELECT 
            'Kenaikan Pangkat' layanan,
            a.nip, b.nama, a.target
            FROM txn_enroll a
            JOIN data_member b ON b.nip = a.nip
            WHERE a.layanan_id = 8
        ")->getResultArray();
    }

    public function getAllocatedTask($nip){
        $builder = $this->db->table('txn_layanan_kp_target');
        $builder->where('nip', $nip);
        $builder->where('task_date', date('Y-m-d'));
        $data = $builder->get()->getRow();

        if (!$data) return [];
        if ((int)$data->allocated < 1) return [];

        return $this->db->table('txn_layanan_kp')
            ->select('id, nip')
            ->where('verified_by', null)
            ->where('DATE(created_at)', date('Y-m-d'))
            ->orderBy('RAND()')
            ->limit((int)$data->allocated)
            ->get()
            ->getResultArray();
    }


}
