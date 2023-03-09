<?php

include_once '../../config.php';

$control = new ListarDatosControl();
$retorno = $control->listarMenu(data_submitted());

echo json_encode($retorno);
