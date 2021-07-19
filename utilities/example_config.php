<?php

//local (your working copy)
class Config{
    var $hostname;
    var $db_host;
    var $db_username;
    var $db_password;
    var $db_name;
    public function __construct() 
    {
        $this->hostname = "";
        $this->db_host = "localhost";
        $this->db_username = "root";
        $this->db_password = "";
        $this->db_name = "business_cards";
    }
    public function getBaseUrl(){
        return $this->hostname;
    }
}

//dev (development server)
class Config{
    var $hostname;
    var $db_host;
    var $db_username;
    var $db_password;
    var $db_name;
    public function __construct() 
    {
        $this->hostname = "http://d.bcfolder.co.za/";
        $this->db_host = "";
        $this->db_username = "root";
        $this->db_password = "";
        $this->db_name = "";
    }
    public function getBaseUrl(){
        return $this->hostname;
    }
}

//staging  (staging server)
class Config{
    var $hostname;
    var $db_host;
    var $db_username;
    var $db_password;
    var $db_name;
    public function __construct() 
    {
        $this->hostname = "http://s.bcfolder.co.za/";
        $this->db_host = "";
        $this->db_username = "";
        $this->db_password = "";
        $this->db_name = "";
    }
    public function getBaseUrl(){
        return $this->hostname;
    }
}

//production (production server)
class Config{
    var $hostname;
    var $db_host;
    var $db_username;
    var $db_password;
    var $db_name;
    public function __construct() 
    {
        $this->hostname = "http://bcfolder.co.za/";
        $this->db_host = "";
        $this->db_username = "";
        $this->db_password = "";
        $this->db_name = "";
    }
    public function getBaseUrl(){
        return $this->hostname;
    }
}

?>