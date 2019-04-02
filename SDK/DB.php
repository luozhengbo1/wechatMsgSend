<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14
 * Time: 10:02
 */

class DB
{
    private $connect;
    public function __construct($conf, $mode='short')
    {
        foreach ($conf as $key => $val) {
            $$key = $val;
        }
        $long = "";
        if($mode == "long") {
            $long = "p:";
        }
        $this->connect = new \mysqli($long.$host, $user, $password, $database, $port);
        $this->connect->query("set names utf8mb4");
        if (!$this->connect) {
            die("数据库连接失败！");
        }
    }
    public function insert($table, $data)
    {
        $i = 1;
        $total = count($data);
        $field = "(" ;
        $value = "(" ;
        foreach ($data as $key => $val) {
            if ($i == $total) {
                $field .= $key;
                $value .= '"'.$val.'"';
            } else {
                $field .= $key .",";
                $value .= '"'.$val.'",';
            }
            $i++;
        }
        $field .= ")";
        $value .= ")";
        $insert = "insert into {$table} {$field} value {$value}";
        file_put_contents("./log/InsertSql.txt", date("Y-m-d H:i:s").":".$insert."\r\n", 8);
        $this->connect->query($insert);
    }
    public function update($tableName, $where, $data)
    {
        $update = '';
        $i = 1;
        $count = count($data);
        foreach ($data as $key => $val) {
            if ($i == $count) {
                $update .= "{$key}='{$val}'";
            } else {
                $update .= "{$key}='{$val}', ";
            }
            $i++;
        }
        $ii = 1;
        $condition = '';
        $count = count($where);
        foreach ($where as $key1 => $val1) {
            if ($ii == $count) {
                $condition .= "{$key1}='{$val1}'";
            } else {
                $condition .= "{$key1}='{$val1}' and ";
            }
            $ii++;
        }
        $sql = "update {$tableName} set {$update} where {$condition}";
        file_put_contents("./log/updateSql.txt", date("Y-m-d H:i:s").":".$sql."\r\n");
        $this->connect->query($sql);
    }
    public function select()
    {

    }
    public function find($tableName, $where)
    {
        $condition= '';
        $i = 1;
        $count = count($where);
        foreach ($where as $key => $val) {
            if ($i == $count) {
                $condition .= "{$key}='{$val}'";
            } else {
                $condition .= "{$key}='{$val}' and ";
            }
            $i++;
        }
        $sql = "select * from {$tableName} where {$condition}";
        file_put_contents("./log/FindRow.sql", date("Y-m-d H:i:s").":".$sql."\r\n");
        $result = $this->connect->query($sql);
        return $this->getData($result);
    }
    private function getData($result)
    {
        $tmp = array();
        while ($row = $result->fetch_assoc()) {
            $tmp[] = $row;
        }
        return $tmp;
    }
    public function exists($tableName, $where)
    {

        return $this->find($tableName, $where);
    }
}