<?php
session_start();
include('conn.php');

if (isset($_GET["idTarefa"])) {
    $idTarefa = $_GET["idTarefa"];
    $sqlAlterarStatus = "UPDATE tab_tarefas 
    SET status_tarefa='1' 
    WHERE id='$idTarefa'";

    if (mysqli_query($conn, $sqlAlterarStatus)) {
        header('location:index.php');
    }
}

if (empty($_SESSION["usuario"])) {
    header('location:login.php');
}
$tarefasf =(!empty($_GET["tarefasf"]) ? 1 : 0);
$vbuscar = (isset($_GET["txtbuscarind"])? $_GET["txtbuscarind"]:"");
$valor = (isset($_GET["btnbuscar"]) ? $_GET["txtbuscar"]: $vbuscar);

if (isset($_GET["btnbuscar"])) {
    $id = $_SESSION["id"];   
    $sqlSelect = "SELECT t.id, t.nome_tarefa,t.desc_tarefa,
    t.data_tarefa, t.id_usuario,u.usuario, t.prioridade
    FROM tab_tarefas as t 
    inner join tab_usuarios as u 
    where t.id_usuario = u.id and t.id_usuario ='$id' and data_tarefa LIKE '$valor%'";
} else {
    $id = $_SESSION["id"];
    $dataAtual = date('Y-m-d');
    $sqlSelect = "SELECT t.id, t.nome_tarefa,t.desc_tarefa,
    t.data_tarefa, t.id_usuario,u.usuario,t.prioridade, t.status_tarefa
    FROM tab_tarefas as t 
    inner join tab_usuarios as u 
    where t.id_usuario = u.id and t.id_usuario ='$id' and t.status_tarefa='$tarefasf' and data_tarefa LIKE '$valor%'";
}
$result = mysqli_query($conn, $sqlSelect);


$quantReg = mysqli_num_rows($result);
$pag = (isset($_GET["pagina"]) ? $_GET["pagina"] : 1);
$quant_por_pag = 6;
$numero_de_pag = ceil($quantReg / $quant_por_pag);
$inicio = ($pag * $quant_por_pag) - $quant_por_pag;


$sqlPaginacao = "SELECT t.id, t.nome_tarefa,t.desc_tarefa,
t.data_tarefa, t.id_usuario,u.usuario,t.prioridade, t.status_tarefa
FROM tab_tarefas as t 
inner join tab_usuarios as u 
where t.id_usuario = u.id and 
t.id_usuario ='$id' 
and data_tarefa LIKE '$valor%'
and t.status_tarefa='$tarefasf' limit $inicio,$quant_por_pag";
$result = mysqli_query($conn, $sqlPaginacao);

