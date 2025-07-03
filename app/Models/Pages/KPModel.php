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
        $builder->where('DATE(created_date) = CURDATE()');
        return $builder->get()->getResultArray();
    }

    public function getCurrentData($user){
        $builder = $this->db->table('txn_layanan_kp');
        $builder->where('verified_by', $user);
        $builder->orderBy('nama', 'ASC');
        return $builder->get()->getResultArray();
    }

}
