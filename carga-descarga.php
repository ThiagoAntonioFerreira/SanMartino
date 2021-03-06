<?php
include 'banco.php';
$id = "";
if (!empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}


if (empty($_POST)) {
    $pdo = Banco::conectar();


    if ($id != "") {
        $pdo = Banco::conectar();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM carga_descargas where id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $carga_descarga = $data['carga_descarga'];

        Banco::desconectar();
    }
} else {

    $id = $_POST['id'];
    $carga_descarga = $_POST['carga_descarga'];
    $pdo = Banco::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (empty($id)) {
        $sql = "INSERT INTO carga_descargas (carga_descarga) VALUES(?)";
        $q = $pdo->prepare($sql);
        $q->execute(array($carga_descarga));

        $id = $pdo->lastInsertId();
    } else {
        $sql = "update carga_descargas set carga_descarga=? where id=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($carga_descarga, $id));
    }

    Banco::desconectar();
    header("Location: carga-descarga-list.php");
}
?>
<?php include("header.php"); ?>
<div class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="card card-user">
                <div class="card-header">
                    <h5 class="card-title">Carga e Descarga</h5>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Carga e Descarga:</label>
                                    <input class="form-control" name="carga_descarga"
                                        value="<?php echo !empty($carga_descarga) ? $carga_descarga : ''; ?>"
                                        required="required" placeholder="Nome da carga descarga">
                                    <input type="hidden" name="id" value="<?php echo !empty($id) ? $id : ''; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="update ml-auto mr-auto">
                                <input type="hidden" name="sent" value="true"> <input type="hidden" name="id"
                                    value="<?php if (isset($_GET["id"])) echo $_GET["id"] ?>">
                                <button type="submit" class="btn btn-primary btn-round">Salvar</button>
                                <a href="carga-descarga-list.php" class="btn btn-danger btn-round">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
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

<script>
$("#btn-conexao-origem").click(function() {
    $.LoadingOverlay("show");
    var _usuario = $("#usuario").val();
    var _senha = $("#senha").val();
    var _odbc = $("#odbc").val();
    var _tipo = $("#tipo").val();
    var _banco = $("#banco").val();
    var _porta = $("#porta").val();
    var _url = 'TestarConexao.php'

    $.ajax({
        url: _url,
        type: 'post',
        data: {
            host: _odbc,
            banco: _banco,
            usuario: _usuario,
            senha: _senha,
            porta: _porta,
            tipo: _tipo,
            odbc: _odbc
        },
        dataType: 'json',
        error: function(xhr, desc, e) {
            $.LoadingOverlay("hide");
            $("#error").html(xhr.responseText);
            $("#myModal").modal('toggle');
        },
        success: function(response) {
            $.LoadingOverlay("hide");
            $("#error").html(response);
            $("#myModal").modal('toggle');
        }
    });

});

$("#btn-conexao-destino").click(function() {
    $.LoadingOverlay("show");
    var _host = $("#hostdestino").val();
    var _banco = $("#bancodestino").val();
    var _usuario = $("#usuariodestino").val();
    var _senha = $("#senhadestino").val();
    var _porta = $("#portadestino").val();
    var _tipo = "3";

    $.ajax({
        url: 'testarconexao.php',
        type: 'post',
        data: {
            host: _host,
            banco: _banco,
            usuario: _usuario,
            senha: _senha,
            porta: _porta,
            tipo: _tipo,
            odbc: ''
        },
        dataType: 'json',
        error: function(xhr, desc, e) {
            $.LoadingOverlay("hide");

            $("#error").html(xhr.responseText);
            $("#myModal").modal('toggle');

        },
        success: function(response) {
            $.LoadingOverlay("hide");
            $("#error").html(response);
            $("#myModal").modal('toggle');
        }
    });

});


$("#tipo").change(function() {
    var valor = $(this).val();
    setDivs(valor);
});


var valor = $("#tipo").val();
setDivs(valor);

function setDivs(valor) {
    if (valor == "10") {
        $("#div-host").show();
        $("#div-odbc").hide();
        $("#div-banco").hide();
        $("#div-schema").hide();
        $("#div-testar-origem").hide();
        $("#odbc").removeAttr("required");
        $("#banco").removeAttr("required");
        $("#host").attr("required");
    } else {
        $("#div-host").hide();
        $("#div-odbc").show();
        $("#div-banco").show();
        $("#div-schema").show();
        $("#div-testar-origem").show();
        $("#odbc").attr("required", "req");
        $("#banco").attr("required", "req");
        $("#host").removeAttr("required");
    }
}
</script>

<?php include("footer.php"); ?>