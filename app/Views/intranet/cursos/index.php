<?= $this->extend('template/layout') ?>
<?= $this->section('css') ?>
<style>
   .accordion-button:hover {
      color: var(--bs-accordion-active-color);
   }

   .accordion-button:not(.collapsed) {}

   .accordion-button i {
      color: #b5c710;
   }

   .accordion-body a.card {
      box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
      border-color: rgb(240, 240, 240);
      transition: transform .2s ease-in-out;
   }

   .accordion-body a.card:hover {
      transform: scale(.9);
      background-color: rgb(243, 243, 243, .5);
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
               <div class="accordion accordion-flush" id="accordioCursos">
                  <?php foreach (@$listaCursosIntranet as $salon => $contenido) { ?>
                     <div class="accordion-item">
                        <h2 class="accordion-header" id="acc-heading-<?= $salon ?>">
                           <button class="accordion-button collapsed py-4" type="button" data-bs-toggle="collapse" data-bs-target="#cur-item-<?= $salon ?>" aria-expanded="false">
                              <i class="fas fa-layer-group fs-4"></i>
                              <span class="">&nbsp;&nbsp; SALÓN <?= $contenido['nombre'] ?></span>
                           </button>
                        </h2>
                        <div id="cur-item-<?= $salon ?>" class="accordion-collapse collapse" aria-labelledby="acc-heading-<?= $salon ?>" data-bs-parent="#accordioCursos">
                           <div class="accordion-body">
                              <?php if (!empty($contenido['cursos'])) { ?>
                                 <div class="row">
                                    <?php foreach ($contenido['cursos'] as $curso) { ?>
                                       <div class="col-sm-4 mb-3 text-black">
                                          <a href="<?= MODULO_URL ?>/cursos/classroom/<?= $salon ?>/<?= $curso['curso'] ?>" class="card" style="color: black">
                                             <div class="card-body">
                                                <div class="text-center">
                                                   <img src="<?= base_url('img/iconos/curso-por-internet.png') ?>" height="50">
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