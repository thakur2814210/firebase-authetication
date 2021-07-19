<?php
class Result
{
	var $success;
	var $result;
	var $folder_name;
	var $meta;
	var $err;
	var $sqlerrors;
	var $sqlsuccesses;
	var $mailerrors;
	public function __construct()
	{
		$this->success = 1;
		$this->sqlerrors = array();
		$this->sqlsuccesses = array();
		$this->mailerrors = array();
	}
	public function addSqlError( $error )
	{
		$this->success = 0;
		array_push( $this->sqlerrors, $error );
	}
	public function addSqlSuccess( $success )
	{
		array_push( $this->sqlsuccesses, $success );
	}
	public function handleQuery( $conn, $query )
	{
//		file_put_contents(__DIR__ .'/../mg_log.log', "\r\n" . date( 'dd/mm/yyyy hh:ii:ss'), FILE_APPEND);
//		file_put_contents(__DIR__ .'/../mg_log.log', "\r\nquery to execute by handleQuery: " . $query, FILE_APPEND);
		$result = mysqli_query( $conn, $query );
		$str = '';
		if ( !$result )
		{
			$this->addSqlError( array(
					'mysqli_error' => mysqli_error( $conn ),
					'executed_query' => $query ) );
		}
		else
		{
			$this->addSqlSuccess( $query );
		}
		return $result;
	}
	public function handleSentMail( $mail_result )
	{
		if ( $mail_result[ "state" ] == "error" )
		{
			array_push( $this->mailerrors, $mail_result );
			return 0;
		}else{
			return 1;
		}
	}
}
