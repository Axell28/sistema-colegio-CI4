<div class="modal fade" id="modalRegistro" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
         <div class="modal-header">
            <h1 class="modal-title fs-5">Año escolar</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body pt-3">

            <div class="alert alert-danger" id="boxAlert" role="alert">
               <i class="fad fa-exclamation-circle"></i>
               <span class="ps-1"></span>
            </div>

            <form id="frmRegistro" class="needs-validation pt-2" novalidate>
               <input type="hidden" name="action" id="txtaction" value="I">
               <div class="row">
                  <div class="col px-3">
                     <div class="mb-3 row">
                        <label for="txtanio" class="col-sm-3 col-form-label">Año:</label>
                        <div class="col-sm-9">
                           <input type="number" class="form-control" name="anio" id="txtanio" min="1789" max="2500" minlength="4" required>
                           <div class="invalid-feedback">Ingrese un año válido</div>
                        </div>
                     </div>
                     <div class="mb-3 row">
                        <label for="txtnombre" class="col-sm-3 col-form-label">Nombre:</label>
                        <div class="col-sm-9">
                           <input type="text" class="form-control" name="nombre" id="txtnombre" required>
                           <div class="invalid-feedback">Ingrese nombre o número del año</div>
                        </div>
                     </div>
                     <div class="mb-3 row">
                        <label for="txtfecini" class="col-sm-3 col-form-label">Fecha inicio:</label>
                        <div class="col-sm-9">
                           <input type="date" class="form-control" name="fecini" id="txtfecini" value="<?= date('Y-m-d') ?>" required>
                           <div class="invalid-feedback">Seleccione fecha de inicio</div>
                        </div>
                     </div>
                     <div class="mb-4 row">
                        <label for="txtfecfin" class="col-sm-3 col-form-label">Fecha fin:</label>
                        <div class="col-sm-9">
                           <input type="date" class="form-control" name="fecfin" id="txtfecfin" value="<?= date('Y-m-d') ?>" required>
                           <div class="invalid-feedback">Seleccione fecha de fin</div>
                        </div>
                     </div>
                     <div class="mb-1 row justify-content-end">
                        <div class="col text-end">
                           <button class="btn btn-primary" type="submit">
                              <i class="fas fa-check-circle"></i>
                              <span>&nbsp;Aceptar</span>
                           </button>
                        </div>
                     </div>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>