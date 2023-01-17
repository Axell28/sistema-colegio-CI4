<?= $this->extend('template/layout') ?>
<?= $this->section('css') ?>
<style>
   legend {
      font-size: inherit;
      line-height: 30px;
      font-weight: 600;
      text-transform: uppercase;
      color: #555;
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
               <li class="breadcrumb-item active" aria-current="page">Salones</li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="row">
      <div class="col-lg-12">
         <div class="card card-main">
            <div class="card-body">
               <div class="row mb-3">
                  <div class="col-md-3 my-2">
                     <div class="row">
                        <label for="cmbAnioFilter" class="col-auto col-form-label">Año:&nbsp;&nbsp;</label>
                        <div class="col-md">
                           <select class="form-select" id="cmbAnioFilter">
                              <?php foreach (@$listaAnios as $value) { ?>
                                 <option value="<?= $value['anio'] ?>" <?= @$anioVigente == $value['anio'] ? 'selected' : '' ?>>
                                    <?= $value['anio'] ?>
                                 </option>
                              <?php } ?>
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-3 my-2">
                     <div class="row">
                        <label for="cmbNivelFilter" class="col-auto col-form-label">Nivel:</label>
                        <div class="col-md">
                           <select class="form-select" id="cmbNivelFilter">
                              <option value="">-Todos-</option>
                              <?php foreach (@$listaNiveles as $value) { ?>
                                 <option value="<?= $value['nivel'] ?>">
                                    <?= $value['descripcion'] ?>
                                 </option>
                              <?php } ?>
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="col-md text-end my-2">
                     <button class="btn btn-primary" id="btnAdd">
                        <i class="fas fa-plus-circle"></i>
                        <span>&nbsp;Agregar salón</span>
                     </button>
                  </div>
               </div>
               <div id="jqxgridSalones"></div>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="modalRegistro" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"></div>
</div>

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script>
   const jqxgridSalones = '#jqxgridSalones';
   const modalRegistro = document.getElementById('modalRegistro');
   const modalRegistroEvent = new bootstrap.Modal(modalRegistro, {
      keyboard: false,
      backdrop: 'static'
   });

   modalRegistro.addEventListener('hidden.bs.modal', event => {
      $('#modalRegistro .modal-dialog').html('');
   });

   const jqxgridSalonesSource = {
      datatype: 'json',
      dataFields: [{
            name: 'salon',
            type: 'string'
         },
         {
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
            name: 'seccion',
            type: 'string'
         },
         {
            name: 'nombre',
            type: 'string'
         },
         {
            name: 'tutor',
            type: 'string'
         },
         {
            name: 'cotutor',
            type: 'string'
         },
         {
            name: 'coordinador',
            type: 'string'
         },
         {
            name: 'vacantes',
            type: 'string'
         },
         {
            name: 'aula',
            type: 'string'
         },
         {
            name: 'turno',
            type: 'string'
         },
         {
            name: 'modalidad',
            type: 'string'
         },
         {
            name: 'tutor_nomb',
            type: 'string'
         },
      ],
      localdata: `<?= json_encode(@$listaSalones) ?>`
   }

   const jqxgridSalonesAdapter = new $.jqx.dataAdapter(jqxgridSalonesSource);

   function mostrarDatosSalon(index) {
      const rowdata = $(jqxgridSalones).jqxGrid('getrowdata', index);
      $.ajax({
         type: "post",
         url: "<?= MODULO_URL ?>/salones/registro",
         data: {
            anio: rowdata.anio,
            salon: rowdata.salon,
            action: 'E'
         },
         beforeSend: function() {
            $('#modalRegistro .modal-dialog').html(getLoadingModal());
            modalRegistroEvent.show();
         },
         success: function(response) {
            $('#modalRegistro .modal-dialog').html(response);
         },
         error: function(jqXHr, status, error) {
            if (jqXHr.statusText) {
               showAlertSweet(jqXHr.statusText, 'error');
            }
         }
      });
   }

   async function eliminarSalon(index) {
      const rowdata = $(jqxgridSalones).jqxGrid('getrowdata', index);
      const confirm = await showConfirmSweet('Está seguro de eliminar el salón ' + rowdata.nombre, 'question');
      if (confirm) {
         $.ajax({
            type: "post",
            url: "<?= MODULO_URL ?>/salones/json/eliminar",
            data: {
               filter_anio: $('#cmbAnioFilter').val(),
               filter_nivel: $('#cmbNivelFilter').val(),
               salon: rowdata.salon
            },
            success: function(response) {
               if (response.listaSalones) {
                  jqxgridSalonesSource.localdata = response.listaSalones;
                  $(jqxgridSalones).jqxGrid('updateBoundData');
               }
            }
         });
      }
   }

   $(document).ready(function() {

      $(jqxgridSalones).jqxGrid({
         width: '100%',
         height: 590,
         source: jqxgridSalonesAdapter,
         columns: [{
               text: "Código",
               datafield: "salon",
               align: 'center',
               cellsalign: 'center',
               width: "10%",
            },
            {
               text: "Nivel",
               datafield: "nivel",
               align: 'center',
               cellsalign: 'center',
               width: "6%"
            },
            {
               text: "Grado",
               datafield: "grado",
               align: 'center',
               cellsalign: 'center',
               width: "6%"
            },
            {
               text: "Sección",
               datafield: "seccion",
               align: 'center',
               cellsalign: 'center',
               width: "6%"
            },
            {
               text: "Nombre",
               datafield: "nombre",
               align: 'center',
               width: "21%"
            },
            {
               text: "Aula",
               datafield: "aula",
               align: 'center',
               cellsalign: 'center',
               width: "8%"
            },
            {
               text: "Tutor(a)",
               datafield: "tutor_nomb",
               align: 'center',
               width: "25%"
            },
            {
               text: "Vacantes",
               datafield: "vacantes",
               align: 'center',
               cellsalign: 'center',
               width: "10%"
            },
            {
               text: '',
               width: '4%',
               cellsrenderer: function(row, column, value) {
                  return `<div class="jqx-center-align"><button class="btn btn-link text-info" onclick="mostrarDatosSalon(${row})" title="Editar"><i class="fas fa-pencil-alt"></i></button></div>`;
               }
            },
            {
               text: '',
               width: '4%',
               cellsrenderer: function(row, column, value) {
                  return `<div class="jqx-center-align"><button class="btn btn-link text-danger" onclick="eliminarSalon(${row})" title="Eliminar"><i class="far fa-trash-alt"></i></button></div>`;
               }
            },
         ]
      });

      $('.form-select').change(function(e) {
         $.ajax({
            type: "post",
            url: "<?= MODULO_URL ?>/salones/json/listar",
            data: {
               filter_anio: $('#cmbAnioFilter').val(),
               filter_nivel: $('#cmbNivelFilter').val()
            },
            beforeSend: function() {
               $(jqxgridSalones).jqxGrid('showloadelement');
            },
            success: function(response) {
               if (response.listaSalones) {
                  jqxgridSalonesSource.localdata = response.listaSalones;
                  $(jqxgridSalones).jqxGrid('updateBoundData');
               }
            }
         });
      });

      $('#btnAdd').on('click', function() {
         $.ajax({
            type: "post",
            url: "<?= MODULO_URL ?>/salones/registro",
            data: {
               anio: $('#cmbAnioFilter').val(),
               salon: null,
               action: 'I'
            },
            beforeSend: function() {
               $('#modalRegistro .modal-dialog').html(getLoadingModal());
               modalRegistroEvent.show();
            },
            success: function(response) {
               $('#modalRegistro .modal-dialog').html(response);
            },
            error: function(jqXHR, status, error) {
               let message = error;
               if (jqXHR.responseJSON) {
                  message = jqXHR.responseJSON.message;
               }
               showAlertSweet(message, 'error');
            },
         });
      });

   });
</script>
<?= $this->endSection() ?>