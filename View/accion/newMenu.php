<?php
include_once("../../config.php");

$control = new NewDatosControl();
$retorno = $control -> newMenu(data_submitted());

echo json_encode($retorno);

