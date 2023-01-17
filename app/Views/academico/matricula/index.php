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
                  <div class="ms-auto" style="min-width: 15%;">
                     <button class="btn btn-primary w-100" id="btnMatricula">
                        <i class="fas fa-user-graduate"></i>
                        <span>&nbsp;Matricular alumno</span>
                     </button>
                  </div>
               </div>
               <div id="jqxgridMatricula"></div>
               <div class="pt-3">
                  <p class="mb-0">Total de matriculados : 0</p>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

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
         }
      ],
      localdata: `[]`
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

   $(document).ready(function() {

      $(jqxgridMatricula).jqxGrid({
         width: '100%',
         height: 670,
         source: jqxgridMatriculaAdapter,
         columns: [{
               text: "Código",
               datafield: "salon",
               align: 'center',
               cellsalign: 'center',
               width: "10%",
            },
            {
               text: "Documento",
               align: 'center',
               cellsalign: 'center',
               width: "10%",
            },
            {
               text: "Apellidos y Nombres",
               align: 'center',
               cellsalign: 'center',
               width: "30%",
            },
            {
               text: "Nivel",
               align: 'center',
               cellsalign: 'center',
               width: "10%",
            },
            {
               text: "Grado",
               align: 'center',
               cellsalign: 'center',
               width: "10%",
            },
            {
               text: "Sección",
               align: 'center',
               cellsalign: 'center',
               width: "10%",
            },
            {
               text: "Condición",
               align: 'center',
               cellsalign: 'center',
               width: "10%",
            },
            {
               text: "Fecha matricula",
               align: 'center',
               cellsalign: 'center',
               width: "15%",
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

   });
</script>
<?= $this->endSection() ?>