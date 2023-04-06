<?php

namespace App\RMVC\Database;

trait QueryBuilder{
    private $where = [];
    private $having = [];
    private $order = [];
    private $groupBy = [];
    private $join = [];
    private $limit = [];

    private $_t;

    public function setTable(string $table)
    {
        $this->_t = $table;
        
    }

    public function limit(int $limit, int $offset = NULL)
    {
        $this->limit = [$limit, $offset];
        return $this;
    }

    private function _join($table, $field, $linkTo = null, $alias = null, $type = "INNER")
    {
        if ($linkTo == null) $linkTo = "{$this->_t}`.`id";
        else $linkTo = str_replace(".", "`.`", $linkTo);
        $on = (empty($alias) ? "`{$table}`" : "`{$alias}`") . ".`{$field}`= `{$linkTo}`";
        $this->join[] = [$type, $table, $alias, $on];
    }

    public function join($table, $field, $linkTo = null, $alias = null)
    {
        $this->_join($table, $field, $linkTo, $alias);
        return $this;
    }
    public function leftJoin($table, $field, $linkTo = null, $alias = null)
    {
        $this->_join($table, $field, $linkTo, $alias, "LEFT");
        return $this;
    }
    public function rightJoin($table, $field, $linkTo = null, $alias = null)
    {
        $this->_join($table, $field, $linkTo, $alias, "RIGHT");
        return $this;
    }
    public function groupBy($field)
    {
        $this->groupBy[] = str_replace(".", "`.`", $field);
        return $this;
    }
    public function asc($field = "id")
    {
        $this->order[] = [str_replace(".", "`.`", $field), "ASC"];
        return $this;
    }
    public function desc($field = "id")
    {
        $this->order[] = [str_replace(".", "`.`", $field), "DESC"];
        return $this;
    }

    private function _where($type, $field, $sign, $value)
    {
        if ($value == null) {
            $value = $sign;
            $sign = "=";
        }
        if (!is_integer($value) && $value[0] != ":" && $value[0] != "?")
            $value = $this->dbh->quote($value);
        return [$type, str_replace(".", "`.`", $field), $sign, $value];
    }

    public function where($field, $sign, $value = null)
    {
        $this->where[] = $this->_where("", $field, $sign, $value);
        return $this;
    }
    public function andWhere($field, $sign, $value = null)
    {
        $this->where[] = $this->_where(" AND ", $field, $sign, $value);
        return $this;
    }
    public function orWhere($field, $sign, $value = null)
    {
        $this->where[] = $this->_where(" OR ", $field, $sign, $value);
        return $this;
    }
    public function whereGroup(callable $where)
    {
        $this->where[] = ["("];
        $where($this);
        $this->where[] = [")"];
        return $this;
    }

    public function having($field, $sign, $value = null)
    {
        $this->having[] = $this->_where("", $field, $sign, $value);
        return $this;
    }
    public function andHaving($field, $sign, $value = null)
    {
        $this->having[] = $this->_where(" AND ", $field, $sign, $value);
        return $this;
    }
    public function orHaving($field, $sign, $value = null)
    {
        $this->having[] = $this->_where(" OR ", $field, $sign, $value);
        return $this;
    }
    public function havingGroup(callable $having)
    {
        $this->having[] = ["("];
        $having($this);
        $this->having[] = [")"];

        return $this;
    }
    private function _select(string $table, $wheres = [], $joins = null, $orders = null, $field = '*', $group_by = null, $having = null, $limits = null)
    {
        $q = "SELECT {$field} FROM `{$table}`";
        if (!empty($joins)) {
            foreach ($joins as $join) {
                $q .= " {$join[0]} JOIN `{$join[1]}` ";
                if (!empty($join[2])) $q .= "AS `{$join[2]}`";
                $q .= "ON ({$join[3]})";
            }
        }
        if (!empty($wheres)) {
            $q .= " WHERE ";
            foreach ($wheres as $where) {
                $q .= "$where[0]";
                if (count($where) > 1) $q .= "(`{$where[1]}` {$where[2]} {$where[3]})";
            }
        }
        if (!empty($group_by)) {
            $q .= " GROUP BY (`" . implode("`,`", $group_by) . "`)";
        }
        if (!empty($having)) {
            $q .= " HAVING ";
            foreach ($having as $where) {
                $q .= "{$where[0]}";
                if (count($where) > 1) $q .= "(`{$where[1]}` {$where[2]} {$where[3]})";
            }
        }

        if (!empty($orders)) {
            $q .= " ORDER BY ";
            $tmp = [];
            foreach ($orders as $order) $tmp[] = "`{$order[0]}` {$order[1]}";
            $q .= implode(",", $tmp);
        }

        if (!empty($limits)) {
            $q .= " LIMIT {$limits[0]}";
            if (!empty($limits[1])) $q .= " OFFSET {$limits[1]}";
        }

        return $q;
    }

    protected function _delete(int $id)
    {
        return "DELETE FROM $this->_t WHERE id ='{$id}'";
    }



    protected function _create($data = [])
    {
        $q = "INSERT INTO `$this->_t` ";


        if (!empty($data)) {
            $q .= "(";
            foreach ($data as $key => $value) {
                $tmp[]   = " `{$key}` ";
                $values[] = "'$value'";
            }
            $q .= implode(",", $tmp);
            $q .= ") VALUES (" . implode(", ", $values).")";
        }
        return $q;
       
    }

    protected function _update($data = [], $id = [])
    {
        $q = "UPDATE `$this->_t` SET ";


        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $tmp[] = "`{$key}` = '$value' ";
            }
            $q .= implode(",", $tmp);
            $q .= " WHERE `{$id[0]}` = '{$id[1]}'";
        }
        return $q;
    }

  
}