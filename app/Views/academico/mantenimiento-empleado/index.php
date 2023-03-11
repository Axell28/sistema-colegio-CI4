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

   .photo-box {
      position: relative;
      overflow: hidden;
      border-radius: .5em;
   }

   .photo-box img {
      cursor: pointer;
      width: 140px;
      height: 150px;
      box-sizing: border-box;
      border-radius: .5em;
   }

   .photo-opt {
      position: absolute;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      width: 100%;
      height: 100%;
      opacity: 0;
      background-color: rgb(80, 80, 80, .7);
      color: white;
      border-radius: 1px;
   }

   .photo-box:hover .photo-opt {
      opacity: 1;
   }

   #quitarPhoto {
      display: none;
   }

   #btnActivarUsuario {
      display: none;
   }
</style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container-fluid">
   <div class="row mt-1 mb-3">
      <div class="col-lg-8 my-auto">
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?= MODULO_URL ?>"><?= MODULO_NAME ?></a></li>
               <li class="breadcrumb-item active" aria-current="page">Mantenimiento Personal</li>
            </ol>
         </nav>
      </div>
      <div class="col-sm text-end my-auto">
         <button class="btn btn-warning btn-sm" id="btnActivarUsuario">
            <i class="fas fa-shield-check"></i>
            <span>&nbsp;Activar usuario</span>
         </button>
      </div>
   </div>
   <div class="row">
      <div class="col-md-6">
         <div class="card card-main">
            <div class="card-body">
               <div class="row mb-1">
                  <div class="col-sm-8 my-1">
                     <div class="form-floating filter">
                        <select class="form-select" id="cmbfilarea">
                           <option value="" selected>-Todos-</option>
                           <?php foreach (@$listaAreasLab as $dato) { ?>
                              <option value="<?= $dato['codigo'] ?>"><?= $dato['descripcion'] ?></option>
                           <?php } ?>
                        </select>
                        <label for="cmbfilgrado">Área laboral:</label>
                     </div>
                  </div>
                  <div class="col-sm-4 my-1">
                     <div class="form-floating filter">
                        <select class="form-select filter" id="cmbfilestado">
                           <option value="">-Todos-</option>
                           <option value="A" selected>Activo</option>
                           <option value="I">Inactivos</option>
                        </select>
                        <label for="cmbfilestado">Estado:</label>
                     </div>
                  </div>
               </div>
               <div class="row mb-2">
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
               </div>
               <div id="jqxgridEmpleados"></div>
               <div class="pt-3">
                  <p class="mb-0" id="totalReg">Total de Personal : &nbsp; 0</p>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-6">
         <div class="card card-main">
            <div class="card-body">
               <form id="frmEmpleado" class="needs-validation" autocomplete="off" onkeypress="return event.keyCode != 13;" novalidate>
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
                              <a href="#data_adicional" class="nav-link text-center" data-bs-toggle="tab">Datos Adicionales</a>
                           </li>
                           <li class="nav-item flex-fill">
                              <a href="#data_academico" class="nav-link text-center" data-bs-toggle="tab">Datos Laborales</a>
                           </li>
                        </ul>
                     </div>
                     <div class="card-body">
                        <div class="tab-content">
                           <div class="tab-pane fade show active" id="data_principal">
                              <div class="row mt-2">
                                 <div class="col-auto">
                                    <div class="photo-box">
                                       <div class="photo-opt">
                                          <label for="imagenUp" class="btn btn-link text-white fs-1" data-bs-toggle="tooltip" title="Subir imagen">
                                             <i class="fal fa-camera-alt"></i>
                                          </label>
                                          <a id="quitarPhoto" class="text-white" data-bs-toggle="tooltip" title="Quitar imagen" style="cursor: pointer;">Quitar</a>
                                       </div>
                                       <img src="" onerror="this.src = '<?= base_url('img/default/man.png') ?>'">
                                       <input type="file" id="imagenUp" accept="image/png, image/jpeg, image/jpg" style="display: none;">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <label for="txtcodigo" class="form-label">Código:</label>
                                    <input type="text" id="txtcodigo" name="codigo" class="form-control mb-3" disabled>
                                    <label for="txtestado" class="form-label">Estado:</label>
                                    <select class="form-select" id="cmbestado" name="estado" required>
                                       <option value="A">Activo</option>
                                       <option value="I">Inactivo</option>
                                    </select>
                                 </div>
                              </div>
                              <div class="row mt-2">
                                 <div class="col my-2">
                                    <label for="txtnombre" class="form-label">Nombres:</label>
                                    <input type="text" class="form-control" name="nombres" id="txtnombre" required>
                                    <div class="invalid-feedback">Campo requerido</div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md my-2">
                                    <label for="txtapepat" class="form-label">Apellido paterno:</label>
                                    <input type="text" class="form-control text-uppercase" name="apepat" id="txtapepat" required>
                                    <div class="invalid-feedback">Campo requerido</div>
                                 </div>
                                 <div class="col-md my-2">
                                    <label for="txtapemat" class="form-label">Apellido materno:</label>
                                    <input type="text" class="form-control text-uppercase" name="apemat" id="txtapemat" required>
                                    <div class="invalid-feedback">Campo requerido</div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-6 my-2">
                                    <label class="form-label" for="txtfecnac">Fecha de nacimiento:</label>
                                    <input type="date" class="form-control" id="txtfecnac" name="fecnac" required>
                                    <div class="invalid-feedback">Campo requerido</div>
                                 </div>
                                 <div class="col-md-2 my-2">
                                    <label for="txtedad" class="form-label">Edad:</label>
                                    <input type="text" class="form-control text-center" id="txtedad" disabled>
                                 </div>
                                 <div class="col-md-4 my-2">
                                    <label for="cmbsexo" class="form-label">Sexo:</label>
                                    <select class="form-select" id="cmbsexo" name="sexo" required>
                                       <option value="">-Seleccione-</option>
                                       <option value="M">Masculino</option>
                                       <option value="F">Femenino</option>
                                    </select>
                                    <div class="invalid-feedback">Campo requerido</div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-6 my-2">
                                    <label for="cmbtipdoc" class="form-label">Tipo documento:</label>
                                    <select class="form-select" id="cmbtipdoc" name="tipdoc" required>
                                       <option value="">-Seleccione-</option>
                                       <?php foreach (esc($listaDocumentosIde) as $dato) { ?>
                                          <option value="<?= $dato['codigo'] ?>"><?= $dato['descripcion'] ?></option>
                                       <?php } ?>
                                    </select>
                                    <div class="invalid-feedback">Campo requerido</div>
                                 </div>
                                 <div class="col-md-6 my-2">
                                    <label for="txtnumdoc" class="form-label">Nro documento:</label>
                                    <input type="text" class="form-control" name="numdoc" id="txtnumdoc" onkeypress="return inputSoloNumeros(event)" required>
                                    <div class="invalid-feedback">Campo requerido</div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-6 my-2">
                                    <label for="cmbestcivil" class="form-label">Estado civil:</label>
                                    <select class="form-select" id="cmbestcivil" name="estcivil">
                                       <option value="">-Seleccione-</option>
                                       <?php foreach (@$listaEstadoCivil as $data) { ?>
                                          <option value="<?= $data['codigo'] ?>"><?= $data['descripcion'] ?></option>
                                       <?php } ?>
                                    </select>
                                    <div class="invalid-feedback">Campo requerido</div>
                                 </div>
                                 <div class="col-md my-2">
                                    <label for="txtruc" class="form-label">RUC:</label>
                                    <input type="text" class="form-control" name="ruc" id="txtruc" onkeypress="return inputSoloNumeros(event)">
                                 </div>
                              </div>
                              <hr class="mb-0 pb-0">
                              <div class="row mt-2">
                                 <div class="col-12 my-2">
                                    <label for="cmbperfil" class="form-label">Perfil de sistema:</label>
                                    <select class="form-select" id="cmbperfil" name="perfil" required>
                                       <option value="">-Seleccione-</option>
                                       <?php foreach (@$listaPerfiles as $dato) { ?>
                                          <option value="<?= $dato['perfil'] ?>"><?= $dato['nombre'] ?></option>
                                       <?php } ?>
                                    </select>
                                    <div class="invalid-feedback">Debe seleccionar el perfil del personal</div>
                                 </div>
                              </div>
                           </div>
                           <div class="tab-pane fade" id="data_adicional">
                              <div class="row">
                                 <div class="col-md my-2">
                                    <label for="txtcelular1" class="form-label">Celular 1:</label>
                                    <input type="text" class="form-control" name="celular1" id="txtcelular1">
                                 </div>
                                 <div class="col-md my-2">
                                    <label for="txtcelular2" class="form-label">Celular 2:</label>
                                    <input type="text" class="form-control" name="celular2" id="txtcelular2">
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col my-2">
                                    <label for="txtemail" class="form-label">Correo:</label>
                                    <input type="email" class="form-control" name="email" id="txtemail">
                                    <div class="invalid-feedback">Ingrese un correo válido</div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-12 my-2">
                                    <label for="txtdireccion" class="form-label">Dirección:</label>
                                    <input type="text" class="form-control" id="txtdireccion">
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-12 my-2">
                                    <label for="txtreferencia" class="form-label">Referencia:</label>
                                    <input type="text" class="form-control" id="txtreferencia">
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-4 my-2">
                                    <label for="cmbdepartamento" class="form-label">Departamento:</label>
                                    <select class="form-select" id="cmbdepartamento" name="departamento">
                                       <option value="">-Seleccione-</option>
                                       <?php foreach (@$listaDepartamentos as $dato) { ?>
                                          <option value="<?= $dato['codigo'] ?>"><?= $dato['nombre'] ?></option>
                                       <?php } ?>
                                    </select>
                                 </div>
                                 <div class="col-md-4 my-2">
                                    <label for="cmbprovincia" class="form-label">Provincia:</label>
                                    <select class="form-select" id="cmbprovincia" name="provincia">
                                       <option value="">-Seleccione-</option>
                                    </select>
                                 </div>
                                 <div class="col-md-4 my-2">
                                    <label for="cmbdistrito" class="form-label">Distrito:</label>
                                    <select class="form-select" id="cmbdistrito" name="distrito">
                                       <option value="">-Seleccione-</option>
                                    </select>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-6 my-2">
                                    <label for="cmbreligion" class="form-label">Religión:</label>
                                    <select class="form-select" id="cmbreligion" name="religion">
                                       <option value="">-Seleccione-</option>
                                    </select>
                                 </div>
                                 <div class="col-md-6 my-2">
                                    <label for="cmbnacionalidad" class="form-label">Nacionalidad:</label>
                                    <select class="form-select" id="cmbnacionalidad" name="nacionalidad">
                                       <option value="">-Seleccione-</option>
                                       <?php foreach (@$listaNacionalidad as $dato) { ?>
                                          <option value="<?= $dato['codigo'] ?>"><?= $dato['descripcion'] ?></option>
                                       <?php } ?>
                                    </select>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-12 my-2">
                                    <label for="txtlugnac" class="form-label">Lugar de nacimiento:</label>
                                    <input type="text" class="form-control" id="txtlugnac">
                                 </div>
                              </div>
                           </div>
                           <div class="tab-pane fade" id="data_academico">
                              <div class="row">
                                 <div class="col-md-6 my-2">
                                    <label for="txtfecing" class="form-label">Fecha de Ingreso:</label>
                                    <input type="date" class="form-control" name="fecing" id="txtfecing" value="<?= date('Y-m-d') ?>">
                                    <div class="invalid-feedback">Seleccion la fecha de ingreso</div>
                                 </div>
                                 <div class="col-md-6 my-2">
                                    <label class="form-label">Fecha de Salida:</label>
                                    <input type="date" class="form-control" name="fecsal" id="txtfecsal">
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-12 my-2">
                                    <label for="txtmotsal" class="form-label">Motivo de salida:</label>
                                    <textarea class="form-control" rows="2" name="motsal" id="txtmotsal"></textarea>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-9 my-2">
                                    <label for="cmbarea" class="form-label">Areá laboral:</label>
                                    <select class="form-select" name="area" id="cmbarea" required>
                                       <option value="">-Seleccione-</option>
                                       <?php foreach (@$listaAreasLab as $dato) { ?>
                                          <option value="<?= $dato['codigo'] ?>"><?= $dato['descripcion'] ?></option>
                                       <?php } ?>
                                    </select>
                                    <div class="invalid-feedback">Seleccione el área de trabajo</div>
                                 </div>
                                 <div class="col-sm-3 my-auto">
                                    <label class="form-label"></label>
                                    <div class="d-flex justify-content-end mt-1">
                                       <div class="form-check">
                                          <input class="form-check-input" type="checkbox" value="S" id="chekcDocente" style="transform: scale(1.3);">
                                          <label class="form-check-label" for="chekcDocente">&nbsp;Docente</label>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-12 my-2">
                                    <label for="cmbcargo" class="form-label">Cargo o puesto:</label>
                                    <select class="form-select" name="cargo" id="cmbcargo">
                                       <option value="">-Seleccione-</option>
                                       <?php foreach (@$listaCargosLab as $dato) { ?>
                                          <option value="<?= $dato['codigo'] ?>"><?= $dato['descripcion'] ?></option>
                                       <?php } ?>
                                    </select>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-12 my-2">
                                    <label for="txtprofesion" class="form-label">Profesión:</label>
                                    <input type="text" class="form-control" name="profesion" id="txtprofesion">
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-12 my-2">
                                    <label id="txtinfoaca" class="form-label">Información Acádemica:</label>
                                    <textarea class="form-control" name="infoaca" id="txtinfoaca" rows="3"></textarea>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <input type="hidden" name="action" id="txtaction" value="I">
                  <input type="hidden" name="codper" id="txtcodper" value="0">
               </form>
            </div>
         </div>
      </div>
   </div>
