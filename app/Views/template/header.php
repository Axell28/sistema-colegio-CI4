<header class="header">
   <button class="btn-opt px-3" style="padding-top: 5px;" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Encoger Menú" onclick="document.querySelector('aside.menu').classList.toggle('close')">
      <i class="fas fa-bars"></i>
   </button>
   <div class="d-flex" style="height: 100%;">
      <div class="vr" style="color: rgb(180, 180, 180);"></div>
   </div>
   <div class="dropdown">
      <button class="btn-opt px-3" type="button" data-bs-toggle="dropdown" aria-expanded="false">
         <span><?= @$layout_mod_name ?>&nbsp;</span>
         <span class="far fa-chevron-down"></span>
      </button>
      <?php if (count($layout_modulos) > 1) { ?>
         <ul class="dropdown-menu">
            <?php foreach (@$layout_modulos as $value) {
               if ($value['codmod'] == MODULO) {
                  continue;
               }
            ?>
               <li>
                  <a class="dropdown-item" href="<?= $value['url'] ?>"><?= $value['nombre'] ?></a>
               </li>
            <?php } ?>
         </ul>
      <?php } ?>
   </div>
   <div class="ms-auto d-flex align-items-stretch">
      <button class="btn-opt px-3" style="padding-top: 6px;" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Notificaciones">
         <i class="fas fa-comment-alt-lines"></i>
      </button>
      <div class="d-flex" style="height: 100%;">
         <div class="vr" style="color: rgb(180, 180, 180);"></div>
      </div>
      <button class="btn-opt px-3" style="padding-top: 6px;" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Sitio web">
         <i class="fas fa-globe-americas"></i>
      </button>
      <div class="d-flex" style="height: 100%;">
         <div class="vr" style="color: rgb(190, 190, 190);"></div>
      </div>
      <div class="dropdown">
         <button class="btn-opt px-3" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php if (SUPER_ADMIN) { ?>
               <img src="<?= base_url('img/default/man.png') ?>" id="user_photo">
            <?php } else { ?>
               <img src="<?= base_url() . esc($usuario_photo) ?>" id="user_photo">
            <?php } ?>

         </button>
         <ul class="dropdown-menu dropdown-menu-end" style="max-width: 200px;">
            <li>
               <div class="px-3 pt-1">
                  <div class="text-truncate text-primary mb-1 text-uppercase"><?= USUARIO ?></div>
                  <div class="text-truncate" style="font-size: calc(var(--font-size) - .5px);">Administrador del sistema</div>
               </div>
            </li>
            <li>
               <hr class="dropdown-divider">
            </li>
            <li>
               <a class="dropdown-item" onclick="openModalDataPerfil()" href="javascript:void(0)">
                  <i class="far fa-user"></i>
                  <span>&nbsp; Mi perfil</span>
               </a>
            </li>
            <li>
               <a class="dropdown-item" href="/auth/logout">
                  <i class="far fa-sign-out"></i>
                  <span>&nbsp; Cerrar sesión</span>
               </a>
            </li>
         </ul>
      </div>
   </div>
</header>