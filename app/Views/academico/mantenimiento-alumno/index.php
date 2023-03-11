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
               <li class="breadcrumb-item active" aria-current="page">Mantenimiento Alumno</li>
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
                  <div class="col-sm-3 my-1">
                     <div class="form-floating">
                        <select class="form-select filter" id="cmbfilnivel">
                           <option value="" selected>-Todos-</option>
                           <?php foreach (@$listaNiveles as $val) { ?>
                              <option value="<?= $val['nivel'] ?>"><?= $val['descripcion'] ?></option>
                           <?php } ?>
                        </select>
                        <label for="cmbfilnivel">Nivel:</label>
                     </div>
                  </div>
                  <div class="col-sm-3 my-1">
                     <div class="form-floating">
                        <select class="form-select filter" id="cmbfilmatricula">
                           <option value="" selected>-Todos-</option>
                           <option value="S">Si</option>
                           <option value="N">No</option>
                        </select>
                        <label for="cmbfilmatricula">Matriculado:</label>
                     </div>
                  </div>
                  <div class="col-sm-3 my-1">
                     <div class="form-floating">
                        <select class="form-select filter" id="cmbfilsexo">
                           <option value="" selected>-Todos-</option>
                           <option value="M">Masculino</option>
                           <option value="F">Femenino</option>
                        </select>
                        <label for="cmbfilsexo">Sexo:</label>
                     </div>
                  </div>
                  <div class="col-sm-3 my-1">
                     <div class="form-floating">
                        <select class="form-select filter" id="cmbfilestado">
                           <option value="">-Todos-</option>
                           <option value="A" selected>Activo</option>
                           <option value="E">Egresados</option>
                           <option value="R">Retirados</option>
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
               <div id="jqxgridAlumnos"></div>
               <div class="pt-3">
                  <p class="mb-0" id="totalReg">Total de Alumnos : &nbsp; 0</p>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-6">
         <div class="card card-main">
            <div class="card-body">
               <form id="frmAlumno" class="needs-validation" autocomplete="off" onkeypress="return event.keyCode != 13;" novalidate>
                  <div class="row mb-2">
                     <div class="col-sm-4 my-2">
                        <button class="btn btn-primary w-100" id="btnAdd" type="button">
                           <i class="fas fa-plus-circle"></i>
                           <span>&nbsp;Nuevo</span>
                        </button>
                     </div>
                     <div class="col-sm-4 my-2">
                        <button class="btn btn-success w-100" type="submit">
                           <i class="fas fa-save"></i>
                           <span>&nbsp;Guardar</span>
                        </button>
                     </div>
                     <div class="col-sm-4 my-2">
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
                              <a href="#data_academico" class="nav-link text-center" data-bs-toggle="tab">Datos Académicos</a>
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
                                 <div class="col-sm-3">
                                    <label for="txtcodigo" class="form-label">Código:</label>
                                    <input type="text" id="txtcodigo" name="codigo" class="form-control mb-3" disabled>
                                    <label for="txtestado" class="form-label">Estado:</label>
                                    <select class="form-select" id="cmbestado" name="estado" required>
                                       <option value="A">Activo</option>
                                       <option value="E">Egresado</option>
                                       <option value="R">Retirado</option>
                                    </select>
                                 </div>
                                 <div class="col-sm-3">
                                    <label for="cmbmatricula" class="form-label">Matriculado:</label>
                                    <select class="form-select mb-3" id="cmbmatricula" name="matricula" disabled>
                                       <option value="N">NO</option>
                                       <option value="S">SI</option>
                                    </select>

                                    <label for="cmbanioing" class="form-label">Año de ingreso</label>
                                    <select class="form-select" id="cmbanioing" name="anioing">
                                       <?php foreach (@$listaAnios as $anio) { ?>
                                          <option value="<?= $anio['anio'] ?>" <?= ANIO == $anio['anio'] ? 'selected' : '' ?>><?= $anio['anio'] ?></option>
                                       <?php } ?>
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
                              <hr class="mb-0 pb-0">
                              <div class="row mt-2">
                                 <div class="col my-2">
                                    <label for="cmbfamilia" class="form-label">Familia:</label>
                                    <select class="form-select-2" id="cmbfamilia" name="familia" required>
                                       <option value="">-Seleccione-</option>
                                       <?php foreach (@$listaFamilias as $value) { ?>
                                          <option value="<?= $value['codigo'] ?>"><?= $value['nombre'] ?></option>
                                       <?php } ?>
                                    </select>
                                    <div class="invalid-feedback">Debe seleccionar la familia del estudiante</div>
                                 </div>
                              </div>
                           </div>
                           <div class="tab-pane fade" id="data_adicional">
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
                                       <?php foreach (esc($listaDepartamentos) as $dept) : ?>
                                          <option value="<?= $dept['codigo'] ?>"><?= $dept['nombre'] ?></option>
                                       <?php endforeach; ?>
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
                                       <?php foreach (esc($listaNacionalidad) as $dato) : ?>
                                          <option value="<?= $dato['codigo'] ?>"><?= $dato['descripcion'] ?></option>
                                       <?php endforeach; ?>
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
                                 <div class="col-md-4 my-2">
                                    <label for="cmbnivel" class="form-label">Nivel:</label>
                                    <select class="form-select" id="cmbnivel" required>
                                       <option value="">-Seleccione-</option>
                                       <?php foreach (@$listaNiveles as $val) { ?>
                                          <option value="<?= $val['nivel'] ?>"><?= $val['descripcion'] ?></option>
                                       <?php } ?>
                                    </select>
                                    <div class="invalid-feedback">Seleccione el nivel</div>
                                 </div>
                                 <div class="col-md-4 my-2">
                                    <label for="cmbgrado" class="form-label">Grado:</label>
                                    <select class="form-select" id="cmbgrado" required>
                                       <option value="">-Seleccione-</option>
                                    </select>
                                    <div class="invalid-feedback">Seleccione el grado</div>
                                 </div>
                                 <div class="col-md-4 my-2">
                                    <label for="cmbseccion" class="form-label">Sección:</label>
                                    <select class="form-select" id="cmbseccion" required>
                                       <option value="">-Seleccione-</option>
                                    </select>
                                    <div class="invalid-feedback">Seleccione la sección</div>
                                 </div>
                              </div>
                              <hr class="mb-2">
                              <div class="row">
                                 <div class="col-md-6 my-2">
                                    <label for="txtfecing" class="form-label">Fecha de Ingreso:</label>
                                    <input type="date" class="form-control" name="fecing" id="txtfecing" value="<?= date('Y-m-d') ?>" required>
                                    <div class="invalid-feedback">Seleccione la fecha de ingreso</div>
                                 </div>
                                 <div class="col-md-6 my-2">
                                    <label for="txtfecsal" class="form-label">Fecha de Salida:</label>
                                    <input type="date" class="form-control" name="fecsal" id="txtfecsal">
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-12 my-2">
                                    <label for="txtmotsal" class="form-label">Motivo de salida:</label>
                                    <textarea class="form-control" id="txtmotsal" rows="2"></textarea>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-12 my-2">
                                    <label class="form-label">Historial Académico:</label>
                                    <div id="jqxgridListadoMatricula"></div>
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
   <input type="hidden" name="codrep" value="0003">
   <input type="hidden" name="matricula" id="rep_matricula" value="">
   <input type="hidden" name="nivel" id="rep_nivel" value="">
   <input type="hidden" name="estado" id="rep_estado" value="">
   <input type="hidden" name="sexo" id="rep_sexo" value="">
