<?php

namespace App\Models\Apps;

use CodeIgniter\Model;

class PresenceModel extends Model
{
    protected $table            = 'presence';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['username','presence_date','start_attendence','end_attendence','status'];

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

    public function storeData($data, $table)
    {
        $this->db->table($table)->insert($data);
        return $this->db->insertID();
    }

    public function updateData($data, $id, $table)
    {
        return $this->db->table($table)->where('id', $id)->update($data);
    }

    public function updateDataInstallment($data, $id, $table)
    {
        return $this->db->table($table)->where('uid', $id)->update($data);
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

    public function insertPresenceData($data, $table)
    {
        $this->db->table($table)->insert($data);
        return $this->db->insertID();
    }

    public function updatePresenceData($data, $id, $table)
    {
        return $this->db->table($table)->where('id', $id)->update($data);
    }

    public function updateByUsername($data, $id, $table)
    {
        $fieldName = $table == 'data_member' ? 'nip' : 'username';
        return $this->db->table($table)->where($fieldName, $id)->update($data);
    }

    public function getPegawainfo($key){
        return $this->db->query("
            SELECT * FROM data_member WHERE nip = '$key'
        ")->getRow();
    }
    
    public function checkDataPresence($param)
    {
        return $this->db->query("
            SELECT * FROM presence WHERE presence_date = CURDATE() AND username = '$param'
        ")->getNumRows();
    }

    public function getDataPresence($param)
    {
        return $this->db->query("
            SELECT * FROM presence WHERE presence_date = CURDATE() AND username = '$param'
        ")->getRow(); 
    }

    public function getHistoryPresence($userid)
    {
        return $this->db->query("
            SELECT 
            a.username, a.presence_date, 
            TIME(a.start_attendence) start_time, 
            TIME(a.end_attendence) end_time, 
            TIMESTAMPDIFF(MINUTE,start_attendence, a.end_attendence) work_time_minutes,
            ROUND(TIMESTAMPDIFF(MINUTE,start_attendence, a.end_attendence)/60,2) work_time_hour
            FROM presence a 
            WHERE a.username = '$userid' AND MONTH(presence_date) = MONTH(CURDATE()) AND YEAR(presence_date) = YEAR(CURDATE())
            ORDER BY a.presence_date desc
        ")->getResultArray();    
    }

    public function getHoursPresence($userid)
    {
        return $this->db->query("
            SELECT 
            day(a.presence_date) ofday, 
            TIMESTAMPDIFF(MINUTE,start_attendence, a.end_attendence) work_time_minutes,
            ROUND(TIMESTAMPDIFF(MINUTE,start_attendence, a.end_attendence)/60,2) work_time_hour
            FROM presence a
            WHERE a.username = '$userid' AND MONTH(presence_date) = MONTH(CURDATE()) AND YEAR(presence_date) = YEAR(CURDATE())
            ORDER BY a.presence_date ASC
        ")->getResultArray();  
    }
     
    public function getListIzin()
    {
        return $this->db->query("
            SELECT * 
            FROM presence_cat a WHERE leave_category = 'IZIN'
            ORDER BY leave_code ASC
        ")->getResultArray();    
    }

    public function getListCuti()
    {
        return $this->db->query("
            SELECT * 
            FROM presence_cat a WHERE leave_category = 'CUTI'
            ORDER BY leave_code ASC
        ")->getResultArray();    
    }    

    public function getHistoryInstallment($userid,$start,$end,$param)
    {

        $addon = ($start == $end) ? "MONTH(a.created_at) = MONTH(CURDATE()) AND YEAR(a.created_at) = YEAR(CURDATE())" : "a.date_start >= '$start' AND a.date_start <= '$end'";
        $filer = $param == 0 ? "IZIN" : "CUTI";

        return $this->db->query("
            SELECT 
            a.id, a.uid, a.doc_key, a.username, a.leave_code, b.leave_name, a.leave_init, a.leave_note, approved_at,
            a.date_start,
            a.date_end,
            DATEDIFF(a.date_end,a.date_start)+1 instance,
            CASE 
                WHEN a.leave_code = 'LV07' THEN a.time_start
                WHEN a.leave_code = 'LV08' THEN a.time_end
                ELSE '-'
            END time_attend,
            -- a.time_start, 
            a.file_name, 
            a.status,
            CASE 
                WHEN a.status = 1 THEN 'Menunggu Approval 1'
                WHEN a.status = 2 THEN 'Menunggu Approval 2'    
                WHEN a.status = 3 THEN 'Approved'
                WHEN a.status = 4 THEN 'Rejected'
                WHEN a.status = 5 THEN 'Canceled'
            END status_msg,
            CASE 
                WHEN a.status = 1 THEN 'warning'
                WHEN a.status = 2 THEN 'warning'
                WHEN a.status = 3 THEN 'success'
                WHEN a.status = 4 THEN 'danger'
                WHEN a.status = 5 THEN 'secondary'
            END status_clr
            FROM presence_app a
            LEFT JOIN presence_cat b ON b.leave_code = a.leave_code
            WHERE a.username = $userid AND b.leave_category = '$filer' AND $addon
            ORDER BY a.created_at DESC
        ")->getResultArray();  
    }

    public function approvalInstallment($param){
        $addon = $param != 0 ? "WHERE a.uid = '$param'" : "WHERE a.status =  1";
        return $this->db->query("
            SELECT 
                a.id, a.uid, a.doc_key, a.username, a.leave_code, b.leave_name, a.leave_init, a.leave_note, a.created_at,
                c.nip, c.nama,
                a.date_start,
                a.date_end,
                DATEDIFF(a.date_end,a.date_start)+1 instance,
                CASE 
                    WHEN a.leave_code = 'LV07' THEN a.time_start
                    WHEN a.leave_code = 'LV08' THEN a.time_end
                    ELSE '-'
                END time_attend,
                -- a.time_start, 
                a.file_name, 
                a.status
                FROM presence_app a
                LEFT JOIN presence_cat b ON b.leave_code = a.leave_code
                LEFT JOIN data_member c ON c.nip = a.username
            $addon
            ORDER BY a.created_at DESC
        ")->getResultArray(); 
    }

    public function CheckStockLeave($user)
    {
        $stock = $this->db->query("
            SELECT SUM(stock) stock
            FROM
            (
                SELECT stock FROM presence_leave_stock WHERE period = YEAR(CURDATE()) AND username = '$user'
                UNION ALL
                SELECT -SUM(DATEDIFF(a.date_end,a.date_start)+1) stock FROM presence_app a WHERE leave_code = 'LV10' AND username = '$user' AND a.status <> 5
            ) aa
        ")->getRow();    

        return $stock->stock;
    }

    public function getCalenderPresence($user)
    {
        return $this->db->query("
            SELECT 
                a.id,
                a.presence_date,
                TIME(a.start_attendence) AS start_attend,
                TIME(a.end_attendence) AS end_attend,
                STR_TO_DATE(s_in.setting_value, '%H:%i') plan_in,
                STR_TO_DATE(s_out.setting_value, '%H:%i') plan_out,
                CONCAT(
                    'Presence IN : ', TIME(a.start_attendence), '<br>',
                    'Presence OUT : ', COALESCE(TIME(a.end_attendence), '-'), '<br>',
                    CASE 
                        WHEN a.start_attendence IS NULL THEN 'Tidak Absen Datang'
                        WHEN a.end_attendence IS NULL THEN 'Tidak Absen Pulang'
                        WHEN TIME(a.start_attendence) > STR_TO_DATE(s_in.setting_value, '%H:%i') THEN 'Terlambat Masuk'
                        WHEN TIME(a.end_attendence) < STR_TO_DATE(s_out.setting_value, '%H:%i') THEN 'Pulang Lebih Awal'
                        ELSE 'Presensi Normal'
                    END
                ) AS desc_attend,
                CASE 
                    WHEN a.start_attendence IS NULL THEN 'fc-event-danger'
                    WHEN a.end_attendence IS NULL THEN 'fc-event-danger'
                    WHEN TIME(a.start_attendence) > STR_TO_DATE(s_in.setting_value, '%H:%i') THEN 'fc-event-warning' -- Telat masuk
                    WHEN TIME(a.end_attendence) < STR_TO_DATE(s_out.setting_value, '%H:%i') THEN 'fc-event-warning' -- Pulang lebih awal
                    ELSE 'fc-event-success'
                END AS flag_color,
                CASE 
                    WHEN a.start_attendence IS NULL THEN 'Tidak Absen Datang'
                    WHEN a.end_attendence IS NULL THEN 'Tidak Absen Pulang'
                    WHEN TIME(a.start_attendence) > STR_TO_DATE(s_in.setting_value, '%H:%i') THEN 'Terlambat Masuk'
                    WHEN TIME(a.end_attendence) < STR_TO_DATE(s_out.setting_value, '%H:%i') THEN 'Pulang Lebih Awal'
                    ELSE 'Presensi Normal'
                END AS flag_mesg
            FROM presence a
            LEFT JOIN presence_setting s_in ON s_in.setting_param = 'set_presence_in'
            LEFT JOIN presence_setting s_out ON s_out.setting_param = 'set_presence_out'
            WHERE 
                a.username = '$user' 
                AND MONTH(a.presence_date) = MONTH(CURDATE()) 
                AND YEAR(a.presence_date) = YEAR(CURDATE())
            GROUP BY a.username, a.presence_date
        ")->getResultArray();   
    }

    public function getResume($user)
    {
        return $this->db->query("
            SELECT 
                COUNT(a.presence_date) AS total_presensi, 
                ROUND(IFNULL(SUM(TIMESTAMPDIFF(SECOND, a.start_attendence, a.end_attendence)) / 3600, 2), 0) AS total_hours,
                IFNULL(TIME_FORMAT(SEC_TO_TIME(AVG(TIME_TO_SEC(a.start_attendence))), '%H:%i'), '00:00') AS avg_waktu_masuk,
                IFNULL(TIME_FORMAT(SEC_TO_TIME(AVG(TIME_TO_SEC(a.end_attendence))), '%H:%i'), '00:00') AS avg_waktu_pulang
            FROM presence a
            WHERE a.username = '$user' 
            AND MONTH(a.presence_date) = MONTH(CURDATE()) 
            AND YEAR(a.presence_date) = YEAR(CURDATE())
            AND a.start_attendence IS NOT NULL
            AND a.end_attendence IS NOT NULL
        ")->getRow();
    }

    public function getHistoryPresenceAll($userid,$start,$end)
    {
        $addon = ($start == $end) ? "MONTH(a.created_at) = MONTH(CURDATE()) AND YEAR(a.created_at) = YEAR(CURDATE())" : "a.presence_date >= '$start' AND a.presence_date <= '$end'";
        return $this->db->query("
            SELECT 
                a.username, a.presence_date, 
                TIME(a.start_attendence) start_time, 
                TIME(a.end_attendence) end_time, 
                TIMESTAMPDIFF(MINUTE,start_attendence, a.end_attendence) work_time_minutes,
                ROUND(TIMESTAMPDIFF(MINUTE,start_attendence, a.end_attendence)/60,2) work_time_hour
            FROM presence a WHERE a.username = '$userid' AND $addon
            ORDER BY a.presence_date desc
        ")->getResultArray();  
    }

    public function getTimeAttende(){
        return $this->db->query("
            SELECT * FROM presence_setting WHERE setting_category = 'time_attende' ORDER BY id ASC
        ")->getResultArray();
    }

}
