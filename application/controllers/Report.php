<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

    }

	public function index()
    {
        echo 'index';
    }

    public function yearly_earning_report_pdf()
    {
        $data = [];

        //load the view and saved it into $html variable
        $data['content'] = 'reports/test_report_pdf';
        $html = $this->load->view('templates/pdf_template', $data, true);

        //this the the PDF filename that user will get to download
        $pdfFilePath = "output_pdf_name.pdf";

        //load mPDF library
        $this->load->library('m_pdf');

        //generate the PDF from the given html
        $this->m_pdf->pdf->WriteHTML($html);

        //download it.
        $this->m_pdf->pdf->Output($pdfFilePath, "D");
    }

}
