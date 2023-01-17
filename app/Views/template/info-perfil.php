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
            <a href="javascript:void(0)">Cambiar contraseña</a>
         </div>
      </div>
   </div>
</div>