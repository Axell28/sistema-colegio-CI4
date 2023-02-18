<div class="modal-content">
   <div class="modal-header">
      <h1 class="modal-title fs-5">Mi Perfil</h1>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
   </div>
   <div class="modal-body px-4">
      <div class="text-center">
         <img src="<?= base_url() . esc($usuario_photo) ?>" width="150" height="150" style="border-radius: 50%;">
      </div>
      <div class="row pt-3">
         <div class="col-md-12 my-2">
            <label for="" class="form-label">Nombre de usuario:</label>
            <input type="text" class="form-control" value="<?= esc($datosUsuario['nombre']) ?>" disabled>
         </div>
      </div>
      <div id="panel-01-perfil">
         <div class="row">
            <div class="col-md-12 my-2">
               <label for="" class="form-label">Perfil:</label>
               <select class="form-select" disabled>
                  <option value="">-Ninguno-</option>
                  <?php foreach (esc($listaPerfiles) as $value) { ?>
                     <option value="<?= $value['perfil'] ?>" <?= $value['perfil'] == esc($datosUsuario['perfil']) ? 'selected' : '' ?>>
                        <?= $value['nombre'] ?>
                     </option>
                  <?php } ?>
               </select>
            </div>
         </div>
         <div class="row">
            <div class="col-md-6 my-2">
               <label for="" class="form-label">Código:</label>
               <input type="text" class="form-control" value="<?= esc($datosUsuario['codigo']) ?>" disabled>
            </div>
            <div class="col-md-6 my-2">
               <label for="" class="form-label">Última conexión:</label>
               <input type="text" class="form-control" value="<?= esc($datosUsuario['ultcon']) ?>" disabled>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12 my-2">
               <label for="" class="form-label">Contraseña:</label>
               <input type="password" class="form-control" value="12345-12345" disabled>
            </div>
         </div>
         <div class="row mt-1">
            <div class="col text-end">
               <a href="javascript:void(0)" id="changePwd">Cambiar contraseña</a>
            </div>
         </div>
      </div>
      <div id="panel-02-perfil">
         <div class="row">
            <div class="col-md-12 my-2">
               <label for="pwd_actual" class="form-label">Contraseña actual:</label>
               <input type="password" id="pwd_actual" class="form-control" value="">
            </div>
         </div>
         <div class="row">
            <div class="col-md-12 my-2">
               <label for="pwd_nuevo" class="form-label">Nueva Contraseña:</label>
               <input type="password" id="pwd_nuevo" class="form-control" value="">
            </div>
         </div>
         <div class="row pt-2">
            <div class="col-md-6 my-2">
               <button class="btn btn-primary w-100">
                  Actualizar
               </button>
            </div>
            <div class="col-md-6 my-2">
               <button class="btn btn-danger w-100" id="btnCancelarPwd">
                  Cancelar
               </button>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
   (function() {

      $('#changePwd').on('click', function() {
         $('#panel-01-perfil').slideUp();
         $('#panel-02-perfil').slideDown();
      });

      $('#btnCancelarPwd').on('click', function() {
         $('#panel-02-perfil').slideUp();
         $('#panel-01-perfil').slideDown();
      });

   })();
</script>