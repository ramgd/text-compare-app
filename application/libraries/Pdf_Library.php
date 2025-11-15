<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Correct TCPDF path
require_once(dirname(__FILE__) . '/tcpdf/tcpdf.php');

class Pdf_Library extends TCPDF {

    public function __construct($orientation = 'P')
    {
        parent::__construct($orientation, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // PDF default settings
        $this->SetCreator(PDF_CREATOR);
        $this->SetMargins(10, 10, 10);
        $this->SetAutoPageBreak(TRUE, 10);
    }
}
