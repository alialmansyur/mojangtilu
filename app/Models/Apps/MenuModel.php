<?php

namespace App\Models\Apps;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table = 'auth_users_permissions';
     
    public function __construct()
    {
        parent::__construct();
    }

    public function getMenusPermissions($userId)
    {
        $sql = "SELECT b.*, a.is_create, is_read, a.is_update, a.is_delete
                FROM auth_users_permissions a
                LEFT JOIN auth_permissions b ON b.id = a.permission_id
                WHERE a.user_id = ? AND parent_id IS NULL AND is_show <> 0
                ORDER BY b.is_order ASC";
        return $this->db->query($sql, [$userId])->getResultArray();
    }

    public function getSubMenus($parentId)
    {
        $sql = "SELECT * FROM auth_permissions a WHERE parent_id = ? ORDER BY is_order ASC";
        return $this->db->query($sql, [$parentId])->getResultArray();
    }

    public function getPermissions($userId, $menuId)
    {
        $sql = "SELECT permission_id, is_create, is_read, is_update, is_delete 
                FROM auth_users_permissions WHERE user_id = ? AND permission_id = ?";
        $permissions = $this->db->query($sql, [$userId,$menuId])->getResultArray();
        $formatted = [];
        foreach ($permissions as $perm) {
            $formatted = [
                'create' => $perm['is_create'],
                'update' => $perm['is_update'],
                'delete' => $perm['is_delete'],
                'view'   => $perm['is_read'],
            ];
        }
        return $formatted;
    }
}
