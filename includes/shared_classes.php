<?php
class Settings
{
	public $requested_setting;
	public $settings_type;
	public $settings_blob;
	public function __construct()
	{
		$this->requested_setting = FALSE;
		$this->settings_type = "";
		//$this->settings_blob = FALSE;
	}
}
