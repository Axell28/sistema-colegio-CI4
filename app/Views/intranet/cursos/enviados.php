<?= $this->extend('template/layout') ?>
<?= $this->section('css') ?>
<style>
     h4.titulo {
          font-weight: bold;
          text-transform: uppercase;
          font-size: 15px;
     }

     .table {
          border: 1px solid #E6E7EC;
     }

     .table th {
          text-align: center;
          text-transform: uppercase;
          font-size: 11px;
          background-color: #F8F8F8;
     }

     .table td {
          vertical-align: middle;
     }

     tr.row_item_S {
          background-color: #F1FFF1;
     }

     tr.row_item_N {
          background-color: white;
     }

     @media only screen and (max-width: 1380px) {
          thead th:first-child {
               display: none;
          }

          tbody td:first-child {
               display: none;
          }
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
                         <li class="breadcrumb-item"><a href="<?= MODULO_URL ?>/cursos">Cursos</a></li>
                         <li class="breadcrumb-item active" aria-current="page">Trabajos Enviados</li>
                    </ol>
               </nav>
          </div>
     </div>
     <div class="row">
          <div class="col-lg">
               <div class="card card-main">
                    <div class="card-body">
                         <div class="row mb-3">
                              <div class="col-sm pt-2">
                                   <h4 class="titulo mb-3">Curso : &nbsp;<?= esc($datosItemAuv['nombre']) ?></h4>
                                   <h6 style="color: #5B6B85;"><?= esc($datosItemAuv['tipodes']) . " : " . esc($datosItemAuv['titulo']) ?></h6>
                              </div>
                              <div class="col-auto my-auto">
                                   <select class="form-select" id="cmbEnviados">
                                        <option value="">-Todos-</option>
                                        <option value="S">Enviados</option>
                                        <option value="N">No enviados</option>
                                   </select>
                              </div>
                              <div class="col-auto my-auto text-end">
                                   <button class="btn btn-primary" id="btnGuardar"><i class="fas fa-save"></i><span>&nbsp; Guardar cambios</span></button>
                              </div>
                         </div>
                         <hr>
                         <div class="table-responsive">
                              <table class="table">
                                   <thead>
                                        <tr>
                                             <th style="min-width: 100px; width: 100px;">Código</th>
                                             <th class="text-start" style="min-width: 170px;">Estudiante</th>
                                             <th style="min-width: 90px; width: 90px;">Estado</th>
                                             <th class="text-center" style="min-width: 100px;">Solución</th>
                                             <th style="min-width: 180px; width: 180px;">Fec. entrega</th>
                                             <th style="min-width: 70px; width: 70px;">Nota</th>
                                             <th style="min-width: 95px; width: 95px;">Revisado</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        <?php foreach (esc($listaRespuestas) as $value) : ?>
                                             <tr class="row_item_<?= $value['enviado'] ?>">
                                                  <td class="text-center"><?= $value['codalu'] ?></td>
                                                  <td>
                                                       <span><?= $value['nomcomp'] ?>&nbsp;&nbsp;</span>
                                                       <?php if (!empty($value['comentalu'])) { ?>
                                                            <span title="<?= $value['comentalu'] ?>">
                                                                 <i class="fas fa-comment-alt"></i>
                                                            </span>
                                                       <?php } ?>
                                                  </td>
                                                  <td class="text-center">
                                                       <?php if ($value['enviado'] == 'S') { ?>
                                                            <span class="badge text-bg-success text-white">Enviado</span>
                                                       <?php } else { ?>
                                                            <span class="badge text-bg-danger">No enviado</span>
                                                       <?php } ?>
                                                  </td>
                                                  <td class="text-center">
                                                       <?php if (!empty($value['adjuntos'])) { ?>
                                                            <?php foreach ($value['adjuntos'] as $key => $adjunto) { ?>
                                                                 <div class="text-truncate" style="min-width: 100px;">
                                                                      <a href="<?= $adjunto['ruta'] ?>" target="_blank">Archivo adjuntado N#<?= $adjunto['orden'] ?></a>
                                                                 </div>
                                                            <?php } ?>
                                                       <?php } ?>
                                                  </td>
                                                  <td class="text-center"><?= $value['fecenv'] ?></td>
                                                  <td>
                                                       <input type="text" class="form-control text-center text-nota" codalu="<?= $value['codalu'] ?>" value="<?= $value['nota'] ?>" maxlength="2">
                                                  </td>
                                                  <td>
                                                       <div class="d-flex align-items-center">
                                                            <div class="form-check mx-auto">
                                                                 <input class="form-check-input" type="checkbox" id="chk-<?= $value['codalu'] ?>" <?= $value['revisado'] == 'S' ? 'checked' : '' ?> style="transform: scale(1.3);">
                                                            </div>
                                                       </div>
                                                  </td>
                                             </tr>
                                        <?php endforeach; ?>
                                   </tbody>
                              </table>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script>
     $(document).ready(function() {

          $('#cmbEnviados').change(function(e) {
               let valor = $(this).val();
               if (valor == 'S') {
                    $('.row_item_N').hide();
                    $('.row_item_S').show();
               } else if (valor == 'N') {
                    $('.row_item_S').hide();
                    $('.row_item_N').show();
               } else {
                    $('.row_item_N').show();
                    $('.row_item_S').show();
               }
          });

          $('#btnGuardar').click(function(e) {
               let listado = [];
               $('tbody .text-nota').each(function(index, element) {
                    let codalu = $(element).attr('codalu');
                    let nota = $(element).val();
                    let revisado = $('#chk-' + codalu).prop('checked');
                    listado.push({
                         codalu,
                         nota,
                         revisado: revisado ? 'S' : 'N'
                    });
               });
               $.ajax({
                    type: "POST",
                    url: "<?= MODULO_URL ?>/cursos/json/save-respuestas",
                    data: {
                         salon: '<?= esc($datosItemAuv['salon']) ?>',
                         curso: '<?= esc($datosItemAuv['curso']) ?>',
                         periodo: '<?= esc($datosItemAuv['periodo']) ?>',
                         coditem: '<?= @$coditem ?>',
                         listado: JSON.stringify(listado)
                    },
                    success: function(response) {
                         showAlertSweet('Cambios actualizados correctamente.', 'success', true);
                    }
               });
          });

     });
</script>
<?= $this->endSection() ?>