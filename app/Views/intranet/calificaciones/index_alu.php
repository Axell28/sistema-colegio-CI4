<?= $this->extend('template/layout') ?>
<?= $this->section('css') ?>
<style>
     .table th {
          text-transform: uppercase;
          font-weight: normal;
          vertical-align: middle;
          pointer-events: none;
     }

     .table td {
          vertical-align: middle;
     }

     tr.nota-cur {
          background-color: #CDFCBE;
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
          <div class="col-lg-12">
               <div class="card card-main">
                    <div class="card-body">
                         <div class="row mb-3">
                              <div class="col-sm-2 my-2">
                                   <button class="btn btn-danger" id="btn-reporte" type="button">
                                        <i class="fas fa-file-alt"></i><span>&nbsp; Reporte de notas</span>
                                   </button>
                              </div>
                         </div>
                         <div class="table-responsive">
                              <table class="table table-bordered mb-0">
                                   <thead class="bg-info text-white">
                                        <tr>
                                             <th rowspan="2" class="text-center" style="min-width: 180px;">Curso</th>
                                             <th colspan="<?= esc($totalPeriodos + 1) ?>" class="text-center">Periodos</th>
                                        </tr>
                                        <tr>
                                             <?php foreach (esc($listaPeriodos) as $per) { ?>
                                                  <th style="min-width: 100px; width: 110px;" class="text-center"><?= $per['periodo'] . " " . substr($per['periododes'], 0, 4) ?></th>
                                             <?php } ?>
                                             <th style="min-width: 100px; width: 120px;" class="text-center">Prom. Anual</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        <?php if (empty(@$listaCurricula)) { ?>
                                             <tr>
                                                  <td class="text-center" colspan="<?= esc($totalPeriodos + 2) ?>">No hay notas disponibles</td>
                                             </tr>
                                        <?php } else { ?>
                                             <?php foreach (@$listaCurricula as $value) { ?>
                                                  <tr class="nota-cur fw-bold">
                                                       <td class="text-uppercase"> <i class="far fa-chevron-right"></i>&nbsp;&nbsp; <?= $value['curnom'] ?></td>
                                                       <?php foreach (esc($listaPeriodos) as $per) {
                                                            $periodo = $per['periodo'];
                                                            $curnota = isset($value['notas'][$periodo]) ? $value['notas'][$periodo] : array();
                                                            $notaPP = isset($curnota['nota_pp']) ? $curnota['nota_pp'] : "";
                                                       ?>
                                                            <td class="text-center"><?= $notaPP ?></td>
                                                       <?php } ?>
                                                       <td class="text-center"></td>
                                                  </tr>
                                                  <tr class="bg-light">
                                                       <td class="ps-4"><i class="far fa-circle"></i>&nbsp;&nbsp; Prom. de actividades</td>
                                                       <?php foreach (esc($listaPeriodos) as $per) {
                                                            $periodo = $per['periodo'];
                                                            $curnota = isset($value['notas'][$periodo]) ? $value['notas'][$periodo] : array();
                                                            $notaACT = isset($curnota['nota_act']) ? $curnota['nota_act'] : "";
                                                       ?>
                                                            <td class="text-center"><?= $notaACT ?></td>
                                                       <?php } ?>
                                                       <td class="text-center"></td>
                                                  </tr>
                                                  <tr class="bg-light">
                                                       <td class="ps-4"><i class="far fa-circle"></i>&nbsp;&nbsp; Prom. de exámenes</td>
                                                       <?php foreach (esc($listaPeriodos) as $per) {
                                                            $periodo = $per['periodo'];
                                                            $curnota = isset($value['notas'][$periodo]) ? $value['notas'][$periodo] : array();
                                                            $notaEXM = isset($curnota['nota_exm']) ? $curnota['nota_exm'] : "";
                                                       ?>
                                                            <td class="text-center"><?= $notaEXM ?></td>
                                                       <?php } ?>
                                                       <td class="text-center"></td>
                                                  </tr>
                                                  <tr class="bg-light">
                                                       <td class="ps-4"><i class="far fa-circle"></i>&nbsp;&nbsp; Prom. de conducta</td>
                                                       <?php foreach (esc($listaPeriodos) as $per) {
                                                            $periodo = $per['periodo'];
                                                            $curnota = isset($value['notas'][$periodo]) ? $value['notas'][$periodo] : array();
                                                            $notaCON = isset($curnota['nota_con']) ? $curnota['nota_con'] : "";
                                                       ?>
                                                            <td class="text-center"><?= $notaCON ?></td>
                                                       <?php } ?>
                                                       <td class="text-center"></td>
                                                  </tr>
                                             <?php } ?>
                                        <?php } ?>
                                   </tbody>
                              </table>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>

<form id="frmReporte" action="<?= MODULO_URL ?>/reporte/generate" target="_blank" method="POST">
     <input type="hidden" name="codrep" value="006">
     <input type="hidden" name="codalu" value="<?= CODIGO ?>">
</form>

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script>
     $(document).ready(function() {

          $('#btn-reporte').click(function(e) {
               e.preventDefault();
               $('#frmReporte').submit();
          });

     });
</script>
<?= $this->endSection() ?>