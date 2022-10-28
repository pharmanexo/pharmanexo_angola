<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Model extends CI_Model
{
    protected $table = '';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = '';
    protected $order_direction = 'ASC';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * get
     *
     * @param   string $fields
     * @return array
     */
    public function get($fields = '*')
    {
        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->order_by($this->order_field, $this->order_direction);

        return $this->db->get()->result_array();
    }

    /**
     * find
     *
     * @param   string  $fields
     * @param   string  $where
     * @param   boolean $single
     * @return  array
     */
    public function find($fields = '*', $where = NULL, $single = FALSE, $order = NULL, $group = null)
    {
        $method = ($single == TRUE) ? 'row_array' : 'result_array';

        $this->db->select($fields);
        $this->db->from($this->table);
        if (isset($where)) $this->db->where($where);
        if (isset($order)) $this->db->order_by($order);
        if (isset($group)) $this->db->group_by($group);

        return $this->db->get()->$method();
    }

    /**
     * findById
     *
     * @param   integer     $id
     * @return  array
     */
    public function findById($id)
    {
        if (!isset($id)) return false;

        $filter = $this->primary_filter;
        $id = $filter($id);

        $this->db->where($this->primary_key, $id);
        $this->db->limit(1);

        return $this->db->get($this->table)->row_array();
    }

    /**
     * insert
     *
     * @param   array       $data
     * @return  integer
     */
    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * update
     *
     * @param   array       $data
     * @return  integer
     */
    public function update($data)
    {
        if (!isset($data[$this->primary_key])) {
            return false;
        }

        $filter = $this->primary_filter;
        $id = $filter($data[$this->primary_key]);

        unset($data[$this->primary_key]);

        $this->db->where($this->primary_key, $id);
        $this->db->update($this->table, $data);

        return $id;
    }

    /**
     * delete
     *
     * @param   integer     $id
     * @return  boolean
     */
    public function delete($id)
    {
        if (!isset($id)) {
            return false;
        }

        $filter = $this->primary_filter;
        $id = $filter($id);

        $this->db->where($this->primary_key, $id);
        $this->db->limit(1);

        return $this->db->delete($this->table);
    }

    /**
     * dataTables
     *
     * @param 	Object 		$dtObject
     * @return 	Array
     */
    public function dataTables($dtObject)
    {
        $this->db->start_cache();

        $cols = '';

        $columns = $this->_columnArray();
        if (!is_null($columns) && !empty($columns)) {
            foreach ($columns as $k => $v) {
                $cols .= $v['db'] . ' AS ' . $v['dt'] . ', ';
            }
        }

        $this->db->select(rtrim($cols, ','));
        $this->db->from($this->table);

        $joinArray = $this->_joinArray();
        if (!is_null($joinArray) && !empty($joinArray)) {
            foreach ($joinArray as $k => $v) {
                $this->db->join($v[0], $v[1], $v[2]);
            }
        }

        # datatable search columns
        if (isset($dtObject['columns'])) {
            $_columns = $dtObject['columns'];

            # global like filter
            if (!empty($dtObject['search']['value'])) {
                $this->db->group_start();
                foreach ($dtObject['columns'] as $column) {
                    if ($column['searchable'] == 'true') $this->db->or_like($column['name'], $dtObject['search']['value']);
                }
                $this->db->group_end();
            }

            # individual search
            foreach ($dtObject['columns'] as $column) {
                if ($column['searchable'] == 'true' and ($column['search']['value'] !== '')) {
                    if (isset($column['search']['type'])) {
                        $col = $column['name'];
                        $val = explode(',', $column['search']['value']);
                        switch ($column['search']['type']) {
                            case  'equal':
                                $this->db->where($col, $column['search']['value']);
                                break;
                            case  'in':
                                $this->db->where_in($col, $val);
                                break;
                            case  'or_in':
                                $this->db->or_where_in($col, $val);
                                break;
                            case  'not_in':
                                $this->db->where_not_in($col, $val);
                                break;
                            case  'or_not_in':
                                $this->db->or_where_not_in($col, $val);
                                break;
                            case  'between':
                                list($d1, $d2) = $val;
                                if (isset($d1) && isset($d2)) $this->db->where("{$col} BETWEEN '{$d1}' AND '{$d2}'");
                                break;
                            case 'date':
                                list($d1, $d2) = $val;
                                if (isset($d1) && isset($d2)) $this->db->where("DATE({$col}) BETWEEN '{$d1}' AND '{$d2}'");
                                break;
                        }
                    } else {
                        $this->db->like($column['name'], $column['search']['value']);
                    }
                }
            }
        }

        # where tables
        $whereArray = $this->_whereArray();
        if (!is_null($whereArray) && !empty($whereArray)) {
            $this->db->where($whereArray);
        }

        # group by tables
        $groupBy = $this->_groupBy();
        if (!is_null($groupBy) && !empty($groupBy)) {
            $this->db->group_by($groupBy);
        }

        # total record find
        $this->db->stop_cache();
        $recordsFiltered = $this->db->count_all_results();

        # limits (paging)
        if (isset($dtObject['start']) && $dtObject['length'] && $dtObject['length'] > 0) {
            $this->db->limit($dtObject['length'], $dtObject['start']);
        }

        # order
        if (isset($dtObject['order'])) {
            foreach ($dtObject['order'] as $order) {
                if (isset($_columns)) {
                    $this->db->order_by($_columns[$order['column']]['name'], $order['dir']);
                }
            }
        }

        # query
        $query = $this->db->get();
        $rows = $query->result_array();
        $recordsTotal = $this->db->count_all_results($this->table);
        $query->free_result();
        $this->db->flush_cache();

        # output format
        $output = array();
        foreach ($rows as $k => $row) {
            foreach ($columns as $c) {
                if (isset($c['formatter'])) {
                    $output[$k][$c['dt']] = $c['formatter']($row[$c['dt']], $row);
                } else {
                    $output[$k][$c['dt']] = $row[$c['dt']];
                }
            }
        }

        return [
            "draw"            => isset($dtObject['draw']) ? intval($dtObject['draw']) : 0,
            "recordsTotal"    => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data"            => $output
        ];
    }

    protected function _columnArray()
    {
        return NULL;
    }

    protected function _joinArray()
    {
        return NULL;
    }

    protected function _whereArray()
    {
        return NULL;
    }

    protected function _groupBy()
    {
        return NULL;
    }
}

/* End of file: MY_Model.php */
