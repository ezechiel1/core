<?php
/*
 * DB Class
 * This class is used for database related (connect, insert, update, and delete) operations
 * @author Ezechiel Kalengya Ezpk
  */
//require_once '../phpqrcode/qrlib.php';
class DB{
    private $dbHost     = "localhost";
    private $dbUsername = "root";
    private $dbPassword = "";
    private $dbName     = "db_name";

    public function __construct(){
        if(!isset($this->db)){
            // Connect to the database
            $conn = new mysqli($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName);
            if($conn->connect_error){
                die("Failed to connect with MySQL: " . $conn->connect_error);
            }else{
                $this->db = $conn;
            }
        }
    }

     /*
     * variable to handle the main page
     */
   public  $url="";

    /*
     * Returns rows from the database based on the conditions
     * @param string name of the table
     * @param array select, where, order_by, limit and return_type conditions
     */
    public function getRows($table,$conditions = array()){
        $sql = 'SELECT ';
        $sql .= array_key_exists("select",$conditions)?$conditions['select']:'*';
        $sql .= ' FROM '.$table;
        if(array_key_exists("where",$conditions)){
            $sql .= ' WHERE ';
            $i = 0;
            foreach($conditions['where'] as $key => $value){
                $pre = ($i > 0)?' AND ':'';
                $sql .= $pre.$key."= '".$value."'";
                $i++;
            }
        }

        if(array_key_exists("order_by",$conditions)){
            $sql .= ' ORDER BY '.$conditions['order_by'];
        }

        if(array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)){
            $sql .= ' LIMIT '.$conditions['start'].','.$conditions['limit'];
        }elseif(!array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)){
            $sql .= ' LIMIT '.$conditions['limit'];
        }

        $result = $this->db->query($sql);

        if(array_key_exists("return_type",$conditions) && $conditions['return_type'] != 'all'){
            switch($conditions['return_type']){
                case 'count':
                    $data = $result->num_rows;
                    break;
                case 'single':
                    $data = $result->fetch_assoc();
                    break;
                default:
                    $data = '';
            }
        }else{
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $data[] = $row;
                }
            }
        }
        return !empty($data)?$data:false;
    }

    /*
     * Insert data into the database
     * @param string name of the table
     * @param array the data for inserting into the table
     */
    public function insert($table,$data){
        if(!empty($data) && is_array($data)){
            $columns = '';
            $values  = '';
            $i = 0;

            foreach($data as $key=>$val){
                $pre = ($i > 0)?', ':'';
                $columns .= $pre.$key;
                $values  .= $pre."'".$val."'";
                $i++;
            }
            $query = "INSERT INTO ".$table." (".$columns.") VALUES (".$values.")";
            $insert = $this->db->query($query);
            return $insert?$this->db->insert_id:false;
        }else{
            return false;
        }
    }

    /*
     * Update data into the database
     * @param string name of the table
     * @param array the data for updating into the table
     * @param array where condition on updating data
     */
    public function update($table,$data,$conditions){
        if(!empty($data) && is_array($data)){
            $colvalSet = '';
            $whereSql = '';
            $i = 0;

            foreach($data as $key=>$val){
                $pre = ($i > 0)?', ':'';
                $colvalSet .= $pre.$key."='".$val."'";
                $i++;
            }
            if(!empty($conditions)&& is_array($conditions)){
                $whereSql .= ' WHERE ';
                $i = 0;
                foreach($conditions as $key => $value){
                    $pre = ($i > 0)?' AND ':'';
                    $whereSql .= $pre.$key." = '".$value."'";
                    $i++;
                }
            }
            $query = "UPDATE ".$table." SET ".$colvalSet.$whereSql;
            $update = $this->db->query($query);
            return $update?$this->db->affected_rows:false;
        }else{
            return false;
        }
    }

    /*
     * Delete data from the database
     * @param string name of the table
     * @param array where condition on deleting data
     */
    public function delete($table,$conditions){
        $whereSql = '';
        if(!empty($conditions)&& is_array($conditions)){
            $whereSql .= ' WHERE ';
            $i = 0;
            foreach($conditions as $key => $value){
                $pre = ($i > 0)?' AND ':'';
                $whereSql .= $pre.$key." = '".$value."'";
                $i++;
            }
        }
        $query = "DELETE FROM ".$table.$whereSql;
        $delete = $this->db->query($query);
        return $delete?true:false;
    }


 public function login ($table,$conditions){
       $whereSql = '';
       $data;
            if(!empty($conditions) && is_array($conditions)){
                $whereSql .= ' WHERE ';
                $i = 0;
                foreach($conditions as $key => $value){
                    $pre = ($i > 0)?' AND ':'';
                    $whereSql .= $pre.$key." = '".$value."'";
                    $i++;
                }

            $query = "SELECT  *  FROM ".$table.$whereSql;
            $result = $this->db->query($query);

                 if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $data[] = $row;
                }
            }

            return !empty($data)?$data:false;
        }
        else
        {
            return false;
        }
    }

    public function logout($url=''){
      session_unset();
      session_destroy();
      header('location: '.$url.'');
    }

    public function parseJson($jsonFile)
    {
        $jsonData=file_get_contents($jsonFile);
        $json=json_decode($jsonData,true);
        //return the decoded to php array from json
        return $json;
    }

    public function showDate($code)
    {
            $gett = getdate();
            $annee=$gett['year'];
            $mois=$gett['mon'];
            $jour=$gett['mday'];
            $heure=$gett['hours'];
            $minutes=$gett['minutes'];
            $second=$gett['seconds'];
        if($code=='datetime') $currentDate=$annee.'-'.$mois.'-'.$jour.' '.$heure.':'.$minutes.':'.$second;
        else if($code=='date') $currentDate=$annee.'-'.$mois.'-'.$jour;

      return $currentDate;
    }

    //....Code for the logs
    public function registerLogIn($userID,$logs_type, $ip, $device, $os)
    {
        $query="INSERT INTO `logs` (`start_time`, `end_time`, `user_id`, `logs_type`, `user_ip`, `user_device`, `user_os`) VALUES (NOW(), NULL, '$userID', '$logs_type', '$ip', '$device', '$os');";
        $validate=$this->db->query($query);
        return $validate?true:false;
    }

    public function registerLogOut($userID,$logs_type)
    {
        //Track
        $condition=array('order_by' => 'id  desc','limit'=>1, 'where'=>array('user_id'=>$userID, 'logs_type'=>$logs_type));
        $ck=$this->getRows('logs',$condition);
        if(!empty($ck)):
            foreach($ck as $gt):
                $logsID=$gt['id'];
                $query="UPDATE logs SET end_time=NOW() WHERE id='$logsID'";
                $validate=$this->db->query($query);
            endforeach;
        endif;
        return $validate?true:false;
    }

  public function getMembre(){
    $sql ="SELECT  * from admin ";
    $result = $this->db->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $data[] = $row;
                }
        }
        return !empty($data)?$data:false;
    }


    public function getTotal($tableName, $condition=''){
          if($condition==''):  $sql ="SELECT  COUNT(*) AS tot from  ".$tableName."";
          else:  $sql ="SELECT  COUNT(*) AS tot from  ".$tableName." where ".$condition."";
          endif;

            $result = $this->db->query($sql);
                if($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                        $data = $row['tot'];
            }
        return !empty($data)?$data:0;
    }

    public function getLastID($tbl,$ID){
        $sql ="SELECT  ".$ID." AS ID from ".$tbl." order by ".$ID." desc limit 1";
        $result = $this->db->query($sql);
            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                    $data = $row['ID'];
        }
        return !empty($data)?$data:false;
    }

}
