<?php
include_once(dirname(__FILE__).'/../../../config/config.inc.php');
include_once(dirname(__FILE__).'/../../../init.php');
require_once(dirname(__FILE__).'/../callmefast.php');

if(!empty(Tools::getValue('t'))) {

	$token = Tools::getValue('t');
	$name = Tools::getValue('n');
	$phone = Tools::getValue('p');

	$data = array(
		'token' => $token,
		'name' => $name,
		'phone' => $phone,
	);

    $callmefast = new Callmefast();
	die($callmefast->postEmail($data));
}