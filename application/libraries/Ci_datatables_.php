<?php

class DatatableLibrary
{
    public function exec($config, $request, $group = null)
    {
        #DB::enableQueryLog();
        $query = DB::table($config['table']);

        /** SELECT, SE FOR ARRAY USA DB::RAW SE NÃO É UM CAMPO NORMAL
         */
        foreach ($config['columns'] as $c) {
            if (isset($config['dictionary'])) {
                $query->addSelect("{$c} AS " . array_search($c, $config['dictionary']));
            } else {
                $query->addSelect("{$c}");
            }
        }


        /** JOIN CLAUSULE
         *
         * 'join' => [
         *      $table => [
         *          $col1, $sinal[=, >, <, <>], $col2, $typeJoin = "leftJoin, rightJoin, crossJoin"
         *      ]
         * ]
         *
         * EX: 'join' => [
         *           '{$company}.sys_groups_users' => [
         *              "{$company}.sys_users.id_group", "=", "{$company}.sys_groups_users.id", "leftJoin"
         *         ]
         *    ]
         */
        if (isset($config['join']) && is_array($config['join'])) {
            foreach ($config['join'] as $table => $on) {
                if (is_array($on) && count($on) > 3) {
                    $query->{$on[3]}($table, $on[0], $on[1], $on[2]);
                } else {
                    $query->join($table, $on[0], $on[1], $on[2]);
                }
            }
        }
        // END JOIN CLAUSULE


        /** WHERE CLAUSULE
         *  SEARCH DATATABLE
         */
        if (isset($request['search']['value'])) {
            foreach ($request['columns'] as $k => $col) {
                if ($col['searchable'] == 'true') {
                    if (isset($col['search']['type'])) {
                        switch ($col['search']['type']) {
                            case 'date':
                                if (isset($config['dictionary'])) {
                                    $query->orWhereRaw("CAST({$config['dictionary'][$col['name']]} as CHAR)", 'LIKE', "{$request['search']['value']}%");
                                } else {
                                    $query->orWhereRaw("CAST({$col['name']} as CHAR)", 'LIKE', "{$request['search']['value']}%");
                                }
                                break;
                            default:
                                break;
                        }
                    } else {
                        if (isset($config['dictionary'])) {
                            $query->orWhere("{$config['dictionary'][$col['name']]}", 'like', "%{$request['search']['value']}%");
                        } else {
                            $query->orWhere("{$col['name']}", 'like', "%{$request['search']['value']}%");
                        }
                    }
                }
            }
        }
        // ADVANCED FILTER
        foreach ($request['columns'] as $c) {
            if (isset($c['search']['value'])) {
                if (isset($c['search']['type'])) {
                    switch ($c['search']['type']) {
                        case 'date':
                            $query->whereRaw("DATE({$config['dictionary'][$c['name']]}) = '{$c['search']['value']}'");
                            break;
                        default:
                            break;
                    }
                } else {
                    $query->where($config['dictionary'][$c['name']], $c['search']['value']);
                }
            }
        }

        /** WHERE */
        if (isset($config['where'])) {
            if (is_array($config['where'])) {
                foreach ($config['where'] as $w) {
                    $query->whereRaw($w);
                }
            } else {
                $query->whereRaw($config['where']);
            }
        }

        if (isset($group) && !empty($group)){
            $query->group_by($group);
        }

        // ORDER
        $count = $query->get()->count();
        $query->orderBy($request['columns'][$request['order'][0]['column']]['name'], $request['order'][0]['dir'])
            ->offset($request['start'])
            ->limit($request['length']);

        $data = $query->get()->toArray();

        #DB::getQueryLog();

        // END WHERE CLAUSULE
        $result = [
            "draw" => $request['draw'],
            "recordsTotal" => DB::table($config['table'])->count(),
            "recordsFiltered" => $count,
            "data" => $data
        ];

        return $result;
    }
}