if (isset($_GET["cadastrar"])) {
    if (!empty($_GET["tarefa"]) && !empty($_GET["descricao"]) && !empty($_GET["datatarefa"])) {
        $tarefa = $_GET["tarefa"];
        $descricao = $_GET["descricao"];
        $dataTarefa = $_GET["datatarefa"];
        $prior = $_GET["select"];

        $sql = "INSERT INTO tab_tarefas(nome_tarefa,desc_tarefa,data_tarefa,id_usuario,prioridade) 
        VALUES ('$tarefa','$descricao','$dataTarefa','$id','$prior')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Cadastro Realizado!!!')</script>";
            header('location:index.php');
        } else {
            echo "<script>alert('Erro!!!')</script>";
        }
    } else {
        echo "<script>alert('Preencha todos os campos!!!')</script>";
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

    <!-- Modal Sair do sistema -->
    <div class="modal fade text-dark" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Sair do sistema de Tarefas</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Deseja Sair mesmo?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                    <a href="sair.php"> <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Sim</button></a>

                </div>
            </div>
        </div>
    </div>

    <!-- Msg - Tarefa Atualizada -->
    <?php
    if (isset($_GET["msg"]) && $_GET["msg"] == 1) {
    ?>
        <div id="liveAlertPlaceholder" style="margin-top: 150px;"></div>
        <script>
            const alertPlaceholder = document.getElementById('liveAlertPlaceholder')

            const alert = (message, type) => {
                const wrapper = document.createElement('div')
                wrapper.innerHTML = [
                    `<div class="alert alert-${type} alert-dismissible" role="alert">`,
                    `   <div>${message}</div>`,
                    '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
                    '</div>'
                ].join('')

                alertPlaceholder.append(wrapper)
            }
            alert('Tarefa Atualizada !', 'success')
        </script>

    <?php
    }
    ?>

    <!-- Msg - Tarefa Excluida -->
    <?php
    if (isset($_GET["msg"]) && $_GET["msg"] == 2) {
    ?>
        <div class="alert alert-primary d-flex align-items-center" style="margin-top: 100px;" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-airplane" viewBox="0 0 16 16">
                <path d="M6.428 1.151C6.708.591 7.213 0 8 0s1.292.592 1.572 1.151C9.861 1.73 10 2.431 10 3v3.691l5.17 2.585a1.5 1.5 0 0 1 .83 1.342V12a.5.5 0 0 1-.582.493l-5.507-.918-.375 2.253 1.318 1.318A.5.5 0 0 1 10.5 16h-5a.5.5 0 0 1-.354-.854l1.319-1.318-.376-2.253-5.507.918A.5.5 0 0 1 0 12v-1.382a1.5 1.5 0 0 1 .83-1.342L6 6.691V3c0-.568.14-1.271.428-1.849Zm.894.448C7.111 2.02 7 2.569 7 3v4a.5.5 0 0 1-.276.447l-5.448 2.724a.5.5 0 0 0-.276.447v.792l5.418-.903a.5.5 0 0 1 .575.41l.5 3a.5.5 0 0 1-.14.437L6.708 15h2.586l-.647-.646a.5.5 0 0 1-.14-.436l.5-3a.5.5 0 0 1 .576-.411L15 11.41v-.792a.5.5 0 0 0-.276-.447L9.276 7.447A.5.5 0 0 1 9 7V3c0-.432-.11-.979-.322-1.401C8.458 1.159 8.213 1 8 1c-.213 0-.458.158-.678.599Z" />
            </svg>
            <div>
                Tarefa excluida !!!
            </div>
        </div>
    <?php
    }
    ?>

    <!-- Cadastro de Tarefas - form -->
    <div class="container col-6" style="margin-top: 100px;">
        <form class="form-group text-white">
            <legend class="text-center text-danger">Cadastro de tarefas</legend>
            <div class="mb-3">
                <label class="form-label">Nome Tarefa</label>
                <input type="text" class="form-control" name="tarefa">
            </div>
            <div class="mb-3">
                <label class="form-label">Descrição da tarefa</label> <textarea class="form-control" name="descricao" rows="3"></textarea>
            </div>

            <div class="row">
                <div class="mb-3 col-6">
                    <label class="form-label">Data / Prazo</label>
                    <input type="datetime-local" value="<?= date("Y-m-d\TH:i:s") ?>" class="form-control" name="datatarefa">
                </div>
                <div class="mb-3 col-6">
                    <label class="form-label">Prioridade</label>
                    <select name="select" class="form-select">
                        <option value="1">baixa</option>
                        <option value="2">média</option>
                        <option value="3">alta</option>
                    </select>
                </div>

            </div>

            <input type="submit" value="Cadastrar Tarefa" name="cadastrar" class="btn btn-danger text-white">
        </form>
    </div>

    <!-- Card - Tarefas - Select -->
    <div class="container col-12 mt-4">
        <div class="row">
            <?php
            $dataAtual = new DateTime('now');
            $dataAtualFormat = $dataAtual->format('Y-m-d');
            
            while ($linha = mysqli_fetch_assoc($result)) {

                $dataBanco = new DateTime($linha["data_tarefa"]);
                $dataBancolFormat = $dataBanco->format('Y-m-d');

            ?>
                <div class="col-sm-2 mb-3 mb-sm-0">
                    <div class="card mt-3 <?php if ($dataAtualFormat == $dataBancolFormat) {
                                                echo "bg-danger";
                                            } else {
                                                echo "bg-dark";
                                            } ?> text-white">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $linha["nome_tarefa"] ?></h5>
                            <p class="card-text"><?php echo $linha["desc_tarefa"] ?></p>
                            <p class="card-text"><?php echo $linha["data_tarefa"] ?></p>
                            <p class="card-text">
                                <?php
                                if ($linha["prioridade"] == 1) {
                                    echo "Baixa";
                                } else if ($linha["prioridade"] == 2) {
                                    echo "Média";
                                } else {
                                    echo "Alta";
                                }

                                ?>
                            </p>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" data-bs-toggle="modal" data-bs-target="#<?= $linha["id"] ?>" role="switch" id="flexSwitchCheckChecked">
                                <label class="form-check-label" for="flexSwitchCheckChecked">Finalizar?</label>
                            </div>

                            <a href="editar.php?id=<?php echo $linha["id"] ?>" class="btn btn-danger">Editar Tarefa</a>


                            <div class="modal fade text-dark" id="<?= $linha["id"] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Finalizar Tarefa</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Finalizou esta tarefa? <?= $linha["id"] ?>
                                        </div>
                                        <div class="modal-footer">
                                            <a href="index.php"> <button type="button" class="btn btn-secondary" id="btnNao" data-bs-dismiss="modal">Não</button></a>
                                            <a href="index.php?idTarefa=<?= $linha["id"] ?>">
                                                <button type="button" class="btn btn-primary">Sim</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="container mt-5">
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-end">
                <li class="page-item">
                    <?php
                    $pagAnt = $pag - 1;
                    $pagPos = $pag + 1;

                    if ($pagAnt != 0) {
                    ?>
                        <a class="page-link bg-dark text-white" href="index.php?pagina=<?= $pagAnt ?>&tarefasf=<?= $tarefasf ?>">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    <?php } else { ?>
                        <a class="page-link bg-dark text-white">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    <?php } ?>
                </li>

                <?php for ($i = 1; $i <= $numero_de_pag; $i++) { ?>

                    <li class="page-item <?php if ($i == $pag) echo "active" ?>">
                        <a class="page-link bg-dark text-white" href="index.php?pagina=<?= $i ?>&tarefasf=<?= $tarefasf ?>&txtbuscarind=<?= $valor ?>">
                            <?= $i ?>
                        </a>
                    </li>

                <?php } ?>

                <li class="page-item">
                    <?php if ($pagPos <= $numero_de_pag) {  ?>
                        <a class="page-link bg-dark text-white" href="index.php?pagina=<?= $pagPos ?>&tarefasf=<?= $tarefasf ?>">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    <?php } else {  ?>
                        <a class="page-link bg-dark text-white">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    <?php  } ?>
                </li>
        </nav>
    </div>





    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>