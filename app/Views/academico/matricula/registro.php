<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
     <div class="row mt-1 mb-3">
          <div class="col-12">
               <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                         <li class="breadcrumb-item"><a href="<?= MODULO_URL ?>"><?= MODULO_NAME ?></a></li>
                         <li class="breadcrumb-item"><a href="<?= MODULO_URL ?>/matricula">Matricula</a></li>
                         <li class="breadcrumb-item active" aria-current="page">Registro</li>
                    </ol>
               </nav>
          </div>
     </div>
     <div class="row">
          <div class="col-lg-6">
               <div class="card card-main">
                    <div class="card-body">
                         <p class="text-muted">Alumnos no matriculados</p>
                         <div id="jqxgridAlumnos"></div>
                    </div>
               </div>
          </div>
          <div class="col-lg-6">
               <div class="card card-main">
                    <div class="card-body">

                    </div>
               </div>
          </div>
     </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script>
     const jqxgridAlumnos = '#jqxgridAlumnos';

     const jqxgridAlumnosSource = {
          datatype: 'json',
          dataFields: [{
                    name: 'codalu',
                    type: 'string'
               },
               {
                    name: 'nomcomp',
                    type: 'string'
               },
               {
                    name: 'numdoc',
                    type: 'string'
               }
          ],
          localdata: `<?= json_encode(@$listaAlumnosNoMatriculados) ?>`
     };

     const jqxgridAlumnosAdapter = new $.jqx.dataAdapter(jqxgridAlumnosSource);

     $(document).ready(function() {

          $(jqxgridAlumnos).jqxGrid({
               width: '100%',
               height: 670,
               source: jqxgridAlumnosAdapter,
               showfilterrow: true,
               filterable: true,
               columns: [{
                         text: "Código",
                         datafield: "codalu",
                         align: 'center',
                         cellsalign: 'center',
                         width: "20%",
                    },
                    {
                         text: "Apellidos y Nombres",
                         datafield: "nomcomp",
                         align: 'center',
                         width: "60%",
                    },
                    {
                         text: "DNI",
                         datafield: "numdoc",
                         align: 'center',
                         cellsalign: 'center',
                         width: "20%",
                    }
               ]
          });

     });
</script>
<?= $this->endSection() ?>