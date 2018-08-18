<?php
namespace  NoQuery;
/**
 * User: Klaus
 * Date: 9/18/2017
 * Time: 1:55 PM
 * @property string where
 */
class Builder
{

    public $db;
    private static $_instance;
    private  $sql;
    private  $table;
    private  $limit;
    private  $offset;
    private  $prepared;
    private  $result;
    private  $params = [];

    public function __construct($config = null,$is_ado = false)
    {
        if($is_ado){
            $this->db = $config;
        }else{
            $this->init($config);
        }
    }

    public static function getInstance ()
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    /**
     * @param $config
     * @return string
     */
    private function init($config){
        #This is the driver you have selected to use
        try{
            $db = ADONewConnection($config['driver']);
            $db->debug = $config['debug'];
            $db->bulkBind = true;
            $db->bulkBind = true;
            $db->SetFetchMode(ADODB_FETCH_ASSOC);
            $db->connect($config['server'], $config['username'], $config['password'], $config['database']);
            #These are the parameters required to connect to the database see connect()
            if($db->isConnected()){
                $this->db = $db;
            }else{
                die("Sorry an error occurred ");
                $this->db = false;
            }
        }catch (Exception $e){
            die("Sorry an error occured {$e->getMessage()}");
        }


    }


    public function table ($table){
        $this->table = $table;
        return $this;
    }

    /**
     * Inserts entities into db
     * @param array $value
     * @return mixed
     */
    public function insert(array $value){
        $columns = array_keys($value);
        $columns = implode(",",$columns);

        $values = array_values($value);
        $placeholders = array_map(function($val){
                return " ? ";
        },$values);

        $placeholders = implode(",",$placeholders);
//        die(var_dump($values));
        $this->sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $prepared = $this->db->prepare($this->sql);

        return $this->sql = $this->db->execute($prepared,$values);

    }

    /**
     * RAW sql
     */
    public function raw($statement){
        $prepared = $this->sql =  $this->db->prepare($statement);
        return $this->db->execute($prepared);
    }
    /**
     * Sets the select query
     * @param array $columns
     * @return string
     */
    public  function select (array $columns=[]){
        if($columns){
            $columns = implode(',',$columns);
        }else{
            $columns = "*";
        }
        $table = $this->table;
        $sql = "SELECT {$columns} FROM {$table} ";
        $this->sql = $sql;
        return $this;
    }

    /**
     * Adds WHERE condition to the query
     * @param array $conditions
     * @return $this
     */
    public  function where (array $conditions) {
        #@TODO Allow string conditions
        if($conditions){
            #Check if there is where already in the sql statement;
            $contains_where = stripos($this->sql," WHERE ");
            $conditions = implode(' AND ',$conditions);
//            die($conditions);
            $this->where = $conditions;
        }else{
            throw new Exception("Conditions variable must be  set and must be an array");
        }
        if($contains_where == false){
            $sql = $this->sql." WHERE {$conditions}";
        }else{
            $sql =$this->sql." AND {$conditions}";
        }
        $this->sql = $sql;

        return $this;
    }
    /**
     * Adds OR WHERE condition to the query
     * @param array $conditions
     * @return $this
     */
    public  function orWhere (array $conditions) {
        #Ceheck if there is where already in the sql statement;
        if($conditions){
            $contains_where = stripos($this->sql," WHERE ");
            $conditions = implode(' OR ',$conditions);
//            die($conditions);
            $this->where = $conditions;
        }else{
            throw new Exception("Conditions variable must be  set and must be an array");
        }
        if($contains_where == false){
            $sql =$this->sql." WHERE {$conditions}";
        }else{
            $sql =$this->sql." OR {$conditions}";
        }
        $this->sql = $sql;

        return $this;
    }

    /**
     * Adds WHERE IN condition to the query
     * @param array $conditions
     * @return $this
     */
    public  function whereIn (array $conditions) {
        $contains_where = stripos($this->sql," WHERE ");
        if($conditions){
            $array=[];
            foreach ($conditions as $column => $value){
                if(is_array($value)){
                    $value = implode("','",$value);
                }
                $value ="'{$value}'";
                $array[] =" {$column} IN ({$value}) ";
            }

            $conditions = implode(' AND ',$array);

            $this->where = $conditions;
        }else{
            throw new Exception("Conditions variable must be  set and must be an array");
        }
        if($contains_where == false){
            $sql =$this->sql." WHERE {$conditions}";
        }else{
            $sql =$this->sql." AND {$conditions}";
        }
        $this->sql = $sql;
        return $this;
    }


