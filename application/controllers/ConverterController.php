<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ConverterController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session'); // Load session library
        $this->load->library('upload');  // Load upload library
    }

    public function index()
    {
        // Pass empty values for first load
        $data['error'] = '';
        $data['converted_file'] = null;
        $this->load->view('converter_view', $data);
    }

    public function convert()
    {
        // Check if file is selected
        if (empty($_FILES['file']['name'])) {
            $this->session->set_flashdata('error', 'Please select a file to upload.');
            redirect('docxPdfConverter');
        }

        // Absolute upload path
        $upload_path = FCPATH . 'uploads/converter/';

        // Check if folder exists, else create
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        // Confirm folder exists
        if (!is_dir($upload_path)) {
            die("Upload path not found: " . $upload_path);
        }

        // Upload config
        // $config['upload_path']   = $upload_path;
        // $config['allowed_types'] = 'pdf|docx';
        // $config['max_size']      = 10000;
        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = '*';
        $config['max_size']      = 20000;
        $config['detect_mime']   = FALSE;
        $config['overwrite']     = TRUE;


        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file')) {
            $error = $this->upload->display_errors();
            $data['error'] = $error;
            $data['converted_file'] = null;
            $this->load->view('converter_view', $data);
            return;
        }

        $fileData = $this->upload->data();
        $inputPath = $fileData['full_path'];
        $fileName  = $fileData['raw_name'];
        $ext       = strtolower($fileData['file_ext']);
        $outputDir = $upload_path;

        // Check LibreOffice
        $soffice = shell_exec('which soffice');
        if (!$soffice) {
            $data['error'] = 'LibreOffice is not installed on the server.';
            $data['converted_file'] = null;
            $this->load->view('converter_view', $data);
            return;
        }

        // Convert logic
        if ($ext === '.docx') {
            $cmd = "soffice --headless --convert-to pdf --outdir " . escapeshellarg($outputDir) . " " . escapeshellarg($inputPath);
            shell_exec($cmd);
            $convertedFile = $upload_path . $fileName . '.pdf';
        } elseif ($ext === '.pdf') {
            $cmd = "soffice --headless --convert-to docx:\"MS Word 2007 XML\" --outdir " . escapeshellarg($outputDir) . " " . escapeshellarg($inputPath);
            shell_exec($cmd);
            $convertedFile = $upload_path . $fileName . '.docx';
        } else {
            $data['error'] = 'Invalid file type.';
            $data['converted_file'] = null;
            $this->load->view('converter_view', $data);
            return;
        }

        // Check if conversion succeeded
        if (!file_exists($convertedFile)) {
            $data['error'] = 'Conversion failed.';
            $data['converted_file'] = null;
            $this->load->view('converter_view', $data);
            return;
        }

        // Success
        $data['converted_file'] = base_url('uploads/converter/' . basename($convertedFile));
        $data['error'] = '';
        $this->load->view('converter_view', $data);
    }
}
