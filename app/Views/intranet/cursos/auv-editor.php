<div class="modal-content">
     <div class="modal-header">
          <h1 class="modal-title fs-5">Asignar contenido</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
     </div>
     <div class="modal-body px-4">
          <div class="row">
               <div class="col-sm">
                    <textarea id="editor"></textarea>
               </div>
               <div class="col-sm-4">
                    <div class="card bg-light">
                         <div class="card-body">
                              <p class="text-muted fw-bold">INFORMACIÓN</p>
                              <div class="row my-3">
                                   <div class="col-sm">
                                        <label for="mtitulo" class="form-label">Titulo:</label>
                                        <input type="text" class="form-control" id="mtitulo" autocomplete="off">
                                   </div>
                              </div>
                              <div class="row my-3">
                                   <div class="col-sm">
                                        <label for="mtipo" class="form-label">Tipo:</label>
                                        <select class="form-select" id="mtipo">
                                             <option value="">-Seleccione-</option>
                                             <?php foreach (@$listaTiposItems as $val) { ?>
                                                  <option value="<?= $val['codigo'] ?>"><?= $val['descripcion'] ?></option>
                                             <?php } ?>
                                        </select>
                                   </div>
                              </div>
                              <div class="row my-3">
                                   <div class="col-sm">
                                        <label for="mfecpub" class="form-label">Fecha publicación:</label>
                                        <input type="datetime-local" class="form-control" id="mfecpub" value="<?= date('Y-m-d\TH:i') ?>">
                                   </div>
                              </div>
                              <div class="row my-3">
                                   <div class="col-sm">
                                        <label for="mfecmax" class="form-label">Fecha max. entrega:</label>
                                        <input type="datetime-local" class="form-control" id="mfecmax" value="<?= date('Y-m-d\TH:i') ?>" disabled>
                                   </div>
                              </div>
                              <div class="row my-3">
                                   <div class="col-sm">
                                        <label for="madjuntos" class="form-label">Adjuntos:</label>
                                        <input class="form-control" type="file" id="madjuntos" multiple>
                                   </div>
                              </div>
                              <div class="row mt-4">
                                   <div class="col-sm">
                                        <button class="btn btn-primary w-100" id="btnGuardarItem" disabled>
                                             <i class="fas fa-save"></i>
                                             <span>&nbsp;Guardar</span>
                                        </button>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>
<script>
     (function() {
          // run tinymce
          tinymce.init({
               selector: '#editor',
               language: "es",
               encoding: 'UTF-8',
               plugins: 'link media table image emoticons advlist lists code table template example paste table',
               toolbar: 'formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | numlist bullist checklist | forecolor backcolor | link media image pageembed emoticons | table | removeformat',
               menubar: false,
               content_style: '@import url("https://fonts.googleapis.com/css2?family=Lexend+Deca&display=swap"); body { font-family: "Lexend Deca", sans-serif; font-size: 14px; line-height: 1.6; }',
               height: '580',
               object_resizing: true,
               fix_list_elements: true,
               media_dimensions: true,
               forced_root_block: 'div',
               paste_as_text: true,
               paste_remove_styles: true,
               paste_remove_styles_if_webkit: true,
               default_link_target: "_blank",
          });

          $('#mtitulo').on('keyup', function() {
               let empty = $(this).val().length == 0 || $('#mtipo').val() == "";
               if (empty)
                    $('#btnGuardarItem').prop('disabled', true);
               else
                    $('#btnGuardarItem').prop('disabled', false);
          });

          $('#mtipo').change(function(e) {
               if ($(this).val() == "" || $('#mtitulo').val().length == 0) {
                    $('#btnGuardarItem').prop('disabled', true);
               } else {
                    $('#btnGuardarItem').prop('disabled', false);
               }
               if ($(this).val() == 'E' || $(this).val() == 'T') {
                    $('#mfecmax').prop('disabled', false);
               } else {
                    $('#mfecmax').prop('disabled', true);
               }
          });

          $('#btnGuardarItem').click(function(e) {
               e.preventDefault();
               if (($('#mtipo').val() == "E" || $('#mtipo').val() == "T") && $('#mfecmax').val() == "") {
                    showAlertSweet('Debe seleccionar la fecha de máxima de entrega!', 'warning');
                    return;
               }
               const filesUp = document.getElementById('madjuntos').files;
               const form = new FormData();
               form.append('action', 'I');
               form.append('grupo', '<?= @$grupo ?>');
               form.append('titulo', $('#mtitulo').val());
               form.append('tipo', $('#mtipo').val());
               form.append('cuerpo', tinyMCE.get('editor').getContent());
               form.append('fecpub', $('#mfecpub').val());
               form.append('fecmax', $('#mtipo').val() == 'T' ? $('#mfecmax').val() : null);
               form.append('cargoArchivos', filesUp.length > 0 ? 'S' : 'N');
               $.each(filesUp, function(index, value) {
                    form.append('adjuntos[]', value);
               });
               $.ajax({
                    type: "POST",
                    url: "<?= MODULO_URL ?>/cursos/json/guardar-item",
                    data: form,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                         listaAuvGrupoItems('<?= @$grupo  ?>');
                         modalAuvItemsEditorEvent.hide();
                    }
               });
          });
     })();
</script>