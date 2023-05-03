<?php
session_start();
include('conn.php');

if (empty($_SESSION["usuario"])) {
    header('location:login.php');
}

if (!empty($_GET["id"])) {
    $id = $_GET["id"];
    $sql = "SELECT * FROM tab_tarefas WHERE id='$id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($linha = mysqli_fetch_assoc($result)) {
            $nome = $linha["nome_tarefa"];
            $descricao = $linha["desc_tarefa"];
            $data = $linha["data_tarefa"];
            $id = $linha["id"];
            $prior = $linha["prioridade"];
        }
    } else {
        header('location:index.php');
    }
}

if (isset($_GET["atualizar"])) {
    if (!empty($_GET["tarefa"]) && !empty($_GET["descricao"]) && !empty($_GET["datatarefa"])) {
        $tarefa = $_GET["tarefa"];
        $descricao = $_GET["descricao"];
        $dataTarefa = $_GET["datatarefa"];
        $id = $_GET["id"];
        $prior = $_GET["prioridade"];

        $sql = "UPDATE tab_tarefas SET nome_tarefa ='$tarefa', 
        desc_tarefa='$descricao', data_tarefa='$dataTarefa',prioridade='$prior'  WHERE id='$id'";

        if (mysqli_query($conn, $sql)) {
            header('location:index.php?msg=1');
        } else {
            echo "<script>alert('Erro!!!')</script>";
        }
    } else {
        echo "<script>alert('Preencha todos os campos!!!')</script>";
    }
}

if (isset($_GET["excluir"])) {
    $id = $_GET["id"];
    $sql = "DELETE FROM tab_tarefas WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        header('location:index.php?msg=2');
    } else {
        echo "<script>alert('Erro')</script>";
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body style="background-color: #000000;">


 <!-- NavBar - Menu -->
 <?php include('menu.php') ?>
  

    <div class="container col-6" style="margin-top: 50px;">
        <form class="form-group text-white">
            <legend class="text-center text-danger">Cadastro de tarefas</legend>
            <div class="mb-3">
                <label class="form-label">Id da Tarefa</label>
                <input type="text" class="form-control" name="id" value="<?= $id ?>" readonly>
                <label class="form-label">Nome Tarefa</label>
                <input type="text" class="form-control" name="tarefa" value="<?= $nome ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Descrição da tarefa</label>
                <textarea class="form-control" name="descricao" rows="3"><?= $descricao ?></textarea>
            </div>
            <div class="row">
                <div class="mb-3 col-6">
                    <label class="form-label">Data / Prazo</label>
                    <input type="datetime-local" value="<?= date("Y-m-d\TH:i:s") ?>" class="form-control" name="datatarefa">
                </div>
                <div class="mb-3 col-6">
                    <label class="form-label">Prioridade</label>
                    <select name="prioridade" class="form-select">
                        <option value="1" <?php if ($prior == 1) echo "selected"; ?>>baixa</option>
                        <option value="2" <?php if ($prior == 2) echo "selected"; ?>>média</option>
                        <option value="3" <?php if ($prior == 3) echo "selected"; ?>>alta</option>
                    </select>
                </div>
            </div>

            <!-- Button trigger modal -->
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Excluir tarefa
            </button>
            <!-- Modal -->
            <div class="modal fade text-dark" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Excluir Tarefa</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Deseja excluir mesmo?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                            <input type="submit" value="Sim" name="excluir" class="btn btn-danger text-white">
                        </div>
                    </div>
                </div>
            </div>


            <!-- Button trigger modal -->
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModa2">
                Atualizar tarefa
            </button>
            <!-- Modal -->
            <div class="modal fade text-dark" id="exampleModa2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel"> Tarefa</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Deseja mesmo?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                            <input type="submit" value="Sim" name="atualizar" class="btn btn-danger text-white">
                        </div>
                    </div>
                </div>
            </div>




        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>