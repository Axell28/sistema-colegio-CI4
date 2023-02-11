<?= $this->extend('template/layout') ?>
<?= $this->section('css') ?>
<style>
   .list-group-item {
      display: flex;
      align-items: center;
      gap: .95em;
      font-size: calc(var(--font-size) - 1px);
   }

   .box-adjuntos {
      height: 100%;
      max-height: 240px;
      overflow-y: auto;
   }

   .box-adjuntos::-webkit-scrollbar {
      width: 16px;
   }

   .box-adjuntos::-webkit-scrollbar-thumb {
      background-color: #7b7d7f;
      border: 5px solid #fff;
      border-radius: 10rem;
   }

   .box-adjuntos::-webkit-scrollbar-track {
      position: absolute;
      right: -3rem;
      top: -50rem;
      background: transparent;
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
               <li class="breadcrumb-item"><a href="<?= MODULO_URL ?>/publicaciones">Publicaciones</a></li>
               <li class="breadcrumb-item active" aria-current="page">Editor</li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="row">
      <div class="col-lg-8">
         <div class="card card-main">
            <div class="card-body">
               <div class="row mb-3">
                  <div class="col-md-12">
                     <label for="txttitulo" class="form-label">Título:</label>
                     <input type="text" class="form-control" value="<?= @$datosPublicacion['titulo'] ?>" id="txttitulo">
                  </div>
               </div>
               <div class="row">
                  <div class="col-12">
                     <textarea id="editor">
                        <?= @$datosPublicacion['cuerpo'] ?>
                     </textarea>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-lg-4">
         <div class="card card-main">
            <div class="card-body">
               <div class="row mb-3">
                  <div class="col-sm-12">
                     <button class="btn btn-success w-100" id="btnGuardar">
                        <i class="fas fa-save"></i>
                        <span>Guardar Publicación</span>
                     </button>
                  </div>
               </div>
               <div class="row mb-3">
                  <div class="col-sm-12">
                     <label for="cmbtipo" class="form-label">Tipo:</label>
                     <select class="form-select" id="cmbtipo">
                        <option value="">-Seleccione-</option>
                        <?php foreach (@$listaTiposPub as $dato) { ?>
                           <option value="<?= $dato['codigo'] ?>" <?= @$datosPublicacion['tipo'] == $dato['codigo'] ? 'selected' : '' ?>><?= $dato['descripcion'] ?></option>
                        <?php } ?>
                     </select>
                  </div>
               </div>
               <div class="row mb-3">
                  <div class="col-sm-12">
                     <label for="txtfecpubini" class="form-label">Fecha de publicación:</label>
                     <input type="datetime-local" class="form-control" value="<?= @$datosPublicacion['fecpubini'] ?>" id="txtfecpubini">
                  </div>
               </div>
               <div class="row mb-3">
                  <div class="col-sm-12">
                     <label class="form-label">Adjuntos:</label>
                     <div>
                        <label for="filesAdjunto" class="btn btn-outline-danger w-100">
                           <i class="fas fa-cloud-upload"></i>
                           <span>Cargar archivos</span>
                        </label>
                        <input type="file" id="filesAdjunto" multiple style="display: none;">
                     </div>
                     <div class="box-adjuntos mt-3">
                        <ul class="list-group" id="content-files">
                           <li class="list-group-item disabled">
                              Ningún archivo
                           </li>
                        </ul>
                     </div>
                  </div>
               </div>
               <div class="row mb-3">
                  <div class="col-sm-12">
                     <div id="jqxgridTreeDestino"></div>
                  </div>
               </div>
            </div>
            <input type="hidden" name="codigo" id="txtcodigo" value="<?= @$codpub ?>">
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="https://cdn.tiny.cloud/1/m923kxh2ht5z3iggdm8hghimyq6r44s6egfxl8xat90kag3t/tinymce/5/tinymce.min.js"></script>
<script src="<?= base_url('js/jqwidgets/jqxcheckbox.js') ?>"></script>
<script src="<?= base_url('js/jqwidgets/jqxdatatable.js') ?>"></script>
<script src="<?= base_url('js/jqwidgets/jqxtreegrid.js') ?>"></script>
<script>
   const ACTION = '<?= @$action ?>';
   const jqxgridTreeDestino = '#jqxgridTreeDestino';
   const listaAdjuntos = [];
   const listaDestinatarios = JSON.parse('<?= json_encode(@$listarDestinatarios) ?>');

   const jqxgridTreeDestinoSource = {
      datatype: 'json',
      dataFields: [{
            name: 'perfil',
            type: 'string'
         },
         {
            name: 'nombre',
            type: 'string'
         }
      ],
      id: 'perfil',
      localdata: '<?= json_encode(@$listaPerfiles) ?>'
   };

   const jqxgridTreeDestinoAdapter = new $.jqx.dataAdapter(jqxgridTreeDestinoSource);

   // run tinymce
   tinymce.init({
      selector: '#editor',
      language: "es",
      encoding: 'UTF-8',
      plugins: 'link media table image emoticons advlist lists code table template example paste table',
      toolbar: 'formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | numlist bullist checklist | forecolor backcolor | link media image pageembed emoticons | table | removeformat',
      menubar: false,
      content_style: '@import url("https://fonts.googleapis.com/css2?family=Lexend+Deca&display=swap"); body { font-family: "Lexend Deca", sans-serif; font-size: 14px; line-height: 1.6; }',
      height: '630',
      object_resizing: true,
      fix_list_elements: true,
      media_dimensions: true,
      forced_root_block: 'div',
      paste_as_text: true,
      paste_remove_styles: true,
      paste_remove_styles_if_webkit: true,
      default_link_target: "_blank",
   });

   function validarDatos() {
      if ($('#txttitulo').val() == '') {
         showAlertSweet('Debes ingresar un titulo para la publicación', 'warning');
         return false;
      }
      if ($('#cmbtipo').val() == '') {
         showAlertSweet('Debes seleccionar el tipo de publicación', 'warning');
         return false;
      }
      if ($('#txtfecpubini').val() == '') {
         showAlertSweet('Debes seleccionar la fecha de publicación', 'warning');
         return false;
      }
      return true;
   }

   function getDestinatarios() {
      let datosIndex = $(jqxgridTreeDestino).jqxGrid('getselectedrowindexes');
      if (datosIndex.length < 0) return false;
      let list = [];
      for (let i = 0; i < datosIndex.length; i++) {
         let perfil = $(jqxgridTreeDestino).jqxGrid('getcellvalue', i, 'perfil');
         list.push(perfil);
      }
      return list;
   }

   function setDestinatarios() {
      const grillaDest = $(jqxgridTreeDestino).jqxGrid('getrows');
      $.each(grillaDest, function(index, value) {
         let destinatario = listaDestinatarios.find(item => item.perfil == value.perfil);
         if(destinatario) {
            $(jqxgridTreeDestino).jqxGrid('selectrow', index);
         }
      });
   }

   function guardarPublicacion() {
      if (!validarDatos()) return;
      let destinarios = getDestinatarios();
      if (destinarios == false) {
         showAlertSweet('Debe seleccionar al menos un perfil para continuar.', 'warning');
         return;
      }
      const fecpubini = $('#txtfecpubini').val();
      const fecpubfin = $('#txtfecpubfin').val();
      const formdata = new FormData();
      const filesUp = document.getElementById('filesAdjunto').files;
      formdata.append('codpub', $('#txtcodigo').val());
      formdata.append('titulo', $('#txttitulo').val());
      formdata.append('tipo', $('#cmbtipo').val());
      formdata.append('fecpubini', fecpubini !== '' ? getDateTimeFormat(new Date(fecpubini)) : null);
      formdata.append('cuerpo', tinyMCE.get('editor').getContent());
      formdata.append('destinatarios', null);
      formdata.append('action', ACTION);
      formdata.append('cargoArchivo', filesUp.length > 0 ? 'S' : 'N');
      formdata.append('destinatarios', JSON.stringify(destinarios));
      $.each(filesUp, function(index, value) {
         formdata.append('adjuntos[]', value);
      });
      $.ajax({
         type: "post",
         url: "<?= MODULO_URL ?>/publicaciones/json/guardar",
         data: formdata,
         contentType: false,
         processData: false,
         beforeSend: function() {
            $('#btnGuardar').prop('disabled', true);
            $('#btnGuardar').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span>&nbsp;Espere un momento</span>`);
         },
         success: function(response) {
            Swal.fire({
               icon: 'success',
               text: 'Publicación ' + (ACTION == 'I' ? 'agregada' : 'modificada') + ' correctamente',
               allowOutsideClick: false
            }).then((result) => {
               if (result.isConfirmed) {
                  location.href = `<?= base_url(MODULO_URL) ?>/publicaciones`;
               }
            });;
         },
         error: function(jqXHr, status) {
            let message = 'Se ha producido un error';
            if (jqXHr.responseJSON) {
               message = jqXHr.responseJSON.message;
            }
            showAlertSweet(message, 'error');
         },
         complete: function() {
            $('#btnGuardar').prop('disabled', false);
            $('#btnGuardar').html(`<i class="fas fa-save"></i><span>Guardar Publicación</span>`);
         }
      });
   }

   $(document).ready(function() {

      $(jqxgridTreeDestino).jqxGrid({
         width: '100%',
         height: 250,
         source: jqxgridTreeDestinoAdapter,
         editable: false,
         selectionmode: 'checkbox',
         columns: [{
            text: "Destinatarios",
            dataField: 'nombre',
            align: 'center',
            width: "90%",
            editable: false
         }]
      });

      $('#filesAdjunto').change(function(e) {
         e.preventDefault();
         let html = '';
         const filesUp = e.target.files;
         $.each(filesUp, function(index, value) {
            html += `
            <li class="list-group-item">
               <div><i class="fad fa-file-check text-primary" style="font-size: 1.6rem;"></i></div>
               <div class="text-truncate">
                  <div class="text-truncate">${value.name}</div>
                  <div class="text-muted">${convertirBytesToSize(value.size)}</div>
               </div>
            </li>`;
         });
         $('#content-files').html(html);
      });

      $('#btnGuardar').on('click', function() {
         guardarPublicacion();
      });

      setDestinatarios();

   });
</script>
<?= $this->endSection() ?>