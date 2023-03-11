<?= $this->extend('template/layout') ?>
<?= $this->section('css') ?>
<style>
   .accordion-button {
      border: 1px solid white;
   }

   .accordion-button:hover {
      color: var(--bs-accordion-active-color);
   }

   .accordion-button:not(.collapsed) {
      background-color: #F1F9EF;
      color: #44663C;
      border: 1px solid #4C9465;
   }

   .accordion-body a.card {
      box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
      border-color: rgb(240, 240, 240);
      transition: transform .2s ease-in-out;
   }

   .accordion-body a.card:hover {
      transform: scale(.95);
      background-color: rgb(240, 240, 240, .5);
   }

   .accordion-body h4.curso {
      font-weight: bold;
      font-size: 15px;
   }

   .accordion-body p.docente {
      font-size: calc(var(--font-size) - 1.5px);
      margin-bottom: 0;
      color: rgb(100, 100, 100);
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
               <li class="breadcrumb-item active" aria-current="page">Mis Cursos</li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="row">
      <div class="col-lg">
         <div class="card card-main">
            <div class="card-body p-2">
               <?php if (empty(@$listaCursosIntranet)) { ?>
                  <div class="p-2">
                     <p class="mb-0">No tienes cursos disponibles</p>
                  </div>
               <?php } ?>
               <div class="accordion accordion-flush" id="accordioCursos">
                  <?php foreach (@$listaCursosIntranet as $salon => $contenido) { ?>
                     <div class="accordion-item">
                        <h2 class="accordion-header" id="acc-heading-<?= $salon ?>">
                           <button class="accordion-button <?php echo @$esAlumno ? '' : 'collapsed' ?>  py-4" type="button" data-bs-toggle="collapse" data-bs-target="#cur-item-<?= $salon ?>" aria-expanded="<?php echo @$esAlumno ? 'true' : 'false' ?>">
                              <img src="<?= base_url('img/iconos/icono-salon.png') ?>" width="34">
                              <span class="">&nbsp;&nbsp; SALÓN : <?= $contenido['nombre'] ?></span>
                           </button>
                        </h2>
                        <div id="cur-item-<?= $salon ?>" class="accordion-collapse collapse <?php echo @$esAlumno ? 'show' : '' ?>" aria-labelledby="acc-heading-<?= $salon ?>" data-bs-parent="#accordioCursos">
                           <div class="accordion-body">
                              <?php if (!empty($contenido['cursos'])) { ?>
                                 <div class="row">
                                    <?php foreach ($contenido['cursos'] as $curso) { ?>
                                       <div class="col-sm-4 mb-3 text-black">
                                          <a href="<?= MODULO_URL ?>/cursos/auv/<?= $salon ?>/<?= $curso['curso'] ?>" class="card" style="color: black">
                                             <div class="card-body">
                                                <div class="text-center">
                                                   <img src="<?= base_url('img/iconos/curso-por-internet.png') ?>" height="56">
                                                </div>
                                                <div class="text-center mt-3 text-truncate">
                                                   <h4 class="curso mb-2"><?= $curso['curnomb'] ?></h4>
                                                   <p class="docente">DOCENTE: <?= !empty($curso['docentenom']) ? $curso['docentenom'] : 'NO ASIGNADO' ?></p>
                                                </div>
                                             </div>
                                          </a>
                                       </div>
                                    <?php } ?>
                                 </div>
                              <?php } ?>
                           </div>
                        </div>
                     </div>
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<?= $this->endSection() ?>