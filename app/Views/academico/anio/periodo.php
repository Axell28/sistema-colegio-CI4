<div class="modal-content">

     <style>
          #boxAlert2 {
               display: none;
          }
     </style>

     <form id="frmPeriodo" class="needs-validation" onkeypress="return event.keyCode != 13;" autocomplete="off" novalidate>
          <input type="hidden" name="action" id="txtaction" value="">
          <div class="modal-header">
               <h1 class="modal-title fs-5">Asignar Periodo</h1>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-3">

               <div class="alert alert-danger" id="boxAlert2" role="alert">
                    <i class="fad fa-exclamation-circle"></i>
                    <span class="ps-1">99</span>
               </div>

               <div class="row">
                    <div class="col px-3">
                         <div class="mb-3 row">
                              <label for="txtaniop" class="col-sm-3 col-form-label">Año:</label>
                              <div class="col-sm-9">
                                   <input type="number" class="form-control" id="txtaniop" min="1789" value="<?= @$anio ?>" minlength="4" maxlength="4" disabled>
                              </div>
                         </div>
                         <div class="mb-3 row">
                              <label for="txtperiodop" class="col-sm-3 col-form-label">Periodo:</label>
                              <div class="col-sm-3">
                                   <input type="number" class="form-control text-center" id="txtperiodop" min="1" max="4" minlength="1" maxlength="1" required>
                                   <div class="invalid-feedback">Ingrese el número del periodo</div>
                              </div>
                              <label for="cmbperiodo" class="col-sm-2 col-form-label text-end">Tipo:</label>
                              <div class="col-sm">
                                   <select class="form-select" id="cmbperiodo" required>
                                        <?php foreach (@$listaPeriodosTipo as $value) { ?>
                                             <option value="<?= $value['codigo'] ?>"><?= $value['descripcion'] ?></option>
                                        <?php } ?>
                                   </select>
                              </div>
                         </div>
                         <div class="mb-3 row">
                              <label for="txtfecinip" class="col-sm-3 col-form-label">Fecha Inicio:</label>
                              <div class="col-sm-9">
                                   <input type="date" class="form-control" id="txtfecinip" required>
                                   <div class="invalid-feedback">Seleccione la fecha inicio del periodo</div>
                              </div>
                         </div>
                         <div class="row">
                              <label for="txtfecfinp" class="col-sm-3 col-form-label">Fecha Fin:</label>
                              <div class="col-sm-9">
                                   <input type="date" class="form-control" id="txtfecfinp" required>
                                   <div class="invalid-feedback">Seleccione la fecha fin del periiodo</div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
          <div class="modal-footer">
               <button type="submit" class="btn btn-success w-50" id="btnSave">
                    <i class="fas fa-check-circle"></i>
                    <span>&nbsp;Aceptar</span>
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

          const frmPeriodo = document.getElementById('frmPeriodo');

          function guardarPeriodo() {
               $.ajax({
                    type: "POST",
                    url: "<?= MODULO_URL ?>/anio-academico/json/save-periodo",
                    data: {
                         action: '<?= esc($action) ?>',
                         anio: $('#txtaniop').val(),
                         tipo: $('#cmbperiodo').val(),
                         periodo: $('#txtperiodop').val(),
                         fecini: $('#txtfecinip').val(),
                         fecfin: $('#txtfecfinp').val()
                    },
                    success: function(response) {
                         if (response.listaAnioPeriodos) {
                              jqxgridAnioPeriodoSource.localdata = response.listaAnioPeriodos;
                              $(jqxgridAnioPeriodo).jqxGrid('updateBoundData');
                              modalPeriodoEvent.hide();
                         }
                    },
                    error: function(jqXHR) {
                         if (jqXHR.responseJSON) {
                              let erroMsg = jqXHR.responseJSON.message;
                              $('#boxAlert2 span').text(erroMsg);
                              $('#boxAlert2').slideDown();
                         }
                    },
               });
          }

          $('#frmPeriodo').submit(function(e) {
               e.preventDefault();
               if (!frmPeriodo.checkValidity()) {
                    e.stopPropagation();
               } else {
                    guardarPeriodo();
               }
               frmPeriodo.classList.add('was-validated');
          });

          <?php if (@$action == 'E') { ?>
               $('#cmbperiodo').val('<?= @$dataPeriodo['tipo'] ?>');
               $('#txtperiodop').val('<?= @$dataPeriodo['periodo'] ?>');
               $('#txtfecinip').val('<?= @$dataPeriodo['fecini'] ?>');
               $('#txtfecfinp').val('<?= @$dataPeriodo['fecfin'] ?>');
               $('#txtperiodop').prop('disabled', true);
          <?php } ?>

     })();
</script>