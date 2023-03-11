<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
   <div class="row mt-1 mb-3">
      <div class="col-12">
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?= MODULO_URL ?>"><?= MODULO_NAME ?></a></li>
               <li class="breadcrumb-item active" aria-current="page">Matricula</li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="row">
      <div class="col-lg-12">
         <div class="card card-main">
            <div class="card-body">
               <div class="d-flex flex-wrap gap-4 mb-4">
                  <label for="cmbAnioF" class="col-form-label" style="min-width: 40px;">Año :</label>
                  <div style="width: 16%;">
                     <select class="form-select" id="cmbAnioF">
                        <?php foreach (@$listaAnios as $value) { ?>
                           <option value="<?= $value['anio'] ?>" <?= @$anioVigente == $value['anio'] ? 'selected' : '' ?>>
                              <?= $value['anio'] ?>
                           </option>
                        <?php } ?>
                     </select>
                  </div>
                  <label for="cmbNivelF" class="col-form-label" style="min-width: 40px;">Nivel :</label>
                  <div style="width: 16%;">
                     <select class="form-select" id="cmbNivelF">
                        <option value="">-Todos-</option>
                        <?php foreach (@$listaNiveles as $value) { ?>
                           <option value="<?= $value['nivel'] ?>">
                              <?= $value['descripcion'] ?>
                           </option>
                        <?php } ?>
                     </select>
                  </div>
                  <label for="cmbGradoF" class="col-form-label" style="min-width: 40px;">Grado :</label>
                  <div style="width: 16%;">
                     <select class="form-select" id="cmbGradoF">
                        <option value="">-Todos-</option>
                     </select>
                  </div>
                  <div class="ms-auto" style="min-width: 12%;">
                     <a href="<?= MODULO_URL ?>/matricula/registro" class="btn btn-primary w-100">
                        <i class="fas fa-user-graduate"></i>
                        <span>&nbsp;Matricular alumno</span>
                     </a>
                  </div>
               </div>
               <div id="jqxgridMatricula"></div>
               <div class="pt-3">
                  <p class="mb-0" id="totalReg">Total de matriculados : 0</p>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<form id="frmRepFicha" action="<?= MODULO_URL ?>/reporte/generate" target="_blank" method="post">
   <input type="hidden" name="codrep" value="0005">
   <input type="hidden" name="anio" id="rep_anio" value="">
   <input type="hidden" name="codalu" id="rep_codalu" value="">
   <input type="hidden" name="codmat" id="rep_codmat" value="">
</form>

