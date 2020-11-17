<script>
var counter = 0;
</script>
<style>
.select2-container{
 width: 100%!important;
 }
 .select2-search--dropdown .select2-search__field {
 width: 98%;
 }
.select2-container--default .select2-selection--single{
    padding:6px;
    height: 37px;
    font-size: 1.2em;  
    position: relative;
}
    
  </style>
  
<?php
include 'banco.php';
$sqlcliente = 'SELECT id, nome FROM clientes ORDER BY nome';
$sqlservico = 'SELECT id, servico FROM servicos ORDER BY servico';
$sqlcidades = 'SELECT id, nome FROM cidades ORDER BY nome';
$sqlmercadoria = 'SELECT id, mercadoria FROM mercadorias ORDER BY mercadoria';
$sqlcargadescarga = 'SELECT id, carga_descarga FROM carga_descargas ORDER BY carga_descarga';
$sqlseguromercadoria = 'SELECT id, seguro_mercadoria FROM seguro_mercadoria ORDER BY seguro_mercadoria';
$sqlicms = 'SELECT id, icms, percentual FROM icms ORDER BY icms';
$sqlescolta = 'SELECT id, escolta FROM escolta ORDER BY escolta';
$sqltipoveiculo = 'SELECT id, tipo_veiculo FROM tipo_veiculos ORDER BY tipo_veiculo';

