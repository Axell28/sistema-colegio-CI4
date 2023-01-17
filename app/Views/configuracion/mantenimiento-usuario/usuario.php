<div class="modal-content">
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
            <input type="text" class="form-control" id="txtnombre" required>
         </div>
      </div>

      <div class="row mb-3">
         <label for="cmbperfil" class="col-sm-3 col-form-label">Perfil:</label>
         <div class="col-sm-9">
            <select class="form-select" id="cmbperfil" name="perfil" required>
               <option value="">-Seleccione-</option>
               <?php foreach (@$listaPerfiles as $dato) { ?>
                  <option value="<?= $dato['perfil'] ?>"><?= $dato['nombre'] ?></option>
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

      <div class="row mb-3">
         <label for="cmbempleado" class="col-sm-3 col-form-label">Persona:</label>
         <div class="col-sm-9">
            <select class="form-select" id="cmbempleado" name="empleado" required>
               <option value="">-Seleccione-</option>
            </select>
         </div>
      </div>

      <div class="row mb-2">
         <label for="txtpassword" class="col-sm-3 col-form-label">Contraseña:</label>
         <div class="col-sm-9">
            <div class="input-group mb-3">
               <input type="password" class="form-control" id="txtpassword" required>
               <button class="btn btn-outline-primary"><i class="fas fa-eye-slash"></i></button>
            </div>
         </div>
      </div>

      <div class="row">
         <div class="col">
            <div class="form-check form-switch form-check-reverse">
               <input class="form-check-input" type="checkbox" id="checkEstado" style="transform: scale(1.3);">
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
</div>