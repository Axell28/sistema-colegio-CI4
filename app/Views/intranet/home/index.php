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

   @media only screen and (max-width: 1350px) {
      #content-body-pub img {
         width: 100%;
         height: auto;
      }

      #content-body-pub video {
         width: 100%;
         height: auto;
      }

      #content-body-pub iframe {
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
                     <div class="text-white">
                        <i class="fad fa-comment-alt-lines" style="font-size: 2.6em;"></i>
                     </div>
                     <div>
                        <h5 class="titulo-pub"><?= $pub['titulo'] ?></h5>
                        <span class="badge-tipo"><?= $pub['tipodes'] ?></span>
                     </div>
                  </div>
               </div>
               <div class="card-body" id="content-body-pub">
                  <?= $pub['cuerpo'] ?>
               </div>
               <div class="card-footer p-2 border-0">
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
            <div class="col-md order-1">
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