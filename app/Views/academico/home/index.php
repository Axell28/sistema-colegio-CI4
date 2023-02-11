<?= $this->extend('template/layout') ?>
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
      <div class="col-md-3 my-2">
         <a href="<?= MODULO_URL ?>/mantenimiento-alumno">
            <div class="card border-0" style="background: linear-gradient(280deg, rgba(0,22,36,1) 0%, rgba(0,177,213,1) 0%, rgba(50,112,210,1) 100%);">
               <div class="card-body text-white">
                  <div class="d-flex justify-content-between">
                     <div>
                        <h1 class="fw-bold"><?= @$totalAlumnos ?></h1>
                        <h4 style="font-size: 15px;">Alumnos</h4>
                     </div>
                     <img src="<?= base_url('img/iconos/dash_alumno.png') ?>">
                  </div>
               </div>
            </div>
         </a>
      </div>
      <div class="col-md-3 my-2">
         <a href="<?= MODULO_URL ?>/mantenimiento-empleado">
            <div class="card border-0" style="background: linear-gradient(0deg, rgba(255,157,33,1) 0%, rgba(253,224,45,1) 100%);">
               <div class="card-body text-white">
                  <div class="d-flex justify-content-between">
                     <div>
                        <h1 class="fw-bold"><?= @$totalEmpleados ?></h1>
                        <h4 style="font-size: 15px;">Personal</h4>
                     </div>
                     <img src="<?= base_url('img/iconos/dash_personal.png') ?>">
                  </div>
               </div>
            </div>
         </a>
      </div>
      <div class="col-md-3 my-2">
         <a href="<?= MODULO_URL ?>/mantenimiento-familia">
            <div class="card border-0" style="background: linear-gradient(33deg, rgba(0,22,36,1) 0%, rgba(0,213,79,1) 0%, rgba(128,213,129,1) 100%);">
               <div class="card-body text-white">
                  <div class="d-flex justify-content-between">
                     <div>
                        <h1 class="fw-bold"><?= @$totalFamilias ?></h1>
                        <h4 style="font-size: 15px;">Familias</h4>
                     </div>
                     <img src="<?= base_url('img/iconos/dash_familia.png') ?>">
                  </div>
               </div>
            </div>
         </a>
      </div>
      <div class="col-md-3 my-2">
         <a href="<?= MODULO_URL ?>/salones">
            <div class="card border-0" style="background: linear-gradient(267deg, rgba(0,22,36,1) 0%, rgba(217,93,40,1) 0%, rgba(229,57,48,1) 100%);">
               <div class="card-body text-white">
                  <div class="d-flex justify-content-between">
                     <div>
                        <h1 class="fw-bold"><?= @$totalSalones ?></h1>
                        <h4 style="font-size: 15px;">Salones</h4>
                     </div>
                     <img src="<?= base_url('img/iconos/dash_salon.png') ?>">
                  </div>
               </div>
            </div>
         </a>
      </div>
   </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script>
</script>
<?= $this->endSection() ?>