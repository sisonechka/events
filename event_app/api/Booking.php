<?php 

class Booking {
	
	public $title;
	public $start;
	public $allDay = false;
	public $backgroundColor;
	public $borderColor;
	public $url;
	public $className;
	public $block = false;
		
	public function __constructor($t, $s) {
		$this->title = $t;
		$this->start = $s;
	}
	
	
}
?>