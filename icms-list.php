<?php include("header.php"); ?>

<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Cadastro de icms</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="icms.php" class="btn btn-primary btn-block">Adicionar
                                icms</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table" id="myTable">
                            <thead class=" text-primary">
                                <th>ICMS</th>
                                <th>Percentual</th>
                                <th>A&ccedil;&otilde;es</th>
                            </thead>
                            <tbody>
                                <?php
								include 'banco.php';
								$pdo = Banco::conectar();
								$sql = 'select id, icms, percentual from icms ORDER BY icms';

								foreach ($pdo->query($sql) as $row) {
									echo '<tr>';
									echo '<td>' . $row['icms'] . '</td>';
									echo '<td>' . $row['percentual'] . '</td>';
									echo '<td>
										<input type="hidden" value="' . $row['id'] . '" id="hfId" />
										<a href="icms.php?id=' . $row['id'] . '" class="btn btn-primary"><i class="fa fa-edit"></i></a>
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