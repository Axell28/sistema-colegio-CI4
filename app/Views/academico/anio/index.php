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
   <div class="row mb-4">
      <div class="col-lg-12">
         <div class="card card-main">
            <div class="card-body">
               <div class="row mb-3">
                  <div class="col-12">
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
   <div class="row">
      <div class="col-lg-12">
         <div class="card card-main">
            <div class="card-body">
               <div class="row mb-3">
                  <div class="col-12">
                     <button class="btn btn-success" id="btnNuevoPeriodo">
                        <i class="far fa-calendar-check"></i>
                        <span>&nbsp;Agregar Periodo</span>
                     </button>
                  </div>
               </div>
               <div id="jqxgridAnioPeriodo"></div>
            </div>
         </div>
      </div>
   </div>

   <?= $this->include('academico/anio/registro') ?>

</div>

<div class="modal fade" id="modalPeriodo" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered"></div>
</div>

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="<?= base_url('js/jqwidgets/jqxcheckbox.js') ?>"></script>
<script>
   const jqxgridAnioPeriodo = "#jqxgridAnioPeriodo";
   const frmRegistro = document.getElementById('frmRegistro');

   const modalRegistro = new bootstrap.Modal('#modalRegistro', {
      keyboard: false,
      backdrop: 'static'
   });

   const modalPeriodo = document.getElementById('modalPeriodo');
   const modalPeriodoEvent = new bootstrap.Modal(modalPeriodo, {
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

   const jqxgridAnioPeriodoSource = {
      datatype: 'json',
      dataFields: [{
            name: 'anio',
            type: 'string'
         },
         {
            name: 'periodo',
            type: 'string'
         },
         {
            name: 'tipo',
            type: 'string'
         },
         {
            name: 'fecini',
            type: 'date',
            format: 'yyyy-MM-dd'
         },
         {
            name: 'fecfin',
            type: 'date',
            format: 'yyyy-MM-dd'
         },
         {
            name: 'periododes',
            type: 'string'
         },
         {
            name: 'estado',
            type: 'string'
         },
         {
            name: 'estado',
            type: 'string'
         },
         {
            name: 'estadodes',
            type: 'string'
         }
      ],
      localdata: `[]`
   }

   const jqxgridAnioEscolarAdapter = new $.jqx.dataAdapter(jqxgridAnioEscolarSource);

   const jqxgridAnioPeriodoAdapter = new $.jqx.dataAdapter(jqxgridAnioPeriodoSource);

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

   function openModalPeriodo(action = 'I', periodo = null) {
      let rowIndex = $(jqxgridAnioEscolar).jqxGrid('getselectedrowindex');
      $.ajax({
         type: "POST",
         url: "<?= MODULO_URL ?>/anio-academico/periodo",
         data: {
            action: action,
            periodo: periodo,
            anio: $(jqxgridAnioEscolar).jqxGrid('getcellvalue', rowIndex, 'anio')
         },
         beforeSend: function() {
            $('#modalPeriodo .modal-dialog').html(getLoadingModal());
            modalPeriodoEvent.show();
         },
         success: function(response) {
            $('#modalPeriodo .modal-dialog').html(response);
         },
         error: function(jqXHR, status, error) {
            let errorMsg = error;
            if (jqXHR.responseJSON) {
               errorMsg = jqXHR.responseJSON.message;
            }
            showAlertSweet(errorMsg, 'error');
            modalPeriodoEvent.hide();
         }
      });
   }

   $(document).ready(function() {

      $(jqxgridAnioEscolar).jqxGrid({
         width: '100%',
         height: 300,
         source: jqxgridAnioEscolarAdapter,
         editable: true,
         columns: [{
               text: "Año",
               datafield: "anio",
               align: 'center',
               cellsalign: 'center',
               width: "10%",
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
               width: "13%",
               cellsrenderer: function(row, column, value) {
                  if (value == 'A') {
                     return `<div class="jqx-center-align" style="padding-top: 12px"><span class="badge text-bg-success text-white">Abierto</span></div>`;
                  } else if (value == 'C') {
                     return `<div class="jqx-center-align" style="padding-top: 12px"><span class="badge text-bg-danger">Cerrado</span></div>`;
                  }
               }
            }
         ]
      });

      $(jqxgridAnioPeriodo).jqxGrid({
         width: '100%',
         height: 187,
         source: jqxgridAnioPeriodoAdapter,
         editable: false,
         columns: [{
               text: "Año",
               datafield: "anio",
               align: 'center',
               cellsalign: 'center',
               width: "15%"
            },
            {
               text: "Periodo",
               datafield: "periodo",
               align: 'center',
               cellsalign: 'center',
               width: "10%"
            },
            {
               text: "Tipo periodo",
               datafield: "periododes",
               align: 'center',
               cellsalign: 'center',
               width: "15%"
            },
            {
               text: "Fecha Inicio",
               datafield: "fecini",
               align: 'center',
               cellsalign: 'center',
               width: "20%",
               cellsformat: 'dd/MM/yyyy'
            },
            {
               text: "Fecha Fin",
               datafield: "fecfin",
               align: 'center',
               cellsalign: 'center',
               width: "20%",
               cellsformat: 'dd/MM/yyyy'
            },
            {
               text: "Estado",
               datafield: "estadodes",
               align: 'center',
               cellsalign: 'center',
               width: "16%"
            },
            {
               text: '',
               width: '4%',
               cellsrenderer: function(row, column, value) {
                  let rowdata = $(jqxgridAnioPeriodo).jqxGrid('getrowdata', row);
                  return `<div class="jqx-center-align"><button class="btn btn-link text-info" onclick="openModalPeriodo('E', '${rowdata.periodo}')" title="Editar"><i class="fas fa-pencil-alt"></i></button></div>`;
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

      $(jqxgridAnioEscolar).on('rowselect', function(e) {
         $.ajax({
            type: "POST",
            url: "<?= MODULO_URL ?>/anio-academico/json/list-periodos",
            data: {
               anio: e.args.row.anio
            },
            success: function(response) {
               if (response.listaAnioPeriodos) {
                  jqxgridAnioPeriodoSource.localdata = response.listaAnioPeriodos;
                  $(jqxgridAnioPeriodo).jqxGrid('updateBoundData');
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

      $('#btnNuevoPeriodo').click(function(e) {
         openModalPeriodo('I');
      });

      $(jqxgridAnioEscolar).jqxGrid('selectrow', 0);

   });
</script>
<?= $this->endSection() ?>