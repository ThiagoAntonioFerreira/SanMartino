<?php
function GUID()
{
	if (function_exists('com_create_guid') === true) {
		return trim(com_create_guid(), '{}');
	}
	return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}


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
		$sql = "SELECT * FROM tipo_veiculos where id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		$tipo_veiculo = $data['tipo_veiculo'];
		$capacidade = $data['capacidade'];
		Banco::desconectar();
	}
} else {

	$id = $_POST['id'];
	$tipo_veiculo = $_POST['tipo_veiculo'];
	$capacidade = $_POST['capacidade'];
	$pdo = Banco::conectar();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	if (empty($id)) {
		$sql = "INSERT INTO tipo_veiculos (tipo_veiculo, capacidade) VALUES(?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($tipo_veiculo, $capacidade));

		$id = $pdo->lastInsertId();
	} else {
		$sql = "update tipo_veiculos set tipo_veiculo=?, capacidade=? where id=?";
		$q = $pdo->prepare($sql);
		$q->execute(array($tipo_veiculo, $capacidade, $id));
	}

	Banco::desconectar();
	header("Location: tipo-veiculo-list.php");
}
?>
<?php include("header.php"); ?>
<div class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="card card-user">
                <div class="card-header">
                    <h5 class="card-title">Tipo de Veiculo</h5>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Tipo</label>
                                    <input class="form-control" name="tipo_veiculo"
                                        value="<?php echo !empty($tipo_veiculo) ? $tipo_veiculo : ''; ?>"
                                        required="required" placeholder="Nome do Tipo">
                                    <input type="hidden" name="id" value="<?php echo !empty($id) ? $id : ''; ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Capacidade (Toneladas)</label>
                                    <input class="form-control" name="capacidade" type="number"
                                        value="<?php echo !empty($capacidade) ? $capacidade : ''; ?>"
                                        required="required" placeholder="Capacidade em Toneladas">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="update ml-auto mr-auto">
                                <input type="hidden" name="sent" value="true"> <input type="hidden" name="id"
                                    value="<?php if (isset($_GET["id"])) echo $_GET["id"] ?>">
                                <button type="submit" class="btn btn-primary btn-round">Salvar</button>
                                <a href="tipo-veiculo-list.php" class="btn btn-danger btn-round">Cancelar</a>
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