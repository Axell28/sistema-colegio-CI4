<div class="modal-content">
   <div class="modal-header">
      <h1 class="modal-title fs-5">Asignar Docente</h1>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
   </div>
   <div class="modal-body px-4">
      <div class="row mt-2 mb-3">
         <label for="cmbsalon" class="col-sm-2 col-form-label">Salón:</label>
         <div class="col-sm-10">
            <select class="form-select" id="cmbsalon" disabled>
               <?php foreach (@$listaSalones as $value) { ?>
                  <option value="<?= $value['salon'] ?>" <?= $value['salon'] == @$salon ? 'selected' : '' ?>><?= $value['nombre'] ?></option>
               <?php } ?>
            </select>
         </div>
      </div>
      <div class="row mb-3">
         <label for="cmbcurso" class="col-sm-2 col-form-label">Curso:</label>
         <div class="col-sm-10">
            <select class="form-select" id="cmbcurso" disabled>
               <?php foreach (@$listaCursos as $value) { ?>
                  <option value="<?= $value['codcur'] ?>" <?= $value['codcur'] == @$codcur ? 'selected' : '' ?>><?= $value['nombre'] ?></option>
               <?php } ?>
            </select>
         </div>
      </div>
      <div class="row mb-1">
         <label for="cmbdocente" class="col-sm-2 col-form-label">Docente:</label>
         <div class="col-sm-10">
            <select class="form-select-2-modal" id="cmbdocente">
               <option value="">-Seleccione-</option>
               <?php foreach (@$listaDocentes as $value) { ?>
                  <option value="<?= $value['codigo'] ?>" <?= $value['codigo'] == @$docente ? 'selected' : '' ?> ><?= $value['nombre'] ?></option>
               <?php } ?>
            </select>
         </div>
      </div>
   </div>
   <div class="modal-footer">
      <button type="button" id="btnSaveAsig" class="btn btn-success flex-fill">
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
      $('.form-select-2-modal').select2({
         width: '100%',
         theme: 'bootstrap-5',
         dropdownParent: $("#modalAsignacion"),
      });

      $('#btnSaveAsig').on('click', function() {
         $.ajax({
            type: "POST",
            url: "<?= MODULO_URL ?>/asignacion-curso/json/guardar",
            data: {
               anioF: $('#cmbAnioF').val(),
               nivelF: $('#cmbNivelF').val(),
               gradoF: $('#cmbGradoF').val(),
               seccionF: $('#cmbSeccionF').val(),
               cursoF: $('#cmbCursoF').val(),
               docenteF: $('#cmbDocenteF').val(),
               salon: $('#cmbsalon').val(),
               curso: $('#cmbcurso').val(),
               docente: $('#cmbdocente').val()
            },
            success: function(response) {
               if (response.listaCursosAsignados) {
                  jqxgridAsigCursosSource.localdata = response.listaCursosAsignados;
                  $(jqxgridAsigCursos).jqxGrid('updateBoundData', 'data');
                  modalAsignacionEvent.hide();
               }
            },
            error: function(jqXHR) {
               if (jqXHR.responseJSON) {
                  let erroMsg = jqXHR.responseJSON.message;
                  console.error(erroMsg);
               }
            }
         });
      });
   })();
</script>