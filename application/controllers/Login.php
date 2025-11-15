<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function userlogin()
	{
		// echo "Hii";die;
		$this->load->view('login.php');
	}
}
