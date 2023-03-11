<?php if (empty(@$listaItemsPub)) { ?>
     <div class="text-center pb-2">
          <p class="mb-0">No hay contenido que mostrar</p>
     </div>
<?php } ?>

<?php foreach (@$listaItemsPub as $value) {
     $color = "secondary";
     switch ($value['tipo']) {
          case 'S':
               $color = 'success';
               break;
          case 'T':
               $color = 'warning';
               break;
          case 'E':
               $color = 'info';
               break;
          default:
               $color = 'danger';
               break;
     }
     $dt_fecpub = new \DateTime($value['fecpub']);
     $dt_fecmax = !empty($value['fecmax']) ? new \DateTime($value['fecmax']) : new \DateTime();
?>
     <div class="card bg-white border-<?= $color ?> mb-4 text-start" id="card-item-" <?= $value['codigo'] ?>>
          <div class="card-header text-white border-<?= $color ?> bg-<?= $color ?>">
               <div class="d-flex">
                    <div class="me-2" style="font-size: 14px;"><i class="fas fa-book"></i></div>
                    <div class="text-truncate" style="font-size: 14px;">
                         <?= $value['titulo'] ?>
                    </div>
                    <div class="ms-auto">
                         <?php if (@$esDocente || @$esAdmin) { ?>
                              <span title="Eliminar" onclick="eliminarItemGrupo(<?= $value['codigo'] ?>)" style="cursor: pointer;"><i class="far fa-trash-alt"></i></span>
                         <?php } ?>
                    </div>
               </div>
          </div>
          <div class="card-body">
               <div><?= $value['cuerpo'] ?></div>
          </div>
          <?php if (!empty($value['adjuntos'])) { ?>
               <div class="px-3 py-2">
                    <?php foreach ($value['adjuntos'] as $adjunto) { ?>
                         <div class="alert alert-dark mb-2" style="background-color: rgba(245, 245, 245); padding: 9px 14px;">
                              <div class="d-flex align-items-center gap-2">
                                   <i class="fad fa-file-download fs-4"></i>
                                   <a href="<?= base_url($adjunto['ruta']) ?>" class="alert-link fw-normal" title="Descargar" download="<?= $adjunto['nombre'] ?>"><?= $adjunto['nombre'] ?></a>
                              </div>
                         </div>
                    <?php } ?>
               </div>
          <?php } ?>
          <div class="card-footer border-<?= $color ?>" style="background: transparent;">
               <div>
                    <i class="far fa-calendar-alt"></i>
                    <span>&nbsp;Publicado desde: <?= date('d \d\e M \d\e\l Y, h:i A', $dt_fecpub->getTimestamp()) ?></span>
               </div>
               <?php if ($value['evaluar'] == 'S') { ?>
                    <div class="mt-2">
                         <i class="far fa-calendar-exclamation"></i>
                         <span>&nbsp;Cierre de evaluación : <?= date('d \d\e M \d\e\l Y, h:i A', $dt_fecmax->getTimestamp()) ?> </span>
                    </div>
               <?php } ?>
          </div>
          <?php if ($value['evaluar'] == 'S') { ?>
               <div class="card-footer border-<?= $color ?>" style="background: transparent;">
                    <?php if ((@$esDocente || @$esAdmin)) { ?>
                         <a href="<?= MODULO_URL ?>/cursos/enviados/<?= esc($salon) ?>/<?= $value['codigo'] ?>" class="btn btn-outline-<?= $color ?> w-100">
                              <i class="fas fa-clipboard-check"></i>
                              <span>&nbsp;Revisar enviós</span>
                         </a>
                    <?php } ?>
                    <?php if (@$esAlumno) { ?>

                         <?php if (!empty($value['respuesta'])) { ?>

                              <div class="alert alert-success d-flex align-items-center justify-content-center gap-2 mb-0">
                                   <i class="fas fa-smile fs-3"></i>
                                   <span><?= $value['tipo'] == 'T' ? 'Actividad' : ($value['tipo'] == 'E' ? 'Evaluación' : 'Tarea') ?> entregada el <span class="alert-link">&nbsp;<?= $value['respuesta']['fecenv_format'] ?></span></span>
                              </div>

                         <?php } else { ?>

                              <?php if ($value['fecha_vencidad'] == 'S') { ?>
                                   <div class="alert alert-warning d-flex align-items-center justify-content-center gap-2 mb-0">
                                        <i class="fas fa-frown-open fs-3"></i>
                                        <span>Tarea no fue entregada a tiempo.</span>
                                   </div>
                              <?php } else { ?>
                                   <button class="btn btn-outline-<?= $color ?> w-100 btn-send" codigo="<?= $value['codigo'] ?>">
                                        <i class="fas fa-share-alt"></i>
                                        <span>&nbsp; <?= $value['tipo'] == 'T' ? 'Enviar actividad' : ($value['tipo'] == 'E' ? 'Enviar examen' : 'Enviar tarea') ?></span>
                                   </button>
                              <?php } ?>

                         <?php } ?>
                    <?php } ?>
               </div>
          <?php } ?>
     </div>
<?php } ?>

<div class="modal fade" id="modalRespuesta" tabindex="-1" role="dialog" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered" role="document"></div>
</div>

<!-- TEST MBARDALES WhAI&WQfPB -->
<script>
     async function eliminarItemGrupo(codigo) {
          let confirm = await showConfirmSweet('¿Esta seguró de eliminar este contenido?', 'question');
          if (confirm) {
               $.ajax({
                    type: "POST",
                    url: "<?= MODULO_URL ?>/cursos/json/eliminar-item",
                    data: {
                         grupo: '<?= @$grupo ?>',
                         item: codigo
                    },
                    success: function(response) {
                         $('#card-item-' + codigo).remove();
                         listaAuvGrupoItems('<?= @$grupo ?>');
                    },
                    error: function(jqXHR, status, error) {
                         let message = error;
                         if (jqXHR.responseJSON) {
                              message = jqXHR.responseJSON.message;
                         }
                         showAlertSweet(message, 'error');
                    }
               });
          }
     };
     (function() {
          const modalRespuesta = document.getElementById('modalRespuesta');
          const modalRespuestaEvent = new bootstrap.Modal(modalRespuesta);
          modalRespuesta.addEventListener('hidden.bs.modal', event => {
               $('#modalRespuesta .modal-dialog').html('');
          });

          $('.btn-send').click(function(e) {
               let codigo = $(this).attr('codigo');
               let grupo = '<?= @$grupo ?>';
               $.ajax({
                    type: "POST",
                    url: "<?= MODULO_URL ?>/cursos/respuesta",
                    data: {
                         auvitem: codigo,
                         grupo: grupo,
                         salon: '<?= @$salon ?>'
                    },
                    success: function(response) {
                         $('#modalRespuesta .modal-dialog').html(response);
                         modalRespuestaEvent.show();
                    }
               });
          });
     })();

     document.addEventListener('focusin', function(e) {
          if (e.target.closest(".tox-tinymce-aux, .moxman-window, .tam-assetmanager-root") !== null) {
               e.stopImmediatePropagation();
          }
     });
</script>