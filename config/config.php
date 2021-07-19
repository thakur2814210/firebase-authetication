<?php
require __DIR__.'/../vendor/autoload.php';

class Config {

	var $hostname;
	var $db_host;
	var $db_username;
	var $db_password;
	var $db_name;
	var $firebase_dynamic_url;
	//var $firebase_dynamic_ios;

	public function __construct() {
		$dotenv = new Dotenv\Dotenv(__DIR__ . '/../.');
		$dotenv->load();

		$this->hostname = "https://www.cardition.com/";

		$this->db_username = $_ENV['DB_USERNAME'];
		$this->db_password = $_ENV['DB_PASSWORD'];
		$this->db_name = $_ENV['DB_NAME'];
		$this->db_host = $_ENV['DB_HOST'];
		$this->firebase_dynamic_url = "https://cardition.page.link/?link=%s&apn=com.bcfolder.cardition&ibi=com.cardition.cardit&isi=1553537491&efr=1";
		//$this->firebase_dynamic_ios = "https://cardition.page.link/?link=%s&ibi=com.cardition.cardit&isi=1553537491";
	}

	public function getBaseUrl() {
		return $this->hostname;
	}

}
