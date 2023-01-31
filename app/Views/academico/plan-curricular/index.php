<?= $this->extend('template/layout') ?>
<?= $this->section('css') ?>
<style>
   .bg-interno {
      background-color: #FCF9D8;
   }

   .form-check-input {
      transform: scale(1.3);
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
               <li class="breadcrumb-item active" aria-current="page">Plan curricular</li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="row">
      <div class="col-lg-5">
         <div class="card card-main">
            <div class="card-body">
               <div class="row mb-3">
                  <div class="col-md-6">
                     <div class="row">
                        <label for="cmbFilterAnio" class="col-auto col-form-label">Año:</label>
                        <div class="col-sm">
                           <select id="cmbFilterAnio" class="form-select">
                              <?php foreach (@$listaAnios as $value) { ?>
                                 <option value="<?= $value['anio'] ?>" <?= $value['anio'] == @$anio ? 'selected' : '' ?>><?= $value['anio'] ?></option>
                              <?php } ?>
                           </select>
                        </div>
                     </div>
                  </div>
               </div>
               <div id="jqxgridNivelGrado"></div>
            </div>
         </div>
      </div>
      <div class="col-lg-7">
         <div class="card card-main">
            <div class="card-body">
               <div class="row justify-content-between mb-3">
                  <div class="col-md-3">
                     <button class="btn btn-success" id="btnAdd">
                        <i class="fas fa-plus-circle"></i>
                        <span>Agregar curso</span>
                     </button>
                  </div>
                  <div class="col-sm-6 my-auto">
                     <div class="d-flex flex-wrap justify-content-end gap-3">
                        <div class="form-check form-check-inline">
                           <input class="form-check-input" type="radio" name="fgrid" id="option1" value="T" checked>
                           <label class="form-check-label" for="option1">&nbsp;Todos</label>
                        </div>
                        <div class="form-check form-check-inline">
                           <input class="form-check-input" type="radio" name="fgrid" id="option2" value="A">
                           <label class="form-check-label" for="option2">&nbsp;Área</label>
                        </div>
                        <div class="form-check form-check-inline">
                           <input class="form-check-input" type="radio" name="fgrid" id="option3" value="I">
                           <label class="form-check-label" for="option3">&nbsp;Internos</label>
                        </div>
                     </div>
                  </div>
               </div>
               <div id="jqxgridCurriculo"></div>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="modalCurricula" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered"></div>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script>
   const jqxgridNivelGrado = '#jqxgridNivelGrado';
   const jqxgridCurriculo = '#jqxgridCurriculo';
   const modalCurricula = document.getElementById('modalCurricula');
   const modalCurriculaEvent = new bootstrap.Modal(modalCurricula, {
      keyboard: false,
      backdrop: 'static'
   });
   modalCurricula.addEventListener('hidden.bs.modal', event => {
      $('#modalCurricula .modal-dialog').html('');
   });

   const jqxgridNivelGradoSource = {
      datatype: 'json',
      dataFields: [{
            name: 'ng',
            type: 'string'
         },
         {
            name: 'nivel',
            type: 'string'
         },
         {
            name: 'grado',
            type: 'string'
         },
         {
            name: 'niveldes',
            type: 'string'
         },
         {
            name: 'gradodes',
            type: 'string'
         }
      ],
      localdata: `<?= json_encode(@$listaNivelGrados) ?>`
   };

   const jqxgridCurriculoSource = {
      datatype: 'json',
      dataFields: [{
            name: 'anio',
            type: 'string'
         },
         {
            name: 'nivel',
            type: 'string'
         },
         {
            name: 'grado',
            type: 'string'
         },
         {
            name: 'curso',
            type: 'string'
         },
         {
            name: 'curpad',
            type: 'string'
         },
         {
            name: 'interno',
            type: 'string'
         },
         {
            name: 'curnom',
            type: 'string'
         },
         {
            name: 'tipcal',
            type: 'string'
         },
         {
            name: 'tipcaldes',
            type: 'string'
         },
         {
            name: 'nota_min',
            type: 'string'
         },
         {
            name: 'nota_max',
            type: 'string'
         },
         {
            name: 'orden',
            type: 'string'
         }
      ],
      localdata: `[]`
   };

   const jqxgridNivelGradoAdapter = new $.jqx.dataAdapter(jqxgridNivelGradoSource);

   const jqxgridCurriculoAdapter = new $.jqx.dataAdapter(jqxgridCurriculoSource);

   $(document).ready(function() {

      $(jqxgridNivelGrado).jqxGrid({
         width: '100%',
         height: 680,
         source: jqxgridNivelGradoAdapter,
         columns: [{
               text: "Nivel",
               datafield: "niveldes",
               align: 'center',
               width: "50%",
            },
            {
               text: "Grado",
               datafield: "gradodes",
               align: 'center',
               width: "50%",
            }
         ]
      });

      $(jqxgridCurriculo).jqxGrid({
         width: '100%',
         height: 680,
         source: jqxgridCurriculoAdapter,
         columns: [{
               text: "Código",
               datafield: "curso",
               align: 'center',
               cellsalign: 'center',
               width: "10%",
               cellclassname: function(row, column, value, data) {
                  let rowdata = $(jqxgridCurriculo).jqxGrid('getrowdata', row);
                  let bg = rowdata.interno == 'S' ? 'bg-interno' : '';
                  return bg;
               }
            },
            {
               text: "Curso",
               datafield: "curnom",
               align: 'center',
               width: "37%",
               cellclassname: function(row, column, value, data) {
                  let rowdata = $(jqxgridCurriculo).jqxGrid('getrowdata', row);
                  let bg = rowdata.interno == 'S' ? 'bg-interno' : '';
                  return bg;
               }
            },
            {
               text: "Tipo. calif",
               datafield: "tipcaldes",
               align: 'center',
               cellsalign: 'center',
               width: "18%",
               cellclassname: function(row, column, value, data) {
                  let rowdata = $(jqxgridCurriculo).jqxGrid('getrowdata', row);
                  let bg = rowdata.interno == 'S' ? 'bg-interno' : '';
                  return bg;
               }
            },
            {
               text: "Nota Mín.",
               datafield: "nota_min",
               align: 'center',
               cellsalign: 'center',
               width: "13%",
               cellclassname: function(row, column, value, data) {
                  let rowdata = $(jqxgridCurriculo).jqxGrid('getrowdata', row);
                  let bg = rowdata.interno == 'S' ? 'bg-interno' : '';
                  return bg;
               }
            },
            {
               text: "Nota Max.",
               datafield: "nota_max",
               align: 'center',
               cellsalign: 'center',
               width: "13%",
               cellclassname: function(row, column, value, data) {
                  let rowdata = $(jqxgridCurriculo).jqxGrid('getrowdata', row);
                  let bg = rowdata.interno == 'S' ? 'bg-interno' : '';
                  return bg;
               }
            },
            {
               text: "Orden",
               datafield: "orden",
               align: 'center',
               cellsalign: 'center',
               width: "9%",
               cellclassname: function(row, column, value, data) {
                  let rowdata = $(jqxgridCurriculo).jqxGrid('getrowdata', row);
                  let bg = rowdata.interno == 'S' ? 'bg-interno' : '';
                  return bg;
               }
            }
         ]
      });

      $(jqxgridNivelGrado).on('rowselect', function(event) {
         const rowdata = event.args.row;
         $.ajax({
            type: "post",
            url: "<?= MODULO_URL ?>/plan-curricular/json/listar",
            data: {
               anio: $('#cmbFilterAnio').val(),
               nivel: rowdata.nivel,
               grado: rowdata.grado,
               filtro: 'T'
            },
            beforeSend: function() {
               $(jqxgridCurriculo).jqxGrid('showloadelement');
            },
            success: function(response) {
               if (response.listaCurriculoNG) {
                  jqxgridCurriculoSource.localdata = response.listaCurriculoNG;
                  $(jqxgridCurriculo).jqxGrid('hideloadelement');
                  $(jqxgridCurriculo).jqxGrid('updateBoundData');
               }
            }
         });
      });

      $(jqxgridCurriculo).on('rowdoubleclick', function(event) {
         const args = event.args;
         const rowdata = args.row.bounddata;
         $.ajax({
            type: "post",
            url: "<?= MODULO_URL ?>/plan-curricular/asignacion",
            data: {
               action: 'E',
               anio: $('#cmbFilterAnio').val(),
               nivel: rowdata.nivel,
               grado: rowdata.grado,
               curso: rowdata.curso,
               curpad: rowdata.curpad,
               interno: rowdata.interno
            },
            beforeSend: function() {
               $('#modalCurricula .modal-dialog').html(getLoadingModal());
               modalCurriculaEvent.show();
            },
            success: function(response) {
               $('#modalCurricula .modal-dialog').html(response);
            },
            error: function(jqXHr, status, error) {
               if (jqXHr.statusText) {
                  showAlertSweet(jqXHr.statusText, 'error');
               }
            }
         });
      });

      $('#btnAdd').click(function(e) {
         let index = $(jqxgridNivelGrado).jqxGrid('getselectedrowindex');
         let rowdata = $(jqxgridNivelGrado).jqxGrid('getrowdata', index);
         $.ajax({
            type: "POST",
            url: "<?= MODULO_URL ?>/plan-curricular/asignacion",
            data: {
               action: 'I',
               anio: $('#cmbFilterAnio').val(),
               nivel: rowdata.nivel,
               grado: rowdata.grado
            },
            beforeSend: function() {
               $('#modalCurricula .modal-dialog').html(getLoadingModal());
               modalCurriculaEvent.show();
            },
            success: function(response) {
               $('#modalCurricula .modal-dialog').html(response);
            },
            error: function(jqXHr, status, error) {
               if (jqXHr.statusText) {
                  showAlertSweet(jqXHr.statusText, 'error');
               }
            }
         });
      });

      $('#cmbFilterAnio').change(function(e) {
         e.preventDefault();
         const index = $(jqxgridNivelGrado).jqxGrid('getselectedrowindex');
         const rowdata = $(jqxgridNivelGrado).jqxGrid('getrowdata', index);
         $.ajax({
            type: "post",
            url: "<?= MODULO_URL ?>/plan-curricular/json/listar",
            data: {
               anio: $('#cmbFilterAnio').val(),
               nivel: rowdata.nivel,
               grado: rowdata.grado,
               filtro: 'T'
            },
            beforeSend: function() {
               $(jqxgridCurriculo).jqxGrid('showloadelement');
            },
            success: function(response) {
               if (response.listaCurriculoNG) {
                  jqxgridCurriculoSource.localdata = response.listaCurriculoNG;
                  $(jqxgridCurriculo).jqxGrid('hideloadelement');
                  $(jqxgridCurriculo).jqxGrid('updateBoundData');
               }
            }
         });
      });

      $('.form-check-input').change(function(e) {
         e.preventDefault();
         const index = $(jqxgridNivelGrado).jqxGrid('getselectedrowindex');
         const rowdata = $(jqxgridNivelGrado).jqxGrid('getrowdata', index);
         $.ajax({
            type: "POST",
            url: "<?= MODULO_URL ?>/plan-curricular/json/listar",
            data: {
               anio: $('#cmbFilterAnio').val(),
               nivel: rowdata.nivel,
               grado: rowdata.grado,
               tipo: $(this).val()
            },
            beforeSend: function() {
               $(jqxgridCurriculo).jqxGrid('showloadelement');
            },
            success: function(response) {
               if (response.listaCurriculoNG) {
                  jqxgridCurriculoSource.localdata = response.listaCurriculoNG;
                  $(jqxgridCurriculo).jqxGrid('hideloadelement');
                  $(jqxgridCurriculo).jqxGrid('updateBoundData');
               }
            }
         });
      });

      $(jqxgridNivelGrado).jqxGrid('selectrow', 0);

   });
</script>
<?= $this->endSection() ?>