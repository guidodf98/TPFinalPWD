<?php
include_once("../../config.php");

$control = new NewDatosControl();
$retorno = $control->newUsuario(data_submitted());

echo json_encode($retorno);
