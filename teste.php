<?php

// $dataAtual = new DateTime('now');
// $dataAFormat = $dataAtual->format('Y-m-d');

$dataAFormat = date('Y-m-d');

$dataBanco = new DateTime($linha["data_tarefa"]);
$databancoFormat = $dataBanco->format('Y-m-d');

if($dataAFormat == $databancoFormat){

}

echo $dataAFormat;


