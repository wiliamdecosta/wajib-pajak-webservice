<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		echo "<h2>Web server running well :) </h2>";
		exit;
	}
}
