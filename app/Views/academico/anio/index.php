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
               <li class="breadcrumb-item"><a href="<?= MODULO_URL ?>"><?= MODULO_NAME ?></a></li>
               <li class="breadcrumb-item active" aria-current="page">Año Académico</li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="row">
      <div class="col-lg-12">
         <div class="card card-main">
            <div class="card-body">
               <div class="row mb-3">
                  <div class="col">
                     <button class="btn btn-primary" id="btnNuevo">
                        <i class="fas fa-plus-circle"></i>
                        <span>&nbsp;Nuevo año academico</span>
                     </button>
                  </div>
               </div>
               <div id="jqxgridAnioEscolar"></div>
            </div>
         </div>
      </div>
   </div>

   <?= $this->include('academico/anio/registro') ?>

</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="<?= base_url('js/jqwidgets/jqxcheckbox.js') ?>"></script>
<script>
   const frmRegistro = document.getElementById('frmRegistro');

   const modalRegistro = new bootstrap.Modal('#modalRegistro', {
      keyboard: false,
      backdrop: 'static'
   });

   const jqxgridAnioEscolarSource = {
      datatype: 'json',
      dataFields: [{
            name: 'anio',
            type: 'string'
         },
         {
            name: 'nombre',
            type: 'string'
         },
         {
            name: 'fecini',
            type: 'string'
         },
         {
            name: 'fecfin',
            type: 'string'
         },
         {
            name: 'vigente',
            type: 'bool'
         },
         {
            name: 'matricula',
            type: 'bool'
         },
         {
            name: 'estado',
            type: 'string'
         }
      ],
      localdata: `<?= json_encode(@$listaAnios) ?>`
   }

   const jqxgridAnioEscolarAdapter = new $.jqx.dataAdapter(jqxgridAnioEscolarSource);

   function guardarRegistro(form) {
      const action = $('#txtaction').val() == 'I' ? 'insert' : 'update';
      $.ajax({
         type: "post",
         url: "<?= MODULO_URL ?>/anio-academico/json/" + action,
         data: form,
         beforeSend: function() {
            $(jqxgridAnioEscolar).jqxGrid('showloadelement');
         },
         success: function(data) {
            if (data.listaAnios) {
               jqxgridAnioEscolarSource.localdata = data.listaAnios;
               $(jqxgridAnioEscolar).jqxGrid('updateBoundData');
               modalRegistro.hide();
            }
         },
         error: function(jqXHR) {
            if (jqXHR.responseJSON) {
               let erroMsg = jqXHR.responseJSON.message;
               $('#boxAlert').show();
               $('#boxAlert span').text(erroMsg);
            }
         },
         complete: function() {
            $(jqxgridAnioEscolar).jqxGrid('hideloadelement');
         }
      });
   }

   $(document).ready(function() {

      $(jqxgridAnioEscolar).jqxGrid({
         width: '100%',
         height: 340,
         source: jqxgridAnioEscolarAdapter,
         editable: true,
         columns: [{
               text: "Año",
               datafield: "anio",
               align: 'center',
               cellsalign: 'center',
               width: "8%",
               editable: false
            },
            {
               text: "Nombre",
               datafield: "nombre",
               align: 'center',
               width: "27%",
               editable: false
            },
            {
               text: "Fecha Inicio",
               datafield: "fecini",
               align: 'center',
               width: "15%",
               cellsalign: 'center',
               editable: false
            },
            {
               text: "Fecha Fin",
               datafield: "fecfin",
               align: 'center',
               width: "15%",
               cellsalign: 'center',
               editable: false
            },
            {
               text: "Vigente",
               datafield: "vigente",
               align: 'center',
               width: "10%",
               editable: true,
               cellsalign: 'center',
               columntype: 'checkbox'
            },
            {
               text: "Matricula",
               datafield: "matricula",
               align: 'center',
               width: "10%",
               editable: true,
               cellsalign: 'center',
               columntype: 'checkbox'
            },
            {
               text: "Estado",
               datafield: 'estado',
               editable: false,
               align: 'center',
               width: "10%",
               cellsrenderer: function(row, column, value) {
                  if (value == 'A') {
                     return `<div class="jqx-center-align" style="padding-top: 11px"><span class="badge text-bg-success text-white">Abierto</span></div>`;
                  } else if (value == 'C') {
                     return `<div class="jqx-center-align" style="padding-top: 11px"><span class="badge text-bg-danger">Cerrado</span></div>`;
                  }
               }
            },
            {
               text: '',
               width: '5%',
               cellsrenderer: function(row, column, value) {
                  return `<div class="jqx-center-align">
                     <button class="btn btn-link text-primary" title="Programar periodos">
                        <i class="far fa-calendar-check fs-5"></i>
                     </button>
                  </div>`;
               }
            },
         ]
      });

      $(jqxgridAnioEscolar).on('cellendedit', function(event) {
         const args = event.args;
         const rowdata = args.row;
         if (args.datafield == 'vigente' && !args.value) {
            $(jqxgridAnioEscolar).jqxGrid('updateBoundData', 'data');
            return;
         }
         $.ajax({
            type: "post",
            url: "<?= MODULO_URL ?>/anio-academico/json/update-" + args.datafield,
            data: {
               anio: rowdata.anio,
               valor: args.value ? 'S' : 'N'
            },
            success: function(data) {
               if (data.listaAnios) {
                  jqxgridAnioEscolarSource.localdata = data.listaAnios;
                  $(jqxgridAnioEscolar).jqxGrid('updateBoundData');
               }
            }
         });
      });

      $(jqxgridAnioEscolar).on('rowdoubleclick', function(event) {
         const args = event.args;
         const data = args.row.bounddata;
         const parse_fecini = data.fecini.split('/').reverse().join('-');
         const parse_fecfin = data.fecfin.split('/').reverse().join('-');
         $('#txtanio').val(data.anio);
         $('#txtanio').prop("readonly", true);
         $('#txtnombre').val(data.nombre);
         $('#txtfecini').val(parse_fecini);
         $('#txtfecfin').val(parse_fecfin);
         $('#txtaction').val('E');
         modalRegistro.show();
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

      $('#btnNuevo').on('click', function() {
         $('#boxAlert').hide();
         $('#txtanio').prop("readonly", false);
         $('#frmRegistro').trigger("reset");
         $('#txtaction').val('I');
         frmRegistro.classList.remove('was-validated');
         modalRegistro.show();
      });

   });
</script>
<?= $this->endSection() ?>