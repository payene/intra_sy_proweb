<?php 
namespace Loonins\SuiviBundle\Service;


class HTML2PDF {
	private $pdf;

	public function create($orientation = null, $format = null, $lang = null, $unicode = null, $margin = null){
		$this->pdf = new \Spipu\Html2Pdf\Html2Pdf( 
								   	$orientation?$orientation:$this->orientation,
								   	$format?$format:$this->format,
								   	$lang?$lang:$this->lang,
								   	$unicode?$unicode:$this->unicode,
								   	$margin?$margin:$this->margin
		 						  );
	}

	public function generatePdf($template, $name){
		$this->pdf->writeHTML($template);
		return $this->pdf->Output($name . '.pdf' );
	}

}