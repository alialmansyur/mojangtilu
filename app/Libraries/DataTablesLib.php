<?php

namespace App\Libraries;

class DataTablesLib
{
    protected $request;

    public function __construct()
    {
        $this->request = service('request');
    }

    public function render($builder, array $columns){
        $request = $this->request;
        $draw    = $request->getPost('draw');
        $start   = $request->getPost('start');
        $length  = $request->getPost('length');
        $search  = $request->getPost('search')['value'] ?? null;
        $order   = $request->getPost('order')[0] ?? null;
        $totalRecords = $builder->countAllResults(false);

        if ($search) {
            $builder->groupStart();
            foreach ($columns as $col) {
                $builder->orLike($col, $search);
            }
            $builder->groupEnd();
        }

        $totalFiltered = $builder->countAllResults(false);

        if ($order && isset($columns[$order['column']])) {
            $builder->orderBy($columns[$order['column']], $order['dir']);
        }

        $query = $builder->limit($length, $start)->get();
        $data  = $query->getResult();

        $result = [];
        $no = $start + 1;
        foreach ($data as $row) {
            $rowData = ['DT_RowIndex' => $no++];
            foreach ($columns as $col) {
                $rowData[$col] = $row->$col ?? null;
            }
            if (property_exists($row, 'id')) {
                $rowData['id'] = $row->id;
            }
            $result[] = $rowData;
        }

        return [
            'draw'            => intval($draw),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data'            => $result
        ];
    }
}
