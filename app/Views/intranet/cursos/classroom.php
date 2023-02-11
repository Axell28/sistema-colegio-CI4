<?= $this->extend('template/layout') ?>
<?= $this->section('css') ?>
<style>
   .card-header-tabs .nav-link.active {
      background-color: white;
      border-color: rgba(0, 0, 0, 0.175);
      border-bottom-color: white;
   }

   .card-header-tabs .nav-link:not(.active):hover {
      background: transparent;
      border-color: transparent;
   }

   .accordion-button {
      /* border-top: 1px solid black;
      border-radius: 0px; */
      border-top: 1px solid #BBBBBB;
   }

   .accordion-button:focus {
      border-color: #BBBBBB;
   }

   .accordion-button:hover {
      background-color: #5575CA;
      color: white;
      --bs-accordion-btn-icon: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='white'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
   }

   .accordion-button:not(.collapsed) {
      background-color: #5575CA;
      color: white;
      border-top: 1px solid #BBBBBB;
   }

   .accordion-button:not(.collapsed)::after {
      --bs-accordion-btn-active-icon: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='white'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
   }

   .accordion-item {
      border-left: 1px solid #BBBBBB;
      border-right: 1px solid #BBBBBB;
      border-bottom: 1px solid #BBBBBB;
   }

   h4.curso-nombre {
      font-weight: bold;
      font-size: 15px;
      color: #0E1117;
      margin-bottom: 1em;
   }

   p.curso-encargado {
      color: #525252;
      font-size: 12px;
   }
</style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container-fluid">
   <div class="row mt-1 mb-3">
      <div class="col-12">
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?= MODULO_URL ?>"><?= MODULO_NAME ?></a></li>
               <li class="breadcrumb-item"><a href="<?= MODULO_URL ?>/cursos">Cursos</a></li>
               <li class="breadcrumb-item active" aria-current="page"><?= ucfirst(mb_strtolower(@$cursoNombre, 'UTF-8')) ?></li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="row">
      <div class="col-lg-12">
         <div class="card card-main">
            <div class="card-body pt-4">
               <div class="ps-1">
                  <h4 class="curso-nombre"><i class="fad fa-book"></i>&nbsp; <?= @$cursoNombre ?></h4>
                  <p class="curso-encargado">DOCENTE:&nbsp; <?= @$cursoEncargado ?></p>
               </div>
               <div class="card text-center">
                  <div class="card-header">
                     <ul class="nav nav-tabs card-header-tabs" id="myTab">
                        <?php foreach (@$listaPeriodos as $key => $value) {
                           $activo = $value['estado'] == 'A' || $value['periodo'] == '1';
                        ?>
                           <li class="nav-item">
                              <?php if ($value['periodo'] != '1' && $value['estado'] == 'B') { ?>
                                 <a href="#data_tab_p<?= $value['periodo'] ?>" periodo="<?= $value['periodo'] ?>" class="nav-link text-center disabled" data-bs-toggle="tab" title="No habilitado">
                                    <i class="far fa-calendar-alt"></i>
                                    <span>&nbsp; <?= \App\Helpers\Funciones::numeroTipoPeriodo($value['periodo']) . " " . $value['periododes'] ?></span>
                                 </a>
                              <?php } else { ?>
                                 <a href="#data_tab_p<?= $value['periodo'] ?>" periodo="<?= $value['periodo'] ?>" class="nav-link text-center <?= $activo ? 'active' : '' ?>" data-bs-toggle="tab">
                                    <i class="far fa-calendar-alt"></i>
                                    <span>&nbsp; <?= \App\Helpers\Funciones::numeroTipoPeriodo($value['periodo']) . " " . $value['periododes'] ?></span>
                                 </a>
                              <?php } ?>
                           </li>
                        <?php } ?>
                     </ul>
                  </div>
                  <div class="mt-3 px-3">
                     <button class="btn btn-success text-white w-100" id="btnAddGrupo">
                        <i class="fas fa-plus-circle"></i>
                        <span>&nbsp;Agregar contenido</span>
                     </button>
                  </div>
                  <div class="card-body pt-2">
                     <div class="tab-content">
                        <?php foreach (@$listaPeriodos as $key => $value) {
                           $activo = $value['estado'] == 'A' || $value['periodo'] == '1';
                        ?>
                           <?php if ($value['periodo'] != '1' && $value['estado'] == 'B') { ?>
                              <div class="tab-pane fade" id="data_tabl_p<?= $value['periodo'] ?>">
                              </div>
                           <?php } else { ?>
                              <div class="tab-pane fade show active" id="data_tabl_p<?= $value['periodo'] ?>">
                                 <div class="accordion" id="box_accordion_<?= $value['periodo'] ?>">
                                 </div>
                              </div>
                           <?php } ?>
                        <?php } ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="modalAuvGrupo" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered"></div>
</div>