<div class="modal fade" id="modalMatricula" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable"></div>
</div>

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script>
   const jqxgridMatricula = '#jqxgridMatricula';
   const listaGrados = JSON.parse(`<?= json_encode(@$listaGrados) ?>`);

   const modalMatricula = document.getElementById('modalMatricula');
   const modalMatriculaEvent = new bootstrap.Modal(modalMatricula, {
      keyboard: false,
      backdrop: 'static'
   });

   modalMatricula.addEventListener('hidden.bs.modal', event => {
      $('#modalDocumento .modal-dialog').html('');
   });

   const jqxgridMatriculaSource = {
      datatype: 'json',
      dataFields: [{
            name: 'codmat',
            type: 'string'
         },
         {
            name: 'anio',
            type: 'string'
         },
         {
            name: 'salon',
            type: 'string'
         },
         {
            name: 'codalu',
            type: 'string'
         },
         {
            name: 'fecmat',
            type: 'date',
            format: 'yyyy-MM-dd'
         },
         {
            name: 'fecsal',
            type: 'string'
         },
         {
            name: 'condicion',
            type: 'string'
         },
         {
            name: 'modalidad',
            type: 'string'
         },
         {
            name: 'fecreg',
            type: 'date',
            format: 'yyyy-MM-dd HH:mm:ss'
         },
         {
            name: 'salondes',
            type: 'string'
         },
         {
            name: 'alunomb',
            type: 'string'
         },
         {
            name: 'numdoc',
            type: 'string'
         },
         {
            name: 'ngs',
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
            name: 'seccion',
            type: 'string'
         },
         {
            name: 'usunomreg',
            type: 'string'
         },
         {
            name: 'condes',
            type: 'string'
         }
      ],
      localdata: `<?= json_encode(@$listaRegistroMatricula) ?>`
   };

   const jqxgridMatriculaAdapter = new $.jqx.dataAdapter(jqxgridMatriculaSource);

   function openModalMatricula(action = 'I') {
      $.ajax({
         type: "POST",
         url: "<?= MODULO_URL ?>/matricula/registro",
         data: {
            action: action,
         },
         beforeSend: function() {
            $('#modalMatricula .modal-dialog').html(getLoadingModal());
            modalMatriculaEvent.show();
         },
         success: function(response) {
            $('#modalMatricula .modal-dialog').html(response);
         },
         error: function(jqXHR, status, error) {
            let errorMsg = error;
            if (jqXHR.responseJSON) {
               errorMsg = jqXHR.responseJSON.message;
            }
            showAlertSweet(errorMsg, 'error');
            modalMatriculaEvent.hide();
         }
      });
   }

   async function eliminarMatricula(index) {
      let confirm = await showConfirmSweet('¿Está seguro de eliminar la matrícula de este alumno?', 'question');
      if (!confirm) return;
      let rowdata = $(jqxgridMatricula).jqxGrid('getrowdata', index);
      $.ajax({
         type: "POST",
         url: "<?= MODULO_URL ?>/matricula/json/eliminar",
         data: {
            codalu: rowdata.codalu,
            codmat: rowdata.codmat
         },
         success: function(response) {
            $('#cmbAnioF').change();
         }
      });
   }

   function generarFichaMatricula(index) {
      let data = $(jqxgridMatricula).jqxGrid('getrowdata', index);
      $('#rep_anio').val($('#cmbAnioF').val());
      $('#rep_codalu').val(data.codalu);
      $('#rep_codmat').val(data.codmat);
      $('#frmRepFicha').submit();
   }

   function totalRegistros() {
      const info = $(jqxgridMatricula).jqxGrid('getdatainformation');
      $('#totalReg').html(`Total de matriculados : &nbsp; ` + info.rowscount);
   }

   $(document).ready(function() {

      $(jqxgridMatricula).jqxGrid({
         width: '100%',
         height: 670,
         source: jqxgridMatriculaAdapter,
         pagermode: 'simple',
         selectionmode: 'none',
         showfilterrow: true,
         filterable: true,
         columns: [{
               text: "Código",
               datafield: "codmat",
               align: 'center',
               cellsalign: 'center',
               width: "120",
               pinned: true
            },
            {
               text: "Apellidos y Nombres",
               datafield: "alunomb",
               align: 'center',
               width: "320",
               pinned: true
            },
            {
               text: "N",
               datafield: "nivel",
               align: 'center',
               cellsalign: 'center',
               width: "45",
               filterable: false,
            },
            {
               text: "G",
               datafield: "grado",
               align: 'center',
               cellsalign: 'center',
               width: "45",
               filterable: false,
            },
            {
               text: "S",
               datafield: "seccion",
               align: 'center',
               cellsalign: 'center',
               width: "45",
               filterable: false,
            },
            {
               text: "Salón",
               datafield: "salondes",
               align: 'center',
               width: "200",
               filterable: false,
            },
            {
               text: "Fecha Matrícula",
               datafield: "fecmat",
               align: 'center',
               cellsalign: 'center',
               width: "150",
               filterable: false,
               cellsformat: 'dd/MM/yyyy'
            },
            {
               text: "Condición",
               datafield: "condes",
               align: 'center',
               cellsalign: 'center',
               width: "150",
               filterable: false,
            },
            {
               text: "Registrado por",
               datafield: "usunomreg",
               align: 'center',
               width: "250",
               filterable: false,
            },
            {
               text: "Fecha. registro",
               datafield: "fecreg",
               align: 'center',
               cellsalign: 'center',
               width: "180",
               filterable: false,
               cellsformat: 'dd/MM/yyyy hh:mm tt'
            },
            {
               text: '',
               width: '4%',
               filterable: false,
               cellsrenderer: function(row, column, value) {
                  return `<div class="jqx-center-align"><button class="btn btn-link text-success" onclick="generarFichaMatricula(${row})" title="Ficha matrícula"><i class="fas fa-file-alt"></i></button></div>`;
               }
            },
            {
               text: '',
               width: '4%',
               filterable: false,
               cellsrenderer: function(row, column, value) {
                  return `<div class="jqx-center-align"><button class="btn btn-link text-danger" onclick="eliminarMatricula(${row})" title="Eliminar"><i class="far fa-trash-alt"></i></button></div>`;
               }
            },
         ]
      });

      $('#cmbNivelF').change(function(e) {
         let html = '<option value="">-Todos-</option>';
         let nivel = $(this).val();
         let grados = listaGrados[nivel] ?? [];
         $.each(grados, function(index, value) {
            html += `<option value="${value.grado}">${value.descripcion}</option>`;
         });
         $('#cmbGradoF').html(html);
      });

      $('#btnMatricula').on('click', function() {
         let anio = $('#cmbAnioF').val();
         if (anio == `<?= is_null(@$anioMatricula) ? '0000' : @$anioMatricula ?>`) {
            openModalMatricula('I');
         } else {
            showAlertSweet('El año ' + anio + ' no esta activo para la matricula', 'warning');
         }
      });

      $('.form-select').change(function(e) {
         $.ajax({
            type: "POST",
            url: "<?= MODULO_URL ?>/matricula/json/listar",
            data: {
               anioF: $('#cmbAnioF').val(),
               nivelF: $('#cmbNivelF').val(),
               gradoF: $('#cmbGradoF').val()
            },
            beforeSend: function() {
               $(jqxgridMatricula).jqxGrid('showloadelement');
            },
            success: function(response) {
               if (response.listaRegistroMatricula) {
                  jqxgridMatriculaSource.localdata = response.listaRegistroMatricula;
                  $(jqxgridMatricula).jqxGrid('updateBoundData', 'data');
                  totalRegistros();
               }
            }
         });
      });

      totalRegistros();

   });
</script>
<?= $this->endSection() ?>