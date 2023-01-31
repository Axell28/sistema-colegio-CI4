<?php
$salon = esc($datosSalon);
?>
<style>
   #boxAlert {
      display: none;
   }
</style>
<div class="modal-content">
   <form id="frmSalon" class="needs-validation" autocomplete="off" novalidate>
      <input type="hidden" name="action" id="action" value="I">
      <div class="modal-header">
         <h1 class="modal-title fs-5">Info. del salón</h1>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-4">

         <div class="alert alert-danger" id="boxAlert" role="alert">
            <i class="fad fa-exclamation-circle"></i>
            <span class="ps-1"></span>
         </div>

         <div class="row">
            <div class="col-sm-4 my-2">
               <label for="txtsalon" class="form-label">Salón:</label>
               <input type="text" class="form-control text-center" name="salon" value="<?= isset($salon['salon']) ? $salon['salon'] : null ?>" id="txtsalon" disabled>
            </div>
            <div class="col-sm-4 my-2">
               <label for="txtanio" class="form-label">Año:</label>
               <input type="text" class="form-control text-center" name="anio" id="txtanio" value="<?= esc($anio) ?>" required disabled>
            </div>
            <div class="col-sm-4 my-2">
               <label for="txtaula" class="form-label">Aula:</label>
               <input type="text" class="form-control text-center" name="aula" value="<?= isset($salon['aula']) ? $salon['aula'] : null ?>" id="txtaula" required>
               <div class="invalid-feedback">Ingrese el aula</div>
            </div>
         </div>
         <div class="row">
            <div class="col my-2">
               <label for="txtnombre" class="form-label">Nombre:</label>
               <input type="text" class="form-control" name="nombre" id="txtnombre" value="<?= isset($salon['nombre']) ? $salon['nombre'] : null ?>" required>
               <div class="invalid-feedback">Ingrese nombre del salón</div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-4 my-2">
               <label for="cmbnivel" class="form-label">Nivel:</label>
               <select class="form-select" name="nivel" id="cmbnivel" required>
                  <option value="">-Seleccione-</option>
                  <?php foreach (@$listaNiveles as $value) {  ?>
                     <option value="<?= $value['nivel'] ?>">
                        <?= $value['descripcion'] ?>
                     </option>
                  <?php } ?>
               </select>
               <div class="invalid-feedback">Seleccione nivel</div>
            </div>
            <div class="col-md-4 my-2">
               <label for="cmbgrado" class="form-label">Grado:</label>
               <select class="form-select" name="grado" id="cmbgrado" required>
                  <option value="">-Seleccione-</option>
               </select>
               <div class="invalid-feedback">Seleccione grado</div>
            </div>
            <div class="col-md-4 my-2">
               <label for="cmbseccion" class="form-label">Sección:</label>
               <select class="form-select" name="seccion" id="cmbseccion" required>
                  <option value="">-Seleccione-</option>
               </select>
               <div class="invalid-feedback">Seleccione sección</div>
            </div>
         </div>
         <div class="row">
            <div class="col my-2">
               <label for="cmbtutor" class="form-label">Tutor(a):</label>
               <select class="form-select-2" name="tutor" id="cmbtutor">
                  <option value="">-Seleccione-</option>
                  <?php foreach (@$listaDocentes as $value) {  ?>
                     <option value="<?= $value['codigo'] ?>">
                        <?= $value['nombre'] ?>
                     </option>
                  <?php } ?>
               </select>
               <div class="invalid-feedback">Seleccione el tutor(a) encargado</div>
            </div>
         </div>
         <div class="row">
            <div class="col my-2">
               <label for="cmbcotutor" class="form-label">Co-Tutor(a):</label>
               <select class="form-select-2" name="cotutor" id="cmbcotutor">
                  <option value="">-Seleccione-</option>
                  <?php foreach (@$listaDocentes as $value) {  ?>
                     <option value="<?= $value['codigo'] ?>">
                        <?= $value['nombre'] ?>
                     </option>
                  <?php } ?>
               </select>
            </div>
         </div>
         <div class="row">
            <div class="col my-2">
               <label for="cmbcoordinador" class="form-label">Coordinador(a):</label>
               <select class="form-select-2" name="coordinador" id="cmbcoordinador">
                  <option value="">-Seleccione-</option>
                  <?php foreach (@$listaEmpleados as $value) {  ?>
                     <option value="<?= $value['codigo'] ?>">
                        <?= $value['nombre'] ?>
                     </option>
                  <?php } ?>
               </select>
            </div>
         </div>
         <div class="row">
            <div class="col-md-4 my-2">
               <label for="txtvacantes" class="form-label">Nro de vacantes:</label>
               <input type="number" class="form-control" name="vacantes" id="txtvacantes" value="<?= isset($salon['vacantes']) ? $salon['vacantes'] : 0 ?>" min="1" max="40" maxlength="2">
               <div class="invalid-feedback">Ingrese el total de vacantes disponibles</div>
            </div>
            <div class="col-md-4 my-2">
               <label for="cmbmodalidad" class="form-label">Modalidad:</label>
               <select class="form-select" name="modalidad" id="cmbmodalidad">
                  <option value="">-Seleccione-</option>
                  <?php foreach (@$modalidades as $dato) {
                     $modalidad = isset($salon['modalidad']) ? $salon['modalidad'] : '';
                  ?>
                     <option value="<?= $dato['codigo'] ?>" <?= $dato['codigo'] == $modalidad ? 'selected' : '' ?>>
                        <?= $dato['descripcion'] ?>
                     </option>
                  <?php } ?>
               </select>
            </div>
            <div class="col-md-4 my-2">
               <label for="cmbturno" class="form-label">Turno:</label>
               <select class="form-select" name="turno" id="cmbturno">
                  <option value="">-Seleccione-</option>
                  <?php foreach (@$turnos as $dato) {
                     $turno = isset($salon['turno']) ? $salon['turno'] : '';
                  ?>
                     <option value="<?= $dato['codigo'] ?>" <?= $dato['codigo'] == $turno ? 'selected' : '' ?>>
                        <?= $dato['descripcion'] ?>
                     </option>
                  <?php } ?>
               </select>
            </div>
         </div>
      </div>
      <div class="modal-footer">
         <button type="submit" class="btn btn-success w-50">
            <i class="fas fa-check-circle"></i>
            <span>&nbsp;Guardar datos</span>
         </button>
         <button type="button" class="btn btn-danger flex-fill" data-bs-dismiss="modal">
            <i class="fas fa-times-circle"></i>
            <span>&nbsp;Cerrar</span>
         </button>
      </div>
   </form>
