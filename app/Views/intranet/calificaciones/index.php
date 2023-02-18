<?= $this->extend('template/layout') ?>
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
      localdata: `[]`
   };

   const jqxgridCalificacionAdapter = new $.jqx.dataAdapter(jqxgridCalificacionSource);

   function validacionNota(cell, value, tipcal = 'N') {
      if (isNaN(value)) {
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
               text: "Nro",
               align: 'center',
               width: "6%",
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
               width: "auto",
               editable: false,
            },
            {
               text: "Comp. 1",
               align: 'center',
               width: "9%",
               datafield: 'nota_c1',
               cellsalign: 'center',
               columngroup: 'data_periodo',
               validation: validacionNota
            },
            {
               text: "Comp. 2",
               align: 'center',
               width: "9%",
               cellsalign: 'center',
               datafield: 'nota_c2',
               columngroup: 'data_periodo',
               validation: validacionNota
            },
            {
               text: "Comp. 3",
               align: 'center',
               width: "9%",
               cellsalign: 'center',
               datafield: 'nota_c3',
               columngroup: 'data_periodo',
               validation: validacionNota
            },
            {
               text: "Comp. 4",
               align: 'center',
               width: "9%",
               cellsalign: 'center',
               datafield: 'nota_c4',
               columngroup: 'data_periodo',
               validation: validacionNota
            },
            {
               text: "Prom.",
               align: 'center',
               width: "9%",
               cellsalign: 'center',
               datafield: 'nota_pp',
               columngroup: 'data_periodo',
               editable: false
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
         let n1 = datafield == 'nota_c1' ? parseInt(args.value) : (rowdata.nota_c1 !== null ? parseInt(rowdata.nota_c1) : 0);
         let n2 = datafield == 'nota_c2' ? parseInt(args.value) : (rowdata.nota_c2 !== null ? parseInt(rowdata.nota_c2) : 0);
         let n3 = datafield == 'nota_c3' ? parseInt(args.value) : (rowdata.nota_c3 !== null ? parseInt(rowdata.nota_c3) : 0);
         let n4 = datafield == 'nota_c4' ? parseInt(args.value) : (rowdata.nota_c4 !== null ? parseInt(rowdata.nota_c4) : 0);
         let prom = (int)(n1 + n2 + n3 + n4) / 4;
         $(jqxgridCalificacion).jqxGrid('setcellvalue', args.rowindex, 'nota_pp', prom);
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