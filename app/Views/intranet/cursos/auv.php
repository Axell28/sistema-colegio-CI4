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
      font-size: 15.5px;
      color: white;
      margin-bottom: 1em;
   }

   p.curso-encargado {
      color: white;
      font-size: 12px;
   }

   .bg-curso-fondo {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' version='1.1' xmlns:xlink='http://www.w3.org/1999/xlink' xmlns:svgjs='http://svgjs.com/svgjs' width='1900' height='300' preserveAspectRatio='none' viewBox='0 0 1900 300'%3e%3cg mask='url(%26quot%3b%23SvgjsMask1145%26quot%3b)' fill='none'%3e%3crect width='1900' height='300' x='0' y='0' fill='rgba(21%2c 36%2c 159%2c 1)'%3e%3c/rect%3e%3cuse xlink:href='%23SvgjsSymbol1152' x='0' y='0'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsSymbol1152' x='720' y='0'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsSymbol1152' x='1440' y='0'%3e%3c/use%3e%3c/g%3e%3cdefs%3e%3cmask id='SvgjsMask1145'%3e%3crect width='1900' height='300' fill='white'%3e%3c/rect%3e%3c/mask%3e%3cpath d='M-1 0 a1 1 0 1 0 2 0 a1 1 0 1 0 -2 0z' id='SvgjsPath1150'%3e%3c/path%3e%3cpath d='M-3 0 a3 3 0 1 0 6 0 a3 3 0 1 0 -6 0z' id='SvgjsPath1148'%3e%3c/path%3e%3cpath d='M-5 0 a5 5 0 1 0 10 0 a5 5 0 1 0 -10 0z' id='SvgjsPath1147'%3e%3c/path%3e%3cpath d='M2 -2 L-2 2z' id='SvgjsPath1149'%3e%3c/path%3e%3cpath d='M6 -6 L-6 6z' id='SvgjsPath1146'%3e%3c/path%3e%3cpath d='M30 -30 L-30 30z' id='SvgjsPath1151'%3e%3c/path%3e%3c/defs%3e%3csymbol id='SvgjsSymbol1152'%3e%3cuse xlink:href='%23SvgjsPath1146' x='30' y='30' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1147' x='30' y='90' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1147' x='30' y='150' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1147' x='30' y='210' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1147' x='30' y='270' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1147' x='90' y='30' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1148' x='90' y='90' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1146' x='90' y='150' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1146' x='90' y='210' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1146' x='90' y='270' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1146' x='150' y='30' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1146' x='150' y='90' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1149' x='150' y='150' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1146' x='150' y='210' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1146' x='150' y='270' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1147' x='210' y='30' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1146' x='210' y='90' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1147' x='210' y='150' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1150' x='210' y='210' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1148' x='210' y='270' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1150' x='270' y='30' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1147' x='270' y='90' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1147' x='270' y='150' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1146' x='270' y='210' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1151' x='270' y='270' stroke='rgba(138%2c 162%2c 187%2c 0.41)' stroke-width='3'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1146' x='330' y='30' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1146' x='330' y='90' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1146' x='330' y='150' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1146' x='330' y='210' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1147' x='330' y='270' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1150' x='390' y='30' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1147' x='390' y='90' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1147' x='390' y='150' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1147' x='390' y='210' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1150' x='390' y='270' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1146' x='450' y='30' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1147' x='450' y='90' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1146' x='450' y='150' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1151' x='450' y='210' stroke='rgba(138%2c 162%2c 187%2c 0.41)' stroke-width='3'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1149' x='450' y='270' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1147' x='510' y='30' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1148' x='510' y='90' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1149' x='510' y='150' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1147' x='510' y='210' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1147' x='510' y='270' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1147' x='570' y='30' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1149' x='570' y='90' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1146' x='570' y='150' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1146' x='570' y='210' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1147' x='570' y='270' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1149' x='630' y='30' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1147' x='630' y='90' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1151' x='630' y='150' stroke='rgba(138%2c 162%2c 187%2c 0.41)' stroke-width='3'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1147' x='630' y='210' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1149' x='630' y='270' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1149' x='690' y='30' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1146' x='690' y='90' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1146' x='690' y='150' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1146' x='690' y='210' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3cuse xlink:href='%23SvgjsPath1146' x='690' y='270' stroke='rgba(138%2c 162%2c 187%2c 0.41)'%3e%3c/use%3e%3c/symbol%3e%3c/svg%3e");
      border-radius: 4px;
      background-size: cover;
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
               <div class="px-3 py-4 bg-curso-fondo">
                  <h4 class="curso-nombre"><i class="fad fa-book"></i>&nbsp; <?= @$cursoNombre ?></h4>
                  <p class="curso-encargado mb-0">DOCENTE:&nbsp; <?= @$cursoEncargado ?></p>
               </div>
               <br>
               <div class="card text-center">
                  <div class="card-header">
                     <ul class="nav nav-tabs card-header-tabs" id="myTab">
                        <?php
                        $existePeriodoActivo = false;
                        foreach (@$listaPeriodos as $key => $value) {
                           if (!$existePeriodoActivo) {
                              $existePeriodoActivo = $value['activo'] == "S";
                           }
                        ?>
                           <li class="nav-item">
                              <?php if ($value['estado'] == 'A' && $value['activo'] == "S") { ?>

                                 <a href="#data_tab_p<?= $value['periodo'] ?>" periodo="<?= $value['periodo'] ?>" class="nav-link text-center active" data-bs-toggle="tab">
                                    <i class="far fa-calendar-alt"></i>
                                    <span>&nbsp; <?= \App\Helpers\Funciones::numeroTipoPeriodo($value['periodo']) . " " . $value['periododes'] ?></span>
                                 </a>

                              <?php } else if ($value['estado'] == 'A') { ?>

                                 <a href="#data_tab_p<?= $value['periodo'] ?>" periodo="<?= $value['periodo'] ?>" class="nav-link text-center" data-bs-toggle="tab" title="No habilitado">
                                    <i class="far fa-calendar-alt"></i>
                                    <span>&nbsp; <?= \App\Helpers\Funciones::numeroTipoPeriodo($value['periodo']) . " " . $value['periododes'] ?></span>
                                 </a>

                              <?php } else { ?>

                                 <a href="#data_tab_p<?= $value['periodo'] ?>" periodo="<?= $value['periodo'] ?>" class="nav-link text-center disabled" data-bs-toggle="tab" title="No habilitado">
                                    <i class="far fa-calendar-alt"></i>
                                    <span>&nbsp; <?= \App\Helpers\Funciones::numeroTipoPeriodo($value['periodo']) . " " . $value['periododes'] ?></span>
                                 </a>

                              <?php } ?>
                           </li>
                        <?php } ?>
                     </ul>
                  </div>
                  <?php if (@$esDocente || @$esAdmin) { ?>
                     <div class="mt-3 px-3">
                        <button class="btn btn-success text-white w-100 <?= !$existePeriodoActivo ? 'disabled' : '' ?>" id="btnAddGrupo">
                           <i class="fas fa-plus-circle"></i>
                           <span>&nbsp;Agregar contenido</span>
                        </button>
                     </div>
                  <?php } ?>
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

   modalAuvItemsEditor.addEventListener('show.bs.modal', event => {
      $(".tox").addClass('addZindex');
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
         },
      });
   }

   function listarTemplateAccordion() {
      let html = '';
      let periodo = '1';
      $.each(arrayAuvGrupos['P' + periodo], function(index, value) {
         html += `<div class="accordion-item my-3" id="accordion_item_P${value.codigo}"> <h2 class="accordion-header" id="accordion_head_P${value.codigo}"> <button class="accordion-button py-3 collapsed" codigo="${value.codigo}" cargo-contenido="N" type="button" data-bs-toggle="collapse" data-bs-target="#accordion_body_P${value.codigo}" aria-expanded="false" aria-controls="accordion_body_P${value.codigo}"> <div class="d-flex align-items-center w-100"> <i class="far fa-folder-open"></i> <div class="text-truncate pe-3">&nbsp; ${value.titulo}</div>
         <?php if (@$esDocente || @$esAdmin) { ?>
         <div class="ms-auto d-flex flex-row me-2" style="width: 50px;"> 
         <span class="btn-editar" item="${value.codigo}" title="Editar" aria-hidden="true"><i class="far fa-pen-alt"></i> 
         <span>&nbsp;&nbsp;</span> <span class="btn-eliminar" item="${value.codigo}" title="Eliminar" aria-hidden="true"><i class="far fa-trash-alt"></i>
         </div>
         <?php } ?>
         </div></button> </h2> <div id="accordion_body_P${value.codigo}" class="accordion-collapse collapse" aria-labelledby="accordion_head_P${value.codigo}" data-bs-parent="#box_accordion_${value.periodo}"> <div class="accordion-body"> <div class="text-start mb-3">
         <?php if (@$esDocente || @$esAdmin) { ?>
         <button class="btn btn-warning text-white btn-add-item" grupo="${value.codigo}">
         <i class="fas fa-tasks"></i> <span>&nbsp;Agregar Contenido</span>
         </button>
         <?php } ?>
         </div><div id="items-grupo-P${value.codigo}"> </div></div></div></div>`;
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

      $('.btn-add-item').click(function(e) {
         e.preventDefault();
         let grupo = $(this).attr('grupo');
         $.ajax({
            type: "POST",
            url: "<?= MODULO_URL ?>/cursos/auv-grupo-editor",
            data: {
               grupo: grupo,
               action: 'I'
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