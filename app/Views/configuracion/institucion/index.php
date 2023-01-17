<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
   <div class="row mt-1 mb-3">
      <div class="col-12">
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?= MODULO_URL ?>"><?= MODULO_NAME ?></a></li>
               <li class="breadcrumb-item active" aria-current="page">Institución Educativa</li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="row">
      <div class="col-md-7">
         <div class="card card-main">
            <div class="card-body">
               <div class="row mb-3">
                  <div class="col-12">
                     <button class="btn btn-primary" id="btnSave">
                        <i class="fas fa-save"></i>
                        <span>&nbsp;Guadar cambios</span>
                     </button>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12 my-2">
                     <label for="txtnombre" class="form-label">Nombre o razón social:</label>
                     <input type="text" class="form-control" value="<?= esc($datosInstitucion['nombre']) ?>" id="txtnombre">
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12 my-2">
                     <label for="txtdireccion" class="form-label">Dirección:</label>
                     <input type="text" class="form-control" value="<?= esc($datosInstitucion['direccion']) ?>" id="txtdireccion">
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12 my-2">
                     <label for="txtreferencia" class="form-label">Referencia:</label>
                     <input type="text" class="form-control" value="<?= esc($datosInstitucion['referencia']) ?>" id="txtreferencia">
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-4 my-2">
                     <label for="cmbdepartamento" class="form-label">Departamento:</label>
                     <select class="form-select" id="cmbdepartamento" name="departamento">
                        <option value="">-Seleccione-</option>
                        <?php foreach (@$listaDepartamentos as $dato) {
                           $deptselect = esc($datosInstitucion['dept']) == $dato['codigo'];
                        ?>
                           <option value="<?= $dato['codigo'] ?>" <?= $deptselect ? 'selected' : '' ?>><?= $dato['nombre'] ?></option>
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
                     <label for="txttelefono" class="form-label">Teléfono:</label>
                     <input type="text" class="form-control" value="<?= esc($datosInstitucion['telefono']) ?>" id="txttelefono">
                  </div>
                  <div class="col-md-6 my-2">
                     <label for="txtruc" class="form-label">Ruc:</label>
                     <input type="text" class="form-control" value="<?= esc($datosInstitucion['ruc']) ?>" id="txtruc">
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12 my-2">
                     <label for="txtcorreo" class="form-label">Correo Electrónico:</label>
                     <input type="email" class="form-control" value="<?= esc($datosInstitucion['correo']) ?>" id="txtcorreo">
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12 my-2">
                     <label for="txtweb" class="form-label">Sitio web:</label>
                     <input type="text" class="form-control" value="<?= esc($datosInstitucion['web']) ?>" id="txtweb">
                  </div>
               </div>
               <hr class="mb-2">
               <div class="row">
                  <div class="col-md-12 my-2">
                     <label for="txtdirector" class="form-label">Director(a):</label>
                     <input type="text" class="form-control" value="<?= esc($datosInstitucion['director']) ?>" id="txtdirector">
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12 my-2">
                     <label for="txtadministrador" class="form-label">Administrador(a):</label>
                     <input type="text" class="form-control" value="<?= esc($datosInstitucion['administrador']) ?>" id="txtadministrador">
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md">
         <div class="card card-main">
            <div class="card-body">
               <div class="text-center mt-3">
                  <img src="<?= base_url('uploads/local/escudo.png?t') . time() ?>" alt="Escudo de la institución" id="imagenLogo" height="200">
               </div>
               <div class="text-center mt-4">
                  <label for="inputLogoUp" class="btn btn-success">
                     <i class="fas fa-image"></i>
                     <span>&nbsp;Cargar imagen</span>
                  </label>
                  <input type="file" name="inputLogoUp" id="inputLogoUp" accept="image/png" style="display: none;">
               </div>
               <hr class="mt-4 text-muted">
               <div class="row justify-content-around">
                  <div class="col-sm-5">
                     <label for="colorInputPrim" class="form-label">Color Primario:</label>
                     <input type="color" class="form-control form-control-color w-100" id="colorInputPrim" value="<?= esc($datosInstitucion['colorpri']) ?>">
                  </div>
                  <div class="col-sm-5">
                     <label for="colorInputSecu" class="form-label">Color Secundario:</label>
                     <input type="color" class="form-control form-control-color w-100" id="colorInputSecu" value="<?= esc($datosInstitucion['colorsec']) ?>">
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script>
   const listaProvincias = JSON.parse(`<?= json_encode(esc($listaProvincias)) ?>`);
   const listaDistritos = JSON.parse(`<?= json_encode(esc($listaDistritos)) ?>`);
   const MAXIMO_TAMANIO_BYTES = 500000;

   function bloquearFormulario(valor = true) {
      $('#txtnombre').prop('disabled', valor);
      $('#txtdireccion').prop('disabled', valor);
      $('#txtreferencia').prop('disabled', valor);
      $('#cmbdepartamento').prop('disabled', valor);
      $('#cmbprovincia').prop('disabled', valor);
      $('#cmbdistrito').prop('disabled', valor);
      $('#txttelefono').prop('disabled', valor);
      $('#txtruc').prop('disabled', valor);
      $('#txtcorreo').prop('disabled', valor);
      $('#txtweb').prop('disabled', valor);
      $('#txtdirector').prop('disabled', valor);
      $('#txtadministrador').prop('disabled', valor);
      $('#colorInputPrim').prop('disabled', valor);
      $('#colorInputSecu').prop('disabled', valor);
      $('#inputLogoUp').prop('disabled', valor);
   }

   function guardarDatosInstitucion() {
      let data = new FormData();
      let cargoImagen = (document.getElementById('inputLogoUp').files.length > 0);
      data.append('codigo', `<?= esc($datosInstitucion['codigo']) ?>`);
      data.append('nombre', $('#txtnombre').val());
      data.append('direccion', $('#txtdireccion').val());
      data.append('referencia', $('#txtreferencia').val());
      data.append('departamento', $('#cmbdepartamento').val());
      data.append('provincia', $('#cmbprovincia').val());
      data.append('distrito', $('#cmbdistrito').val());
      data.append('telefono', $('#txttelefono').val());
      data.append('ruc', $('#txtruc').val());
      data.append('correo', $('#txtcorreo').val());
      data.append('web', $('#txtweb').val());
      data.append('director', $('#txtdirector').val());
      data.append('administrador', $('#txtadministrador').val());
      data.append('colorpri', $('#colorInputPrim').val());
      data.append('colorsec', $('#colorInputSecu').val());
      data.append('cargoImagen', cargoImagen ? 'S' : 'N');
      data.append('imagenLogo', cargoImagen ? document.getElementById('inputLogoUp').files[0] : null);
      $.ajax({
         type: "POST",
         url: "<?= MODULO_URL ?>/institucion/json/guardar",
         data: data,
         contentType: false,
         processData: false,
         beforeSend: function() {
            $('#btnSave').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span>&nbsp; Guardando ..</span>');
            $('#btnSave').prop('disabled', true);
            bloquearFormulario();
         },
         success: function(response) {
            showAlertSweet('Datos actualizados correctamente', 'success');
         },
         error: function(jqXHR, status, error) {
            let message = error;
            if (jqXHR.responseJSON) {
               message = jqXHR.responseJSON.message;
            }
            showAlertSweet(message, 'error');
         },
         complete: function() {
            $('#btnSave').prop('disabled', false);
            $('#btnSave').html('<i class="fas fa-save"></i><span>&nbsp; Guadar cambios</span>');
            bloquearFormulario(false);
         }
      });
   }

   $(document).ready(function() {

      $('#btnSave').click(function(e) {
         guardarDatosInstitucion();
      });

      $('#colorInputPrim').change(function(e) {
         let color = $(this).val();
         $("body").get(0).style.setProperty("--color-default-primary", color);
      });

      $('#colorInputSecu').change(function(e) {
         let color = $(this).val();
         $("body").get(0).style.setProperty("--color-default-secondary", color);
      });

      $('#inputLogoUp').change(function(e) {
         const imagenUp = e.target.files[0];
         if (imagenUp.type == 'image/png') {

            if (imagenUp.size > MAXIMO_TAMANIO_BYTES) {
               showAlertSweet('La imagen es demasiado grande, redusca el tamaño de la imagen', 'warning');
               $('#inputLogoUp').val('');
               return;
            }

            const reader = new FileReader();
            reader.readAsDataURL(imagenUp);
            reader.onload = (event) => {
               event.preventDefault();
               $('#imagenLogo').attr('src', event.target.result);
            };
         } else {
            showAlertSweet('Debe seleccionar una imagen de tipo PNG o JPG', 'error');
            $('#inputLogoUp').val('');
         }
      });

      $('#cmbdepartamento').change(function() {
         let html = '<option value="">-Seleccione-</option>';
         let dept = $(this).val();
         let select = `<?= esc($datosInstitucion['prov']) ?>`;
         if (dept !== '' && dept !== null) {
            const lista = listaProvincias[dept] ? listaProvincias[dept] : [];
            $.each(lista, function(index, value) {
               html += `<option value="${value.codigo}" ${select == value.codigo ? 'selected' : ''} >${value.nombre}</option>`;
            });
         }
         $('#cmbprovincia').html(html);
         $('#cmbprovincia').change();
      });

      $('#cmbprovincia').on('change', function() {
         let html = '<option value="">-Seleccione-</option>';
         let dept = $('#cmbdepartamento').val();
         let prov = $(this).val();
         let select = `<?= esc($datosInstitucion['ubgdir']) ?>`;
         if (prov !== '' && prov !== null) {
            const lista = listaDistritos[dept][prov] ? listaDistritos[dept][prov] : [];
            $.each(lista, function(index, value) {
               html += `<option value="${value.codigo}" ${select == value.codigo ? 'selected' : ''}>${value.nombre}</option>`;
            });
         }
         $('#cmbdistrito').html(html);
      });

      $('#cmbdepartamento').change();

   });
</script>
<?= $this->endSection() ?>