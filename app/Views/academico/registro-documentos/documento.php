<div class="modal-content">
   <form id="frmDocumento" class="needs-validation" onkeypress="return event.keyCode != 13;" autocomplete="off" novalidate>
      <input type="hidden" name="action" id="txtaction" value="<?= @$action ?>">
      <div class="modal-header">
         <h1 class="modal-title fs-5">Documento</h1>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-4">
         <div class="row mb-3">
            <label for="cmbcategoria" class="col-sm-2 col-form-label">Categoría:</label>
            <div class="col-sm-10">
               <select class="form-select" id="cmbcategoria" required>
                  <option value="">-Seleccione-</option>
                  <?php foreach (@$listaCategorias as $value) { ?>
                     <option value="<?= $value['codigo'] ?>"><?= $value['nombre'] ?></option>
                  <?php } ?>
               </select>
               <div class="invalid-feedback">Debe seleccionar una categoría</div>
            </div>
         </div>
         <div class="row mb-3">
            <label for="txtnombre" class="col-sm-2 col-form-label">Nombre:</label>
            <div class="col-sm-10">
               <input type="text" class="form-control" id="txtnombre" required>
               <div class="invalid-feedback">Debe ingresar nombre de documento</div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12">
               <input class="form-control" type="file" id="filedoc" accept="application/pdf, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel, text/plain, .csv" required>
               <div class="invalid-feedback">Debe seleccionar un archivo</div>
            </div>
         </div>
         <!-- <div class="row">
            <div class="col-12">
               <div class="alert alert-warning py-2 mb-0" role="alert">
                  <div class="d-flex align-items-center gap-2">
                     <i class="fas fa-file-alt fs-4"></i>
                     <a href="<?= base_url('img/iconos/files-up.png') ?>" target="_blank" title="Ver archivo" class="alert-link text-truncate" style="font-weight: normal;">
                        &nbsp;Documento de prueba para todos.png
                     </a>
                  </div>
               </div>
            </div>
         </div> -->
      </div>
      <div class="modal-footer">
         <button type="submit" class="btn btn-success w-50" id="btnSave">
            <i class="fas fa-check-circle"></i>
            <span>&nbsp;Guardar datos</span>
         </button>
         <button type="button" class="btn btn-danger flex-fill" id="btnCancel" data-bs-dismiss="modal">
            <i class="fas fa-times-circle"></i>
            <span>&nbsp;Cerrar</span>
         </button>
      </div>
   </form>
</div>
<script>
   (function() {

      const MAXIMO_TAMANIO_BYTES = 5000000;

      const frmDocumento = document.getElementById('frmDocumento');

      function guardarDocumento() {
         const data = new FormData();
         data.append('action', $('#txtaction').val());
         data.append('nombre', $('#txtnombre').val());
         data.append('codcat', $('#cmbcategoria').val());
         data.append('archivo', document.getElementById('filedoc').files[0]);
         data.append('subio_archivo', document.getElementById('filedoc').files.length > 0 ? 'S' : 'N');
         $.ajax({
            type: "POST",
            url: "<?= MODULO_URL ?>/registro-documentos/json/guardar-doc",
            data: data,
            contentType: false,
            processData: false,
            beforeSend: function() {
               $('#btnSave').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span>&nbsp; Espere un momento ..</span>');
               $('#btnCancel, #btnSave, .btn-close').prop('disabled', true);
            },
            success: function(response) {
               if (response.listaDocumentos) {
                  jqxgridDocumentosSource.localdata = response.listaDocumentos;
                  $('#jqxgridDocumentos').jqxGrid('updateBoundData');
                  modalDocumentoEvent.hide();
               }
            },
            complete: function() {
               $('#btnCancel, #btnSave, .btn-close').prop('disabled', false);
               $('#btnSave').html('<i class="fas fa-check-circle"></i><span>&nbsp;Guardar datos</span>');
            }
         });
      }

      $('#frmDocumento').submit(function(e) {
         e.preventDefault();
         if (!frmDocumento.checkValidity()) {
            e.stopPropagation();
         } else {
            guardarDocumento();
         }
         frmDocumento.classList.add('was-validated');
      });

      $('#filedoc').change(function(e) {
         if (e.target.files.length > 0) {
            let archivo = e.target.files[0];
            if (archivo.size > MAXIMO_TAMANIO_BYTES) {
               showAlertSweet('El archivo superá los 5Mb permitidos, no se puede cargar el archivo', 'warning');
               $('#filedoc').val('');
            }
         }
      });

   })();
</script>