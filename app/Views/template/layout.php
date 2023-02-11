<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="preload" href="<?= base_url('css/pace.css') ?>" as="style" fetchpriority="high">
   <link rel="preload" href="<?= base_url('js/pace.min.js') ?>" as="script" etchpriority="high">
   <link rel="preload" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" as="style" fetchpriority="high">
   <title><?= MODULO_NAME ?> - Sistema Académico</title>
   <link rel="shortcut icon" href="<?= base_url('img/iconos/web_ico.png') ?>" type="image/png">
   <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
   <link rel="stylesheet" href="<?= base_url('css/pace.css') ?>">
   <link rel="stylesheet" href="<?= base_url('css/bootstrap.min.css') ?>">
   <link rel="stylesheet" href="<?= base_url('css/jqwidgets/jqx.base.css')  ?>">
   <link rel="stylesheet" href="<?= base_url('css/main.css') ?>">
   <link rel="stylesheet" href="<?= base_url('css/sweetalert.css') ?>">
   <link rel="stylesheet" href="<?= base_url('css/select2.min.css') ?>">
   <link rel="stylesheet" href="<?= base_url('css/select2_BS5.min.css') ?>" />
</head>

<body>

   <!-- Colores del sistema -->
   <style>
      :root {
         --color-default-primary: <?= @$COLOR_PRIMARIO ?>;
         --color-default-secondary: <?= @$COLOR_SECUNDARIO ?>;
      }
   </style>

   <!-- Archivos Javascript -->
   <script src="<?= base_url('js/pace.min.js') ?>"></script>
   <script src="<?= base_url('js/jquery.min.js') ?>"></script>
   <script src="<?= base_url('js/popper.min.js') ?>"></script>
   <script src="<?= base_url('js/bootstrap.min.js') ?>"></script>
   <script src="<?= base_url('js/sweetalert.min.js') ?>"></script>
   <script src="<?= base_url('js/jqwidgets/jqxcore.js') ?>"></script>
   <script src="<?= base_url('js/jqwidgets/jqxdata.js') ?>"></script>
   <script src="<?= base_url('js/jqwidgets/jqxbuttons.js') ?>"></script>
   <script src="<?= base_url('js/jqwidgets/jqxscrollbar.js') ?>"></script>
   <script src="<?= base_url('js/jqwidgets/jqxlistbox.js') ?>"></script>
   <script src="<?= base_url('js/jqwidgets/jqxdropdownlist.js') ?>"></script>
   <script src="<?= base_url('js/jqwidgets/jqxmenu.js') ?>"></script>
   <script src="<?= base_url('js/jqwidgets/jqxgrid.js') ?>"></script>
   <script src="<?= base_url('js/jqwidgets/jqxgrid.selection.js') ?>"></script>
   <script src="<?= base_url('js/jqwidgets/jqxgrid.edit.js') ?>"></script>
   <script src="<?= base_url('js/jqwidgets/jqxgrid.filter.js') ?>"></script>
   <script src="<?= base_url('js/select2.min.js') ?>"></script>

   <!-- Estilos -->
   <?= $this->renderSection('css') ?>

   <?= $this->include('template/menu') ?>

   <div class="wcontainer">
      <?= $this->include('template/header') ?>
      <?= $this->renderSection('content') ?>
   </div>

   <div class="modal fade" id="modalDataPerfil" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" style="max-width: 430px;"></div>
   </div>

   <!-- Funciones JS globales -->
   <script>
      const modalDataPerfil = document.getElementById('modalDataPerfil');
      const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))

      const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
         return new bootstrap.Tooltip(tooltipTriggerEl)
      });

      const modalDataPerfilEvent = new bootstrap.Modal(modalDataPerfil, {
         keyboard: false,
         backdrop: 'static'
      });

      // función para obtener el HTML de carga para un Modal para Ajax
      function getLoadingModal() {
         return `<div class="modal-content">
            <div class="d-flex justify-content-center align-items-center" style="height: 300px;">
               <div class="spinner-border text-primary" role="status"></div>
            </div>
         </div>`;
      }

      // función para abrirl modal con los datos del perfil logueado
      function openModalDataPerfil() {
         $.ajax({
            type: "GET",
            url: "/ver-perfil",
            beforeSend: function() {
               $('#modalDataPerfil .modal-dialog').html(getLoadingModal());
               modalDataPerfilEvent.show();
            },
            success: function(response) {
               $('#modalDataPerfil .modal-dialog').html(response);
            }
         });
      }

      // funcion para obtener la fecha en formato yyyy-mm-dd hh:mm:ss
      function getDateTimeFormat(date) {
         return (
            [
               date.getFullYear(),
               (date.getMonth() + 1).toString().padStart(2, '0'),
               (date.getDate()).toString().padStart(2, '0'),
            ].join('-') +
            ' ' + [
               (date.getHours()).toString().padStart(2, '0'),
               (date.getMinutes()).toString().padStart(2, '0'),
               '00'
            ].join(':')
         );
      }

      // función para mostrar un alert personalizado
      function showAlertSweet(texto, icono, timerUp = false) {
         const items = {
            icon: icono,
            title: '',
            text: texto
         };
         if (timerUp) {
            items.showConfirmButton = false;
            items.timer = 1500;
         }
         Swal.fire(items);
      }

      // función para mostrar un alert de confirmación personalizado
      async function showConfirmSweet(texto, icono, titulo) {
         const {
            value: isConfirmed
         } = await Swal.fire({
            title: titulo,
            text: texto,
            icon: icono,
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar',
            allowOutsideClick: false
         });
         return isConfirmed == true;
      }

      // función para calcular bytes a KB MB GB
      function convertirBytesToSize(bytes, decimals = 2) {
         if (!+bytes) return '0 Bytes'
         const k = 1024
         const dm = decimals < 0 ? 0 : decimals
         const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
         const i = Math.floor(Math.log(bytes) / Math.log(k))
         return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`
      }

      // funcion para cerrar el menu cuando esta en movil
      $('div.wcontainer .container-fluid').on('click', function() {
         let w = document.documentElement.clientWidth;
         if (w <= 700 && document.querySelector("aside.menu").classList.contains("close") == true) {
            document.querySelector('aside.menu').classList.remove('close');
         }
      });
   </script>

   <!-- Código Javascript -->
   <?= $this->renderSection('js') ?>
</body>

</html>