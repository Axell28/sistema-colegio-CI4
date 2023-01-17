<?= $this->extend('template/layout') ?>
<?= $this->section('css') ?>
<style>
   .container-fluid h3 {
      padding-top: 1.7px;
      font-size: 16px;
      text-transform: uppercase;
   }

   .container-fluid h5 {
      font-size: calc(var(--font-size) + 1.5px);
      line-height: 1.5;
   }
</style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container-fluid">
   <div class="row">
      <div class="col-12 my-2">
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?= MODULO_URL ?>"><?= MODULO_NAME ?></a></li>
               <li class="breadcrumb-item active" aria-current="page">Documentos</li>
            </ol>
         </nav>
      </div>
   </div>
   <?php foreach (@$listaDocumentos as $value) { ?>
      <div class="row pt-2">
         <div class="col-12 py-3">
            <div class="d-flex align-items-center gap-3">
               <img src="<?= base_url('img/iconos/carpeta_003.png') ?>" width="31" height="31">
               <h3 class="fw-bold mb-0"><?= $value['nombre'] ?></h3>
            </div>
         </div>
      </div>
      <div class="row">
         <?php foreach ($value['documentos'] as $docval) { ?>
            <div class="col-md-4 col-sm-12 my-2">
               <div class="card card-main">
                  <div class="card-body d-flex align-items-center gap-3">
                     <img src="<?= base_url('img/iconos/pdf.png') ?>" width="50">
                     <div>
                        <h5 class="mb-1"><?= $docval['docunomb'] ?></h5>
                        <a href="/uploads/documentos/D<?= $docval['coddoc'] . $docval['extension'] ?>" download="<?= $docval['docunomb'] ?>">Descargar documento</a>
                     </div>
                  </div>
               </div>
            </div>
         <?php } ?>
      </div>
   <?php } ?>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<?= $this->endSection() ?>