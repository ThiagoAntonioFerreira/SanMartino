<?php
var_dump($_POST);
include 'banco.php';
$sqlcliente = 'SELECT id, nome FROM clientes ORDER BY nome';
$sqlservico = 'SELECT id, servico FROM servicos ORDER BY servico';
$sqlcidades = 'SELECT id, nome FROM cidades ORDER BY nome';
$sqlmercadoria = 'SELECT id, mercadoria FROM mercadorias ORDER BY mercadoria';
$sqlcargadescarga = 'SELECT id, carga_descarga FROM carga_descargas ORDER BY carga_descarga';
$sqlseguromercadoria = 'SELECT id, seguro_mercadoria FROM seguro_mercadoria ORDER BY seguro_mercadoria';
$sqlicms = 'SELECT id, icms FROM icms ORDER BY icms';
$sqlmescolta = 'SELECT id, escolta FROM escolta ORDER BY escolta';
$sqltipoveiculo = 'SELECT id, tipo_veiculo FROM tipo_veiculos ORDER BY tipo_veiculo';

$id = "";
if (!empty($_GET['id'])) {
    $id = $_GET['id'];
}

if (empty($_POST)) {
    $pdo = Banco::conectar();

    if ($id != "") {
        $pdo = Banco::conectar();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM orcamentos where id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array(
            $id
        ));
        $data = $q->fetch(PDO::FETCH_ASSOC);

        $sql = "SELECT * FROM valores_orcamento where id_orcamento = ?";
        $id_orcamento = $id;
        $q = $pdo->prepare($sql);
        $q->execute(array(
            $id_orcamento
        ));
        $valores_orcamento = $q->fetch(PDO::FETCH_ASSOC);


        Banco::desconectar();
    }
} else {
    $id_cliente = $_POST['id_cliente'];
    $data = $_POST['data'];
    $id_servico = $_POST['id_servico'];
    $id_mercadoria = $_POST['id_mercadoria'];
    $escolta = $_POST['escolta'];
    $tipo_carregamento = $_POST['tipo_carregamento'];
    $observacoes = $_POST['observacoes'];
    $validade_proposta = $_POST['validade_proposta'];
    $icms_cond = $_POST['icms_cond'];
    $seguro_merc = $_POST['seguro_merc'];
    $carg_desc = $_POST['carg_desc'];
    $cond_veiculos = $_POST['cond_veiculos'];
    $carregamento = $_POST['carregamento'];

    $pdo = Banco::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (empty($id)) {
        $sql = "INSERT INTO orcamentos (id_cliente, data, id_servico, id_mercadoria, escolta, tipo_carregamento, observacoes, validade_proposta, icms_cond, seguro_merc, carg_desc, cond_veiculos, carregamento ) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $q = $pdo->prepare($sql);
        $q->execute(array(
            $id_cliente,
            $data,
            $id_mercadoria,
            $id_servico,
            $escolta,
            $tipo_carregamento,
            $observacoes,
            $validade_proposta,
            $icms_cond,
            $carg_desc,
            $seguro_merc,
            $cond_veiculos,
            $carregamento
        ));

        $id = $pdo->lastInsertId();

        for ($i = 0; $i < count($_POST['origem']); $i++) {
            $sql = "INSERT INTO valores_orcamento (id_orcamento, origem, destino, tipo_veiculo, frete_peso, gris, adv, pedagio, icms) VALUES (?,?,?,?,?,?,?,?,?)";
            $q = $pdo->prepare($sql);
            $q->execute(array(
                $id,
                $_POST['origem'][$i],
                $_POST['destino'][$i],
                $_POST['tipo_veiculo'][$i],
                $_POST['frete_peso'][$i],
                $_POST['gris'][$i],
                $_POST['adv'][$i],
                $_POST['pedagio'][$i],
                $_POST['icms'][$i]
            ));
        }
    } else {
        $sql = "update orcamento set id_cliente=?, data=?, id_servico=?, id_mercadoria=?, escolta=?, tipo_carregamento=?, observacoes=?, validade_proposta=?, icms_cond=?, seguro_merc=?, carg_desc=?, cond_veiculos=?, carregamento=? where id=?";
        $q = $pdo->prepare($sql);
        $q->execute(array(
            $id_cliente,
            $data,
            $id_mercadoria,
            $id_servico,
            $escolta,
            $tipo_carregamento,
            $observacoes,
            $validade_proposta,
            $icms_cond,
            $carg_desc,
            $seguro_merc,
            $cond_veiculos,
            $carregamento,
            $id
        ));
    }

    Banco::desconectar();
    header("Location: orcamentos.php");
}
?>
<?php include("header.php"); ?>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-user">
                <div class="card-header">
                    <h5 class="card-title">Clientes</h5>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Cliente </label> <input required type="hidden" name="id"
                                        value="<?php echo !empty($id) ? $id : ''; ?>"> <select class="form-control"
                                        id="cliente" name="id_cliente">
                                        <option value="0">Selecione o Cliente</option>
                                        <?php
                                        foreach ($pdo->query($sqlcliente) as $row) {
                                            if ($row['id'] == $data['id_cliente']) {
                                                echo '<option value=' . $row['id'] . ' selected>';
                                            } else {
                                                echo '<option value=' . $row['id'] . '>';
                                            }
                                            echo $row['nome'];
                                            echo '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Data:</label> <input class="form-control" type="text" name="data" id="data"
                                        value="<?php if ($id != "") {
                                                                                                                                    echo $data['data'];
                                                                                                                                } else {
                                                                                                                                    echo "";
                                                                                                                                } ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mercadoria </label> <select class="form-control" id="mercadoria"
                                        name="id_mercadoria">
                                        <option value="0">Selecione a Mercadoria</option>
                                        <?php
                                        foreach ($pdo->query($sqlmercadoria) as $row) {
                                            if ($row['id'] == $data['id_mercadoria']) {
                                                echo '<option value=' . $row['id'] . ' selected>';
                                            } else {
                                                echo '<option value=' . $row['id'] . '>';
                                            }
                                            echo $row['mercadoria'];
                                            echo '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Servi&ccedil;o </label>
                                    <select class="form-control" id="servico" name="id_servico">
                                        <option value="0">Selecione o Servi&ccedil;o</option>
                                        <?php
                                        foreach ($pdo->query($sqlservico) as $row) {
                                            if ($row['id'] == $data['id_servico']) {
                                                echo '<option value=' . $row['id'] . ' selected>';
                                            } else {
                                                echo '<option value=' . $row['id'] . '>';
                                            }
                                            echo $row['servico'];
                                            echo '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>



                        <fieldset>
                            <legend>Valores</legend>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Origem:</label>

                                        <select class="form-control" id="origem" name="origem_">
                                            <option value="0">Selecione a cidade</option>
                                            <?php
                                            foreach ($pdo->query($sqlcidades) as $row) {
                                                if ($row['id'] == $data['origem']) {
                                                    echo '<option value=' . $row['id'] . ' selected>';
                                                } else {
                                                    echo '<option value=' . $row['id'] . '>';
                                                }
                                                echo $row['nome'];
                                                echo '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Destino:</label>
                                        <select class="form-control" id="destino" name="destino_">
                                            <option value="0">Selecione a cidade</option>
                                            <?php
                                            foreach ($pdo->query($sqlcidades) as $row) {
                                                if ($row['id'] == $data['origem']) {
                                                    echo '<option value=' . $row['id'] . ' selected>';
                                                } else {
                                                    echo '<option value=' . $row['id'] . '>';
                                                }
                                                echo $row['nome'];
                                                echo '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tipo de Ve&iacute;culo:</label>
                                        <select class="form-control" id="tipo_veiculo" name="tipo_veiculo_">
                                            <option value="0">Selecione o tipo</option>
                                            <?php
                                            foreach ($pdo->query($sqltipoveiculo) as $row) {
                                                if ($row['id'] == $data['tipo_veiculo']) {
                                                    echo '<option value=' . $row['id'] . ' selected>';
                                                } else {
                                                    echo '<option value=' . $row['id'] . '>';
                                                }
                                                echo $row['tipo_veiculo'];
                                                echo '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Frete Peso:</label> <input class="form-control" type="text"
                                            name="frete_peso_" id="frete_peso" value="">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>GRIS:</label> <input class="form-control" type="text" name="gris_"
                                            id="gris" value="">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>ADV:</label> <input class="form-control" type="text" name="adv_" id="adv"
                                            value="">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>PEDAGIO:</label> <input class="form-control" type="text" name="pedagio"
                                            id="pedagio" value="">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>ICMS:</label> <input class="form-control" type="text" name="icms"
                                            id="icms" value="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <button type="button" id="btn-add-produtos"
                                        class="btn btn-info btn-round">Adicionar...</button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table" id="tableItens">
                                        <thead class=" text-primary">
                                            <th>Origem</th>
                                            <th>Destino</th>
                                            <th>Tipo de Veiculo</th>
                                            <th>Frete Peso</th>
                                            <th>GRIS</th>
                                            <th>ADV</th>
                                            <th>Pedagio</th>
                                            <th>ICMS</th>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($id != "") {
                                                foreach ($valores_orcamento as $row) {
                                                    echo '<tr>';
                                                    echo "<td>" . $row['origem'] . "</td>";
                                                    echo "<td>" . $row['destino'] . "</td>";
                                                    echo "<td>" . $row['tipo_veiculo'] . "</td>";
                                                    echo "<td>" . $row['frete_Peso'] . "</td>";
                                                    echo "<td>" . $row['gris'] . "</td>";
                                                    echo "<td>" . $row['adv'] . "</td>";
                                                    echo "<td>" . $row['pedagio'] . "</td>";
                                                    echo "<td>" . $row['icms'] . "</td>";
                                                    echo "</tr>";
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </fieldset>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Observa&ccedil;&otilde;es:</label><textarea name="observacoes"
                                        id="observacoes" class="form-control" rows="5">
                                        <?php if ($id != "") {
                                            echo $observacoes;
                                        } ?>
                                        </textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Escolta:</label>

                                    <select class="form-control" id="escolta" name="escolta">
                                        <option value="0">Selecione a escolta</option>
                                        <?php
                                        foreach ($pdo->query($sqlescolta) as $row) {
                                            if ($row['id'] == $data['escolta']) {
                                                echo '<option value=' . $row['id'] . ' selected>';
                                            } else {
                                                echo '<option value=' . $row['id'] . '>';
                                            }
                                            echo $row['escolta'];
                                            echo '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>ICMS:</label>
                                    <select class="form-control" id="icms_cond" name="icms_cond">
                                        <option value="0">Selecione o ICMS</option>
                                        <?php
                                        foreach ($pdo->query($sqlicms) as $row) {
                                            if ($row['id'] == $data['icms']) {
                                                echo '<option value=' . $row['id'] . ' selected>';
                                            } else {
                                                echo '<option value=' . $row['id'] . '>';
                                            }
                                            echo $row['icms'];
                                            echo '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Seguro de Mercadoria:</label>
                                    <select class="form-control" id="seguro_merc" name="seguro_merc">
                                        <option value="0">Selecione o Seguro</option>
                                        <?php
                                        foreach ($pdo->query($sqlseguromercadoria) as $row) {
                                            if ($row['id'] == $data['seguro_mercadoria']) {
                                                echo '<option value=' . $row['id'] . ' selected>';
                                            } else {
                                                echo '<option value=' . $row['id'] . '>';
                                            }
                                            echo $row['seguro_mercadoria'];
                                            echo '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Carga e Descarga:</label>
                                    <select class="form-control" id="carg_desc" name="carg_desc">
                                        <option value="0">Selecione carga e descarga</option>
                                        <?php
                                        foreach ($pdo->query($sqlcargadescarga) as $row) {
                                            if ($row['id'] == $data['carga_descarga']) {
                                                echo '<option value=' . $row['id'] . ' selected>';
                                            } else {
                                                echo '<option value=' . $row['id'] . '>';
                                            }
                                            echo $row['carga_descarga'];
                                            echo '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Carregamento:</label> <input class="form-control" type="text"
                                        name="carregamento" id="carregamento"
                                        value="<?php if ($id != "") {
                                                                                                                                                            echo
                                                                                                                                                                $data['carregamento'];
                                                                                                                                                        } else {
                                                                                                                                                            echo "";
                                                                                                                                                        } ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Condições dos veículos:</label> <input class="form-control" type="text"
                                        name="cond_veiculos" id="cond_veiculos"
                                        value="<?php if ($id != "") {
                                                                                                                                                                        echo $data['cond_veiculos'];
                                                                                                                                                                    } else {
                                                                                                                                                                        echo "Todos os veículos conforme leis e normas ANTT, as emissoes de CO² esta dentro do minimo exigido conforme leis ambientais";
                                                                                                                                                                    } ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Validade da Proposta:</label> <input class="form-control" type="text"
                                        name="validade_proposta" id="validade_proposta"
                                        value="<?php if ($id != "") {
                                                                                                                                                                            echo  $data['validade_proposta'];
                                                                                                                                                                        } else {
                                                                                                                                                                            echo "12 meses";
                                                                                                                                                                        } ?>">
                                </div>
                            </div>
                            <div class="col-md-3" style="text-align: center;">
                                <label>Tipo Carregamento:</label>
                                <div class="form-group">
                                    <label>Paletizado:</label>
                                    <input class="form-control" type="radio" name="tipo_carregamento" id="paletizado"
                                        value="paletizado"
                                        <?php if ($id != "") {
                                                                                                                                                if ($data['tipo_carregamento'] == "paletizado") {
                                                                                                                                                    echo " checked";
                                                                                                                                                }
                                                                                                                                            }
                                                                                                                                            ?>>
                                </div>
                                <div class="form-group">
                                    <label>Solto:</label>
                                    <input class="form-control" type="radio" name="tipo_carregamento" id="solto"
                                        value="solto"
                                        <?php if ($id != "") {
                                                                                                                                    if ($data['tipo_carregamento'] == "solto") {
                                                                                                                                        echo " checked";
                                                                                                                                    }
                                                                                                                                }
                                                                                                                                ?>>
                                </div>
                                <div class="form-group">
                                    <label>BIG BAG:</label>
                                    <input class="form-control" type="radio" name="tipo_carregamento" id="BIG BAG"
                                        value="BIG BAG"
                                        <?php if ($id != "") {
                                                                                                                                        if ($data['tipo_carregamento'] == "BIG BAG") {
                                                                                                                                            echo " checked";
                                                                                                                                        }
                                                                                                                                    }
                                                                                                                                    ?>>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="update ml-auto mr-auto">
                                <input type="hidden" name="sent" value="true"> <input type="hidden" name="id"
                                    value="<?php if (isset($_GET["id"])) echo $_GET["id"] ?>">
                                <button type="submit" class="btn btn-primary btn-round">Salvar</button>
                                <a href="clientes.php" class="btn btn-danger btn-round">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<form action="" method="post" id="valores_form">
    <div></div>
</form>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Conex&atilde;o</h4>
            </div>
            <div class="modal-body">
                <p id="error"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>



<script>
$("#origem").select2()
$("#destino").select2()

function RetornaDataHoraAtual() {
    if ($("#data").val() == "") {
        var dNow = new Date();
        var localdate = dNow.getDate() + '/' + (dNow.getMonth() + 1) + '/' + dNow.getFullYear() + ' ' + dNow
            .getHours() +
            ':' + dNow.getMinutes() + ':' + dNow.getSeconds();
        $("#data").val(localdate);
        return localdate;
    } else {
        return false
    }

}
RetornaDataHoraAtual();
var valores;
var counter = 0;
$("#btn-add-produtos").click(function() {

    if ($("#origem").val() === "") {
        toastr["error"]("Informe a Origem!", "SanMartino");
        $("#origem").focus();
        return;
    }

    if ($("#destino").val() === "") {
        toastr["error"]("Informe o Destino!", "SanMartino");
        $("#destino").focus();
        return;
    }

    if ($("#tipo_veiculo").val() === "") {
        toastr["error"]("Informe o Tipo de Veiculo!", "SanMartino");
        $("#destino").focus();
        return;
    }
    var hiddens = '<input type="hidden" name="origem[' + counter + ']" value="' + $("#origem").val() + '" />' +
        '<input type="hidden" name="destino[' + counter + ']" value="' + $("#destino").val() + '" />' +
        '<input type="hidden" name="tipo_veiculo[' + counter + ']" value="' + $("#tipo_veiculo").val() +
        '" />' +
        '<input type="hidden" name="frete_peso[' + counter + ']" value="' + $("#frete_peso").val() + '" />' +
        '<input type="hidden" name="gris[' + counter + ']" value="' + $("#gris").val() + '" />' +
        '<input type="hidden" name="adv[' + counter + ']" value="' + $("#adv").val() + '" />' +
        '<input type="hidden" name="pedagio[' + counter + ']" value="' + $("#pedagio").val() + '" />' +
        '<input type="hidden" name="icms[' + counter + ']" value="' + $("#icms").val() + '" />';

    counter++;
    $('#valores_form').find('div').append(hiddens);

    $('#tableItens').append('<tr>' +
        "<td>" + $("#origem").val() + "</td>" +
        "<td>" + $("#destino").val() + "</td>" +
        "<td>" + $("#tipo_veiculo").val() + "</td>" +
        "<td>" + $("#frete_peso").val() + "</td>" +
        "<td>" + $("#gris").val() + "</td>" +
        "<td>" + $("#adv").val() + "</td>" +
        "<td>" + $("#pedagio").val() + "</td>" +
        "<td>" + $("#icms").val() + "</td>" +
        "</tr>"
    );

});
</script>

<?php include("footer.php"); ?>