$id = "";
if (! empty($_GET['id'])) {
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
    $seguro_merc = $_POST['seguro_merc'];
    $carg_desc = $_POST['carg_desc'];
    $cond_veiculos = $_POST['cond_veiculos'];    
    
    $pdo = Banco::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (empty($id)) {
        $sql = "INSERT INTO orcamentos (id_cliente, data, id_servico, id_mercadoria, escolta, tipo_carregamento, observacoes, 
validade_proposta, seguro_merc, carg_desc, cond_veiculos ) 
VALUES(?,?,?,?,?,?,?,?,?,?,?)";
        $q = $pdo->prepare($sql);
        $q->execute(array(
            $id_cliente,
            date('Y-m-d', strtotime(str_replace('/', '-', $data))),
            $id_mercadoria,
            $id_servico,
            $escolta,
            $tipo_carregamento,
            $observacoes,
            $validade_proposta,
            $carg_desc,
            $seguro_merc,
            $cond_veiculos
        ));

        $id = $pdo->lastInsertId();
        
        $origens = $_POST['origem'];
        for ($i = 0; $i < count($origens); $i ++) {
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
                $_POST['icms_cond'][$i]
            ));
        }
    } else {
        $sql = "update orcamentos set id_cliente=?, data=?, id_servico=?, id_mercadoria=?, escolta=?, 
tipo_carregamento=?, observacoes=?, validade_proposta=?, seguro_merc=?, carg_desc=?, cond_veiculos=? where id=?";
        $q = $pdo->prepare($sql);
        $q->execute(array(
            $id_cliente,
            date('Y-m-d', strtotime(str_replace('/', '-', $data))),
            $id_mercadoria,
            $id_servico,
            $escolta,
            $tipo_carregamento,
            $observacoes,
            $validade_proposta,
            $carg_desc,
            $seguro_merc,
            $cond_veiculos,
            $id
        ));
        
        
        //Remover os itens
        $sql = "delete from valores_orcamento where id_orcamento=?";
        $q = $pdo->prepare($sql);
        $q->execute(array(
            $id
        ));
        
        $origens = $_POST['origem'];
        for ($i = 0; $i < count($origens); $i ++) {
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
                $_POST['icms_cond'][$i]
            ));
        }
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
							<div class="col-md-9">
								<div class="form-group">
									<label>Cliente </label> <input required type="hidden" name="id"
										value="<?php echo !empty($id) ? $id : ''; ?>"> <select
										class="form-control" required="required" id="cliente"
										name="id_cliente">
										<option value="">Selecione o Cliente</option>
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
							<div class="col-md-3">
								<div class="form-group">
									<label>Data:</label> <input class="form-control" type="text"
										name="data" id="data" readonly="readonly" 
										value="<?php if ($id != "") {
										    $date=date_create($data['data']);
										    echo date_format($date,"d/m/Y");
                                    } else {
                                        echo date('d/m/Y');
                                    }
                                    ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Mercadoria </label> <select class="form-control"
										id="mercadoria" name="id_mercadoria" required>
										<option value="">Selecione a Mercadoria</option>
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
									<label>Servi&ccedil;o </label> <select class="form-control"
										id="servico" name="id_servico">
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
										<input class="form-control" type="text" id="origem" placeholder="Digite a Origem" />
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>Destino:</label> 
										<input class="form-control" type="text" id="destino" placeholder="Digite o Destino" />
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>Tipo de Ve&iacute;culo:</label> <select
											class="form-control" id="tipo_veiculo" name="tipo_veiculo_">
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
								<div class="col-md-2">
									<div class="form-group">
										<label>Frete Peso:</label> <input class="form-control"
											type="text" name="frete_peso_" id="frete_peso" value="">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>GRIS:</label> <input class="form-control" type="text"
											name="gris_" id="gris" value="">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>ADV:</label> <input class="form-control" type="text"
											name="adv_" id="adv" value="">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>PEDAGIO:</label> <input class="form-control"
											type="text" name="pedagio" id="pedagio" value="">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>ICMS:</label>
										<select class="form-control"
										id="icms_cond" name="icms_cond">
										<option value="">Selecione o ICMS</option>
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
                                                $sqlitens = "SELECT vo.*, t.tipo_veiculo, t.id as id_tipo_veiculo FROM valores_orcamento vo left join 
                                                             tipo_veiculos t on (vo.tipo_veiculo = t.id) where id_orcamento = ?";
                                                $id_orcamento = $id;
                                                $q = $pdo->prepare($sqlitens);
                                                $q->execute(array(
                                                    $id_orcamento
                                                ));
                                                $result = $q->fetchAll(\PDO::FETCH_ASSOC);
                                                $counter  = 0;
                                                foreach ($result as $row) {
                                                    echo '<input type="hidden" name="origem['.$counter.']" value="' . $row['origem'] . '" />' .
                                                    '<input type="hidden" name="destino['.$counter.']" value="'. $row['origem'] . '" />' .
                                                    '<input type="hidden" name="tipo_veiculo['.$counter.']" value="' . $row['id_tipo_veiculo'] .'" />' .
                                                    '<input type="hidden" name="frete_peso['.$counter.']" value="'. $row['frete_peso'] .'" />' .
                                                    '<input type="hidden" name="gris['.$counter.']" value="' . $row['gris'] . '" />' .
                                                    '<input type="hidden" name="adv['.$counter.']" value="' . $row['adv'] .'" />' .
                                                    '<input type="hidden" name="pedagio['.$counter.']" value="' . $row['pedagio'] . '" />' .
                                                    '<input type="hidden" name="icms_cond['.$counter.']" value="' . $row['icms'] . '" />';
                                                    
                                                    
                                                    echo '<tr>';
                                                    echo "<td>" . $row['origem'] . "</td>";
                                                    echo "<td>" . $row['destino'] . "</td>";
                                                    echo "<td>" . $row['tipo_veiculo'] . "</td>";
                                                    echo "<td>" . $row['frete_peso'] . "</td>";
                                                    echo "<td>" . $row['gris'] . "</td>";
                                                    echo "<td>" . $row['adv'] . "</td>";
                                                    echo "<td>" . $row['pedagio'] . "</td>";
                                                    echo "<td>" . $row['icms'] . "</td>";
                                                    echo "</tr>";
                                                    $counter++;
                                                }
                                                echo "<script>counter = ".$counter.";</script>";
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
									<label>Observa&ccedil;&otilde;es:</label>
									<textarea name="observacoes" id="observacoes" class="form-control" rows="5" ><?php if ($id != "") {echo $data['observacoes'];}?></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Escolta:</label> <select class="form-control"
										id="escolta" name="escolta">
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
							<div class="col-md-4">
								<div class="form-group">
									<label>Seguro de Mercadoria:</label> <select
										class="form-control" id="seguro_merc" name="seguro_merc">
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
							<div class="col-md-4">
								<div class="form-group">
									<label>Carga e Descarga:</label> <select class="form-control"
										id="carg_desc" name="carg_desc">
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
							<div class="col-md-4">
								<div class="form-group">
									<label>Condições dos veículos:</label> <input
										class="form-control" type="text" name="cond_veiculos"
										id="cond_veiculos"
										value="<?php

