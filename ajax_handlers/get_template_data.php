<?php
require_once 'ControllerOperation.php';
header('Content-Type: application/json');
$controller = new ControllerOperation();
$controller->getTemplateDataById();
?>