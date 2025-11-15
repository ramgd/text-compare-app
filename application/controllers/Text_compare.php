<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Text_compare extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Text_compare_model');
        $this->load->helper('url');
    }

    public function index() {
        $this->load->view('text_compare_view');
    }

    public function compare() {
        $text1 = $this->input->post('text1');
        $text2 = $this->input->post('text2');

        $differences = $this->Text_compare_model->compare_texts($text1, $text2);
        echo json_encode($differences);
    }

    public function json_formatter() {
        $this->load->view('json_formatter_view');
    }
    public function passwordGenerator() {
        $this->load->view('passwordGenerator');
    }
    public function sqlQueryMinifier() {
        $this->load->view('sql_query_minifier');
    }
}