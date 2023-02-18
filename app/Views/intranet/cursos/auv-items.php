<?php if (empty(@$listaItemsPub)) { ?>
     <div class="text-center pb-2">
          <p class="mb-0">No hay contenido que mostrar</p>
     </div>
<?php } ?>

<?php foreach (@$listaItemsPub as $value) {
     $color = $value['tipo'] == 'A' ? 'success' : ($value['tipo'] == 'T' ? 'danger' : 'primary');
?>
     <div class="card border-<?= $color ?> mb-3 text-start" id="card-item-" <?= $value['codigo'] ?>>
          <div class="card-header text-<?= $color ?> border-<?= $color ?>" style="background: transparent;">
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
                    <span>&nbsp;Publicado el <?= date('d M, Y h:i A', strtotime($value['fecpub'])) ?></span>
               </div>
               <div class="mt-2">
                    <i class="far fa-calendar-exclamation"></i>
                    <span>&nbsp;Fecha max. para responder: <?= date('d M, Y h:i A', strtotime($value['fecmax'])) ?> </span>
               </div>
          </div>
          <div class="card-footer border-<?= $color ?>" style="background: transparent;">
               <?php if ((@$esDocente || @$esAdmin) && $value['tipo'] == 'T') { ?>
                    <a href="<?= MODULO_URL ?>/cursos/enviados/<?= $value['codigo'] ?>" class="btn btn-danger w-100">
                         <i class="fas fa-clipboard-check"></i>
                         <span>&nbsp;Revisar enviós</span>
                    </a>
               <?php } ?>
               <?php if (@$esAlumno && $value['tipo'] == 'T') { ?>

                    <?php if (!empty($value['respuesta'])) { ?>

                         <div class="alert alert-success d-flex align-items-center justify-content-center gap-2 mb-0">
                              <i class="fas fa-smile fs-3"></i>
                              <span>Tarea entregada el <span class="alert-link"><?= date('d M, Y h:i A', strtotime($value['respuesta'][0]['fecenv'])) ?></span></span>
                         </div>

                    <?php } else { ?>

                         <?php if ($value['fecha_vencidad'] == 'S') { ?>
                              <div class="alert alert-warning d-flex align-items-center justify-content-center gap-2 mb-0">
                                   <i class="fas fa-frown-open fs-3"></i>
                                   <span>Tarea no fue entregada a tiempo.</span>
                              </div>
                         <?php } else { ?>
                              <button class="btn btn-outline-dark w-100 btn-send" codigo="<?= $value['codigo'] ?>">
                                   <i class="fas fa-share-alt"></i>
                                   <span>&nbsp; Enviar tarea</span>
                              </button>
                         <?php } ?>

                    <?php } ?>
               <?php } ?>
          </div>
     </div>
<?php } ?>

<div class="modal fade" id="modalRespuesta" tabindex="-1" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered"></div>
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
                         item: codigo
                    },
                    success: function(response) {
                         $('#card-item-' + codigo).remove();
                         listaAuvGrupoItems('<?= @$grupo ?>');
                    }
               });
          }
     };
     (function() {
          const modalRespuesta = document.getElementById('modalRespuesta');
          const modalRespuestaEvent = new bootstrap.Modal(modalRespuesta, {
               keyboard: false,
               backdrop: 'static'
          });
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
                         grupo: grupo
                    },
                    success: function(response) {
                         $('#modalRespuesta .modal-dialog').html(response);
                         modalRespuestaEvent.show();
                    }
               });
          });
     })();
</script>