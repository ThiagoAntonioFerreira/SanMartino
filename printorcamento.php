<script>
var counter = 0;
</script>
<style>
.table td, .table th {
        font-size: 13px;
    }


.footer {
  bottom: 0;
  text-align: center;
  border: 2px solid black;
}

        @media print {
            .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
                float: left;
            }

            .col-sm-12 {
                width: 100%;
            }

            .col-sm-11 {
                width: 91.66666667%;
            }

            .col-sm-10 {
                width: 83.33333333%;
            }

            .col-sm-9 {
                width: 75%;
            }

            .col-sm-8 {
                width: 66.66666667%;
            }

            .col-sm-7 {
                width: 58.33333333%;
            }

            .col-sm-6 {
                width: 50%;
            }

            .col-sm-5 {
                width: 41.66666667%;
            }

            .col-sm-4 {
                width: 33.33333333%;
            }

            .col-sm-3 {
                width: 25%;
            }

            .col-sm-2 {
                width: 16.66666667%;
            }

            .col-sm-1 {
                width: 8.33333333%;
            }
        }
    </style>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


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
        $sql = "SELECT orc.id, orc.id_cliente, cli.nome as nome_cliente, cli.telefone as telefone_cliente, cli.responsavel as responsavel_cliente, 
                cli.email as email_cliente, orc.data, orc.id_servico, ser.servico as servico, seg.seguro_mercadoria as desc_seguro_mercadoria, 
                orc.id_mercadoria, mer.mercadoria as mercadoria, orc.escolta, esc.escolta as desc_escolta, orc.tipo_carregamento, orc.observacoes, 
                orc.validade_proposta, orc.icms_cond, orc.seguro_merc, orc.carg_desc, 
                orc.cond_veiculos, orc.carregamento, carg.carga_descarga as desc_carga_descarga, 
                orc.desc_carregamento, cond.descricao as desc_condicao_pagamento, orc.icms as desc_icms,
                orc.usuario_criacao, orc.data_criacao
                FROM orcamentos orc 
                inner join clientes cli on (orc.id_cliente = cli.id)
                left join mercadorias mer on (orc.id_mercadoria = mer.id)
                left join servicos ser on (orc.id_servico = ser.id)
                left join escolta esc on (orc.escolta = esc.id)  
                left join seguro_mercadoria seg on (orc.seguro_merc = seg.id)
                left join carga_descargas carg on (orc.carg_desc = carg.id)
                left join condicao_pagamento cond on (orc.id_condicao_pagamento = cond.id)
                where orc.id = ?";

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
<body onload="window.print()">
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
									<label>Proposta N&uacute;mero</label> <br> <b><?php 
									   
									$numero = str_pad($data['id'], 8, "0", STR_PAD_LEFT);
									//echo $numero;
									$strInicial = substr($numero, 0, 5);
									$strFinal = substr($numero, -3);
									echo $strInicial.'-'. $strFinal;
									
									?></b>
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
							<div class="col-md-9">
								<div class="form-group">
									<label>Respons&aacute;vel: </label> <b><?php echo $data['responsavel_cliente']; ?></b>
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
									<label>Servi&ccedil;o: </label> <b><?php echo $data['servico']; ?></b>
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
											<th>Tip. Veiculo</th>
											<th>Cap. Veiculo</th>
											<th>Frete Peso</th>
											<th>GRIS</th>
											<th>ADV</th>
											<th>Ped&aacute;gio</th>
											<th>ICMS (%)</th>
										</thead>
										<tbody>
                                            <?php
                                            if ($id != "") {
                                                $sqlitens = "SELECT vo.*, t.tipo_veiculo, t.capacidade, t.id as id_tipo_veiculo, icms.percentual FROM valores_orcamento vo 
                                                            left join tipo_veiculos t on (vo.tipo_veiculo = t.id) 
                                                            left join icms icms on (icms.id = vo.icms) where id_orcamento = ?";
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
                                                    echo "<td>" . $row['capacidade'] . " TONS.</td>";
                                                    echo "<td>" . $row['frete_peso'] . "</td>";
                                                    echo "<td>" . $row['gris'] . "</td>";
                                                    echo "<td>" . $row['adv'] . "</td>";
                                                    echo "<td>" . $row['pedagio'] . "</td>";
                                                    echo "<td>" . $row['percentual'] . "% </td>";
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
						<hr>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Observa&ccedil;&otilde;es:</label> 
									<p><b><?php echo nl2br($data['observacoes']) ; ?></b></p>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>ICMS:</label> <b><?php if ($id != "") {echo $data['desc_icms'];}?></b>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Escolta:</label> <b><?php if ($id != "") {echo $data['desc_escolta'];}?></b>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Seguro de Mercadoria:</label> <b><?php if ($id != "") {echo $data['desc_seguro_mercadoria'];}?></b>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Carga e Descarga:</label> <b><?php if ($id != "") {echo $data['desc_carga_descarga'];}?></b>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
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
									<label>Validade da Proposta (Em Meses):</label> <b><?php

        if ($id != "") {
            echo $data['validade_proposta'];
        }
        ?></b>
								</div>
							</div>
							<div class="col-md-4">
								<label>Tipo Carregamento:</label> <b>
								 <?php
                                        if (isset($_GET["id"]) && $data['tipo_carregamento'] == "1") {
                                                    echo 'Paletizado';
                                                } else if (isset($_GET["id"]) && $data['tipo_carregamento'] == "2") {
                                                    echo 'Solto';
                                                } else if (isset($_GET["id"]) && $data['tipo_carregamento'] == "3") {
                                                    echo 'BIG BAG';
                                                }else if (isset($_GET["id"]) && $data['tipo_carregamento'] == "4") {
                                                    echo "Container 20'";
                                                }else if (isset($_GET["id"]) && $data['tipo_carregamento'] == "5") {
                                                    echo "Container 40'";
                                                }
                                 ?>								
								</b>

							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Prazo e Condi&ccedil;&otilde;es de Pagamento:</label> <b><?php if ($id != "") {echo $data['desc_condicao_pagamento'];}?></b>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Carregamento:</label> <b><?php if ($id != "") {echo $data['desc_carregamento'];}?></b>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="footer"><b>Or&ccedil;amento gerador por <?php if ($id != "") {echo  $data['usuario_criacao'];?> em <?php if ($id != "") {
	    $date=date_create($data['data_criacao']);
	    echo date_format($date,"d/m/Y H:i:s");}}?></b></div>
</body>
</html>
