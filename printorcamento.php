<script>
var counter = 0;
</script>
<style>
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

<?php
include 'banco.php';

$id = "";
if (! empty($_GET['id'])) {
    $id = $_GET['id'];
}

if (empty($_POST)) {
    $pdo = Banco::conectar();

    if ($id != "") {
        $pdo = Banco::conectar();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT orc.id, orc.id_cliente, cli.nome as nome_cliente, cli.telefone as telefone_cliente, 
                cli.email as email_cliente, orc.data, orc.id_servico, ser.servico as servico, 
                orc.id_mercadoria, mer.mercadoria as mercadoria, orc.escolta, orc.tipo_carregamento, orc.observacoes, 
                orc.validade_proposta, orc.icms_cond, orc.seguro_merc, orc.carg_desc, 
                orc.cond_veiculos, orc.carregamento 
                FROM orcamentos orc 
                inner join clientes cli on (orc.id_cliente = cli.id)
                left join mercadorias mer on (orc.id_mercadoria = mer.id)
                left join servicos ser on (orc.id_servico = ser.id)";

        $q = $pdo->prepare($sql);
        $q->execute(array(
            $id
        ));
        $data = $q->fetch(PDO::FETCH_ASSOC);

        $cliente = Banco::desconectar();
    }
}
?>

<html>
<head>
<link href="assets/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body  onload="window.print()">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-user">
					<div class="card-header">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<img alt="" src="assets/img/logo.jpeg" width="50%">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Proposta N&uacute;mero</label> <br> <b><?php echo $data['id']; ?></b>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Data:</label> <br> <b>
									<?php

        if ($id != "") {
            $date = date_create($data['data']);
            echo date_format($date, "d/m/Y");
        } else {
            echo date('d/m/Y');
        }
        ?></b>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-9">
								<div class="form-group">
									<label>Empresa: </label> <b><?php echo $data['nome_cliente']; ?></b>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Fone: </label> <b><?php echo $data['telefone_cliente']; ?></b>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>E-mail: </label> <b><?php echo $data['email_cliente']; ?></b>
								</div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Mercadoria: </label> <b><?php echo $data['mercadoria']; ?></b>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Servi&ccedil;o: </label>
									<b><?php echo $data['servico']; ?></b>
								</div>
							</div>
						</div>
						<fieldset>
							<legend>Valores</legend>
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
                                                $counter = 0;
                                                foreach ($result as $row) {
                                                    echo '<input type="hidden" name="origem[' . $counter . ']" value="' . $row['origem'] . '" />' . '<input type="hidden" name="destino[' . $counter . ']" value="' . $row['origem'] . '" />' . '<input type="hidden" name="tipo_veiculo[' . $counter . ']" value="' . $row['id_tipo_veiculo'] . '" />' . '<input type="hidden" name="frete_peso[' . $counter . ']" value="' . $row['frete_peso'] . '" />' . '<input type="hidden" name="gris[' . $counter . ']" value="' . $row['gris'] . '" />' . '<input type="hidden" name="adv[' . $counter . ']" value="' . $row['adv'] . '" />' . '<input type="hidden" name="pedagio[' . $counter . ']" value="' . $row['pedagio'] . '" />' . '<input type="hidden" name="icms_cond[' . $counter . ']" value="' . $row['icms'] . '" />';

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
                                                    $counter ++;
                                                }
                                                echo "<script>counter = " . $counter . ";</script>";
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
									<?php if ($id != "") {echo $data['observacoes'];}?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Escolta:</label>

								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Seguro de Mercadoria:</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Carga e Descarga:</label>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Condições dos veículos:</label> <b><?php

        if ($id != "") {
            echo $data['cond_veiculos'];
        } else {
            echo "Todos os veículos conforme leis e normas ANTT, as emissoes de CO² esta dentro do minimo exigido conforme leis ambientais";
        }
        ?></b>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Validade da Proposta (Em Dias):</label> <b><?php

        if ($id != "") {
            echo $data['validade_proposta'];
        } else {
            echo "12";
        }
        ?></b>
								</div>
							</div>
							<div class="col-md-4">
								<label>Tipo Carregamento:</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

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
</body>
</html>
