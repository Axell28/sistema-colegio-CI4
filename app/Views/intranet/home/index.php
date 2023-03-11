<?= $this->extend('template/layout') ?>
<?= $this->section('css') ?>
<style>
   .card-header {
      background: transparent;
      border: none;
   }

   .card-header h5.titulo-pub {
      color: #fff;
      line-height: 1.5;
      font-weight: 500;
   }

   .card-header .badge-tipo {
      font-size: calc(var(--font-size) - 3px);
      padding: 5px 8px;
      border-radius: 3px;
      font-weight: bold;
      text-transform: uppercase;
      background-color: rgba(255, 255, 255, .8);
   }

   .content-body-pub {
      padding-bottom: 10px;
   }

   .card-footer {
      background: transparent;
   }

   .card-footer .pub-usuario {
      font-size: calc(var(--font-size) - 2px);
   }

   .wrapper {
      width: 100%;
      background: #fff;
   }

   .wrapper header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 19px;
   }

   header .icons {
      display: flex;
   }

   header .icons span {
      height: 38px;
      width: 38px;
      margin: 0 1px;
      cursor: pointer;
      color: #878787;
      text-align: center;
      line-height: 38px;
      font-size: .9rem;
      user-select: none;
      border-radius: 50%;
   }

   .icons span:last-child {
      margin-right: -10px;
   }

   header .icons span:hover {
      background: #f2f2f2;
   }

   header .current-date {
      font-weight: bold;
      margin-bottom: 0px;
      letter-spacing: 1px;
      font-size: 14px;
   }

   .calendar ul {
      display: flex;
      flex-wrap: wrap;
      list-style: none;
      text-align: center;
      margin-left: 0px;
      padding-left: 0px;
   }

   .calendar .days {
      margin-bottom: 10px;
   }

   .calendar li {
      color: #333;
      width: calc(100% / 7);
      font-size: var(--font-size);
   }

   .calendar .weeks {
      margin-bottom: 0px;
   }

   .calendar .weeks li {
      font-weight: 500;
      cursor: default;
   }

   .calendar .days li {
      z-index: 1;
      cursor: pointer;
      position: relative;
      margin-top: 25px;
   }

   .days li.inactive {
      color: #aaa;
   }

   .days li.active {
      color: #fff;
   }

   .days li::before {
      position: absolute;
      content: "";
      left: 50%;
      top: 50%;
      height: 40px;
      width: 40px;
      z-index: -1;
      border-radius: 50%;
      transform: translate(-50%, -50%);
   }

   .days li.active::before {
      background: var(--color-default-primary);
   }

   .days li:not(.active):hover::before {
      background: #f2f2f2;
   }

   .list-group-item {
      cursor: pointer;
   }

   @media only screen and (max-width: 1350px) {
      .content-body-pub img {
         width: 100%;
         height: auto;
      }

      .content-body-pub video {
         width: 100%;
         height: auto;
      }

      .content-body-pub iframe {
         max-width: 100%;
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
               <li class="breadcrumb-item active" aria-current="page">Principal</li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="row">
      <div class="col-md-8">

         <?php if (empty(@$listaPublicaciones)) { ?>
            <div class="card card-main mb-4">
               <div class="card-body text-center py-4">
                  <p class="mb-0">No hay contenido para mostrar</p>
                  <br>
                  <img src="<?= base_url('img/default/4354122.jpg') ?>" width="300">
               </div>
            </div>
         <?php } ?>

         <?php foreach (@$listaPublicaciones as $pub) {
            $color = $pub['tipo'] == 'A' ? 'success' : ($pub['tipo'] == 'E' ? 'info' : 'warning');
            $photoUser = !empty($pub['fotourl']) ? $pub['fotourl'] : ($pub['per_sexo'] == 'F' ? '/img/default/woman.png' : '/img/default/man.png');
         ?>
            <div class="card card-main mb-4">
               <div class="card-header bg-<?= $color ?> rounded m-2" style="padding-top: 10px; padding-bottom: 14px;">
                  <div class="d-flex align-items-center gap-3">
                     <img src="<?= base_url('img/iconos/megafono.png') ?>" width="40" height="40">
                     <div>
                        <h5 class="titulo-pub"><?= $pub['titulo'] ?></h5>
                        <span class="badge-tipo"><?= $pub['tipodes'] ?></span>
                     </div>
                  </div>
               </div>
               <div class="card-body content-body-pub">
                  <?= $pub['cuerpo'] ?>
               </div>
               <?php if (!empty($pub['adjuntos'])) { ?>
                  <div class="px-3 py-2">
                     <?php foreach ($pub['adjuntos'] as $adjunto) { ?>
                        <div class="alert alert-dark mb-2" style="background-color: rgba(245, 245, 245); padding: 9px 14px;">
                           <div class="d-flex align-items-center gap-2">
                              <i class="fad fa-file-download fs-4"></i>
                              <a href="<?= base_url($adjunto['ruta']) ?>" class="alert-link fw-normal" title="Descargar" download="<?= $adjunto['nombre'] ?>"><?= $adjunto['nombre'] ?></a>
                           </div>
                        </div>
                     <?php } ?>
                  </div>
               <?php } ?>
               <div class="card-footer p-2 border-0" style="padding-top: 0px;">
                  <div class="alert alert-<?= $color ?> d-flex align-items-center gap-3 mb-0 py-2">
                     <img src="<?= base_url() . $photoUser ?>" class="rounded-circle" width="39" height="39">
                     <div>
                        <div class="mb-1 pub-usuario"><strong><?= $pub['usuario_nomb'] ?></strong></div>
                        <span><i class="fal fa-calendar-check fs-5"></i></span>
                        <span>&nbsp;<?= $pub['fecpubini'] ?></span>
                     </div>
                  </div>
               </div>
            </div>
         <?php } ?>
      </div>
      <div class="col-md-4">
         <div class="row mb-4 sticky-top">
            <div class="col-md-12 order-1 mb-4">
               <div class="card card-main">
                  <div class="card-body">
                     <div class="wrapper">
                        <header>
                           <h5 class="current-date"></h5>
                           <div class="icons">
                              <span id="prev" class="material-symbols-rounded"><i class="far fa-chevron-left"></i></span>
                              <span id="next" class="material-symbols-rounded"><i class="far fa-chevron-right"></i></span>
                           </div>
                        </header>
                        <div class="calendar">
                           <ul class="weeks">
                              <li>Dom</li>
                              <li>Lun</li>
                              <li>Mar</li>
                              <li>Mir</li>
                              <li>Jue</li>
                              <li>Vie</li>
                              <li>Sab</li>
                           </ul>
                           <ul class="days"></ul>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <?php if (ENTIDAD == 'ALU') { ?>
               <div class="col-md-12 order-2">
                  <div class="card card-main">
                     <div class="card-body">
                        <h5 class="fw-bold" style="font-size: 14px;">Actividades / Exámenes pendientes</h5>
                        <?php if(empty(@$listadoPendientes)) { ?>
                           <p class="mb-0">Nada que mostrar</p>
                        <?php } ?>
                        <ul class="list-group list-group-flush">
                           <?php foreach (@$listadoPendientes as $value) { ?>
                              <a href="/intranet/cursos/auv/<?= $value['salon'] ?>/<?= $value['curso'] ?>" class="list-group-item d-flex align-items-center gap-2 px-0">
                                 <img src="<?= base_url('img/iconos/lista.png') ?>" width="29" height="29">
                                 <div style="max-width: 80%;">
                                    <p class="mb-0 text-truncate"><?= $value['titulo'] ?></p>
                                    <div style="font-size: 12px; color: #A2A2A2;"><?= $value['curnom'] ?></div>
                                 </div>
                           </a>
                           <?php } ?>
                        </ul>
                     </div>
                  </div>
               </div>
            <?php } ?>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="<?= base_url('js/calendar.js') ?>"></script>
<script>
</script>
<?= $this->endSection() ?>