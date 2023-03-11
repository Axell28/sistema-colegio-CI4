<?= $this->extend('template/layout') ?>
<?= $this->section('css') ?>
<style>
   .card-header-tabs .nav-link.active {
      background-color: white;
      border-color: rgba(0, 0, 0, 0.175);
      border-bottom-color: white;
   }

   .card-header-tabs .nav-link:not(.active):hover {
      background: transparent;
      border-color: transparent;
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
               <li class="breadcrumb-item active" aria-current="page">Mantenimiento Familia</li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="row">
      <div class="col-md-6">
         <div class="card card-main">
            <div class="card-body">
               <!-- <div class="row mb-3">
                  <div class="col-sm-6 my-1 text-end">
                     <button class="btn btn-outline-danger w-100" id="btnReporte">
                        <i class="fas fa-file-pdf"></i>
                        <span>&nbsp;Exportar pdf</span>
                     </button>
                  </div>
                  <div class="col-sm-6 my-1 text-end">
                     <button class="btn btn-outline-success w-100" id="btnReporte2">
                        <i class="fas fa-file-excel"></i>
                        <span>&nbsp;Exportar Excel</span>
                     </button>
                  </div>
               </div> -->
               <div id="jqxgridFamilia"></div>
               <div class="pt-3">
                  <p class="mb-0" id="totalReg">Total de Familias : &nbsp; 0</p>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-6">
         <div class="card card-main">
            <div class="card-body">
               <form id="frmFamilia" class="needs-validation" autocomplete="off" onkeypress="return event.keyCode != 13;" novalidate>
                  <div class="row mb-2">
                     <div class="col-sm-4 my-1">
                        <button class="btn btn-primary w-100" id="btnAdd" type="button">
                           <i class="fas fa-plus-circle"></i>
                           <span>&nbsp;Nuevo</span>
                        </button>
                     </div>
                     <div class="col-sm-4 my-1">
                        <button class="btn btn-success w-100" type="submit">
                           <i class="fas fa-save"></i>
                           <span>&nbsp;Guardar</span>
                        </button>
                     </div>
                     <div class="col-sm-4 my-1">
                        <button class="btn btn-danger w-100" id="btnDelete" type="button" disabled>
                           <i class="far fa-trash-alt"></i>
                           <span>&nbsp;Eliminar</span>
                        </button>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" id="myTab">
                           <li class="nav-item flex-fill">
                              <a href="#data_principal" class="nav-link text-center active" data-bs-toggle="tab">Datos Principales</a>
                           </li>
                           <li class="nav-item flex-fill">
                              <a href="#data_adicional" class="nav-link text-center" data-bs-toggle="tab">Padre / Apoderado</a>
                           </li>
                           <li class="nav-item flex-fill">
                              <a href="#data_academico" class="nav-link text-center" data-bs-toggle="tab">Madre / Apoderada</a>
                           </li>
                        </ul>
                     </div>
                     <div class="card-body">
                        <div class="tab-content">
                           <div class="tab-pane fade show active" id="data_principal">
                              <div class="row">
                                 <div class="col-md-4 my-2">
                                    <label for="txtcodigo" class="form-label">Código:</label>
                                    <input type="text" class="form-control" name="codigo" id="txtcodigo" disabled>
                                 </div>
                                 <div class="col-md-4 my-2">
                                    <label for="txtfecing" class="form-label">Fecha de Ingreso:</label>
                                    <input type="date" class="form-control" id="txtfecing" value="<?= date('Y-m-d') ?>" required>
                                    <div class="invalid-feedback">Seleccione la fecha de ingreso</div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col my-2">
                                    <label for="txtnombreFam" class="form-label">Nombre de familia:</label>
                                    <input type="text" class="form-control" id="txtnombreFam" required>
                                    <div class="invalid-feedback">Ingrese nombre de familia</div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col my-2">
                                    <label for="txtdirFam" class="form-label">Dirección:</label>
                                    <input type="text" class="form-control" id="txtdirFam">
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-8 my-2">
                                    <label for="txtcorreoFam" class="form-label">Correo principal:</label>
                                    <input type="text" class="form-control" id="txtcorreoFam" required>
                                    <div class="invalid-feedback">Campo requerido</div>
                                 </div>
                                 <div class="col-md-4 my-2">
                                    <label for="txtcelularFam" class="form-label">Teléfono / Celular:</label>
                                    <input type="text" class="form-control" id="txtcelularFam">
                                    <div class="invalid-feedback">Campo requerido</div>
                                 </div>
                              </div>
                              <hr class="mb-0">
                              <div class="row mt-3" id="divgridListadoHijo">
                                 <div class="col-12">
                                    <label class="form-label">Datos de Hijos:</label>
                                    <div id="jqxgridListadoHijos"></div>
                                 </div>
                              </div>
                           </div>
                           <div class="tab-pane fade" id="data_adicional">
                              <div class="row">
                                 <div class="col">
                                    <div class="form-check form-check-reverse">
                                       <input class="form-check-input" type="checkbox" value="S" id="chkResponsable1" style="transform: scale(1.2);" checked>
                                       <label class="form-check-label" for="chkResponsable1">Responsable &nbsp;</label>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-6 my-2">
                                    <label for="txtnombre1" class="form-label">Nombres:</label>
                                    <input type="text" class="form-control" id="txtnombre1" required>
                                    <div class="invalid-feedback">Campo requerido</div>
                                 </div>
                                 <div class="col-md-6 my-2">
                                    <label for="cmbparentesco1" class="form-label">Parentesco:</label>
                                    <select class="form-select" id="cmbparentesco1" required>
                                       <option value="">-Seleccione-</option>
                                       <?php foreach (@$listaParentesco as $value) { ?>
                                          <option value="<?= $value['codigo'] ?>"><?= $value['descripcion'] ?></option>
                                       <?php } ?>
                                    </select>
                                    <div class="invalid-feedback">Seleccione el parentesco</div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-6 my-2">
                                    <label for="txtapepat1" class="form-label">Apellido Paterno:</label>
                                    <input type="text" class="form-control text-uppercase" id="txtapepat1" required>
                                    <div class="invalid-feedback">Campo requerido</div>
                                 </div>
                                 <div class="col-md-6 my-2">
                                    <label for="txtapemat1" class="form-label">Apellido Materno:</label>
                                    <input type="text" class="form-control text-uppercase" id="txtapemat1" required>
                                    <div class="invalid-feedback">Campo requerido</div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-6 my-2">
                                    <label for="txtfecnac1" class="form-label">Fecha nacimiento:</label>
                                    <input type="date" class="form-control" id="txtfecnac1">
                                    <div class="invalid-feedback">Campo requerido</div>
                                 </div>
                                 <div class="col-md-6 my-2">
                                    <label for="cmbnacionalidad1" class="form-label">Nacionalidad:</label>
                                    <select class="form-select" id="cmbnacionalidad1">
                                       <option value="">-Seleccione-</option>
                                       <?php foreach (@$listaNacionalidad as $dato) { ?>
                                          <option value="<?= $dato['codigo'] ?>"><?= $dato['descripcion'] ?></option>
                                       <?php } ?>
                                    </select>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-6 my-2">
                                    <label for="cmbtipdoc1" class="form-label">Tipo documento:</label>
                                    <select class="form-select" id="cmbtipdoc1">
                                       <option value="">-Seleccione-</option>
                                       <?php foreach (esc($listaDocumentosIde) as $dato) { ?>
                                          <option value="<?= $dato['codigo'] ?>"><?= $dato['descripcion'] ?></option>
                                       <?php } ?>
                                    </select>
                                    <div class="invalid-feedback">Campo requerido</div>
                                 </div>
                                 <div class="col-md-6 my-2">
                                    <label for="txtnumdoc1" class="form-label">Nro documento:</label>
                                    <input type="text" class="form-control" id="txtnumdoc1" onkeypress="return inputSoloNumeros(event)">
                                    <div class="invalid-feedback">Campo requerido</div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-6 my-2">
                                    <label for="cmbestcivil1" class="form-label">Estado civil:</label>
                                    <select class="form-select" id="cmbestcivil1">
                                       <option value="">-Seleccione-</option>
                                       <?php foreach (@$listaEstadoCivil as $data) { ?>
                                          <option value="<?= $data['codigo'] ?>"><?= $data['descripcion'] ?></option>
                                       <?php } ?>
                                    </select>
                                 </div>
                                 <div class="col-md-6 my-2">
                                    <label for="txtcelular1" class="form-label">Celular:</label>
                                    <input type="text" class="form-control text-uppercase" id="txtcelular1">
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-12 my-2">
                                    <label for="txtcorreo1" class="form-label">Correo:</label>
                                    <input type="text" class="form-control" id="txtcorreo1">
                                 </div>
                              </div>
                           </div>
                           <div class="tab-pane fade" id="data_academico">
                              <div class="row">
                                 <div class="col">
                                    <div class="form-check form-check-reverse">
                                       <input class="form-check-input" type="checkbox" value="S" id="chkResponsable2" style="transform: scale(1.2);">
                                       <label class="form-check-label" for="chkResponsable2">Responsable &nbsp;</label>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-6 my-2">
                                    <label for="txtnombre2" class="form-label">Nombres:</label>
                                    <input type="text" class="form-control" id="txtnombre2" required>
                                    <div class="invalid-feedback">Campo requerido</div>
                                 </div>
                                 <div class="col-md-6 my-2">
                                    <label for="cmbparentesco2" class="form-label">Parentesco:</label>
                                    <select class="form-select" id="cmbparentesco2" required>
                                       <option value="">-Seleccione-</option>
                                       <?php foreach (@$listaParentesco as $value) { ?>
                                          <option value="<?= $value['codigo'] ?>"><?= $value['descripcion'] ?></option>
                                       <?php } ?>
                                    </select>
                                    <div class="invalid-feedback">Seleccione el parentesco</div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-6 my-2">
                                    <label for="txtapepat2" class="form-label">Apellido Paterno:</label>
                                    <input type="text" class="form-control text-uppercase" id="txtapepat2" required>
                                    <div class="invalid-feedback">Campo requerido</div>
                                 </div>
                                 <div class="col-md-6 my-2">
                                    <label for="txtapemat2" class="form-label">Apellido Materno:</label>
                                    <input type="text" class="form-control text-uppercase" id="txtapemat2" required>
                                    <div class="invalid-feedback">Campo requerido</div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-6 my-2">
                                    <label for="txtfecnac2" class="form-label">Fecha nacimiento:</label>
                                    <input type="date" class="form-control" id="txtfecnac2">
                                    <div class="invalid-feedback">Campo requerido</div>
                                 </div>
                                 <div class="col-md-6 my-2">
                                    <label for="cmbnacionalidad2" class="form-label">Nacionalidad:</label>
                                    <select class="form-select" id="cmbnacionalidad2">
                                       <option value="">-Seleccione-</option>
                                       <?php foreach (@$listaNacionalidad as $dato) { ?>
                                          <option value="<?= $dato['codigo'] ?>"><?= $dato['descripcion'] ?></option>
                                       <?php } ?>
                                    </select>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-6 my-2">
                                    <label for="cmbtipdoc2" class="form-label">Tipo documento:</label>
                                    <select class="form-select" id="cmbtipdoc2">
                                       <option value="">-Seleccione-</option>
                                       <?php foreach (esc($listaDocumentosIde) as $dato) { ?>
                                          <option value="<?= $dato['codigo'] ?>"><?= $dato['descripcion'] ?></option>
                                       <?php } ?>
                                    </select>
                                    <div class="invalid-feedback">Campo requerido</div>
                                 </div>
                                 <div class="col-md-6 my-2">
                                    <label for="txtnumdoc2" class="form-label">Nro documento:</label>
                                    <input type="text" class="form-control" id="txtnumdoc2" onkeypress="return inputSoloNumeros(event)">
                                    <div class="invalid-feedback">Campo requerido</div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-6 my-2">
                                    <label for="cmbestcivil2" class="form-label">Estado civil:</label>
                                    <select class="form-select" id="cmbestcivil2">
                                       <option value="">-Seleccione-</option>
                                       <?php foreach (@$listaEstadoCivil as $data) { ?>
                                          <option value="<?= $data['codigo'] ?>"><?= $data['descripcion'] ?></option>
                                       <?php } ?>
                                    </select>
                                 </div>
                                 <div class="col-md-6 my-2">
                                    <label for="txtcelular2" class="form-label">Celular:</label>
                                    <input type="text" class="form-control text-uppercase" id="txtcelular2">
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-12 my-2">
                                    <label for="txtcorreo2" class="form-label">Correo:</label>
                                    <input type="text" class="form-control" id="txtcorreo2">
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <input type="hidden" name="action" id="txtaction" value="I">
                  <input type="hidden" name="codfam" id="txtcodfam" value="">
                  <input type="hidden" name="codper1" id="txtcodper1" value="">
                  <input type="hidden" name="codper2" id="txtcodper2" value="">
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script>
   const frmFamilia = document.getElementById('frmFamilia');
   const jqxgridFamilia = "#jqxgridFamilia";
   const jqxgridListadoHijos = "#jqxgridListadoHijos";

   const listaFamiliaHijos = JSON.parse(`<?= json_encode(esc($listaFamiliaHijos)) ?>`);

   const jqxgridFamiliaSource = {
      datatype: 'json',
      localdata: `<?= json_encode(@$listaFamilias) ?>`
   };

   const jqxgridListadoHijosSource = {
      datatype: 'json',
      localdata: `[]`
   };

   const jqxgridFamiliaAdapter = new $.jqx.dataAdapter(jqxgridFamiliaSource);

   const jqxgridListadoHijosAdapter = new $.jqx.dataAdapter(jqxgridListadoHijosSource);

   function inputSoloNumeros(evt) {
      var code = (evt.which) ? evt.which : evt.keyCode
      if (code > 31 && (code < 48 || code > 57))
         return false;
      return true;
   }

   function totalRegistros() {
      const info = $(jqxgridFamilia).jqxGrid('getdatainformation');
      $('#totalReg').html(`Total de Familias : &nbsp; ` + info.rowscount);
   }

   function limpiarFormulario() {
      $('#frmFamilia').trigger("reset");
      $('#txtaction').val('I');
      $('#btnDelete').prop('disabled', true);
      $('#txtfecing').prop('disabled', false);
      $(jqxgridFamilia).jqxGrid('clearselection');
      frmFamilia.classList.remove('was-validated');
      jqxgridListadoHijosSource.localdata = [];
      $(jqxgridListadoHijos).jqxGrid('updateBoundData', 'data');
      $('#divgridListadoHijo').hide();
   }

   function seleccionarFamilia(data) {
      limpiarFormulario();
      $('#txtaction').val('E');
      $('#txtcodigo').val(data.codfam);
      $('#txtfecing').val(data.fecing);
      $('#txtnombreFam').val(data.nombre);
      $('#txtdirFam').val(data.direccion);
      $('#txtcorreoFam').val(data.email);
      $('#txtcelularFam').val(data.telefono);

      // padres - apoderados
      if (data.famdet[0]) {
         $('#txtcodper1').val(data.famdet[0].codper);
         $('#cmbparentesco1').val(data.famdet[0].tipofam);
         $('#txtnombre1').val(data.famdet[0].nombres);
         $('#txtapepat1').val(data.famdet[0].apepat);
         $('#txtapemat1').val(data.famdet[0].apemat);
         $('#txtfecnac1').val(data.famdet[0].fecnac);
         $('#cmbtipdoc1').val(data.famdet[0].tipdoc);
         $('#txtnumdoc1').val(data.famdet[0].numdoc);
         $('#cmbestcivil1').val(data.famdet[0].estcivil);
         $('#cmbnacionalidad1').val(data.famdet[0].nacionalidad);
         $('#txtcelular1').val(data.famdet[0].celular1);
         $('#txtcorreo1').val(data.famdet[0].email);
         $('#chkResponsable1').prop('checked', data.famdet[0].responsable == 'S' ? true : false);
      }

      if (data.famdet[1]) {
         $('#txtcodper2').val(data.famdet[1].codper);
         $('#cmbparentesco2').val(data.famdet[1].tipofam);
         $('#txtnombre2').val(data.famdet[1].nombres);
         $('#txtapepat2').val(data.famdet[1].apepat);
         $('#txtapemat2').val(data.famdet[1].apemat);
         $('#txtfecnac2').val(data.famdet[1].fecnac);
         $('#cmbtipdoc2').val(data.famdet[1].tipdoc);
         $('#txtnumdoc2').val(data.famdet[1].numdoc);
         $('#cmbestcivil2').val(data.famdet[1].estcivil);
         $('#cmbnacionalidad2').val(data.famdet[1].nacionalidad);
         $('#txtcelular2').val(data.famdet[1].celular1);
         $('#txtcorreo2').val(data.famdet[1].email);
         $('#chkResponsable2').prop('checked', data.famdet[1].responsable == 'S' ? true : false);
      }

      // mostar hijos
      jqxgridListadoHijosSource.localdata = [];
      if (listaFamiliaHijos[data.codfam]) {
         jqxgridListadoHijosSource.localdata = listaFamiliaHijos[data.codfam];
      }
      $(jqxgridListadoHijos).jqxGrid('updateBoundData', 'data');

      $('#divgridListadoHijo').show();
      $('#btnDelete').prop('disabled', false);
      $('#txtfecing').prop('disabled', true);
   }

   function guardarDatosFamilia() {
      let datos = new FormData();
      // datos familia
      datos.append('action', $('#txtaction').val());
      datos.append('codigoFam', $('#txtcodigo').val());
      datos.append('fecingFam', $('#txtfecing').val());
      datos.append('nombreFam', $('#txtnombreFam').val());
      datos.append('direccionFam', $('#txtdirFam').val());
      datos.append('correoFam', $('#txtcorreoFam').val());
      datos.append('celularFam', $('#txtcelularFam').val());
      // datos padre / apoderado
      datos.append('codper1', $('#txtcodper1').val());
      datos.append('parentesco1', $('#cmbparentesco1').val());
      datos.append('nombre1', $('#txtnombre1').val());
      datos.append('apepat1', $('#txtapepat1').val());
      datos.append('apemat1', $('#txtapemat1').val());
      datos.append('fecnac1', $('#txtfecnac1').val());
      datos.append('tipdoc1', $('#cmbtipdoc1').val());
      datos.append('numdoc1', $('#txtnumdoc1').val());
      datos.append('estcivil1', $('#cmbestcivil1').val());
      datos.append('nacionalidad1', $('#cmbnacionalidad1').val());
      datos.append('celular1', $('#txtcelular1').val());
      datos.append('correo1', $('#txtcorreo1').val());
      datos.append('responsable1', $('#chkResponsable1').is(':checked') ? 'S' : 'N');
      // datos madre / apoderada
      datos.append('codper2', $('#txtcodper2').val());
      datos.append('parentesco2', $('#cmbparentesco2').val());
      datos.append('nombre2', $('#txtnombre2').val());
      datos.append('apepat2', $('#txtapepat2').val());
      datos.append('apemat2', $('#txtapemat2').val());
      datos.append('fecnac2', $('#txtfecnac2').val());
      datos.append('tipdoc2', $('#cmbtipdoc2').val());
      datos.append('numdoc2', $('#txtnumdoc2').val());
      datos.append('estcivil2', $('#cmbestcivil2').val());
      datos.append('nacionalidad2', $('#cmbnacionalidad2').val());
      datos.append('celular2', $('#txtcelular2').val());
      datos.append('correo2', $('#txtcorreo2').val());
      datos.append('responsable2', $('#chkResponsable2').is(':checked') ? 'S' : 'N');

      $.ajax({
         type: "POST",
         url: "<?= MODULO_URL ?>/mantenimiento-familia/json/guardar",
         data: datos,
         contentType: false,
         processData: false,
         beforeSend: function() {
            $(jqxgridFamilia).jqxGrid('showloadelement');
         },
         success: function(response) {
            if (response.listaFamilias) {
               jqxgridFamiliaSource.localdata = response.listaFamilias;
               $(jqxgridFamilia).jqxGrid('updateBoundData', 'data');
               showAlertSweet('Familia guardada correctamente', 'success');
               limpiarFormulario();
               totalRegistros();
            }
         },
         error: function(jqXHR, status, error) {
            let message = error;
            if (jqXHR.responseJSON) {
               message = jqXHR.responseJSON.message;
            }
            showAlertSweet(message, 'error');
         },
      });
   }

   $(document).ready(function() {

      $(jqxgridFamilia).jqxGrid({
         width: '100%',
         height: '653',
         source: jqxgridFamiliaAdapter,
         showfilterrow: true,
         filterable: true,
         columns: [{
               text: "Código",
               datafield: 'codfam',
               align: 'center',
               cellsalign: 'center',
               width: "20%",
            },
            {
               text: "Familia",
               datafield: 'nombre',
               align: 'center',
               width: "80%",
            }
         ]
      });

      $(jqxgridListadoHijos).jqxGrid({
         width: '100%',
         height: '160',
         source: jqxgridListadoHijosAdapter,
         filterable: false,
         columns: [{
               text: "Código",
               datafield: 'codalu',
               align: 'center',
               cellsalign: 'center',
               width: "20%",
            },
            {
               text: "Apellidos y Nombres",
               datafield: 'nomcomp',
               align: 'center',
               width: "65%",
            },
            {
               text: "Estado",
               datafield: 'estado',
               align: 'center',
               cellsalign: 'center',
               width: "15%",
            }
         ]
      });

      $(jqxgridFamilia).on('rowselect', function(event) {
         const datarow = event.args.row;
         if (datarow) {
            frmFamilia.classList.remove('was-validated');
            seleccionarFamilia(datarow);
         } else {
            $('#txtaction').val('I');
         }
      });

      $('#frmFamilia').submit(function(e) {
         e.preventDefault();
         if (!frmFamilia.checkValidity()) {
            e.stopPropagation();
            showAlertSweet('Debe completar todos los campos obligatorios para continuar', 'warning');
         } else {
            guardarDatosFamilia();
         }
         frmFamilia.classList.add('was-validated');
      });

      $('#btnAdd').click(function() {
         limpiarFormulario();
      });

      $('#chkResponsable1').change(function(e) {
         let valor = $(this).is(':checked');
         $('#chkResponsable2').prop('checked', !valor);
      });

      $('#chkResponsable2').change(function(e) {
         let valor = $(this).is(':checked');
         $('#chkResponsable1').prop('checked', !valor);
      });

      $(jqxgridFamilia).jqxGrid('selectrow', 0);

      totalRegistros();

   });
</script>
<?= $this->endSection() ?>