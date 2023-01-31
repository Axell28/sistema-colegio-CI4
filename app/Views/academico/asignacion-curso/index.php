<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
   <div class="row mt-1 mb-3">
      <div class="col-12">
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?= MODULO_URL ?>"><?= MODULO_NAME ?></a></li>
               <li class="breadcrumb-item active" aria-current="page">Asignación Curso</li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="row">
      <div class="col-lg-12">
         <div class="card card-main">
            <div class="card-body">
               <div class="row">
                  <div class="col-md-3 mb-2">
                     <label for="cmbAnioF" class="form-label">Año:</label>
                     <select class="form-select filter" name="anio" id="cmbAnioF">
                        <?php foreach (@$listaAnios as $key => $value) { ?>
                           <option value="<?= $value['anio'] ?>" <?= $value['anio'] == @$anio ? 'selected' : '' ?>><?= $value['anio'] ?></option>
                        <?php } ?>
                     </select>
                  </div>
                  <div class="col-md-3 mb-2">
                     <label for="cmbNivelF" class="form-label">Nivel:</label>
                     <select class="form-select" name="nivel" id="cmbNivelF">
                        <option value="">-Todos-</option>
                        <?php foreach (@$listaNiveles as $key => $value) { ?>
                           <option value="<?= $value['nivel'] ?>"><?= $value['descripcion'] ?></option>
                        <?php } ?>
                     </select>
                  </div>
                  <div class="col-md-3 mb-2">
                     <label for="cmbGradoF" class="form-label">Grado:</label>
                     <select class="form-select" name="grado" id="cmbGradoF">
                        <option value="">-Todos-</option>
                     </select>
                  </div>
                  <div class="col-md-3 mb-2">
                     <label for="cmbSeccionF" class="form-label">Sección:</label>
                     <select class="form-select filter" name="seccion" id="cmbSeccionF">
                        <option value="">-Todos-</option>
                     </select>
                  </div>
               </div>
               <div class="row mb-3">
                  <div class="col-md-6 mb-2">
                     <label for="cmbCursoF" class="form-label">Curso:</label>
                     <select class="form-select filter" name="curso" id="cmbCursoF">
                        <option value="">-Todos-</option>
                        <?php foreach (@$listaCursos as $key => $value) { ?>
                           <option value="<?= $value['codcur'] ?>"><?= $value['nombre'] ?></option>
                        <?php } ?>
                     </select>
                  </div>
                  <div class="col-md-6 mb-2">
                     <label for="cmbDocenteF" class="form-label">Docentes:</label>
                     <select class="form-select-2 filter" name="docente" id="cmbDocenteF">
                        <option value="">-Todos-</option>
                        <?php foreach (@$listaDocentes as $key => $value) { ?>
                           <option value="<?= $value['codigo'] ?>"><?= $value['nombre'] ?></option>
                        <?php } ?>
                     </select>
                  </div>
               </div>
               <div id="jqxgridAsigCursos"></div>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="modalAsignacion" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered"></div>
