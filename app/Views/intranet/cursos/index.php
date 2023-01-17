<?= $this->extend('template/layout') ?>
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
   <div class="row mb-3">
      <div class="col-lg-12">
         <div class="card card-main">
            <div class="card-body">
               <div class="d-flex align-items-center flex-wrap">
                  <h4 class="fw-bold mb-0">5TO PRIMARIA A</h4>
                  <div class="ms-auto d-flex align-items-center gap-3">
                     <label for="">Salón:</label>
                     <div>
                        <select class="form-select">
                           <option value="">-Todos-</option>
                        </select>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="row">
      <?php for ($i = 0; $i < 22; $i++) { ?>
         <div class="col-md-4 col-sm-6 my-2">
            <div class="card bg-white" style="border-color: white; border-left: 4px solid red;">
               <div class="card-body">
                  <p>
                     Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse magni rerum quos, officiis dolor, veritatis officia inventore quod dolorem et ad deleniti eaque nobis, unde praesentium porro iusto omnis totam.
                  </p>
               </div>
            </div>
         </div>
      <?php } ?>
   </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<?= $this->endSection() ?>