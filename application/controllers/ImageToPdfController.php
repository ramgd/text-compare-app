<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ImageToPdfController extends CI_Controller {

    public function index()
    {
        $this->load->view('image_to_pdf_view');
    }

    // ======================
    // APPLY FILTER FUNCTION
    // ======================
    public function apply_filter()
    {
        $image_path = $this->input->post('image_path');
        $filter = $this->input->post('filter');

        $img = imagecreatefromjpeg($image_path);
        if(!$img){
            echo json_encode(['status' => 'error', 'msg' => 'Invalid image']);
            return;
        }

        switch ($filter) {
            case 'bright':
                imagefilter($img, IMG_FILTER_BRIGHTNESS, 35);
                break;

            case 'warm':
                imagefilter($img, IMG_FILTER_COLORIZE, 50, 20, -10);
                break;

            case 'white_doc':
                imagefilter($img, IMG_FILTER_BRIGHTNESS, 20);
                imagefilter($img, IMG_FILTER_CONTRAST, -15);
                break;

            default:
                break;
        }

        $output = $image_path;
        imagejpeg($img, $output, 90);
        imagedestroy($img);

        echo json_encode(['status' => 'success', 'path' => base_url($output)]);
    }

    // ======================
    // CREATE PDF FUNCTION
    // ======================
    public function create_pdf()
    {
        $images = $this->input->post('sorted_images');
        $orientation = $this->input->post('orientation');
        $new_name = $this->input->post('pdf_name');

        if (!$images || !$new_name) {
            echo "Missing input data!";
            return;
        }

        // $this->load->library('Pdf_Library'); // custom TCPDF wrapper
        $this->load->library('Pdf_Library');
        $pdf = new Pdf_Library();

        $pdf = new Pdf_Library($orientation); // horizontal/vertical

        foreach ($images as $img_path) {
            $pdf->AddPage();
            $pdf->Image(FCPATH . $img_path, 10, 10, 190, 0, '', '', true);
        }

        $file_name = $new_name . ".pdf";
        $pdf_path = FCPATH . "uploads/pdf/" . $file_name;

        if (!is_dir(FCPATH . "uploads/pdf/")) {
            mkdir(FCPATH . "uploads/pdf/", 0777, true);
        }

        $pdf->Output($pdf_path, 'F');

        echo json_encode([
            'status' => 'success',
            'download' => base_url("uploads/pdf/" . $file_name)
        ]);
    }
}
