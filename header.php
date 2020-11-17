<?php
session_start();
if (empty($_SESSION['email'])) {
    header("Location: login.php");
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>
        SanMartino
    </title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />
    <!--     Fonts and icons     -->
    <link href="assets/css/fonts.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
    <!-- CSS Files -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />


    <link href="assets/css/paper-dashboard.css?v=2.0.0" rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="assets/demo/demo.css" rel="stylesheet" />

    <!--   Core JS Files   -->
    <script src="assets/js/core/jquery.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
    <!--  Notifications Plugin    -->
    <script src="assets/js/plugins/bootstrap-notify.js"></script>
    <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="assets/js/bootstrap-confirm-delete.js"></script>
    <link href="assets/css/bootstrap-confirm-delete.css" rel="stylesheet">


    <link href="assets/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="assets/js/jquery.dataTables.min.js"></script>

    <script src="assets/js/loadingoverlay.min.js"></script>

    <link href="assets/css/select2.min.css" rel="stylesheet">
    <script src="assets/js/select2.min.js"></script>

    <link href="assets/css/toastr.min.css" rel="stylesheet">
    <script src="assets/js/toastr.min.js"></script>

    <!-- Autocomplete -->
    <link href="assets/css/easy-autocomplete.min.css" rel="stylesheet">
    <link href="assets/css/easy-autocomplete.themes.min.css" rel="stylesheet">
    <script src="assets/js/jquery.easy-autocomplete.min.js"></script>

    <style>
    div.dataTables_wrapper div.dataTables_length select {
        background-color: #FFFFFF;
        border: 1px solid #DDDDDD;
        border-radius: 4px;
        color: #66615b;
        line-height: normal;
        font-size: 14px;
        box-shadow: none;
        height: calc(2.25rem + 2px);
    }

    div.dataTables_wrapper div.dataTables_filter input {
        background-color: #FFFFFF;
        border: 1px solid #DDDDDD;
        border-radius: 4px;
        color: #66615b;
        line-height: normal;
        font-size: 14px;
        height: calc(2.25rem + 2px);
    }



    .select2-container {
        width: 100% !important;
    }

    .select2-search--dropdown .select2-search__field {
        width: 98%;
    }

    .select2-container--default .select2-selection--single {
        padding: 6px;
        height: 37px;
        font-size: 1.2em;
        position: relative;
    }
    </style>

</head>

<body class="">
    <div class="wrapper ">
        <div class="sidebar" data-color="white" data-active-color="danger">
            <!--
        Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow"
    -->
            <div class="logo">
                <a href="./" class="simple-text logo-mini">
                    <div class="logo-image-small">
                        <img src="assets/img/logo.jpeg">
                    </div>
                </a>
                <a href="./" class="simple-text logo-normal">
                    SanMartino
                    <!-- <div class="logo-image-big">
            <img src="assets/img/logo-big.png">
          </div> -->
                </a>
            </div>
            <div class="sidebar-wrapper">
                <ul class="nav">
                    <li <?php if (basename($_SERVER['PHP_SELF']) == "index.php") echo "class='active'"; ?>>
                        <a href="./">
                            <i class="nc-icon nc-bank"></i>
                            <p>Home</p>
                        </a>
                    </li>
                    <li <?php if (
                            basename($_SERVER['PHP_SELF']) == "agendamentosextracao.php"
                            || basename($_SERVER['PHP_SELF']) == "consultatarefa.php"
                            || basename($_SERVER['PHP_SELF']) == "tarefa.php"
                            || basename($_SERVER['PHP_SELF']) == "agendamento.php"
                            || basename($_SERVER['PHP_SELF']) == "log.php"
                        ) echo "class='active'"; ?>>
                        <a data-toggle="collapse" href="#dashboardOverview1" aria-expanded="false" class="">
                            <i class="nc-icon nc-cloud-download-93"></i>
                            <p>Cadastros
                                <b class="caret"></b>
                            </p>
                        </a>
                        <div class="in collapse" id="dashboardOverview1" aria-expanded="true"
                            style="padding-left: 46px;">
                            <ul class="nav">
                                <li
                                    <?php if (basename($_SERVER['PHP_SELF']) == "clientes.php"  || basename($_SERVER['PHP_SELF']) == "cliente.php") echo "class='active'"; ?>>
                                    <a href="./clientes.php">
                                        <span class="sidebar-normal">Clientes</span>
                                    </a>
                                </li>
                                <li
                                    <?php if (basename($_SERVER['PHP_SELF']) == "servicos.php"  || basename($_SERVER['PHP_SELF']) == "servico.php") echo "class='active'"; ?>>
                                    <a href="./servicos.php">
                                        <span class="sidebar-normal">Servi&ccedil;os</span>
                                    </a>
                                </li>
                                <li
                                    <?php if (basename($_SERVER['PHP_SELF']) == "mercadoria.php" || basename($_SERVER['PHP_SELF']) == "mercadorias.php") echo "class='active'"; ?>>
                                    <a href="./mercadorias.php">
                                        <span class="sidebar-normal">Mercadorias</span>
                                    </a>
                                </li>
                                <li
                                    <?php if (basename($_SERVER['PHP_SELF']) == "carga-descarga-list.php" || basename($_SERVER['PHP_SELF']) == "carga-descarga-list.php") echo "class='active'"; ?>>
                                    <a href="./carga-descarga-list.php">
                                        <span class="sidebar-normal">Carga e Descarga</span>
                                    </a>
                                </li>
                                <li
                                    <?php if (basename($_SERVER['PHP_SELF']) == "escoltas.php" || basename($_SERVER['PHP_SELF']) == "escoltas.php") echo "class='active'"; ?>>
                                    <a href="./escoltas.php">
                                        <span class="sidebar-normal">Escoltas</span>
                                    </a>
                                </li>
                                <li
                                    <?php if (basename($_SERVER['PHP_SELF']) == "icms-list.php" || basename($_SERVER['PHP_SELF']) == "icms-list.php") echo "class='active'"; ?>>
                                    <a href="./icms-list.php">
                                        <span class="sidebar-normal">ICMS</span>
                                    </a>
                                </li>
                                <li
                                    <?php if (basename($_SERVER['PHP_SELF']) == "seguro-mercadoria-list.php" || basename($_SERVER['PHP_SELF']) == "seguro-mercadoria-list.php") echo "class='active'"; ?>>
                                    <a href="./seguro-mercadoria-list.php">
                                        <span class="sidebar-normal">Seguro Mercadoria</span>
                                    </a>
                                </li>
                                <li
                                    <?php if (basename($_SERVER['PHP_SELF']) == "tipo-veiculo-list.php" || basename($_SERVER['PHP_SELF']) == "tipo-veiculo-list.php") echo "class='active'"; ?>>
                                    <a href="./tipo-veiculo-list.php">
                                        <span class="sidebar-normal">Tipos de Veiculos</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li
                        <?php if (basename($_SERVER['PHP_SELF']) == "orcamentos.php" || basename($_SERVER['PHP_SELF']) == "orcamento.php") echo "class='active'"; ?>>
                        <a href="./orcamentos.php">
                            <i class="nc-icon nc-tile-56"></i>
                            <p>Or&ccedil;amentos</p>
                        </a>
                    </li>

                    <li
                        <?php if (basename($_SERVER['PHP_SELF']) == "usuarios.php" || basename($_SERVER['PHP_SELF']) == "usuario.php") echo "class='active'"; ?>>
                        <a href="./usuarios.php">
                            <i class="nc-icon nc-single-02"></i>
                            <p>Usu&aacute;rios</p>
                        </a>
                    </li>
                    <li>
                        <a href="./logoff.php">
                            <i class="nc-icon nc-button-power"></i>
                            <p>Sair</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-panel">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
                <div class="container-fluid">
                    <div class="navbar-wrapper">
                        <div class="navbar-toggle">
                            <button type="button" class="navbar-toggler">
                                <span class="navbar-toggler-bar bar1"></span>
                                <span class="navbar-toggler-bar bar2"></span>
                                <span class="navbar-toggler-bar bar3"></span>
                            </button>
                        </div>
                        <a class="navbar-brand" href="">San Martino</a>
                    </div>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation"
                        aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">

                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navigation">
                        <form>
                            <div class="input-group no-border">

                            </div>
                        </form>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                            </li>
                            <li class="nav-item btn-rotate dropdown">
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>