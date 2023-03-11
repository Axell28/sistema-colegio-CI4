<div class="modal-content">
     <div class="modal-header">
          <h1 class="modal-title fs-5">Enviar resolución</h1>
     </div>
     <div class="modal-body text-start px-4">
          <div class="mb-3 row">
               <div class="col-sm-12">
                    <label for="txtreparea" class="form-label">Comentario:</label>
                    <textarea class="form-control" id="txtreparea" rows="3"></textarea>
               </div>
          </div>
          <div class="mb-4 row">
               <div class="col-sm-12">
                    <label for="formFile" class="form-label">Adjuntar archivo:</label>
                    <input class="form-control" type="file" multiple id="formFile">
               </div>
          </div>
          <div class="row mt-3">
               <div class="col-sm-12">
                    <button class="btn btn-success text-white w-100" id="btnEnviarResp" disabled>
                         <span>Enviar resolución&nbsp;</span>
                         <i class="far fa-arrow-right"></i>
                    </button>
               </div>
          </div>
     </div>
</div>
<script>
     (function() {

          const modalRespuesta2Event = new bootstrap.Modal(document.getElementById('modalRespuesta'), {
               keyboard: false,
               backdrop: 'static'
          });

          $('#btnEnviarResp').on('click', function() {
               const form = new FormData();
               const filesUp = document.getElementById('formFile').files;
               form.append('salon', '<?= @$salon ?>');
               form.append('grupo', '<?= @$grupo ?>');
               form.append('auvitem', '<?= @$auvitem ?>');
               form.append('comentario', $('#txtreparea').val());
               $.each(filesUp, function(index, value) {
                    form.append('adjuntos[]', value);
               });
               $.ajax({
                    type: "POST",
                    url: "<?= MODULO_URL ?>/cursos/json/enviar-resp",
                    data: form,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                         $('#btnEnviarResp').prop('disabled', true);
                    },
                    success: function(response) {
                         $('#btnEnviarResp').prop('disabled', false);
                    },
                    error: function(jqXHR) {
                         if (jqXHR.responseJSON) {
                              let erroMsg = jqXHR.responseJSON.message;
                              showAlertSweet(erroMsg, 'error');
                         }
                    },
                    complete: function() {
                         modalRespuesta2Event.hide();
                         listaAuvGrupoItems('<?= @$grupo ?>');
                    }
               });
          });

          $('#formFile').change(function(e) {
               let valor = document.getElementById('formFile').files.length;
               if (valor > 0) {
                    $('#btnEnviarResp').prop('disabled', false);
               } else {
                    $('#btnEnviarResp').prop('disabled', true);
               }
          });

     })();
</script>