    /**
     * Adds WHERE NOT IN condition to the query
     * @param string $column
     * @param array $conditions
     * @return $this
     */
    public  function whereNotIn (string $column,array $conditions) {
        $value = implode("','",$conditions);
        $value ="'{$value}'";
        $conditions =" {$column} NOT IN ({$value})";
//        $conditions = implode(' AND ',$array);
        $this->where = $conditions;
        #Check if sql contains WHERE already
        $contains_where = stripos($this->sql," WHERE ");
        if($contains_where == false){
            $sql =$this->sql." WHERE {$conditions}";
        }else{
            $sql =$this->sql." AND {$conditions}";
        }
        $this->sql = $sql;

        return $this;
    }
    /**
     * Adds WHERE BETWEEN condition to the query
     * EG ->whereBetween('age',[18,25])
     * @param array $conditions
     * @return $this
     */
    public  function whereBetween ($column,array $conditions) {

        $value = implode(" AND ",$conditions);
//            $value ="'{$value}'";
        $conditions =" {$column} BETWEEN {$value} ";
//        $conditions = implode(' AND ',$array);
        $this->where = $conditions;
        #Check if sql contains WHERE already
        $contains_where = stripos($this->sql," WHERE ");
        if($contains_where == false){
            $sql =$this->sql." WHERE {$conditions}";
        }else{
            $sql =$this->sql." AND {$conditions}";
        }
        $this->sql = $sql;

        return $this;
    }    /**
     * Adds WHERE BETWEEN condition to the query
     * EG ->whereBetween('age',[18,25])
     * @param array $conditions
     * @return $this
     */
    public  function wherenNotBetween ($column,array $conditions) {

        $value = implode(" AND ",$conditions);
//            $value ="'{$value}'";
        $conditions =" {$column} NOT BETWEEN {$value} ";
//        $conditions = implode(' AND ',$array);
        $this->where = $conditions;
        #Check if sql contains WHERE already
        $contains_where = stripos($this->sql," WHERE ");
        if($contains_where == false){
            $sql =$this->sql." WHERE {$conditions}";
        }else{
            $sql =$this->sql." AND {$conditions}";
        }
        $this->sql = $sql;

        return $this;
    }
    /**
     * Adds WHERE IN condition to the query
     * @param array $conditions
     * @return $this
     */
    public  function orWhereIn (array $conditions) {
        $contains_where = stripos($this->sql," WHERE ");
        if($conditions){
            $array=[];
            foreach ($conditions as $column => $value){
                if(is_array($value)){
                    $value = implode("','",$value);
                }
                $value ="'{$value}'";
                $array[] =" {$column} IN ({$value}) ";
            }

            $conditions = implode(' OR ',$array);

            $this->where = $conditions;
        }else{
            throw new Exception("Conditions variable must be  set and must be an array");
        }
        if($contains_where == false){
            $sql =$this->sql." WHERE {$conditions}";
        }else{
            $sql =$this->sql." OR {$conditions}";
        }
        $this->sql = $sql;
        return $this;
    }

    /**
     * @param null $offset
     * @param null $limit
     * @return mixed
     */
    public function get($limit=null, $offset=null){

        if($limit){
            $this->limit = intval($limit);
            $this->sql = "{$this->sql} LIMIT {$this->limit}";
        }

        if($offset){
            $this->offset = intval($offset);
            $this->sql = "{$this->sql} OFFSET {$this->offset}";
        }

        $this->prepared = $this->db->prepare($this->sql);
        $this->result = $this->db->getAll($this->prepared,$this->params);
        return $this->result;
    }

    /**
     * @param $limit
     */
    public function limit($limit){
        $this->limit = intval($limit);
    }
    public function offset($offset){
        $this->offset = intval($offset);
    }

    /**
     *
     * @param array $args
     * @return $this
     */
    public function update(array $args){

//        $columns_array = array_keys($args);
//        $values_array = array_values($args);
//        #implode to strings
//        $columns = implode(",",$columns_array);
        $values = [];
        foreach ($args as $key=>$value){
            $values[] = "{$key}='{$value}'";
        }

        $values = implode(",",$values);

        $this->sql = "UPDATE {$this->table} SET {$values} ";

        return $this;
    }

    /**
     * Executes
     * @return mixed
     */
    public function go(){
        $this->prepared = $this->db->prepare($this->sql);
        $this->result = $this->db->execute($this->prepared,$this->params);
        return $this->result;
    }
    /**
     * Executes
     * @return mixed
     */
    public function run(){
        $this->prepared = $this->db->prepare($this->sql);
        $this->result = $this->db->execute($this->prepared,$this->params);
        return $this->result;
    }

    /**
     * Executes
     * @return mixed
     */
    public function getSingle(){
        $this->prepared = $this->db->prepare($this->sql);
        $result = $this->db->getAll($this->prepared,$this->params);
        $this->result = $result;
        return $result[0];
    }

    /**
     * Return the final sql statements
     * @return mixed
     */
    public function toSql(){
        return $this->sql;
    }

}
