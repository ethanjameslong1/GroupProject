<?php
define("DB_HOST", "sql200.infinityfree.com");
define("DB_NAME", "if0_39264781_userdb");
define("DB_CHARSET", "utf8mb4");
define("DB_USER", "if0_39264781");
define("DB_PASSWORD", "RVmLRvAbI828n");

class DB
{
    //Connect to Database
    public $error ="";
    private $pdo = null;
    private $stmt = null;
    
   
    function __construct()
    {
        try
        {
            $this->pdo = new PDO(
            "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET,
             DB_USER, DB_PASSWORD, [
                 PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                 PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
            
            
        } 
        catch (Exception $e) 
        {
            echo "Error connecting <br>".$e->getMessage();
            return null;
        }
    }
    
    //Closes Connection
    function __destruct()
    {
        if($this->stmt!==null) { $this->stmt = null;}
        if($this->pdo!==null) { $this->pdo = null;}
    }
    
    //Runs a select query
    function select ($sql, $data=null)
    {
        try 
        {
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute($data);
            return $this->stmt->fetchAll();
        } catch (Exception $e) 
        {
            echo $sql ."<br>".$e->getMessage();
            return null;
        }
    }
    
    public function getPDO()
    {
        return $this->pdo;
    }
}



$_DB = new DB();
/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

