<?= $this->extend('template/layout') ?>
<?= $this->section('css') ?>
<style>
   #boxAlert {
      display: none;
   }
</style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container-fluid">
   <div class="row mt-1 mb-3">
      <div class="col-12">
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?= MODULO_URL ?>">Académico</a></li>
               <li class="breadcrumb-item active" aria-current="page">Cursos</li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="row">
      <div class="col-lg-12">
         <div class="card card-main">
            <div class="card-body">
               <div class="row mb-3">
                  <div class="col-md-6">
                     <button class="btn btn-primary me-2" id="btnNuevo">
                        <i class="fas fa-plus-circle"></i>
                        <span>&nbsp;Agregar curso</span>
                     </button>
                     <button class="btn btn-danger" id="btnDelete">
                        <i class="fas fa-minus-circle"></i>
                        <span>&nbsp;Eliminar curso</span>
                     </button>
                  </div>
                  <div class="col-md-6 my-auto">
                     <div class="text-end" id="totalReg">
                        Total de registrados : 0
                     </div>
                  </div>
               </div>
               <div id="jqxgridCursos"></div>
            </div>
         </div>
      </div>
   </div>

   <?= $this->include('academico/cursos/registro') ?>

</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script>
   const frmRegistro = document.getElementById('frmRegistro');

   const modalRegistro = new bootstrap.Modal('#modalRegistro', {
      keyboard: false,
      backdrop: 'static'
   });

   const jqxgridCursos = '#jqxgridCursos';

   const jqxgridCursosSource = {
      datatype: 'json',
      dataFields: [{
            name: 'codcur',
            type: 'string'
         },
         {
            name: 'nombre',
            type: 'string'
         },
         {
            name: 'curabr',
            type: 'string'
         },
         {
            name: 'interno',
            type: 'string'
         }
      ],
      localdata: `<?= json_encode(@$listaCursos) ?>`
   }

   const jqxgridCursosAdapter = new $.jqx.dataAdapter(jqxgridCursosSource);

   function guardarRegistro(form) {
      $.ajax({
         type: "post",
         url: "<?= MODULO_URL ?>/cursos/json/insert",
         data: form,
         beforeSend: function() {
            $(jqxgridCursos).jqxGrid('showloadelement');
         },
         success: function(response) {
            if (response.listaCursos) {
               jqxgridCursosSource.localdata = response.listaCursos;
               $(jqxgridCursos).jqxGrid('updateBoundData');
               modalRegistro.hide();
            }
         },
         error: function(jqXHR) {
            let message = 'Se ha producido un error';
            if (jqXHR.responseJSON) {
               message = jqXHR.responseJSON.message;
            }
            showAlertSweet(message, 'error');
         },
         complete: function() {
            $(jqxgridCursos).jqxGrid('hideloadelement');
         }
      });
   }

   function totalRegistros() {
      const info = $(jqxgridCursos).jqxGrid('getdatainformation');
      $('#totalReg').text(`Total de cursos : ` + info.rowscount);
   }

   async function eliminarCurso(index) {
      const confirm = await showConfirmSweet('¿Esta seguro de eliminar el curso?', 'question');
      if (confirm) {
         let codcur = $(jqxgridCursos).jqxGrid('getcellvalue', index, 'codcur');
         $.ajax({
            type: "get",
            url: "<?= MODULO_URL ?>/cursos/json/delete?codcur=" + codcur,
            beforeSend: function() {
               $(jqxgridCursos).jqxGrid('showloadelement');
            },
            success: function(response) {
               if (response.listaCursos) {
                  jqxgridCursosSource.localdata = response.listaCursos;
                  $(jqxgridCursos).jqxGrid('updateBoundData');
                  showAlertSweet('Curso eliminado', 'success', true);
               }
            },
            error: function(jqXHR) {
               let message = 'Se ha producido un error';
               if (jqXHR.responseJSON) {
                  message = jqXHR.responseJSON.message;
               }
               showAlertSweet(message, 'error');
            },
            complete: function() {
               $(jqxgridCursos).jqxGrid('hideloadelement');
            }
         });
      }
   }

   $(document).ready(function() {
      $(jqxgridCursos).jqxGrid({
         width: '100%',
         height: 630,
         source: jqxgridCursosAdapter,
         editable: true,
         editmode: 'dblclick',
         columns: [{
               text: "Código",
               datafield: "codcur",
               align: 'center',
               cellsalign: 'center',
               width: "10%",
               editable: false
            },
            {
               text: "Nombre",
               datafield: "nombre",
               align: 'center',
               width: "66%",
            },
            {
               text: "Abreviatura",
               datafield: "curabr",
               align: 'center',
               width: "15%",
               cellsalign: 'center',
            },
            {
               text: "Interno",
               datafield: "interno",
               align: 'center',
               width: "9%",
               cellsalign: 'center',
               editable: false
            }
         ]
      });

      $(jqxgridCursos).on('cellendedit', function(event) {
         const args = event.args;
         const rowdata = args.row;
         rowdata.nombre = args.datafield == 'nombre' ? args.value : rowdata.nombre;
         rowdata.curabr = args.datafield == 'curabr' ? args.value : rowdata.curabr;
         $.ajax({
            type: "post",
            url: "<?= MODULO_URL ?>/cursos/json/update",
            data: {
               codcur: rowdata.codcur,
               nombre: rowdata.nombre,
               curabr: rowdata.curabr
            },
            error: function(jqXHR) {
               if (jqXHR.responseJSON) {
                  let erroMsg = jqXHR.responseJSON.error;
                  showAlertSweet(erroMsg, 'error');
               }
            }
         });
      });

      $('#btnNuevo').on('click', function() {
         $('#frmRegistro').trigger("reset");
         frmRegistro.classList.remove('was-validated');
         modalRegistro.show();
      });

      $('#btnDelete').on('click', function() {
         let index = $(jqxgridCursos).jqxGrid('getselectedrowindex');
         if (index >= 0) {
            eliminarCurso(index);
         } else {
            showAlertSweet('Debe seleccionar el curso a eliminar', 'warning');
         }
      });

      $('#frmRegistro').submit(function(e) {
         e.preventDefault();
         if (!frmRegistro.checkValidity()) {
            e.stopPropagation();
         } else {
            let form = $(this).serializeArray();
            guardarRegistro(form);
         }
         frmRegistro.classList.add('was-validated');
      });

      totalRegistros();

   });
</script>
<?= $this->endSection() ?>