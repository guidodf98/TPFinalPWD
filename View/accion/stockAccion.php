<?php
include_once "../../config.php";


$control = new StockControl();
$control->accion(data_submitted());


header("Status: 301 Moved Permanently");
header("Location: ../stock.php");
