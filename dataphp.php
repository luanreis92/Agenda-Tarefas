<?php

function entradaVeiculo()
{
    $dataEntrada = date('Y-m-d H:i');
    //inserir carro na tabela
}

function saidaVeiculo()
{
   
    $dataEntradabanco = //buscar do banco
    $dataSaida = date('Y-m-d H:i');
    $dataEntrada = date_create($dataEntradabanco);
    $dataSaida = date_create($dataSaida);

    $tempodoCliente = date_diff($dataSaida, $dataEntrada);

    $horas = (int) $tempodoCliente->format('%h');
    $minutos = (int) $tempodoCliente->format('%i');
    $dias = (int) $tempodoCliente->format('%d');

    $vl_por_hora = 5.00;
    $vl_a_pagar = 0;


    if ($dias > 0) {
        $vl_a_pagar = ($dias * 24) * $vl_por_hora;
    }

    if ($horas > 0) {
        $vl_a_pagar = $vl_a_pagar + ($horas * $vl_por_hora);
    }

    if ($minutos > 0) {
        $vl_a_pagar = $vl_a_pagar + $vl_por_hora;
    }

    echo "O valor a pagar é = $vl_a_pagar";
}









$datadoUserEntrada = $_GET["inputEntrada"];
$datadoUserSaida = $_GET["inputSaida"];
$dataEntrada = date_create($datadoUserEntrada);
$dataSaida = date_create($datadoUserSaida);

$tempodoCliente = date_diff($dataSaida, $dataEntrada);

$horas = (int) $tempodoCliente->format('%h');
$minutos = (int) $tempodoCliente->format('%i');
$dias = (int) $tempodoCliente->format('%d');

$vl_por_hora = 5.00;
$vl_a_pagar = 0;


if ($dias > 0) {
    $vl_a_pagar = ($dias * 24) * $vl_por_hora;
}

if ($horas > 0) {
    $vl_a_pagar = $vl_a_pagar + ($horas * $vl_por_hora);
}

if ($minutos > 0) {
    $vl_a_pagar = $vl_a_pagar + $vl_por_hora;
}

echo "O valor a pagar é = $vl_a_pagar";
