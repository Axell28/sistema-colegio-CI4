<?= $this->extend('template/layout') ?>
<?= $this->section('css') ?>
<style></style>
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
                    <div class="card-body p-2">
                         <?php for ($i=0; $i < 12; $i++) { ?>
                         <?php } ?>
                    </div>
               </div>
          </div>
     </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<?= $this->endSection() ?>