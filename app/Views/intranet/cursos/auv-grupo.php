<div class="modal-content">
     <!-- <div class="modal-header">
          <h1 class="modal-title fs-5">Agregar Grupo</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
     </div> -->
     <div class="modal-body px-4">
          <div class="my-3">
               <label for="txttitulo" class="form-label">Titulo de grupo:</label>
               <input type="text" class="form-control" id="txttitulo" autocomplete="off">
          </div>
          <div class="form-check ms-1">
               <input class="form-check-input" type="checkbox" id="checkOcultar" style="transform: scale(1.3);">
               <label class="form-check-label" for="checkOcultar">
                    &nbsp;Ocultar grupo para los alumnos
               </label>
          </div>
     </div>
     <div class="modal-footer">
          <button type="button" id="btnGuardarGrupo" disabled class="btn btn-success flex-fill">
               <i class="fas fa-save"></i>
               <span>&nbsp;Guardar</span>
          </button>
          <button type="button" class="btn btn-danger flex-fill" data-bs-dismiss="modal">
               <i class="fas fa-times-circle"></i>
               <span>&nbsp;Cerrar</span>
          </button>
     </div>
</div>
<script>
     (function() {
          function guardarGrupo() {
               $.ajax({
                    type: "POST",
                    url: "<?= MODULO_URL ?>/cursos/json/save-grupo",
                    data: {
                         action: '<?= @$action ?>',
                         codigo: '<?= @$codigo ?>',
                         salon: '<?= @$salon ?>',
                         curso: '<?= @$curso ?>',
                         periodo: '<?= @$periodo ?>',
                         titulo: $('#txttitulo').val(),
                         ocultar: $('#checkOcultar').prop('checked') ? 'S' : 'N'
                    },
                    success: function(response) {
                         if (response.listaAuvGrupos) {
                              arrayAuvGrupos['P<?= @$periodo ?>'] = response.listaAuvGrupos;
                              listarTemplateAccordion();
                              modalAuvGrupoEvent.hide();
                         }
                    }
               });
          }

          $('#txttitulo').on('keyup', function() {
               let empty = $(this).val().length == 0;
               if (empty)
                    $('#btnGuardarGrupo').prop('disabled', true);
               else
                    $('#btnGuardarGrupo').prop('disabled', false);
          });

          $('#btnGuardarGrupo').click(function(e) {
               guardarGrupo();
          });

          <?php if (@$action == 'E') { ?>
               $('#txttitulo').val('<?= @$datosGrupo['titulo'] ?>');
               $('#checkOcultar').prop('checked', <?= @$datosGrupo['ocultar'] == 'N' ? 'false' : 'true' ?>);
               $('#btnGuardarGrupo').prop('disabled', false);
          <?php } ?>
     })();
</script>