if ($id != "") {
            echo $data['cond_veiculos'];
        } else {
            echo "Todos os veículos conforme leis e normas ANTT, as emissoes de CO² esta dentro do minimo exigido conforme leis ambientais";
        }
        ?>">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Validade da Proposta (Em Dias):</label> <input
										class="form-control" type="number" min="1" max="999" maxlength="3" name="validade_proposta"
										id="validade_proposta"
										value="<?php

if ($id != "") {
            echo $data['validade_proposta'];
        } else {
            echo "12";
        }
        ?>">
								</div>
							</div>
							<div class="col-md-4">
								<label>Tipo Carregamento:</label>
								
									<select
										class="form-control" required="required" id="tipo_carregamento"
										name="tipo_carregamento">
										<option value="">Tipo de Carregamento</option>
										<option value="1" <?php if (isset($_GET["id"]) && $data['tipo_carregamento'] == "1") {
                                                echo 'selected';
                                            }?>>Paletizado</option>
                                        <option value="2" <?php if (isset($_GET["id"]) && $data['tipo_carregamento'] == "2") {
                                                echo 'selected';
                                            }?>>Solto</option>                                        
                                        <option value="3" <?php if (isset($_GET["id"]) && $data['tipo_carregamento'] == "3") {
                                                echo 'selected';
                                            }?>>BIG BAG</option>
                                    </select>
								
							</div>
						</div>
						<div class="row">
							<div class="update ml-auto mr-auto">
								<input type="hidden" name="sent" value="true"> <input
									type="hidden" name="id"
									value="<?php if (isset($_GET["id"])) echo $_GET["id"] ?>">
								<button type="submit" class="btn btn-primary btn-round">Salvar</button>
								<a href="orcamentos.php" class="btn btn-danger btn-round">Cancelar</a>
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
<link
	href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css"
	rel="stylesheet" />
<script
	src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>



<script>

var options = {
		//arquivo com os dados em json
	    url: "cidades.php",
	    //valor a ser listado - nome ou ra
	    getValue: function(element) {
	        return element.nome;
	    }
	};
	$("#origem").easyAutocomplete(options);
	$("#destino").easyAutocomplete(options);
		

var valores;

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

    if ($("#icms_cond").val() === "") {
        toastr["error"]("Informe o ICMS!", "SanMartino");
        $("#icms_cond").focus();
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
        '<input type="hidden" name="icms_cond[' + counter + ']" value="' + $("#icms_cond").val() + '" />';

    counter++;

    $('#tableItens').append('<tr>' +
        "<td>" + $("#origem").val() + hiddens +"</td>" +
        "<td>" + $("#destino").val() + "</td>" +
        "<td>" + $( "#tipo_veiculo option:selected" ).text() + "</td>" +
        "<td>" + $("#frete_peso").val() + "</td>" +
        "<td>" + $("#gris").val() + "</td>" +
        "<td>" + $("#adv").val() + "</td>" +
        "<td>" + $("#pedagio").val() + "</td>" +
        "<td>" + $("#icms_cond option:selected" ).text() + "</td>" +
        "</tr>"
    );

});
</script>

<?php include("footer.php"); ?>