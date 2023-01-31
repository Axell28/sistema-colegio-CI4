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

   const jqxgridCalificacionSource = {
      datatype: 'json',
      localdata: `<?= json_encode(@$listaAlumnos) ?>`
   };

   const jqxgridCalificacionAdapter = new $.jqx.dataAdapter(jqxgridCalificacionSource);

   $(document).ready(function() {

      $(jqxgridCalificacion).jqxGrid({
         width: '100%',
         height: 700,
         source: jqxgridCalificacionAdapter,
         editable: false,
         columns: [{
               text: "Nro",
               align: 'center',
               datafield: 'codalu',
               width: "7%",
               cellsalign: 'center',
            },
            {
               text: "Alumno",
               datafield: 'nomcomp',
               align: 'center',
               width: "27%",
            }
            <?php for ($i = 0; $i < 6; $i++) { ?>, {
                  text: "Sem <?= ($i + 1) ?>",
                  align: 'center',
                  width: "5%",
                  cellsalign: 'center',
                  columngroup: 'data_bimestre'
               },
            <?php } ?>
         ],
         columnGroups: [{
            text: 'I BIMESTRE',
            name: 'data_bimestre',
            align: 'center'
         }]
      });

   });
</script>
<?= $this->endSection() ?>