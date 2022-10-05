<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 22/07/2017
 * Time: 16:28
 */

class Ci_select2
{
    protected $CI;

    /**
     * CI_DataTables constructor.
     */
    public function __construct()
    {
        $this->CI = &get_instance();
    }

    /**
     * @param $data
     * @param $table
     * @param $columns
     * @param null $join
     * @param null $where
     * @return array
     */
    public function exec($data, $table, $columns, $join = NULL, $where = NULL, $group = null)
    {
        $db = $this->CI->db;

        # global like filter
        // Pesquisa o termo em todas as colunas explicitamente declaradas no argumento $columns
        if (isset($data['search']) && $data['search'] != '') {
            $db->group_start();
            foreach ($columns as $column) {
                $db->or_like($column['db'], $data['search']);
            }
            $db->group_end();
        }

        // pesquisa individualmente em cada coluna declarada explicitamente no argumento $data['columns']
        if (isset($data['columns'])) {
            foreach ($data['columns'] as $column) {
                if (!empty($column['search'])) {
                    if (isset($column['multiple']) && $column['multiple']) {
                        $db->where_in($column['name'], (is_array($column['search']) ? $column['search'] : explode(',', $column['search'])));
                    } else {
                        if (isset($column['equal']) and $column['equal']) {
                            $db->where($column['name'], $column['search']);
                        } else {
                            if (isset($column['type'])) {
                                switch ($column['type']) {
                                    case 'and':
                                        $db->where($column['name'] . " LIKE '%" . $column['search'] . "%'");
                                        break;
                                    case 'or':
                                        $db->or_where($column['name'] . " LIKE '%" . $column['search'] . "%'");
                                        break;
                                }
                            } else {
                                $db->or_where($column['name'] . " LIKE '%" . $column['search'] . "%'");
                            }
                        }
                    }
                }
            }
        }

        # set columns show
        $cols = '';
        foreach ($columns as $c) {
            $cols .= $c['db'] . ' AS ' . $c['dt'] . ',';
        }

        # select...
        $db->select(rtrim($cols, ','));

        # join tables
        if (isset($join)) {
            foreach ($join as $j) {
                $db->join($j[0], $j[1]);
            }
        }

        # where tables
        if (isset($where)) {
            $db->where($where);
        }

        # limits (paging)
        if (isset($data['start']) && $data['length'] && $data['length'] > 0) {
            $db->limit($data['length'], $data['start']);
        }

        # order
        if (isset($data['group_by'])) {
            $db->order_by($data['group_by'], 'ASC');
        }


        if (isset($data['order'])) {
            foreach ($data['order'] as $order) {
                $db->order_by($order['column'], $order['dir']);
            }
        }

        #group_by

        if (isset($group)){
            $db->group_by($group);
        }


        # query
        $query = $db->get($table);

        # output format
        $output = array();
        foreach ($query->result_array() as $k => $row) {
            foreach ($columns as $c) {
                if (isset($c['formatter'])) {
                    $output[$k][$c['dt']] = $c['formatter']($row[$c['dt']], $row);
                } else {
                    $output[$k][$c['dt']] = $row[$c['dt']];
                }
            }
        }

        # TODO: Select2 Group Result - Finalizar esta parte...
        # group
        if (isset($data['group_by'])) {
            $group = array();
            foreach ($output as $k => $row) {
                $group[$k]['text'] = $row[$data['group_by']];
                $group[$k]['children'][] = $row;
            }
            $output = $group;
        }

        $num_rows = $query->num_rows();

        $result = [
            "more" => "false",
            "results" => $output,
            "total_count" => intval($db->count_all($table)),
        ];

        if (isset($data['length'])) {
            $result['pagination'] = [
                "more" => (!empty($num_rows) && $num_rows <= $data['length'])
            ];
        }

        # single object return
        return $result;
    }
}
