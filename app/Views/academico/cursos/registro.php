<div class="modal fade" id="modalRegistro" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header">
            <h1 class="modal-title fs-5">Registrar Curso</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body pt-3">

            <div class="alert alert-danger" id="boxAlert" role="alert">
               <i class="fad fa-exclamation-circle"></i>
               <span class="ps-1"></span>
            </div>

            <form id="frmRegistro" class="needs-validation pt-2" novalidate>
               <div class="row">
                  <div class="col px-3">
                     <div class="mb-3 row">
                        <label for="txtnombre" class="col-sm-3 col-form-label">Nombre:</label>
                        <div class="col-sm-9">
                           <input type="text" class="form-control" name="nombre" id="txtnombre" autocomplete="off" required>
                           <div class="invalid-feedback">Debe ingresar el nombre del curso</div>
                        </div>
                     </div>
                     <div class="mb-3 row">
                        <label for="txtcurabr" class="col-sm-3 col-form-label">Abreviatura:</label>
                        <div class="col-sm-9">
                           <input type="text" class="form-control" name="curabr" id="txtcurabr" autocomplete="off">
                        </div>
                     </div>
                     <div class="mb-3 row">
                        <div class="col-12 pe-3">
                           <div class="form-check form-check-reverse">
                              <input class="form-check-input" type="checkbox" name="interno" value="S" id="chkInterno" style="transform: scale(1.4);">
                              <label class="form-check-label" for="chkInterno">Curso interno &nbsp;</label>
                           </div>
                        </div>
                     </div>
                     <div class="mb-1 row justify-content-end">
                        <div class="col text-end">
                           <button class="btn btn-success" type="submit">
                              <i class="fas fa-check-circle"></i>
                              <span>&nbsp;Guardar curso</span>
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