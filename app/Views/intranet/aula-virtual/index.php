<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
   <div class="row mt-1 mb-3">
      <div class="col-12">
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?= MODULO_URL ?>"><?= MODULO_NAME ?></a></li>
               <li class="breadcrumb-item active" aria-current="page">Aula virtual</li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="row">
      <div class="col-lg-12">
         <div class="card card-main">
            <div class="card-body">
               <nav>
                  <div class="nav nav-tabs" id="nav-tab" role="tablist">
                     <button class="nav-link active flex-fill" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
                        <i class="fad fa-calendar"></i>
                        <span>&nbsp;Primer Bimestre</span>
                     </button>
                     <button class="nav-link flex-fill" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">
                        <i class="fad fa-calendar"></i>
                        <span>&nbsp;Segundo Bimestre</span>
                     </button>
                     <button class="nav-link flex-fill" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">
                        <i class="fad fa-calendar"></i>
                        <span>&nbsp;Tercer Bimestre</span>
                     </button>
                     <button class="nav-link flex-fill" id="nav-disabled-tab" data-bs-toggle="tab" data-bs-target="#nav-disabled" type="button" role="tab" aria-controls="nav-disabled" aria-selected="false">
                        <i class="fad fa-calendar"></i>
                        <span>&nbsp;Cuarto Bimestre</span>
                     </button>
                  </div>
               </nav>
               <div class="tab-content py-3 px-2" id="nav-tabContent">
                  <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">

                     <div class="mb-3">
                        <button class="btn btn-success w-100">
                           <i class="fas fa-plus-circle"></i>
                           <span>Agregar contenido</span>
                        </button>
                     </div>

                     <div class="accordion" id="accordionItems">
                        <?php for ($i = 1; $i <= 6; $i++) { ?>
                           <div class="accordion-item">
                              <h2 class="accordion-header" id="head-item-<?= $i ?>">
                                 <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?= $i ?>" aria-expanded="false" aria-controls="collapse-<?= $i ?>">
                                    <div class="d-flex align-items-center w-100">
                                       <i class="far fa-calendar-check"></i>
                                       <div>&nbsp;&nbsp; Semana <?= $i ?></div>
                                       <!-- <a href="javascript:void(0)" class="ms-auto me-3"><i class="far fa-pen"></i></a> -->
                                       <div class="ms-auto me-3">
                                          <a href="javascript:void(0)" class="me-2" onclick="alert('saludos');"><i class="far fa-pen"></i></a>
                                          <a href="javascript:void(0)" class="ms-2" onclick="alert('saludos');"><i class="far fa-trash-alt"></i></a>
                                       </div>
                                    </div>
                                 </button>
                              </h2>
                              <div id="collapse-<?= $i ?>" class="accordion-collapse collapse" aria-labelledby="head-item-<?= $i ?>" data-bs-parent="#accordionItems">
                                 <div class="accordion-body">
                                    <div class="card border-success mb-3">
                                       <div class="card-header bg-transparent border-success">
                                          <div class="d-flex align-items-center">
                                             <div>Ciencia en los animales prueba titulo para todos</div>
                                             <div class="ms-auto">
                                                <a href="javascript:void(0)" class="me-2" onclick="alert('saludos');"><i class="far fa-pen"></i></a>
                                                <a href="javascript:void(0)" class="ms-2" onclick="alert('saludos');"><i class="far fa-trash-alt"></i></a>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="card-body">
                                          <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                       </div>
                                       <div class="card-footer bg-transparent border-success">Footer</div>
                                    </div>
                                    <div class="card border-info mb-3">
                                       <div class="card-header bg-transparent border-info">Ciencia en los animales prueba titulo para todos</div>
                                       <div class="card-body">
                                          <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                       </div>
                                       <div class="card-footer bg-transparent border-info">Footer</div>
                                    </div>
                                    <div class="card border-warning mb-3">
                                       <div class="card-header bg-transparent border-warning">Ciencia en los animales prueba titulo para todos</div>
                                       <div class="card-body">
                                          <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                       </div>
                                       <div class="card-footer bg-transparent border-warning">Footer</div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        <?php } ?>
                     </div>

                  </div>
                  <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">...</div>
                  <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab" tabindex="0">...</div>
                  <div class="tab-pane fade" id="nav-disabled" role="tabpanel" aria-labelledby="nav-disabled-tab" tabindex="0">...</div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('javascript') ?>
<?= $this->endSection() ?>