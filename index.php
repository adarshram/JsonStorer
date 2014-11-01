<?php 


$base_path = '/home/adarsh/projects/modules/JsonStorer/';
include_once($base_path.'IO/IO.class.php');

$a = IOFactory::getInstance('File',array());
$data = array('Name'=>'Pranoti','type'=>'java Dev');
$params = array();
$params['type'] = 'read';
$params['data'] = $data;
//$params['id'] = '3';

$a->setParams($params);
$a->executeQuery();
var_dump($a->getResults());
exit;


$a = IOFactory::getInstance('UserDataLogReader',array());
$params = array();

//$params['id'] = '1709';
$a->setParams($params);
$a->execute();
var_dump($a->getResults());





exit;




$a = IOFactory::getInstance('File',array());
$data = array('Name'=>'A2222darsh','type'=>'Php Dev');
$params = array();
$params['type'] = 'write';
$params['data'] = $data;
//$params['id'] = '2';

$a->setParams($params);
$a->executeQuery();
var_dump($a->getResults());