</form>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script>
   const frmAlumno = document.getElementById('frmAlumno');
   const jqxgridAlumnos = "#jqxgridAlumnos";
   const jqxgridListadoMatricula = "#jqxgridListadoMatricula";
   const listaGrados = JSON.parse(`<?= json_encode(esc($listaGrados)) ?>`);
   const listaSecciones = JSON.parse(`<?= json_encode(esc($listaSecciones)) ?>`);
   const listaProvincias = JSON.parse(`<?= json_encode(esc($listaProvincias)) ?>`);
   const listaDistritos = JSON.parse(`<?= json_encode(esc($listaDistritos)) ?>`);
   const listaHistorialMatricula = JSON.parse(`<?= json_encode(@$listaHistorialMatricula) ?>`);

   const jqxgridAlumnosSource = {
      datatype: 'json',
      dataFields: [{
            name: 'codalu',
            type: 'string'
         },
         {
            name: 'codfam',
            type: 'string'
         },
         {
            name: 'anioing',
            type: 'string'
         },
         {
            name: 'fecsal',
            type: 'string'
         },
         {
            name: 'nivel',
            type: 'string'
         },
         {
            name: 'grado',
            type: 'string'
         },
         {
            name: 'seccion',
            type: 'string'
         },
         {
            name: 'matricula',
            type: 'string'
         },
         {
            name: 'estado',
            type: 'string'
         },
         {
            name: 'fecing',
            type: 'string'
         },
         {
            name: 'motsal',
            type: 'string'
         },
         {
            name: 'fotourl',
            type: 'string'
         },
         {
            name: 'nivel_des',
            type: 'string'
         },
         {
            name: 'grado_des',
            type: 'string'
         },
         {
            name: 'codper',
            type: 'string'
         },
         {
            name: 'nombres',
            type: 'string'
         },
         {
            name: 'apepat',
            type: 'string'
         },
         {
            name: 'apemat',
            type: 'string'
         },
         {
            name: 'fecnac',
            type: 'string'
         },
         {
            name: 'sexo',
            type: 'string'
         },
         {
            name: 'tipdoc',
            type: 'string'
         },
         {
            name: 'numdoc',
            type: 'string'
         },
         {
            name: 'estcivil',
            type: 'string'
         },
         {
            name: 'ruc',
            type: 'string'
         },
         {
            name: 'celular1',
            type: 'string'
         },
         {
            name: 'celular2',
            type: 'string'
         },
         {
            name: 'email',
            type: 'string'
         },
         {
            name: 'direccion',
            type: 'string'
         },
         {
            name: 'referencia',
            type: 'string'
         },
         {
            name: 'ubgdir',
            type: 'string'
         },
         {
            name: 'lugnac',
            type: 'string'
         },
         {
            name: 'nacionalidad',
            type: 'string'
         },
         {
            name: 'profesion',
            type: 'string'
         },
         {
            name: 'religion',
            type: 'string'
         },
         {
            name: 'infoaca',
            type: 'string'
         },
         {
            name: 'dept',
            type: 'string'
         },
         {
            name: 'prov',
            type: 'string'
         },
         {
            name: 'estado_des',
            type: 'string'
         },
         {
            name: 'nomcomp',
            type: 'string'
         },
         {
            name: 'tiene_usuario',
            type: 'string'
         }
      ],
      localdata: `<?= json_encode(esc($listaAlumnos)) ?>`
   };

   const jqxgridListadoMatriculaSource = {
      datatype: 'json',
      localdata: `[]`
   };

   const jqxgridAlumnosAdapter = new $.jqx.dataAdapter(jqxgridAlumnosSource);

   const jqxgridListadoMatriculaAdapter = new $.jqx.dataAdapter(jqxgridListadoMatriculaSource);

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
      const info = $(jqxgridAlumnos).jqxGrid('getdatainformation');
      $('#totalReg').html(`Total de Alumnos : &nbsp; ` + info.rowscount);
   }

   function limpiarFormulario() {
      $('#frmAlumno').trigger("reset");
      $('#txtaction').val('I');
      $('.photo-box img').attr('src', `<?= base_url('img/default/man.png') ?>`);
      $('#btnDelete').prop('disabled', true);
      $('#cmbestado').prop('disabled', false);
      $('#cmbanioing').prop('disabled', false);
      $('#cmbgrado').prop('disabled', false);
      $('#cmbnivel').prop('disabled', false);
      $('#cmbseccion').prop('disabled', false);
      $('#btnActivarUsuario').hide();
      $('#cmbfamilia').change();
      $(jqxgridAlumnos).jqxGrid('clearselection');
      frmAlumno.classList.remove('was-validated');
   }

   function filtarGrillaAlumno() {
      $.ajax({
         type: "POST",
         url: "<?= MODULO_URL ?>/mantenimiento-alumno/json/listar",
         data: {
            filnivel: $('#cmbfilnivel').val(),
            filmatricula: $('#cmbfilmatricula').val(),
            filestado: $('#cmbfilestado').val(),
            filsexo: $('#cmbfilsexo').val()
         },
         beforeSend: function() {
            $(jqxgridAlumnos).jqxGrid('showloadelement');
            $('#rep_matricula').val($('#cmbfilmatricula').val());
            $('#rep_nivel').val($('#cmbfilnivel').val());
            $('#rep_sexo').val($('#cmbfilsexo').val());
            $('#rep_estado').val($('#cmbfilestado').val());
         },
         success: function(response) {
            if (response.listaAlumnos) {
               jqxgridAlumnosSource.localdata = response.listaAlumnos;
               $(jqxgridAlumnos).jqxGrid('updateBoundData', 'data');
               totalRegistros();
            }
         }
      });
   }

   function seleccionarAlumno(data) {
      $('#frmAlumno').trigger("reset");
      $('#txtaction').val('E');
      $('#txtcodigo').val(data.codalu);
      $('#txtcodper').val(data.codper);
      $('#cmbestado').val(data.estado);
      $('#cmbanioing').val(data.anioing);
      $('#txtnombre').val(data.nombres);
      $('#txtapepat').val(data.apepat);
      $('#txtapemat').val(data.apemat);
      $('#txtfecnac').val(data.fecnac);
      $('#cmbsexo').val(data.sexo);
      $('#cmbtipdoc').val(data.tipdoc);
      $('#txtnumdoc').val(data.numdoc);
      $('#txtemail').val(data.email);
      $('#txtdireccion').val(data.direccion);
      $('#txtreferencia').val(data.referencia);
      $('#cmbdepartamento').val(data.dept);
      $('#cmbdepartamento').change();
      $('#cmbprovincia').val(data.prov);
      $('#cmbprovincia').change();
      $('#cmbdistrito').val(data.ubgdir);
      $('#txtfecing').val(data.fecing);
      $('#txtfecsal').val(data.fecsal);
      $('#txtmotsal').val(data.motsal);
      $('#cmbfamilia').val(data.codfam);
      $('#cmbfamilia').change();

      if (data.matricula == 'S') {
         $('#cmbmatricula').val('S');
      } else {
         $('#cmbmatricula').val('N');
      }
      $('#cmbnivel').val(data.nivel);
      $('#cmbnivel').change();
      $('#cmbgrado').val(data.grado);
      $('#cmbgrado').change();
      $('#cmbseccion').val(data.seccion);
      $('#cmbseccion').change();

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

      if (listaHistorialMatricula[data.codalu]) {
         jqxgridListadoMatriculaSource.localdata = listaHistorialMatricula[data.codalu];
         $(jqxgridListadoMatricula).jqxGrid('updateBoundData', 'data');
      } else {
         jqxgridListadoMatriculaSource.localdata = '[]';
         $(jqxgridListadoMatricula).jqxGrid('updateBoundData', 'data');
      }

      $('#cmbgrado').prop('disabled', true);
      $('#cmbnivel').prop('disabled', true);
      $('#cmbseccion').prop('disabled', true);
      $('#btnDelete').prop('disabled', false);
      $('#cmbestado').prop('disabled', true);
      $('#cmbanioing').prop('disabled', true);

   }

   function guardarAlumno() {
      let datos = new FormData();
      let cargoImagen = (document.getElementById('imagenUp').files.length > 0);
      datos.append('action', $('#txtaction').val());
      datos.append('codalu', $('#txtcodigo').val());
      datos.append('codfam', $('#cmbfamilia').val());
      datos.append('codper', $('#txtcodper').val());
      datos.append('estado', $('#cmbestado').val());
      datos.append('anioing', $('#cmbanioing').val());
      datos.append('nombres', $('#txtnombre').val());
      datos.append('apepat', $('#txtapepat').val());
      datos.append('apemat', $('#txtapemat').val());
      datos.append('fecnac', $('#txtfecnac').val());
      datos.append('sexo', $('#cmbsexo').val());
      datos.append('tipdoc', $('#cmbtipdoc').val());
      datos.append('numdoc', $('#txtnumdoc').val());
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
      datos.append('nivel', $('#cmbnivel').val());
      datos.append('grado', $('#cmbgrado').val());
      datos.append('seccion', $('#cmbseccion').val());
      datos.append('cargoImagen', cargoImagen ? 'S' : 'N');
      datos.append('imagenUp', cargoImagen ? document.getElementById('imagenUp').files[0] : null);

      $.ajax({
         type: "POST",
         url: "<?= MODULO_URL ?>/mantenimiento-alumno/json/guardar",
         data: datos,
         contentType: false,
         processData: false,
         beforeSend: function() {
            $(jqxgridAlumnos).jqxGrid('showloadelement');
         },
         success: function(response) {
            if (response.listaAlumnos) {
               jqxgridAlumnosSource.localdata = response.listaAlumnos;
               $(jqxgridAlumnos).jqxGrid('updateBoundData', 'data');
               showAlertSweet('Alumno guardado correctamente', 'success');
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

   async function eliminarAlumno() {
      let confirm = await showConfirmSweet('¿Está seguro de eliminar los datos del alumno?', 'question');
      if (confirm) {
         $.ajax({
            type: "GET",
            url: "<?= MODULO_URL ?>/mantenimiento-alumno/json/eliminar",
            data: {
               codalu: $('#txtcodigo').val(),
               codper: $('#txtcodper').val()
            },
            beforeSend: function() {
               $(jqxgridAlumnos).jqxGrid('showloadelement');
            },
            success: function(response) {
               if (response.listaAlumnos) {
                  jqxgridAlumnosSource.localdata = response.listaAlumnos;
                  $(jqxgridAlumnos).jqxGrid('updateBoundData', 'data');
                  $(jqxgridAlumnos).jqxGrid('selectrow', 0);
                  showAlertSweet('Alumno eliminado correctamente', 'success');
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
   }

   async function eliminarFotoAlumno() {
      let confirm = await showConfirmSweet('¿Está seguro de eliminar la foto del alumno?', 'question');
      if (confirm) {
         $.ajax({
            type: "GET",
            url: "<?= MODULO_URL ?>/mantenimiento-alumno/json/eliminar-foto",
            data: {
               codalu: $('#txtcodigo').val()
            },
            beforeSend: function() {

            },
            success: function(response) {
               if (response.listaAlumnos) {
                  jqxgridAlumnosSource.localdata = response.listaAlumnos;
                  $(jqxgridAlumnos).jqxGrid('updateBoundData', 'data');
                  $(jqxgridAlumnos).jqxGrid('selectrow', $(jqxgridAlumnos).jqxGrid('getselectedrowindex'));
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

      $('.form-select-2').select2({
         width: '100%',
         theme: 'bootstrap-5',
      });

      $(jqxgridAlumnos).jqxGrid({
         width: '100%',
         height: '588',
         source: jqxgridAlumnosAdapter,
         showfilterrow: true,
         filterable: true,
         columns: [{
               text: "Código",
               datafield: 'codalu',
               align: 'center',
               cellsalign: 'center',
               width: "20%",
            },
            {
               text: "Nombre completo",
               datafield: 'nomcomp',
               align: 'center',
               width: "56%",
            },
            {
               text: "N",
               align: 'center',
               datafield: 'nivel',
               cellsalign: 'center',
               filterable: false,
               width: "8%",
            },
            {
               text: "G",
               align: 'center',
               datafield: 'grado',
               cellsalign: 'center',
               filterable: true,
               width: "8%",
            },
            {
               text: "S",
               align: 'center',
               datafield: 'seccion',
               cellsalign: 'center',
               filterable: true,
               width: "8%",
            }
         ]
      });

      $(jqxgridListadoMatricula).jqxGrid({
         width: '100%',
         height: '190',
         source: jqxgridListadoMatriculaAdapter,
         filterable: false,
         columns: [{
               text: "Año",
               datafield: 'anio',
               align: 'center',
               cellsalign: 'center',
               width: "15%",
            },
            {
               text: "N",
               datafield: 'nivel',
               align: 'center',
               cellsalign: 'center',
               width: "8%",
            },
            {
               text: "G",
               datafield: 'grado',
               align: 'center',
               cellsalign: 'center',
               width: "8%",
            },
            {
               text: "S",
               datafield: 'seccion',
               align: 'center',
               cellsalign: 'center',
               width: "8%",
            },
            {
               text: "Salón",
               datafield: 'salonnom',
               align: 'center',
               width: "41%",
            },
            {
               text: "Sit. Final",
               datafield: 'sitacades',
               align: 'center',
               width: "20%",
               cellsalign: 'center',
            }
         ]
      });

      $('#frmAlumno').submit(function(e) {
         e.preventDefault();
         if (!frmAlumno.checkValidity()) {
            e.stopPropagation();
            showAlertSweet('Debe completar todos los campos obligatorios para continuar', 'warning');
         } else {
            guardarAlumno();
         }
         frmAlumno.classList.add('was-validated');
      });

      $('#cmbnivel').change(function(e) {
         let html = '<option value="">-Seleccione-</option>';
         let nivel = $(this).val();
         if (nivel != null && nivel != '') {
            let grados = listaGrados[nivel] ?? [];
            $.each(grados, function(index, value) {
               html += `<option value="${value.grado}">${value.descripcion}</option>`;
            });
         }
         $('#cmbgrado').html(html);
      });

      $('#cmbgrado').change(function(e) {
         let list = '<option>-Seleccione-</option>';
         let nivel = $('#cmbnivel').val();
         let grado = $(this).val();
         if (grado != null && grado != '') {
            let secciones = listaSecciones[nivel][grado] ? listaSecciones[nivel][grado] : [];
            $.each(secciones, function(index, value) {
               list += `<option value="${value.seccion}">${value.descripcion}</option>`;
            });
         }
         $('#cmbseccion').html(list);
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
               $('#subioFoto').val('S');
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

      $(jqxgridAlumnos).on('rowselect', function(event) {
         const datarow = event.args.row;
         if (datarow) {
            frmAlumno.classList.remove('was-validated');
            seleccionarAlumno(datarow);
         } else {
            $('#txtaction').val('I');
         }
      });

      $('#btnAdd').click(function() {
         limpiarFormulario();
      });

      $('#btnDelete').click(function(e) {
         if ($('#txtcodigo').val() == '') return;
         eliminarAlumno();
      });

      $('#quitarPhoto').click(function(e) {
         eliminarFotoAlumno();
      });

      $('#btnActivarUsuario').click(function(e) {
         let index = $(jqxgridAlumnos).jqxGrid('getselectedrowindex');
         let rowdata = $(jqxgridAlumnos).jqxGrid('getrowdata', index);
         $.ajax({
            type: "POST",
            url: "<?= MODULO_URL ?>/mantenimiento-alumno/json/activar-usuario",
            data: {
               codigo: rowdata.codalu,
               nomcomp: rowdata.nomcomp,
               apellidos: rowdata.apepat + " " + rowdata.apemat,
               nombres: rowdata.nombres,
               email: rowdata.email
            },
            success: function(response) {
               if (response.listaAlumnos) {
                  showAlertSweet('Usuario activado correctamente!', 'success');
                  Swal.fire({
                     icon: 'success',
                     title: 'Usuario activado',
                     text: '',
                     html: `<div class="mb-2">Usuario:&nbsp; ${response.usuario}</div><div>Contraseña:&nbsp; ${response.password}</div>`,
                     allowOutsideClick: false
                  });
                  $('#btnActivarUsuario').hide();
                  jqxgridAlumnosSource.localdata = response.listaAlumnos;
                  $(jqxgridAlumnos).jqxGrid('updateBoundData', 'data');
                  $(jqxgridAlumnos).jqxGrid('selectrow', $(jqxgridAlumnos).jqxGrid('getselectedrowindex'));
               }
            }
         });
      });

      $('#cmbfilnivel').change(function(e) {
         let list = '<option value="">-Todos-</option>';
         let nivel = $(this).val();
         let grados = listaGrados[nivel] ? listaGrados[nivel] : [];
         $.each(grados, function(index, value) {
            list += `<option value="${value.grado}">${value.descripcion}</option>`;
         });
         $('#cmbfilgrado').html(list);
      });

      $('.filter').change(function(e) {
         e.preventDefault();
         filtarGrillaAlumno();
      });

      $('#btnReporte').click(function(e) {
         $('#frmReporte').submit();
      });

      $(jqxgridAlumnos).jqxGrid('selectrow', 0);

      totalRegistros();

   });
</script>
<?= $this->endSection() ?>