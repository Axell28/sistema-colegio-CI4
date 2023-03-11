<div class="modal-content">
   <form id="frmUsuario" class="needs-validation" onkeypress="return event.keyCode != 13;" autocomplete="off" novalidate>
      <style>
         #boxAlert {
            display: none;
         }
      </style>
      <div class="modal-header">
         <h1 class="modal-title fs-5"><?= esc($action) == 'I' ? 'Registrar Usuario' : 'Actualizar Usuario' ?> </h1>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-4">

         <div class="alert alert-danger" id="boxAlert" role="alert">
            <i class="fad fa-exclamation-circle"></i>
            <span class="ps-1"></span>
         </div>

         <div class="row mb-3 mt-2">
            <label for="txtnombre" class="col-sm-3 col-form-label">Nombre:</label>
            <div class="col-sm-9">
               <input type="text" class="form-control" id="txtnombre" value="<?= $usuarioDatos->nombre ?>" required>
            </div>
         </div>

         <div class="row mb-3">
            <label for="cmbperfil" class="col-sm-3 col-form-label">Perfil:</label>
            <div class="col-sm-9">
               <select class="form-select" id="cmbperfil" name="perfil" required>
                  <option value="">-Seleccione-</option>
                  <?php foreach (@$listaPerfiles as $dato) { ?>
                     <option value="<?= $dato['perfil'] ?>" <?= $usuarioDatos->perfil == $dato['perfil'] ? 'selected' : '' ?> ><?= $dato['nombre'] ?></option>
                  <?php } ?>
               </select>
            </div>
         </div>

         <!-- <div class="row mb-3">
         <label for="txtnombre" class="col-sm-3 col-form-label">Entidad:</label>
         <div class="col-sm-9">
            <input type="text" class="form-control" id="txtnombre" required>
         </div>
      </div> -->

         <!-- <div class="row mb-3">
            <label for="cmbempleado" class="col-sm-3 col-form-label">Persona:</label>
            <div class="col-sm-9">
               <select class="form-select" id="cmbempleado" name="empleado" required>
                  <option value="">-Seleccione-</option>
               </select>
            </div>
         </div> -->

         <div class="row mb-3">
            <label for="txtpassword" class="col-sm-3 col-form-label">Contraseña:</label>
            <div class="col-sm-9">
               <input type="password" class="form-control" id="txtpassword" <?= @$action == 'I' ? 'required' : 'disabled' ?>>
            </div>
         </div>

         <div class="row">
            <div class="col-sm-3"></div>
            <?php if (esc($action == 'E')) { ?>
               <div class="col-sm-6">
                  <div class="form-check form-check-inline form-check-reverse">
                     <input class="form-check-input" type="checkbox" id="chkpassword" style="transform: scale(1.3);">
                     <label class="form-check-label" for="chkpassword">Cambiar contraseña &nbsp;</label>
                  </div>
               </div>
            <?php } ?>
            <div class="col-sm">
               <div class="form-check form-check-inline form-check-reverse">
                  <input class="form-check-input" type="checkbox" id="checkEstado" <?= @$usuarioDatos->estado == 'A' ? 'checked' : '' ?> style="transform: scale(1.3);">
                  <label class="form-check-label" for="checkEstado">Estado activo &nbsp;</label>
               </div>
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
   </form>
</div>
<script>
   (function() {

      const frmUsuario = document.getElementById("frmUsuario");

      function guardarUsuario() {
         let action = '<?= esc($action) ?>';
         let usuario = '<?= @$usuarioDatos->usuario ?>';
         $.ajax({
            type: "POST",
            url: "<?= MODULO_URL ?>/mantenimiento-usuario/json/save",
            data: {
               action,
                usuario,
               nombre: $('#txtnombre').val(),
               perfil: $('#cmbperfil').val(),
               password: $('#txtpassword').val(),
               estado: $('#checkEstado').prop('checked') ? 'A' : 'I',
               cambiarpwd: $(this).prop('checked') ? 'S' : 'N'
            },
            success: function(response) {
               $('#cmbperfilF').change();
               modalUsuarioEvent.hide();
            }
         });
      }

      $('#frmUsuario').submit(function(e) {
         e.preventDefault();
         if (!frmUsuario.checkValidity()) {
            e.stopPropagation();
            showAlertSweet('Debe completar todos los campos obligatorios para continuar', 'warning');
         } else {
            guardarUsuario();
         }
         frmUsuario.classList.add('was-validated');
      });

      $('#verPassword').click(function(e) {
         e.preventDefault();
         let type = $('#txtpassword').attr('type');
         if (type == 'text') {
            $('#txtpassword').attr('type', 'password');
         } else {
            $('#txtpassword').attr('type', 'text');
         }
      });

      $('#chkpassword').change(function(e) {
         e.preventDefault();
         let valor = $(this).prop('checked');
         if (valor) {
             $('#txtpassword').prop('required', true);
             $('#txtpassword').prop('disabled', false);
         } else {
             $('#txtpassword').prop('required', false);
             $('#txtpassword').prop('disabled', true);
         }
          $('#txtpassword').val('');
      });
   })();
</script>