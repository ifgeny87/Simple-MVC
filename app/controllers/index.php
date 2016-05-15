<?php

Class Controller_Index Extends BaseController
{
	function index() {
		$template = Registry::get('template');
		$template->set ('first_name', 'Dennis');
		$template->show('index');
	}
	
	function view() {
		echo 'Called view() action';
	}
}
