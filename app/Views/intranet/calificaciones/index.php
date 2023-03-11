<?= $this->extend('template/layout') ?>
<?= $this->section('css') ?>
<style>
   .color-blue {
      color: blue;
   }

   .color-red {
      color: red;
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
               <li class="breadcrumb-item active" aria-current="page">Calificaciones</li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="row">
      <div class="col-12">
         <div class="card card-main">
            <div class="card-body">
               <div class="row mb-4">
                  <div class="col-sm-4">
                     <label for="cmbsalon" class="form-label">Salón:</label>
                     <select class="form-select" id="cmbsalon">
                        <option value="">-Seleccione-</option>
                        <?php foreach ($listaSalones as $value) { ?>
                           <option value="<?= $value['salon'] ?>"><?= $value['nombre'] ?></option>
                        <?php } ?>
                     </select>
                  </div>
                  <div class="col-sm-5">
                     <label for="cmbcurso" class="form-label">Curso:</label>
                     <select class="form-select" id="cmbcurso">
                        <option value="">-Seleccione-</option>
                     </select>
                  </div>
                  <div class="col-sm-3">
                     <label for="cmbperiodo" class="form-label">Periodo:</label>
                     <select class="form-select" id="cmbperiodo">
                        <option value="">-Seleccione-</option>
                        <?php foreach ($listaPeriodos as $value) { ?>
                           <option value="<?= $value['periodo'] ?>"> <?= \App\Helpers\Funciones::numeroTipoPeriodo($value['periodo']) . " " . $value['periododes'] ?></option>
                        <?php } ?>
                     </select>
                  </div>
               </div>
               <div id="jqxgridCalificacion"></div>
            </div>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script>
   const jqxgridCalificacion = "#jqxgridCalificacion";
   const listaCursosCurricula = JSON.parse('<?= json_encode(@$listaCursosCurricula) ?>');

   const jqxgridCalificacionSource = {
      datatype: 'json',
      dataFields: [{
            name: 'codigo',
            type: 'string'
         },
         {
            name: 'codalu',
            type: 'string'
         },
         {
            name: 'fila',
            type: 'string'
         },
         {
            name: 'nomcomp',
            type: 'string'
         },
         {
            name: 'nota_act',
            type: 'number'
         },
         {
            name: 'nota_exm',
            type: 'number'
         },
         {
            name: 'nota_con',
            type: 'number'
         },
         {
            name: 'nota_pp',
            type: 'number'
         },
         {
            name: 'periodo',
            type: 'string'
         },
         {
            name: 'salon',
            type: 'string'
         },
         {
            name: 'salondes',
            type: 'string'
         }
      ],
      localdata: `[]`
   };

   const jqxgridCalificacionAdapter = new $.jqx.dataAdapter(jqxgridCalificacionSource);

   function validacionNota(cell, value, tipcal = 'N') {
      if (isNaN(value)) {
         return {
            result: false,
            message: "Ingrese un número del 05 a 20"
         };
      } else if (value > 20 || value < 0) {
         return {
            result: false,
            message: "Ingrese un número del 05 a 20"
         };
      }
      return true;
   }

   function calcularNota() {
      let index = $(jqxgridCalificacion).jqxGrid('getselectedrowindex');
   }

   $(document).ready(function() {

      $(jqxgridCalificacion).jqxGrid({
         width: '100%',
         height: 700,
         source: jqxgridCalificacionAdapter,
         editable: true,
         editmode: 'dblclick',
         columns: [{
               text: "Orden",
               align: 'center',
               width: "5%",
               datafield: 'fila',
               cellsalign: 'center',
               editable: false,
            },
            {
               text: "Código",
               align: 'center',
               datafield: 'codalu',
               width: "11%",
               cellsalign: 'center',
               editable: false,
            },
            {
               text: "Alumno",
               datafield: 'nomcomp',
               align: 'center',
               width: "44%",
               editable: false,
            },
            {
               text: "Nota Act.",
               align: 'center',
               width: "10%",
               datafield: 'nota_act',
               cellsalign: 'center',
               columngroup: 'data_periodo',
               validation: validacionNota,
               editable: false,
               cellclassname: function(row, column, value, data) {
                  let rowdata = $(jqxgridCalificacion).jqxGrid('getrowdata', row);
                  let color = rowdata.nota_act >= 11 ? 'color-blue' : (rowdata.nota_act <= 10 && rowdata.nota_act >= 0 ? 'color-red' : '');
                  return color;
               }
            },
            {
               text: "Nota Exm.",
               align: 'center',
               width: "10%",
               cellsalign: 'center',
               datafield: 'nota_exm',
               columngroup: 'data_periodo',
               validation: validacionNota,
               editable: false,
               cellclassname: function(row, column, value, data) {
                  let rowdata = $(jqxgridCalificacion).jqxGrid('getrowdata', row);
                  let color = rowdata.nota_exm >= 11 ? 'color-blue' : (rowdata.nota_exm <= 10 && rowdata.nota_exm >= 0 ? 'color-red' : '');
                  return color;
               }
            },
            {
               text: "Nota Cond.",
               align: 'center',
               width: "10%",
               cellsalign: 'center',
               datafield: 'nota_con',
               columngroup: 'data_periodo',
               validation: validacionNota,
               cellclassname: function(row, column, value, data) {
                  let rowdata = $(jqxgridCalificacion).jqxGrid('getrowdata', row);
                  let color = rowdata.nota_con >= 11 ? 'color-blue' : (rowdata.nota_con <= 10 && rowdata.nota_con >= 0 ? 'color-red' : '');
                  return color;
               }
            },
            {
               text: "Promedio",
               align: 'center',
               datafield: 'nota_pp',
               width: "10%",
               cellsalign: 'center',
               columngroup: 'data_periodo',
               editable: false,
               cellclassname: function(row, column, value, data) {
                  let rowdata = $(jqxgridCalificacion).jqxGrid('getrowdata', row);
                  let color = rowdata.nota_pp >= 11 ? 'color-blue' : (rowdata.nota_pp <= 10 && rowdata.nota_pp >= 0 ? 'color-red' : '');
                  return color;
               }
            }
         ],
         columnGroups: [{
            text: 'Periodo',
            name: 'data_periodo',
            align: 'center'
         }]
      });

      $(jqxgridCalificacion).on('cellendedit', function(event) {
         const args = event.args;
         const rowdata = args.row;
         const datafield = args.datafield;
         $.ajax({
            type: "POST",
            url: "<?= MODULO_URL ?>/calificaciones/json/update-nota",
            data: {
               fsalon: $('#cmbsalon').val(),
               fcurso: $('#cmbcurso').val(),
               fperiodo: $('#cmbperiodo').val(),
               campo: datafield,
               valor: args.value,
               codigo: rowdata.codigo
            },
            beforeSend: function() {
               $(jqxgridCalificacion).jqxGrid('showloadelement');
            },
            success: function(response) {
               if (response.listaCalificacion) {
                  jqxgridCalificacionSource.localdata = response.listaCalificacion;
                  $(jqxgridCalificacion).jqxGrid('updateBoundData', 'data');
               }
            }
         });
      });

      $('#cmbsalon').change(function(e) {
         e.preventDefault();
         let salon = $(this).val();
         let html = '<option>-Seleccione-</option>';
         if (listaCursosCurricula[salon]) {
            $.each(listaCursosCurricula[salon], function(index, value) {
               html += `<option value="${value.curso}">${value.curnomb}</option>`
            });
         }
         $('#cmbcurso').html(html);
      });

      $('#cmbcurso').change(function(e) {
         e.preventDefault();
         $('#cmbperiodo').val('');
      });

      $('#cmbperiodo').change(function(e) {
         e.preventDefault();
         $.ajax({
            type: "POST",
            url: "<?= MODULO_URL ?>/calificaciones/json/listar",
            data: {
               fsalon: $('#cmbsalon').val(),
               fcurso: $('#cmbcurso').val(),
               fperiodo: $('#cmbperiodo').val(),
            },
            beforeSend: function() {
               $(jqxgridCalificacion).jqxGrid('showloadelement');
            },
            success: function(response) {
               if (response.listaCalificacion) {
                  jqxgridCalificacionSource.localdata = response.listaCalificacion;
                  $(jqxgridCalificacion).jqxGrid('updateBoundData', 'data');
                  let perdes = $('#cmbperiodo option:selected').text();
                  $(jqxgridCalificacion).jqxGrid({
                     columnGroups: [{
                        text: perdes,
                        name: 'data_periodo',
                        align: 'center'
                     }]
                  });
               }
            }
         });
      });

   });
</script>
<?= $this->endSection() ?>