<div class="modal fade" id="modalAuvItemsEditor" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-xl"></div>
</div>

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="https://cdn.tiny.cloud/1/m923kxh2ht5z3iggdm8hghimyq6r44s6egfxl8xat90kag3t/tinymce/5/tinymce.min.js"></script>
<script>
   let arrayAuvGrupos = JSON.parse('<?= json_encode(@$listaAuvGrupos) ?>');
   const modalAuvGrupo = document.getElementById('modalAuvGrupo');
   const modalAuvGrupoEvent = new bootstrap.Modal(modalAuvGrupo, {
      keyboard: false,
      backdrop: 'static'
   });

   const modalAuvItemsEditor = document.getElementById('modalAuvItemsEditor');
   const modalAuvItemsEditorEvent = new bootstrap.Modal(modalAuvItemsEditor, {
      keyboard: false,
      backdrop: 'static'
   });

   modalAuvItemsEditor.addEventListener('hidden.bs.modal', event => {
      $('#modalAuvItemsEditor .modal-dialog').html('');
      tinyMCE.get("editor").remove();
   });

   modalAuvGrupo.addEventListener('hidden.bs.modal', event => {
      $('#modalAuvGrupo .modal-dialog').html('');
   });

   function openModalAuvGrupo(codigo = 0, action = 'I') {
      let periodo = $('a.nav-link.active').attr('periodo');
      $.ajax({
         type: "POST",
         url: "<?= MODULO_URL ?>/cursos/auv-grupo",
         data: {
            action: action,
            codigo: codigo,
            anio: '<?= @$anio ?>',
            salon: '<?= @$salon ?>',
            curso: '<?= @$curso ?>',
            periodo: periodo
         },
         beforeSend: function() {
            $('#modalAuvGrupo .modal-dialog').html(getLoadingModal());
            modalAuvGrupoEvent.show();
         },
         success: function(response) {
            $('#modalAuvGrupo .modal-dialog').html(response);
         },
         error: function(jqXHr, status, error) {
            if (jqXHr.statusText) {
               showAlertSweet(jqXHr.statusText, 'error');
               modalAuvGrupoEvent.hide();
            }
         }
      });
   }

   async function eliminarGrupo(codigo) {
      let periodo = $('a.nav-link.active').attr('periodo');
      let confirm = await showConfirmSweet('¿Está seguro de eliminar este contenido?', 'question');
      if (confirm) {
         $.ajax({
            type: "GET",
            url: "<?= MODULO_URL ?>/cursos/json/eliminar-grupo",
            data: {
               codigo: codigo,
               periodo: periodo,
               salon: '<?= @$salon ?>',
               curso: '<?= @$curso ?>',
            },
            success: function(response) {
               if (response.listaAuvGrupos) {
                  arrayAuvGrupos['P' + periodo] = response.listaAuvGrupos;
                  listarTemplateAccordion();
               }
            }
         });
      }
   }

   function listaAuvGrupoItems(codigo) {
      $.ajax({
         type: "POST",
         url: "<?= MODULO_URL ?>/cursos/auv-grupo-items",
         data: {
            grupo: codigo
         },
         beforeSend: function() {
            $('#items-grupo-P' + codigo).html(`<div class="d-flex justify-content-center"><div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div></div>`);
         },
         success: function(response) {
            $('#items-grupo-P' + codigo).html(response);
         }
      });
   }

   function listarTemplateAccordion() {
      let html = '';
      let periodo = '1';
      $.each(arrayAuvGrupos['P' + periodo], function(index, value) {
         html += `<div class="accordion-item my-3" id="accordion_item_P${value.codigo}"> <h2 class="accordion-header" id="accordion_head_P${value.codigo}"> <button class="accordion-button py-3 collapsed" codigo="${value.codigo}" cargo-contenido="N" type="button" data-bs-toggle="collapse" data-bs-target="#accordion_body_P${value.codigo}" aria-expanded="false" aria-controls="accordion_body_P${value.codigo}"> <div class="d-flex align-items-center w-100"> <i class="far fa-folder-open"></i> <div class="text-truncate pe-3">&nbsp; ${value.titulo}</div><div class="ms-auto d-flex flex-row me-2" style="width: 50px;"> <span class="btn-editar" item="${value.codigo}" title="Editar" aria-hidden="true"><i class="far fa-pen-alt"></i></a> <span>&nbsp;&nbsp;</span> <span class="btn-eliminar" item="${value.codigo}" title="Eliminar" aria-hidden="true"><i class="far fa-trash-alt"></i></a> </div></div></button> </h2> <div id="accordion_body_P${value.codigo}" class="accordion-collapse collapse" aria-labelledby="accordion_head_P${value.codigo}" data-bs-parent="#box_accordion_${value.periodo}"> <div class="accordion-body"> <div class="text-start mb-3"> <div class="dropdown"> <button class="btn btn-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false"> <i class="fas fa-plus-circle"></i><span>&nbsp; Agregar</span> </button> <ul class="dropdown-menu"> <li><a class="dropdown-item item-opt" href="#">Notificación</a></li><li><a class="dropdown-item" href="#">Actividad</a></li><li><a class="dropdown-item" href="#">Tarea</a></li></ul> </div></div><div id="items-grupo-P${value.codigo}"> </div></div></div></div>`;
      });
      $('#box_accordion_' + periodo).html(html);

      $('.accordion-button').click(function(e) {
         e.preventDefault();
         if ($(this).attr('cargo-contenido') == 'N') {
            listaAuvGrupoItems($(this).attr('codigo'));
            $(this).attr('cargo-contenido', 'S');
         }
      });

      $('.btn-editar').click(function(e) {
         e.preventDefault();
         openModalAuvGrupo($(this).attr('item'), 'E');
      });

      $('.btn-eliminar').click(function(e) {
         e.stopImmediatePropagation()
         e.preventDefault();
         eliminarGrupo($(this).attr('item'));
      });

      $('.item-opt').click(function(e) {
         e.preventDefault();
         //let tipo = $(this).attr('tipo');
         $.ajax({
            type: "POST",
            url: "<?= MODULO_URL ?>/cursos/auv-grupo-editor",
            data: {

            },
            success: function(response) {
               $('#modalAuvItemsEditor .modal-dialog').html(response);
               modalAuvItemsEditorEvent.show();
            }
         });
      });
   }

   $(document).ready(function() {

      $('#btnAddGrupo').on('click', function() {
         openModalAuvGrupo();
      });

      listarTemplateAccordion();

   });
</script>
<?= $this->endSection() ?>