</div>
<script>
   (function() {

      const frmSalon = document.getElementById('frmSalon');
      const listaGrados = JSON.parse(`<?= json_encode(@$listaGrados) ?>`);
      const listaSecciones = JSON.parse(`<?= json_encode(@$listaSecciones) ?>`);

      $('.form-select-2').select2({
         width: '100%',
         dropdownParent: $("#modalRegistro"),
         theme: 'bootstrap-5',
      });

      function guardarDatosSalon() {
         let action = '<?= esc($action) ?>';
         $.ajax({
            type: "post",
            url: "<?= MODULO_URL ?>/salones/json/guardar",
            data: {
               action: action,
               salon: $('#txtsalon').val(),
               anio: $('#txtanio').val(),
               aula: $('#txtaula').val(),
               nombre: $('#txtnombre').val(),
               nivel: $('#cmbnivel').val(),
               grado: $('#cmbgrado').val(),
               seccion: $('#cmbseccion').val(),
               tutor: $('#cmbtutor').val(),
               cotutor: $('#cmbcotutor').val(),
               coordinador: $('#cmbcoordinador').val(),
               vacantes: $('#txtvacantes').val(),
               modalidad: $('#cmbmodalidad').val(),
               turno: $('#cmbturno').val()
            },
            success: function(response) {
               if (response.listaSalones) {
                  jqxgridSalonesSource.localdata = response.listaSalones;
                  $(jqxgridSalones).jqxGrid('updateBoundData');
                  modalRegistroEvent.hide();
               }
            },
            error: function(jqXHR) {
               if (jqXHR.responseJSON) {
                  let erroMsg = jqXHR.responseJSON.message;
                  $('#boxAlert').slideDown();
                  $('#boxAlert span').text(erroMsg);
               }
            }
         });
      }

      $('#frmSalon').submit(function(e) {
         e.preventDefault();
         if (!frmSalon.checkValidity()) {
            e.stopPropagation();
         } else {
            guardarDatosSalon();
         }
         frmSalon.classList.add('was-validated');
      });

      $('#cmbnivel').change(function(e) {
         e.preventDefault();
         let list = '<option>-Seleccione-</option>';
         let nivel = $(this).val();
         let grados = listaGrados[nivel] ? listaGrados[nivel] : [];
         $.each(grados, function(index, value) {
            list += `<option value="${value.grado}">${value.descripcion}</option>`;
         });
         $('#cmbgrado').html(list);
      });

      $('#cmbgrado').change(function(e) {
         e.preventDefault();
         let list = '<option>-Seleccione-</option>';
         let nivel = $('#cmbnivel').val();
         let grado = $(this).val();
         let secciones = listaSecciones[nivel][grado] ? listaSecciones[nivel][grado] : [];
         $.each(secciones, function(index, value) {
            list += `<option value="${value.seccion}">${value.descripcion}</option>`;
         });
         $('#cmbseccion').html(list);
      });

      <?php if (!empty($salon)) { ?>
         $('#cmbnivel').val(`<?= isset($salon['nivel']) ? $salon['nivel'] : '' ?>`);
         $('#cmbnivel').change();
         $('#cmbgrado').val(`<?= isset($salon['grado']) ? $salon['grado'] : '' ?>`);
         $('#cmbgrado').change();
         $('#cmbseccion').val(`<?= isset($salon['seccion']) ? $salon['seccion'] : '' ?>`);
         $('#cmbtutor').val(`<?= isset($salon['tutor']) ? $salon['tutor'] : '' ?>`);
         $('#cmbtutor').change();
         $('#cmbcotutor').val(`<?= isset($salon['cotutor']) ? $salon['cotutor'] : '' ?>`);
         $('#cmbcotutor').change();
      <?php } ?>
   })();
</script>