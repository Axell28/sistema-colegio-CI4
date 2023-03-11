<?php $curricula = @$dataCurricula; ?>
<style>
   #boxAlert {
      display: none;
   }
</style>
<div class="modal-content">
   <form id="frmAsignacion" class="needs-validation" onkeypress="return event.keyCode != 13;" autocomplete="off" novalidate>
      <div class="modal-header">
         <h1 class="modal-title fs-5"><?= esc($action) == 'I' ? 'Asignar curso curricular' : 'Actualizar curso curricular' ?> </h1>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-4">

         <div class="alert alert-danger" id="boxAlert" role="alert">
            <i class="fad fa-exclamation-circle"></i>
            <span class="ps-1"></span>
         </div>

         <div class="mb-3 row">
            <label for="cmbnivel" class="col-sm-2 col-form-label">Nivel:</label>
            <div class="col-sm-10">
               <select class="form-select" id="cmbnivel" disabled>
                  <?php foreach (@$listaNiveles as $value) { ?>
                     <option value="<?= $value['nivel'] ?>" <?= $value['nivel'] == @$nivel ? 'selected' : '' ?>><?= $value['descripcion'] ?></option>
                  <?php } ?>
               </select>
            </div>
         </div>
         <div class="mb-3 row">
            <label for="cmbgrado" class="col-sm-2 col-form-label">Grado:</label>
            <div class="col-sm-10">
               <select class="form-select" id="cmbgrado" disabled>
                  <?php foreach (@$listaGrados as $value) { ?>
                     <option value="<?= $value['grado'] ?>" <?= $value['grado'] == @$grado ? 'selected' : '' ?>><?= $value['descripcion'] ?></option>
                  <?php } ?>
               </select>
            </div>
         </div>
         <div class="mb-3 row">
            <label for="cmbcurso" class="col-sm-2 col-form-label">Área:</label>
            <div class="col-sm-10">
               <select class="form-select" id="cmbcurso" required>
                  <option value="">-Seleccione-</option>
                  <?php foreach (@$listaCursos as $value) { ?>
                     <option value="<?= $value['codcur'] ?>" <?= $value['codcur'] == @$curso ? 'selected' : '' ?>><?= $value['nombre'] ?></option>
                  <?php } ?>
               </select>
            </div>
         </div>
         <div class="mb-3 row">
            <label for="cmbcursoI" class="col-sm-2 col-form-label">Interno:</label>
            <div class="col-sm-10">
               <select class="form-select" id="cmbcursoI" <?= @$action == 'E' ? 'disabled' : '' ?>>
                  <option value="">-Seleccione-</option>
                  <?php foreach (@$listaCurInt as $value) { ?>
                     <option value="<?= $value['codcur'] ?>" <?= $value['codcur'] == @$cursoI ? 'selected' : '' ?>><?= $value['nombre'] ?></option>
                  <?php } ?>
               </select>
            </div>
         </div>
         <!--
         <div class="mb-3 row">
            <label for="cmbTipCalif" class="col-sm-2 col-form-label">Tipo calif:</label>
            <div class="col-sm-10">
               <select class="form-select" id="cmbTipCalif" required>
                  <option value="">-Seleccione-</option>
                  <option value="L" <?= ''; //isset($curricula->tipcal) ? ($curricula->tipcal == 'L' ? 'selected' : '') : '' ?>>Letra</option>
                  <option value="N" <?= ''; //isset($curricula->tipcal) ? ($curricula->tipcal == 'N' ? 'selected' : '') : '' ?>>Núméro</option>
               </select>
            </div>
         </div> -->
         <div class="mb-3 row">
            <label for="txthoras" class="col-sm-2 col-form-label">Horas:</label>
            <div class="col-sm-2">
               <input type="number" class="form-control" id="txthoras" value="<?= isset($curricula->horas) ? $curricula->horas : '' ?>" min="0">
            </div>
            <label for="txtnotamin" class="col-sm-2 col-form-label">Nota min:</label>
            <div class="col-sm-2">
               <input type="text" class="form-control" id="txtnotamin" value="<?= isset($curricula->nota_min) ? $curricula->nota_min : '' ?>" title="Nota minima" required>
            </div>
            <label for="txtnotamax" class="col-sm-2 col-form-label">Nota max:</label>
            <div class="col-sm-2">
               <input type="text" class="form-control" id="txtnotamax" value="<?= isset($curricula->nota_max) ? $curricula->nota_max : '' ?>" title="Nota maxima" required>
            </div>
         </div>
         <div class="mb-3 row">
            <label for="txtorden" class="col-sm-2 col-form-label">Orden:</label>
            <div class="col-sm-2">
               <input type="number" class="form-control" id="txtorden" value="<?= isset($curricula->orden) ? $curricula->orden : '' ?>" min="0">
            </div>
            <div class="col-sm-2"></div>
            <label for="txtnotaminaprob" class="col-sm-4 col-form-label">Nota mínima aprobatoria:</label>
            <div class="col-sm-2">
               <input type="text" class="form-control" id="txtnotaminaprob" value="<?= isset($curricula->nota_min_aprob) ? $curricula->nota_min_aprob : '' ?>" required>
            </div>
         </div>
      </div>
      <div class="modal-footer">
         <button type="submit" class="btn btn-success flex-fill">
            <i class="fas fa-save"></i>
            <span>&nbsp;Guardar</span>
         </button>
         <button type="button" class="btn btn-danger flex-fill" data-bs-dismiss="modal">
            <i class="fas fa-times-circle"></i>
            <span>&nbsp;Cerrar</span>
         </button>
      </div>
      <input type="hidden" name="anio" id="txtanio" value="<?= @$anio ?>">
      <input type="hidden" name="action" id="txtaction" value="<?= @$action ?>">
   </form>
