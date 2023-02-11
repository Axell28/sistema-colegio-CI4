<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>

<style>
     h5 {
          font-size: 13px;
          color: #5591D1;
     }

     h5.linea {
          position: relative;
          z-index: 1;
     }

     h5.linea:before {
          border-top: 1px solid #BACFE6;
          content: "";
          position: absolute;
          top: 50%;
          left: 0;
          right: 0;
          bottom: 0;
          width: 100%;
          z-index: -1;
     }

     h5.linea span {
          background: #fff;
          padding: 0 15px 0px 0px;
     }

     #row_ficha {
          display: none;
     }
</style>

<div class="container-fluid">
     <div class="row mt-1 mb-3">
          <div class="col-12">
               <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                         <li class="breadcrumb-item"><a href="<?= MODULO_URL ?>"><?= MODULO_NAME ?></a></li>
                         <li class="breadcrumb-item"><a href="<?= MODULO_URL ?>/matricula">Matricula</a></li>
                         <li class="breadcrumb-item active" aria-current="page">Registro</li>
                    </ol>
               </nav>
          </div>
     </div>
     <div class="row">
          <div class="col-lg-6">
               <div class="card card-main">
                    <div class="card-body">
                         <p class="text-muted">Alumnos no matriculados</p>
                         <div id="jqxgridAlumnos"></div>
                    </div>
               </div>
          </div>
          <div class="col-lg-6">
               <div class="card card-main">
                    <div class="card-body">
                         <h5 class="linea mb-3"><span>Alumno(a)</span></h5>
                         <div class="row mb-4">
                              <div class="col-12">
                                   <label for="txtalunom" class="form-label">Apellidos y Nombres:</label>
                                   <input type="text" class="form-control" id="txtalunom" required disabled>
                              </div>
                         </div>
                         <div class="mb-4">
                              <h5 class="linea mb-3"><span>Historial de Matricula</span></h5>
                              <div id="jqxgridHistorialMatricula"></div>
                         </div>

                         <form id="frmMatricula" class="needs-validation" autocomplete="off" novalidate>
                              <h5 class="linea mb-3"><span>Datos de Matrícula</span></h5>
                              <div class="row mb-3">
                                   <div class="col-sm-4">
                                        <label for="cmbAnio" class="form-label">Año Académico:</label>
                                        <select class="form-select" id="cmbAnio" required>
                                             <?php foreach (@$listaAnioMatricula as $value) { ?>
                                                  <option value="<?= $value['anio'] ?>"><?= $value['anio'] ?></option>
                                             <?php } ?>
                                        </select>
                                   </div>
                                   <div class="col-sm-4">
                                        <label for="txtfecmat" class="form-label">Fecha matrícula:</label>
                                        <input type="date" class="form-control" id="txtfecmat" value="<?= date('Y-m-d') ?>" disabled>
                                   </div>
                                   <div class="col-sm-4">
                                        <label for="cmbCondicion" class="form-label">Condición:</label>
                                        <select class="form-select" id="cmbCondicion" required>
                                             <option value="">-Seleccione-</option>
                                             <?php foreach (@$listaCondicion as $value) { ?>
                                                  <option value="<?= $value['codigo'] ?>"><?= $value['descripcion'] ?></option>
                                             <?php } ?>
                                        </select>
                                   </div>
                              </div>
                              <div class="row mb-4">
                                   <div class="col-sm-4">
                                        <label for="cmbNivel" class="form-label">Nivel:</label>
                                        <select class="form-select" id="cmbNivel" required>
                                             <option value="">-Seleccione-</option>
                                             <?php foreach (@$listaNiveles as $value) { ?>
                                                  <option value="<?= $value['nivel'] ?>"><?= $value['descripcion'] ?></option>
                                             <?php } ?>
                                        </select>
                                   </div>
                                   <div class="col-sm-4">
                                        <label for="cmbGrado" class="form-label">Grado:</label>
                                        <select class="form-select" id="cmbGrado" required>
                                             <option value="">-Seleccione-</option>
                                        </select>
                                   </div>
                                   <div class="col-sm-4">
                                        <label for="cmbSeccion" class="form-label">Sección:</label>
                                        <select class="form-select" id="cmbSeccion" required>
                                             <option value="">-Seleccione-</option>
                                        </select>
                                   </div>
                              </div>
                              <h5 class="linea mb-3"><span>Familiar Responsable</span></h5>
                              <div class="row mb-3">
                                   <div class="col-12">
                                        <label for="txtfamnom" class="form-label">Apellidos y Nombres:</label>
                                        <input type="text" class="form-control" id="txtfamnom" disabled required>
                                   </div>
                              </div>
                              <div class="row mb-3">
                                   <div class="col-sm-6">
                                        <label for="txtfamdoc" class="form-label">Nro. documento:</label>
                                        <input type="text" class="form-control" id="txtfamdoc" required disabled>
                                   </div>
                                   <div class="col-sm-6">
                                        <label for="txtfamtipo" class="form-label">Parentesco:</label>
                                        <input type="text" class="form-control" id="txtfamtipo" disabled>
                                   </div>
                              </div>
                              <div class="row pt-3">
                                   <div class="col-sm">
                                        <button class="btn btn-primary w-100" id="btnRegistrar" disabled type="submit">
                                             <i class="fas fa-pen-square"></i>
                                             <span>&nbsp;Registrar Matrícula</span>
                                        </button>
                                   </div>
                              </div>
                         </form>
                    </div>
               </div>
          </div>
     </div>
