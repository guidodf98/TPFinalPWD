<?php

include_once '../../config.php';

$control = new ListarDatosControl();
$retorno = $control->listarMenuPadre(data_submitted());

echo json_encode($retorno);