</div>

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="<?= base_url('js/jqwidgets/jqxgrid.grouping.js') ?>"></script>
<script>
   const jqxgridAsigCursos = '#jqxgridAsigCursos';
   const listaGrados = JSON.parse(`<?= json_encode(@$listaGrados) ?>`);
   const listaSecciones = JSON.parse(`<?= json_encode(@$listaSecciones) ?>`);

   const modalAsignacion = document.getElementById('modalAsignacion');
   const modalAsignacionEvent = new bootstrap.Modal(modalAsignacion, {
      keyboard: false,
      backdrop: 'static'
   });

   modalAsignacion.addEventListener('hidden.bs.modal', event => {
      $('#modalAsignacion .modal-dialog').html('');
   });

   const jqxgridAsigCursosSource = {
      datatype: 'json',
      dataFields: [{
            name: 'ide',
            type: 'string'
         },
         {
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
            name: 'ngs',
            type: 'string'
         },
         {
            name: 'seccion',
            type: 'string'
         },
         {
            name: 'codcur',
            type: 'string'
         },
         {
            name: 'curnom',
            type: 'string'
         },
         {
            name: 'codemp',
            type: 'string'
         },
         {
            name: 'encargado',
            type: 'string'
         }
      ],
      id: 'ide',
      localdata: `<?= json_encode(@$listaCursosAsignados) ?>`
   }

   const jqxgridAsigCursosAdapter = new $.jqx.dataAdapter(jqxgridAsigCursosSource);

   function filtrarGrillaAsigCurso() {
      $.ajax({
         type: "POST",
         url: "<?= MODULO_URL ?>/asignacion-curso/json/listar",
         data: {
            anioF: $('#cmbAnioF').val(),
            nivelF: $('#cmbNivelF').val(),
            gradoF: $('#cmbGradoF').val(),
            seccionF: $('#cmbSeccionF').val(),
            cursoF: $('#cmbCursoF').val(),
            docenteF: $('#cmbDocenteF').val()
         },
         beforeSend: function() {
            $(jqxgridAsigCursos).jqxGrid('showloadelement');
         },
         success: function(response) {
            if (response.listaCursosAsignados) {
               jqxgridAsigCursosSource.localdata = response.listaCursosAsignados;
               $(jqxgridAsigCursos).jqxGrid('updateBoundData', 'data');
            }
         }
      });
   }

   function openModalAsignacion(index) {
      const rowdata = $(jqxgridAsigCursos).jqxGrid('getrowdata', index);
      $.ajax({
         type: "POST",
         url: "<?= MODULO_URL ?>/asignacion-curso/asignacion",
         data: {
            anio: rowdata.anio,
            salon: rowdata.salon,
            curso: rowdata.codcur,
            docente: rowdata.codemp
         },
         beforeSend: function() {
            $('#modalAsignacion .modal-dialog').html(getLoadingModal());
            modalAsignacionEvent.show();
         },
         success: function(response) {
            $('#modalAsignacion .modal-dialog').html(response);
         },
         error: function(jqXHr, status, error) {
            if (jqXHr.statusText) {
               showAlertSweet(jqXHr.statusText, 'error');
            }
         }
      });
   }

   $(document).ready(function() {

      $('.form-select-2').select2({
         width: '100%',
         theme: 'bootstrap-5',
      });

      $(jqxgridAsigCursos).jqxGrid({
         width: '100%',
         height: 600,
         source: jqxgridAsigCursosAdapter,
         editable: false,
         groupable: true,
         columns: [{
               dataField: 'salon',
               hidden: true
            },
            {
               text: 'SALÓN  ',
               dataField: 'ngs',
               hidden: true
            },
            {
               text: 'N',
               dataField: 'nivel',
               hidden: true
            },
            {
               text: 'G',
               dataField: 'grado',
               hidden: true
            },
            {
               text: 'S',
               dataField: 'seccion',
               hidden: true
            },
            {
               text: 'Curso',
               dataField: 'codcur',
               width: '15%',
               align: 'center',
               cellsalign: 'center',
            },
            {
               text: 'Nombre del curso',
               dataField: 'curnom',
               width: '40%',
               align: 'center',
            },
            {
               text: 'Docente encargado',
               datafield: 'encargado',
               width: '40%',
               align: 'center'
            },
            {
               text: '',
               width: '5%',
               cellsrenderer: function(row, column, value) {
                  return `
                  <div class="jqx-center-align">
                     <button class="btn btn-link text-success" onclick="openModalAsignacion(${row})" title="Asignar docente">
                        <i class="fas fa-user-edit"></i>
                     </button>
                  </div>`;
               }
            },
         ],
         groups: ['ngs'],
         groupsexpandedbydefault: true,
         showgroupsheader: false
      });

      $('.filter').change(function(e) {
         filtrarGrillaAsigCurso();
      });

      $('#cmbNivelF').change(function(e) {
         let list = '<option value="">-Todos-</option>';
         let nivel = $(this).val();
         if (nivel != '') {
            let grados = listaGrados[nivel] ? listaGrados[nivel] : [];
            $.each(grados, function(index, value) {
               list += `<option value="${value.grado}">${value.descripcion}</option>`;
            });
         }
         $('#cmbGradoF').html(list);
         filtrarGrillaAsigCurso();
      });

      $('#cmbGradoF').change(function(e) {
         let list = '<option value="">-Seleccione-</option>';
         let nivel = $('#cmbNivelF').val();
         let grado = $(this).val();
         let secciones = listaSecciones[nivel][grado] ? listaSecciones[nivel][grado] : [];
         $.each(secciones, function(index, value) {
            list += `<option value="${value.seccion}">${value.descripcion}</option>`;
         });
         $('#cmbSeccionF').html(list);
         filtrarGrillaAsigCurso();
      });

   });
</script>
<?= $this->endSection() ?>