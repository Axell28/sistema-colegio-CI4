<?= $this->extend('template/layout') ?>
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
      <div class="col-12">
         <div class="card card-main">
            <div class="card-body">
               <h2 class="mb-0">En desarrollo :D</h2>
            </div>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script>
</script>
<?= $this->endSection() ?>