</div>
<script>
   (function() {

      const frmAsignacion = document.getElementById('frmAsignacion');

      function guardarAsignacion() {
         $.ajax({
            type: "post",
            url: "<?= MODULO_URL ?>/plan-curricular/json/guardar",
            data: {
               action: $('#txtaction').val(),
               anio: $('#txtanio').val(),
               nivel: $('#cmbnivel').val(),
               grado: $('#cmbgrado').val(),
               curso: $('#cmbcurso').val(),
               cursoI: $('#cmbcursoI').val(),
               tipcal: $('#cmbTipCalif').val(),
               orden: $('#txtorden').val(),
               horas: $('#txthoras').val(),
               nota_min: $('#txtnotamin').val(),
               nota_max: $('#txtnotamax').val(),
               nota_min_aprob: $('#txtnotaminaprob').val()
            },
            success: function(response) {
               if (response.listaCurriculoNG) {
                  jqxgridCurriculoSource.localdata = response.listaCurriculoNG;
                  $(jqxgridCurriculo).jqxGrid('updateBoundData');
                  $(jqxgridCurriculo).jqxGrid('hideloadelement');
               }
               modalCurriculaEvent.hide();
            },
            error: function(jqXHR) {
               if (jqXHR.responseJSON) {
                  let erroMsg = jqXHR.responseJSON.message;
                  $('#boxAlert').show();
                  $('#boxAlert span').text(erroMsg);
               }
            },
         });
      }

      $('.js-example-basic-multiple').select2({
         width: '100%',
         dropdownParent: $("#frmAsignacion"),
         theme: 'bootstrap-5',
      });

      $('#frmAsignacion').submit(function(e) {
         e.preventDefault();
         if (!frmAsignacion.checkValidity()) {
            e.stopPropagation();
         } else {
            guardarAsignacion();
         }
         frmAsignacion.classList.add('was-validated');
      });

      $('#cmbTipCalif').change(function() {
         const tipo = $(this).val();
         const notaMin = tipo == 'L' ? 'C' : (tipo == 'N' ? '05' : '');
         const notaMax = tipo == 'L' ? 'AD' : (tipo == 'N' ? '20' : '');
         const notaApb = tipo == 'L' ? 'B' : (tipo == 'N' ? '11' : '');
         $('#txtnotamin').val(notaMin);
         $('#txtnotamax').val(notaMax);
         $('#txtnotaminaprob').val(notaApb);
      });
   })();
</script>