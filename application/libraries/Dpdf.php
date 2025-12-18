<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

require_once APPPATH . '../vendor/autoload.php';

class Dpdf {

    protected $dompdf;

    public function __construct()
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $this->dompdf = new Dompdf($options);
    }

    public function loadHtml($html)
    {
        return $this->dompdf->loadHtml($html);
    }

    public function setPaper($paper, $orientation = 'portrait')
    {
        return $this->dompdf->setPaper($paper, $orientation);
    }

    public function render()
    {
        return $this->dompdf->render();
    }

    public function stream($filename, $options = [])
    {
        return $this->dompdf->stream($filename, $options);
    }
}
