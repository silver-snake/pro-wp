<?php

function varDump($var, $label = '', $die = 1) {
	echo $label . ': <pre>';
	print_r($var);
	echo '</pre>';
	if ($die) {
		die();
	}
}