</div>

<form id="frmReporte" action="<?= MODULO_URL ?>/reporte/generate" target="_blank" method="POST">
   <input type="hidden" name="codrep" value="0002">
   <input type="hidden" name="area" id="rep_area" value="">
   <input type="hidden" name="estado" id="rep_estado" value="">
</form>

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script>
   const frmEmpleado = document.getElementById('frmEmpleado');
   const jqxgridEmpleados = "#jqxgridEmpleados";

   const listaProvincias = JSON.parse(`<?= json_encode(esc($listaProvincias)) ?>`);
   const listaDistritos = JSON.parse(`<?= json_encode(esc($listaDistritos)) ?>`);

   const jqxgridEmpleadosSource = {
      datatype: 'json',
      localdata: `<?= json_encode(@$listaEmpleados) ?>`
   };

   const jqxgridEmpleadosAdapter = new $.jqx.dataAdapter(jqxgridEmpleadosSource);

   function calcularEdad(strdate) {
      const date_actual = new Date()
      const date_fecnac = new Date(strdate);
      const date_difage = date_actual.getTime() - date_fecnac.getTime();
      const ageDate = new Date(date_difage);
      return Math.abs(ageDate.getUTCFullYear() - 1970);
   }

   function inputSoloNumeros(evt) {
      var code = (evt.which) ? evt.which : evt.keyCode
      if (code > 31 && (code < 48 || code > 57))
         return false;
      return true;
   }

   function totalRegistros() {
      const info = $(jqxgridEmpleados).jqxGrid('getdatainformation');
      $('#totalReg').html(`Total de Personal : &nbsp; ` + info.rowscount);
   }

   function limpiarFormulario() {
      $('#frmEmpleado').trigger("reset");
      $('#txtaction').val('I');
      $('.photo-box img').attr('src', `<?= base_url('img/default/man.png') ?>`);
      $('#btnDelete').prop('disabled', true);
      $('#cmbestado').prop('disabled', false);
      $('#btnActivarUsuario').hide();
      $(jqxgridEmpleados).jqxGrid('clearselection');
      frmEmpleado.classList.remove('was-validated');
   }

   function filtarGrillaEmpleado() {
      $.ajax({
         type: "POST",
         url: "<?= MODULO_URL ?>/mantenimiento-empleado/json/listar",
         data: {
            filestado: $('#cmbfilestado').val(),
            filarea: $('#cmbfilarea').val()
         },
         beforeSend: function() {
            $(jqxgridEmpleados).jqxGrid('showloadelement');
         },
         success: function(response) {
            if (response.listaEmpleados) {
               jqxgridEmpleadosSource.localdata = response.listaEmpleados;
               $(jqxgridEmpleados).jqxGrid('updateBoundData', 'data');
               totalRegistros();
            }
         }
      });
   }

   function guardarEmpleado() {
      let datos = new FormData();
      let cargoImagen = (document.getElementById('imagenUp').files.length > 0);
      datos.append('action', $('#txtaction').val());
      datos.append('codemp', $('#txtcodigo').val());
      datos.append('codper', $('#txtcodper').val());
      datos.append('estado', $('#cmbestado').val());
      datos.append('nombres', $('#txtnombre').val());
      datos.append('apepat', $('#txtapepat').val());
      datos.append('apemat', $('#txtapemat').val());
      datos.append('fecnac', $('#txtfecnac').val());
      datos.append('sexo', $('#cmbsexo').val());
      datos.append('tipdoc', $('#cmbtipdoc').val());
      datos.append('numdoc', $('#txtnumdoc').val());
      datos.append('estcivil', $('#cmbestcivil').val());
      datos.append('ruc', $('#txtruc').val());
      datos.append('perfil', $('#cmbperfil').val());
      datos.append('celular1', $('#txtcelular1').val());
      datos.append('celular2', $('#txtcelular2').val());
      datos.append('email', $('#txtemail').val());
      datos.append('direccion', $('#txtdireccion').val());
      datos.append('referencia', $('#txtreferencia').val());
      datos.append('departamento', $('#cmbdepartamento').val());
      datos.append('provincia', $('#cmbprovincia').val());
      datos.append('distrito', $('#cmbdistrito').val());
      datos.append('religion', $('#cmbreligion').val());
      datos.append('nacionalidad', $('#cmbnacionalidad').val());
      datos.append('lugnac', $('#txtlugnac').val());
      datos.append('fecing', $('#txtfecing').val());
      datos.append('fecsal', $('#txtfecsal').val());
      datos.append('motsal', $('#txtmotsal').val());
      datos.append('area', $('#cmbarea').val());
      datos.append('cargo', $('#cmbcargo').val());
      datos.append('profesion', $('#txtprofesion').val());
      datos.append('infoaca', $('#txtinfoaca').val());
      datos.append('docente', $('#chekcDocente').prop('checked') ? 'S' : 'N');
      datos.append('cargoImagen', cargoImagen ? 'S' : 'N');
      datos.append('imagen', cargoImagen ? document.getElementById('imagenUp').files[0] : null);
      datos.append('filestado', $('#cmbfilestado').val());

      $.ajax({
         type: "POST",
         url: "<?= MODULO_URL ?>/mantenimiento-empleado/json/guardar",
         data: datos,
         contentType: false,
         processData: false,
         beforeSend: function() {
            $(jqxgridEmpleados).jqxGrid('showloadelement');
         },
         success: function(response) {
            if (response.listaEmpleados) {
               jqxgridEmpleadosSource.localdata = response.listaEmpleados;
               $(jqxgridEmpleados).jqxGrid('updateBoundData', 'data');
               showAlertSweet('Personal guardado correctamente', 'success');
               totalRegistros();
               limpiarFormulario();
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

   function seleccionarEmpleado(data) {
      $('#frmEmpleado').trigger("reset");
      $('#txtaction').val('E');
      $('#txtcodigo').val(data.codemp);
      $('#txtcodper').val(data.codper);
      $('#cmbestado').val(data.estado);
      $('#txtnombre').val(data.nombres);
      $('#txtapepat').val(data.apepat);
      $('#txtapemat').val(data.apemat);
      $('#txtfecnac').val(data.fecnac);
      $('#cmbsexo').val(data.sexo);
      $('#cmbtipdoc').val(data.tipdoc);
      $('#txtnumdoc').val(data.numdoc);
      $('#cmbestcivil').val(data.estcivil);
      $('#txtruc').val(data.ruc);
      $('#txtcelular1').val(data.celular1);
      $('#txtcelular2').val(data.celular2);
      $('#txtemail').val(data.email);
      $('#txtdireccion').val(data.direccion);
      $('#txtreferencia').val(data.referencia);
      $('#cmbdepartamento').val(data.dept);
      $('#cmbdepartamento').change();
      $('#cmbprovincia').val(data.prov);
      $('#cmbprovincia').change();
      $('#cmbdistrito').val(data.ubgdir);
      $('#cmbreligion').val(data.religion);
      $('#cmbnacionalidad').val(data.nacionalidad);
      $('#txtlugnac').val(data.lugnac);
      $('#txtfecing').val(data.fecing);
      $('#txtfecsal').val(data.fecsal);
      $('#txtmotsal').val(data.motsal);
      $('#txtprofesion').val(data.profesion);
      $('#txtinfoaca').val(data.infoaca);
      $('#cmbperfil').val(data.perfil);
      $('#chekcDocente').prop('checked', (data.docente == 'S'));
      if (data.fecnac != null) {
         $('#txtedad').val(calcularEdad(data.fecnac));
      }
      if (data.fotourl != null && data.fotourl != '') {
         $('.photo-box img').attr('src', `${data.fotourl}?t<?= time() ?>`);
         $('#quitarPhoto').show();
      } else {
         let src = data.sexo !== 'F' ? `<?= base_url('img/default/man.png') ?>` : `<?= base_url('img/default/woman.png') ?>`;
         $('.photo-box img').attr('src', src);
         $('#quitarPhoto').hide();
      }

      if (data.tiene_usuario == 'S') {
         $('#btnActivarUsuario').hide();
      } else {
         $('#btnActivarUsuario').show();
      }

      $('#cmbarea').val(data.area);
      $('#cmbcargo').val(data.cargo);
      $('#btnDelete').prop('disabled', false);
   }

   async function eliminarEmpleado() {
      let confirm = await showConfirmSweet('¿Está seguro de eliminar los datos del personal?', 'question');
      if (confirm) {
         $.ajax({
            type: "GET",
            url: "<?= MODULO_URL ?>/mantenimiento-empleado/json/eliminar",
            data: {
               codemp: $('#txtcodigo').val(),
            },
            success: function(response) {
               if (response.listaEmpleados) {
                  jqxgridEmpleadosSource.localdata = response.listaEmpleados;
                  $(jqxgridEmpleados).jqxGrid('updateBoundData', 'data');
                  $(jqxgridEmpleados).jqxGrid('selectrow', 0);
                  showAlertSweet('Empleado eliminado correctamente', 'success');
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
   }

   async function eliminarFotoEmpleado() {
      let confirm = await showConfirmSweet('¿Está seguro de eliminar la foto del personal?', 'question');
      if (confirm) {
         $.ajax({
            type: "GET",
            url: "<?= MODULO_URL ?>/mantenimiento-empleado/json/eliminar-foto",
            data: {
               codemp: $('#txtcodigo').val()
            },
            beforeSend: function() {

            },
            success: function(response) {
               if (response.listaEmpleados) {
                  jqxgridEmpleadosSource.localdata = response.listaEmpleados;
                  $(jqxgridEmpleados).jqxGrid('updateBoundData', 'data');
                  $(jqxgridEmpleados).jqxGrid('selectrow', $(jqxgridEmpleados).jqxGrid('getselectedrowindex'));
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
   }

   $(document).ready(function() {

      $(jqxgridEmpleados).jqxGrid({
         width: '100%',
         height: '663',
         source: jqxgridEmpleadosAdapter,
         showfilterrow: true,
         filterable: true,
         columns: [{
               text: "Código",
               datafield: 'codemp',
               align: 'center',
               cellsalign: 'center',
               width: "20%",
            },
            {
               text: "Nombre completo",
               datafield: 'nomcomp',
               align: 'center',
               width: "50%",
            },
            {
               text: "Nro. Documento",
               datafield: 'numdoc',
               align: 'center',
               cellsalign: 'center',
               width: "30%",
            }
         ]
      });

      $('#frmEmpleado').submit(function(e) {
         e.preventDefault();
         if (!frmEmpleado.checkValidity()) {
            e.stopPropagation();
            showAlertSweet('Debe completar todos los campos obligatorios para continuar', 'warning');
         } else {
            guardarEmpleado();
         }
         frmEmpleado.classList.add('was-validated');
      });

      $('#txtfecnac').change(function() {
         let edad = calcularEdad($(this).val());
         $('#txtedad').val(edad);
      });

      $('#imagenUp').change(function(e) {
         const imagenUp = e.target.files[0];
         if (imagenUp.type == 'image/png' || imagenUp.type == 'image/jpeg') {
            const reader = new FileReader();
            reader.readAsDataURL(imagenUp);
            reader.onload = (event) => {
               event.preventDefault();
               $('.photo-box img').attr('src', event.target.result);
            };
         } else {
            showAlertSweet('Debe seleccionar una imagen de tipo PNG o JPG', 'error');
         }
      });

      $('#cmbdepartamento').change(function() {
         let html = '<option value="">-Seleccione-</option>';
         let dept = $(this).val();
         if (dept !== '' && dept !== null) {
            const lista = listaProvincias[dept] ? listaProvincias[dept] : [];
            $.each(lista, function(index, value) {
               html += `<option value="${value.codigo}">${value.nombre}</option>`;
            });
         }
         $('#cmbprovincia').html(html);
      });

      $('#cmbprovincia').on('change', function() {
         let html = '<option value="">-Seleccione-</option>';
         let dept = $('#cmbdepartamento').val();
         let prov = $(this).val();
         if (prov !== '' && prov !== null) {
            const lista = listaDistritos[dept][prov] ? listaDistritos[dept][prov] : [];
            $.each(lista, function(index, value) {
               html += `<option value="${value.codigo}">${value.nombre}</option>`;
            });
         }
         $('#cmbdistrito').html(html);
      });

      $(jqxgridEmpleados).on('rowselect', function(event) {
         const datarow = event.args.row;
         if (datarow) {
            frmEmpleado.classList.remove('was-validated');
            seleccionarEmpleado(datarow);
         } else {
            $('#txtaction').val('I');
         }
      });

      $('#btnAdd').click(function() {
         limpiarFormulario();
      });

      $('#btnDelete').click(function(e) {
         if ($('#txtcodigo').val() == '') return;
         eliminarEmpleado();
      });

      $('#quitarPhoto').click(function(e) {
         eliminarFotoEmpleado();
      });

      $('#btnActivarUsuario').click(function(e) {
         let index = $(jqxgridEmpleados).jqxGrid('getselectedrowindex');
         let rowdata = $(jqxgridEmpleados).jqxGrid('getrowdata', index);
         $.ajax({
            type: "POST",
            url: "<?= MODULO_URL ?>/mantenimiento-empleado/json/activar-usuario",
            data: {
               codigo: rowdata.codemp,
               nomcomp: rowdata.nomcomp,
               apellidos: rowdata.apepat + " " + rowdata.apemat,
               nombres: rowdata.nombres,
               email: rowdata.email
            },
            success: function(response) {
               if (response.listaEmpleados) {
                  showAlertSweet('Usuario activado correctamente!', 'success');
                  Swal.fire({
                     icon: 'success',
                     title: 'Usuario activado',
                     text: '',
                     html: `<div class="mb-2">Usuario:&nbsp; ${response.usuario}</div><div>Contraseña:&nbsp; ${response.password}</div>`,
                     allowOutsideClick: false
                  });
                  $('#btnActivarUsuario').hide();
                  jqxgridEmpleadosSource.localdata = response.listaEmpleados;
                  $(jqxgridEmpleados).jqxGrid('updateBoundData', 'data');
                  $(jqxgridEmpleados).jqxGrid('selectrow', $(jqxgridEmpleados).jqxGrid('getselectedrowindex'));
               }
            }
         });
      });

      $('#btnReporte').click(function(e) {
         $('#rep_estado').val($('#cmbfilestado').val());
         $('#frmReporte').submit();
      });

      $('.filter').change(function(e) {
         e.preventDefault();
         filtarGrillaEmpleado();
      });

      $(jqxgridEmpleados).jqxGrid('selectrow', 0);

      totalRegistros();

   });
</script>
<?= $this->endSection() ?>