</div>

<div class="modal fade" id="modalConfirmacion" tabindex="-1" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
               <!-- <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Notificación</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div> -->
               <div class="modal-body py-4">
                    <div class="alert alert-success mb-0" role="alert">
                         <h4 class="mb-0 fw-bold" style="font-size: 14px;">Matrícula registrada correctamente.</h4>
                    </div>
               </div>
               <div class="modal-footer align-items-center">
                    <button type="button" class="btn btn-danger w-50" id="btnFicha">
                         <i class="far fa-print"></i>
                         <span>&nbsp;Imprimir ficha de matrícula</span>
                    </button>
                    <button type="button" class="btn btn-primary flex-fill" data-bs-dismiss="modal">
                         <i class="fas fa-times-circle"></i>
                         <span>&nbsp;Cerrar&nbsp;</span>
                    </button>
               </div>
          </div>
     </div>
</div>

<form id="frmRepFicha" action="<?= MODULO_URL ?>/reporte/generate" target="_blank" method="post">
     <input type="hidden" name="codrep" value="0005">
     <input type="hidden" name="anio" id="rep_anio" value="">
     <input type="hidden" name="codalu" id="rep_codalu" value="">
     <input type="hidden" name="codmat" id="rep_codmat" value="">
</form>

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script>
     const jqxgridAlumnos = '#jqxgridAlumnos';
     const jqxgridHistorialMatricula = '#jqxgridHistorialMatricula';

     const frmMatricula = document.getElementById('frmMatricula');
     const modalConfirmacion = document.getElementById('modalConfirmacion');
     const listaGrados = JSON.parse(`<?= json_encode(@$listaGrados) ?>`);
     const listaSecciones = JSON.parse(`<?= json_encode(@$listaSecciones) ?>`);
     const listaFamiliarResponsable = JSON.parse(`<?= json_encode(@$listaFamiliarResponsable) ?>`);
     const listaHistorialMatricula = JSON.parse(`<?= json_encode(@$listaHistorialMatricula) ?>`);
     const modalConfirmacionEvent = new bootstrap.Modal(modalConfirmacion, {
          keyboard: false,
          backdrop: 'static'
     });

     modalConfirmacion.addEventListener('hidden.bs.modal', event => {
          $('#frmMatricula').trigger("reset");
     });

     const jqxgridAlumnosSource = {
          datatype: 'json',
          dataFields: [{
                    name: 'codalu',
                    type: 'string'
               },
               {
                    name: 'nomcomp',
                    type: 'string'
               },
               {
                    name: 'numdoc',
                    type: 'string'
               }
          ],
          localdata: `<?= json_encode(@$listaAlumnosNoMatriculados) ?>`
     };

     const jqxgridHistorialMatriculaSource = {
          datatype: 'json',
          localdata: `[]`
     };

     const jqxgridAlumnosAdapter = new $.jqx.dataAdapter(jqxgridAlumnosSource);

     const jqxgridHistorialMatriculaAdapter = new $.jqx.dataAdapter(jqxgridHistorialMatriculaSource);

     async function registrarMatricula() {
          let index = $(jqxgridAlumnos).jqxGrid('getselectedrowindex');
          if (index < 0) {
               showAlertSweet('Seleccione un Alumno para continuar.', 'warning');
               return;
          }
          let confirm = await showConfirmSweet('Confirme la matrícula para continuar', 'question');
          if (confirm) {
               let rowdata = $(jqxgridAlumnos).jqxGrid('getrowdata', index);
               $.ajax({
                    type: "POST",
                    url: "<?= MODULO_URL ?>/matricula/json/save-matricula",
                    data: {
                         anio: $('#cmbAnio').val(),
                         fecmat: $('#txtfecmat').val(),
                         nivel: $('#cmbNivel').val(),
                         grado: $('#cmbGrado').val(),
                         seccion: $('#cmbSeccion').val(),
                         condicion: $('#cmbCondicion').val(),
                         alumno: rowdata.codalu
                    },
                    beforeSend: function() {
                         $('#rep_anio').val($('#cmbAnio').val());
                         $('#rep_codalu').val(rowdata.codalu);
                         $(jqxgridAlumnos).jqxGrid({
                              disabled: true
                         });
                    },
                    success: function(response) {
                         if (response.codmat) {
                              $('#rep_codmat').val(response.codmat);
                              jqxgridAlumnosSource.localdata = response.listaAlumnosNoMatriculados;
                              $(jqxgridAlumnos).jqxGrid('updateBoundData', 'data');
                              frmMatricula.classList.remove('was-validated');
                              modalConfirmacionEvent.show();
                         }
                    },
                    error: function(jqXHr, status, error) {
                         let errorMsg = error;
                         if (jqXHr.responseJSON) {
                              errorMsg = jqXHr.responseJSON.message;
                         } else {
                              errorMsg = jqXHr.responseText;
                         }
                         showAlertSweet(errorMsg, 'error');
                    },
                    complete: function() {
                         $(jqxgridAlumnos).jqxGrid({
                              disabled: false
                         });
                    }
               });
          }

     }

     $(document).ready(function() {

          $(jqxgridAlumnos).jqxGrid({
               width: '100%',
               height: 735,
               source: jqxgridAlumnosAdapter,
               showfilterrow: true,
               filterable: true,
               columns: [{
                         text: "Código",
                         datafield: "codalu",
                         align: 'center',
                         cellsalign: 'center',
                         width: "20%",
                    },
                    {
                         text: "Apellidos y Nombres",
                         datafield: "nomcomp",
                         align: 'center',
                         width: "60%",
                    },
                    {
                         text: "DNI",
                         datafield: "numdoc",
                         align: 'center',
                         cellsalign: 'center',
                         width: "20%",
                    }
               ]
          });

          $(jqxgridHistorialMatricula).jqxGrid({
               width: '100%',
               height: 148,
               source: jqxgridHistorialMatriculaAdapter,
               showfilterrow: false,
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

          $(jqxgridAlumnos).on('rowselect', function(event) {
               const datarow = event.args.row;
               $('#txtalunom').val(datarow.nomcomp);
               $('#btnRegistrar').prop('disabled', false);
               if (listaFamiliarResponsable[datarow.codalu]) {
                    const dataresp = listaFamiliarResponsable[datarow.codalu];
                    jqxgridHistorialMatriculaSource.localdata = listaHistorialMatricula[datarow.codalu];
                    $(jqxgridHistorialMatricula).jqxGrid('updateBoundData', 'data');
                    $('#txtfamnom').val(dataresp.nomcomp);
                    $('#txtfamdoc').val(dataresp.numdoc);
                    $('#txtfamtipo').val(dataresp.parentesco);
               } else {
                    $('#txtfamnom').val('');
                    $('#txtfamdoc').val('');
                    $('#txtfamtipo').val('');
               }
          });

          $('#frmMatricula').submit(function(e) {
               e.preventDefault();
               if (!frmMatricula.checkValidity()) {
                    e.stopPropagation();
               } else {
                    registrarMatricula();
               }
               frmMatricula.classList.add('was-validated');
          });

          $('#btnFicha').on('click', function() {
               $('#frmRepFicha').submit();
          });

          $('#cmbNivel').change(function(e) {
               let list = '<option value="">-Seleccione-</option>';
               let nivel = $(this).val();
               let grados = listaGrados[nivel] ? listaGrados[nivel] : [];
               $.each(grados, function(index, value) {
                    list += `<option value="${value.grado}">${value.descripcion}</option>`;
               });
               $('#cmbGrado').html(list);
          });

          $('#cmbGrado').change(function(e) {
               let list = '<option value="">-Seleccione-</option>';
               let nivel = $('#cmbNivel').val();
               let grado = $(this).val();
               let secciones = listaSecciones[nivel][grado] ? listaSecciones[nivel][grado] : [];
               $.each(secciones, function(index, value) {
                    list += `<option value="${value.seccion}">${value.descripcion}</option>`;
               });
               $('#cmbSeccion').html(list);
          });

     });
</script>
<?= $this->endSection() ?>