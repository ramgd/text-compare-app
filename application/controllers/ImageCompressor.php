<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ImageCompressor extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
    }

    public function index() {
        $this->load->view('image_compressor_view');
    }

    public function compress()
    {
        // === BUILD ABSOLUTE PATHS ===
        // Make sure FCPATH ends with slash and build canonical upload path
        $project_root = rtrim(FCPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $uploads_root = $project_root . 'uploads' . DIRECTORY_SEPARATOR;
        $upload_dir   = $uploads_root . 'images' . DIRECTORY_SEPARATOR;

        // === CREATE FOLDERS IF MISSING ===
        if (!is_dir($uploads_root)) {
            if (!mkdir($uploads_root, 0777, true)) {
                $data['error'] = "Failed to create uploads folder: {$uploads_root}";
                $this->load->view('image_compressor_view', $data);
                return;
            }
            @chmod($uploads_root, 0777);
        }

        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                $data['error'] = "Failed to create upload images folder: {$upload_dir}";
                $this->load->view('image_compressor_view', $data);
                return;
            }
            @chmod($upload_dir, 0777);
        }

        // === PERMISSION CHECK ===
        if (!is_writable($upload_dir)) {
            // try to fix permissions
            @chmod($upload_dir, 0777);
            if (!is_writable($upload_dir)) {
                $diag  = "Upload folder is not writable: {$upload_dir}\n";
                $diag .= "Owner: " . trim(shell_exec('ls -ld ' . escapeshellarg($upload_dir))) . "\n";
                $data['error'] = "Upload folder is not writable. Try: sudo chmod -R 777 " . escapeshellarg($upload_dir) . "<br><pre>{$diag}</pre>";
                $this->load->view('image_compressor_view', $data);
                return;
            }
        }

        // === QUICK PRECHECKS ===
        if (!isset($_FILES['image_file'])) {
            $data['error'] = "No file was uploaded. Make sure the input name is 'image_file'.";
            $this->load->view('image_compressor_view', $data);
            return;
        }

        if (empty($_FILES['image_file']['tmp_name'])) {
            $data['error'] = "Uploaded file tmp_name is empty. Check PHP upload_max_filesize and post_max_size.";
            $this->load->view('image_compressor_view', $data);
            return;
        }

        // === UPLOAD CONFIG (use absolute path) ===
        $config = [
            'upload_path'   => $upload_dir,
            'allowed_types' => '*',        // temporarily allow all; we'll validate manually
            'max_size'      => 51200,      // 50 MB
            'encrypt_name'  => TRUE,
            'remove_spaces' => TRUE,
            'detect_mime'   => FALSE       // disable CI's mime detection — we'll do manual check
        ];

        // Initialize upload library properly
        $this->load->library('upload');
        $this->upload->initialize($config, TRUE);

        // Attempt upload
        if (!$this->upload->do_upload('image_file')) {
            // Build helpful diagnostics when path error occurs
            $err = strip_tags($this->upload->display_errors());
            $diag  = "<b>Upload diagnostics</b><br>";
            $diag .= "upload_path = " . htmlspecialchars($config['upload_path']) . "<br>";
            $diag .= "is_dir = " . (is_dir($config['upload_path']) ? 'true' : 'false') . "<br>";
            $diag .= "realpath = " . (@realpath($config['upload_path']) ?: 'n/a') . "<br>";
            $diag .= "is_writable = " . (is_writable($config['upload_path']) ? 'true' : 'false') . "<br>";
            $diag .= "PHP user = " . trim(shell_exec('whoami')) . "<br>";
            $diag .= "<pre>ls -ld: " . shell_exec('ls -ld ' . escapeshellarg($config['upload_path']) . ' 2>&1') . "</pre>";

            $data['error'] = $err . '<br>' . $diag;
            $this->load->view('image_compressor_view', $data);
            return;
        }

        // === UPLOAD SUCCESS ===
        $upload_data = $this->upload->data();
        $source_path = $upload_data['full_path'];

        // === MANUAL MIME VALIDATION (robust) ===
        $mime = @mime_content_type($source_path);
        $valid_mimes = ['image/jpeg', 'image/png'];

        if ($mime === false) {
            // fallback using getimagesize
            $info = @getimagesize($source_path);
            $mime = ($info && isset($info['mime'])) ? $info['mime'] : '';
        }

        if (!in_array($mime, $valid_mimes, true)) {
            // remove uploaded file if invalid
            @unlink($source_path);
            $data['error'] = "Only JPG and PNG images are allowed. Detected MIME: " . htmlspecialchars($mime);
            $this->load->view('image_compressor_view', $data);
            return;
        }

        // === PREPARE COMPRESSED OUTPUT ===
        // $compressed_name = 'compressed_' . time() . '_' . $upload_data['file_name'] . '.jpg';
        $original_name = pathinfo($upload_data['client_name'], PATHINFO_FILENAME);
        $original_ext  = pathinfo($upload_data['client_name'], PATHINFO_EXTENSION);

        $compressed_name = $original_name . '' . $original_ext;

        $compressed_path = $upload_dir . $compressed_name;

        // === COMPRESS (GD) ===
        try {
            if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
                $img = imagecreatefromjpeg($source_path);
                if (!$img) throw new Exception('Failed to read JPEG image.');
                imagejpeg($img, $compressed_path, 60);
                imagedestroy($img);

            } elseif ($mime === 'image/png') {
                $img = imagecreatefrompng($source_path);
                if (!$img) throw new Exception('Failed to read PNG image.');
                $w = imagesx($img);
                $h = imagesy($img);

                // convert and flatten alpha
                $bg = imagecreatetruecolor($w, $h);
                $white = imagecolorallocate($bg, 255, 255, 255);
                imagefill($bg, 0, 0, $white);
                imagecopy($bg, $img, 0, 0, 0, 0, $w, $h);

                imagejpeg($bg, $compressed_path, 60);
                imagedestroy($img);
                imagedestroy($bg);
            }
        } catch (Exception $e) {
            @unlink($source_path);
            $data['error'] = "Compression error: " . $e->getMessage();
            $this->load->view('image_compressor_view', $data);
            return;
        }

        if (!file_exists($compressed_path)) {
            @unlink($source_path);
            $data['error'] = "Compression failed (no output file created).";
            $this->load->view('image_compressor_view', $data);
            return;
        }

        // Optionally remove original upload to save space:
        // @unlink($source_path);

        // === SUCCESS ===
        $data['download_link'] = base_url('uploads/images/' . $compressed_name);
        $this->load->view('image_compressor_view', $data);
    }


}
