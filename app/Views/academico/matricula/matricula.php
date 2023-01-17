<div class="modal-content">
   <style>
      .table thead th {
         text-transform: uppercase;
         font-size: 10.5px;
         color: #353535;
      }

      .table>:not(caption)>*>* {
         padding: 0.68rem 0.5rem;
      }

      /* .table-hover>tbody>tr:hover>* {
         background-color: #cdebffa8;
      } */

      .table>tbody>tr.selected {
         background-color: #cdebffa8;
      }
   </style>
   <form id="frmDocumento" class="needs-validation" onkeypress="return event.keyCode != 13;" autocomplete="off" novalidate>
      <input type="hidden" name="action" id="txtaction" value="">
      <div class="modal-header">
         <h1 class="modal-title fs-5">Matricula</h1>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-4" style="height: 580px;">
         <div class="row justify-content-between">
            <div class="col-md-5">
               <div class="row">
                  <div class="col-sm-6 my-2">
                     <label for="cmbanio" class="form-label">Año académico:</label>
                     <input type="text" class="form-control" value="2023" disabled>
                  </div>
                  <div class="col-sm-6 my-2">
                     <label for="txtmatricula" class="form-label">Fecha matricula:</label>
                     <input type="date" class="form-control" value="<?= date('Y-m-d') ?>" disabled>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-12 my-2">
                     <label for="txtusuario" class="form-label">Registrado por:</label>
                     <input type="text" class="form-control" value="ADMINISTRADOR DEL SISTEMA" disabled>
                  </div>
               </div>
               <hr class="mb-0">
               <div class="row mt-2">
                  <div class="col-md-4 my-2">
                     <label for="cmbnivel" class="form-label">Nivel:</label>
                     <select class="form-select">
                        <option value="">-Seleccione-</option>
                     </select>
                  </div>
                  <div class="col-md-4 my-2">
                     <label for="cmbgrado" class="form-label">Grado:</label>
                     <select class="form-select">
                        <option value="">-Seleccione-</option>
                     </select>
                  </div>
                  <div class="col-md-4 my-2">
                     <label for="cmbseccion" class="form-label">Sección:</label>
                     <select class="form-select">
                        <option value="">-Seleccione-</option>
                     </select>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-12 my-2">
                     <label for="txtusuario" class="form-label">Alumno:</label>
                     <input type="text" class="form-control" value="" disabled>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-12 my-2">
                     <label for="txtusuario" class="form-label">Familia Responsable:</label>
                     <input type="text" class="form-control" value="" disabled>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-12 my-2">
                     <button class="btn btn-outline-info" id="btnReporte" type="button">
                        <i class="far fa-print"></i>
                        <span>&nbsp;Imprimir constancia de matriula</span>
                     </button>
                  </div>
               </div>
            </div>
            <div class="col-md-6">
               <div class="row">
                  <div class="col-12">
                     <div class="row justify-content-between mb-3">
                        <label for="txtusuario" class="col-sm-4 col-form-label">Alumnos no matriculados:</label>
                        <div class="col-sm-6">
                           <input type="search" class="form-control" id="txtBuscarAlu" placeholder="Buscar">
                        </div>
                     </div>
                     <div class="table-responsive border" style="max-height: 530px; overflow-y: auto;">
                        <table class="table mb-0" id="table-alu">
                           <thead class="sticky-top" style="background-color: rgb(242, 242, 242);">
                              <tr>
                                 <th>
                                    <div class="d-flex align-items-center justify-content-center">
                                       <div class="form-check pt-1">
                                          <input class="form-check-input" type="radio" name="filtro" value="0" id="filtro_codigo" style="transform: scale(1.3);" checked>
                                          <label class="form-check-label" for="filtro_codigo">&nbsp;CÓDIGO</label>
                                       </div>
                                    </div>
                                 </th>
                                 <th>
                                    <div class="d-flex align-items-center justify-content-center">
                                       <div class="form-check pt-1">
                                          <input class="form-check-input" type="radio" name="filtro" value="1" id="filtro_nombre" style="transform: scale(1.3);">
                                          <label class="form-check-label" for="filtro_nombre">&nbsp;apellidos y nombres</label>
                                       </div>
                                    </div>
                                 </th>
                                 <th>
                                    <div class="d-flex align-items-center justify-content-center">
                                       <div class="form-check pt-1">
                                          <input class="form-check-input" type="radio" name="filtro" value="2" id="filtro_numdoc" style="transform: scale(1.3);">
                                          <label class="form-check-label" for="filtro_numdoc">&nbsp;nro. documento</label>
                                       </div>
                                    </div>
                                 </th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php if (empty(esc($listaAlumnosNoMatriculados))) { ?>
                              <?php } ?>
                              <?php foreach (esc($listaAlumnosNoMatriculados) as $value) { ?>
                                 <tr style="cursor: pointer;">
                                    <td class="text-center"><?= $value['codalu'] ?></td>
                                    <td class="ps-2"><?= $value['nomcomp'] ?></td>
                                    <td class="text-center"><?= $value['numdoc'] ?></td>
                                 </tr>
                              <?php } ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="modal-footer">
         <button type="submit" class="btn btn-success w-50" id="btnSave">
            <i class="fas fa-check-circle"></i>
            <span>&nbsp;Matricular Alumno</span>
         </button>
         <button type="button" class="btn btn-danger flex-fill" id="btnCancel" data-bs-dismiss="modal">
            <i class="fas fa-times-circle"></i>
            <span>&nbsp;Cerrar</span>
         </button>
      </div>
   </form>
</div>

<form id="frmReporte2" action="<?= MODULO_URL ?>/reporte/generate" target="_blank" method="POST">
   <input type="hidden" name="codrep" value="0004">
   <input type="hidden" name="codalu" id="rep_codalu" value="">
   <input type="hidden" name="anio" value="2023">
</form>

<script>
   (function() {

      const frmMatricula = document.getElementById('frmMatricula');

      let filtroIndex = 0;

      $('.form-check-input').change(function(e) {
         e.preventDefault();
         filtroIndex = parseInt($('input[name="filtro"]:checked').val());
      });

      $('.table>tbody>tr').click(function(e) {
         e.preventDefault();
         $('.table>tbody>tr').removeClass('selected');
         $(this).addClass('selected');
      });

      $('#btnReporte').on('click', function(e) {
         $('#frmReporte2').submit();
      });

      $('#txtBuscarAlu').keyup(function(e) {
         let input = this.value;
         input = input.toUpperCase();
         let tabla = document.getElementById('table-alu');
         let tablaRows = tabla.getElementsByTagName("tr");
         for (let i = 0; i < tablaRows.length; i++) {
            let column = tablaRows[i].getElementsByTagName("td")[filtroIndex];
            if (column) {
               let cellvalue = column.textContent || column.innerText;
               cellvalue = cellvalue.toUpperCase();
               if (cellvalue.indexOf(input) > -1) {
                  tablaRows[i].style.display = "";
               } else {
                  tablaRows[i].style.display = "none";
               }
            }
         }
      });

   })();
</script>