<?php include("header.php"); ?>

<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Or&ccedil;amentos</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="orcamento.php" class="btn btn-primary btn-block">Novo
                                Or&ccedil;amento</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table" id="myTable">
                            <thead class=" text-primary">
                                <th>Nro.</th>
                                <th>Data</th>
                                <th>Cliente</th>
                                <th>Servi&ccedil;o</th>
                                <th>Mercadoria</th>
                                <th>A&ccedil;&otilde;es</th>
                            </thead>
                            <tbody>
                                <?php
                                include 'banco.php';
                                $pdo = Banco::conectar();
                                $sql = 'select id, id_cliente, data, id_servico, id_mercadoria from orcamentos ORDER BY data desc';

                                foreach ($pdo->query($sql) as $row) {
                                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    $sql = "SELECT * FROM clientes where id = ?";
                                    $q = $pdo->prepare($sql);
                                    $id = $row['id_cliente'];
                                    $q->execute(array(
                                        $id
                                    ));
                                    $cliente = $q->fetch(PDO::FETCH_ASSOC);

                                    $sql = "SELECT * FROM servicos where id = ?";
                                    $q = $pdo->prepare($sql);
                                    $id = $row['id_servico'];
                                    $q->execute(array(
                                        $id
                                    ));
                                    $servico = $q->fetch(PDO::FETCH_ASSOC);

                                    $sql = "SELECT * FROM mercadorias where id = ?";
                                    $q = $pdo->prepare($sql);
                                    $id = $row['id_mercadoria'];
                                    $q->execute(array(
                                        $id
                                    ));
                                    $mercadoria = $q->fetch(PDO::FETCH_ASSOC);

                                    echo '<tr>';
                                    echo '<td>' . $row['id'] . '</td>';
                                    echo '<td>' . $row['data'] . '</td>';
                                    echo '<td>' . $cliente['nome'] . '</td>';
                                    echo '<td>' . $servico['servico'] . '</td>';
                                    echo '<td>' . $mercadoria['mercadoria'] . '</td>';
                                    echo '<td>
										<input type="hidden" value="' . $row['id'] . '" id="hfId" />
										<a href="orcamento.php?id=' . $row['id'] . '" class="btn btn-primary"><i class="fa fa-edit"></i></a>
										<a href="#" class="btn btn-danger"><i class="fa fa-trash"></i></a></td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<script>
$(document).ready(function() {
    $('.btn-excluir').bootstrap_confirm_delete({
        heading: 'Excluir',
        message: 'Deseja excluir este registro?',
        data_type: null,
        callback: function(event) {
            var link = event.data.originalObject;
            var id = link.siblings('#hfId').val();

            $.ajax({
                url: 'consultatarefa.php?excluir=' + id,
                type: 'DELETE',
                success: function(result) {
                    var button = event.data.originalObject;
                    button.closest('tr').remove();
                }
            });
        }
    });
});

$(document).ready(function() {
    $('#myTable').DataTable({
        "language": {
            "sEmptyTable": "N&atilde;o foi encontrado nenhum registo",
            "sLoadingRecords": "A carregar...",
            "sProcessing": "A processar...",
            "sLengthMenu": "Mostrar _MENU_ registos",
            "sZeroRecords": "N&atilde;o foram encontrados resultados",
            "sInfo": "Mostrando de _START_ at&eacute; _END_ de _TOTAL_ registos",
            "sInfoEmpty": "Mostrando de 0 at&eacute; 0 de 0 registos",
            "sInfoFiltered": "(filtrado de _MAX_ registos no total)",
            "sInfoPostFix": "",
            "sSearch": "Procurar:",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "Primeiro",
                "sPrevious": "Anterior",
                "sNext": "Seguinte",
                "sLast": "&Uacute;ltimo"
            },
            "oAria": {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            }
        }
    });
});
</script>
<?php include("footer.php"); ?>