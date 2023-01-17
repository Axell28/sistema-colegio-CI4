<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
   <div class="row mt-1 mb-3">
      <div class="col-12">
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?= MODULO_URL ?>"><?= MODULO_NAME ?></a></li>
               <li class="breadcrumb-item active" aria-current="page">Documentos</li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="row">
      <div class="col-md-4">
         <div class="card card-main">
            <div class="card-body">
               <div class="mb-3">
                  <button class="btn btn-primary" id="btnAdd">
                     <i class="far fa-clipboard-check"></i>
                     <span>&nbsp;Agregar Categoría</span>
                  </button>
               </div>
               <div id="jqxgridCategoria"></div>
            </div>
         </div>
      </div>
      <div class="col-md">
         <div class="card card-main">
            <div class="card-body">
               <div class="mb-3">
                  <button class="btn btn-success" id="btnAddDoc">
                     <i class="fas fa-cloud-upload-alt"></i>
                     <span>&nbsp;Agregar Documento</span>
                  </button>
               </div>
               <div id="jqxgridDocumentos"></div>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="modalDocumento" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered"></div>
</div>

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script>
   const jqxgridCategoria = "#jqxgridCategoria";
   const jqxgridDocumentos = "#jqxgridDocumentos";

   const modalDocumento = document.getElementById('modalDocumento');
   const modalDocumentoEvent = new bootstrap.Modal(document.getElementById('modalDocumento'), {
      keyboard: false,
      backdrop: 'static'
   });

   modalDocumento.addEventListener('hidden.bs.modal', event => {
      $('#modalDocumento .modal-dialog').html('');
   });

   const jqxgridCategoriaSource = {
      datatype: "json",
      dataFields: [{
            name: 'codigo',
            type: 'string'
         },
         {
            name: 'nombre',
            type: 'string'
         },
         {
            name: 'action',
            type: 'string'
         }
      ],
      localdata: `<?= json_encode(@$listaCategorias) ?>`
   };

   const jqxgridDocumentosSource = {
      datatype: "json",
      dataFields: [{
            name: 'codcat',
            type: 'string'
         },
         {
            name: 'coddoc',
            type: 'string'
         },
         {
            name: 'descripcion',
            type: 'string'
         },
         {
            name: 'estado',
            type: 'string'
         },
         {
            name: 'nombre',
            type: 'string'
         },
         {
            name: 'fecreg',
            type: 'string'
         },
         {
            name: 'extension',
            type: 'string'
         }
      ],
      localdata: `[]`
   };

   const jqxgridCategoriaAdapter = new $.jqx.dataAdapter(jqxgridCategoriaSource);
   const jqxgridDocumentosAdapter = new $.jqx.dataAdapter(jqxgridDocumentosSource);

   function openModalDocumento(action) {
      $.ajax({
         type: "POST",
         url: "<?= MODULO_URL ?>/registro-documentos/documento",
         data: {
            action: action,
         },
         beforeSend: function() {
            $('#modalDocumento .modal-dialog').html(getLoadingModal());
            modalDocumentoEvent.show();
         },
         success: function(response) {
            $('#modalDocumento .modal-dialog').html(response);
         },
         error: function(jqXHR, status, error) {
            let errorMsg = error;
            if (jqXHR.responseJSON) {
               errorMsg = jqXHR.responseJSON.message;
            }
            showAlertSweet(errorMsg, 'error');
         }
      });
   }

   async function eliminarCategoria(index) {
      const rowdata = $(jqxgridCategoria).jqxGrid('getrowdata', index);
      const confirm = await showConfirmSweet('¿Está seguro de eliminar la categoría?', 'question');
      if (confirm) {
         $.ajax({
            type: "POST",
            url: "<?= MODULO_URL ?>/registro-documentos/json/eliminar-ctg",
            data: {
               codcat: rowdata.codigo
            },
            beforeSend: function() {
               $(jqxgridCategoria).jqxGrid('showloadelement');
            },
            success: function(response) {
               if (response.listaCategorias) {
                  jqxgridCategoriaSource.localdata = response.listaCategorias;
                  $(jqxgridCategoria).jqxGrid('updateBoundData');
                  $(jqxgridCategoria).jqxGrid('selectrow', 0);
                  showAlertSweet('Categoría de documentos eliminada', 'success');
               }
            },
            error: function(jqXHR, status, error) {
               let errorMsg = error;
               if (jqXHR.responseJSON) {
                  errorMsg = jqXHR.responseJSON.message;
               }
               showAlertSweet(errorMsg, 'error');
               $(jqxgridCategoria).jqxGrid('hideloadelement');
            }
         });
      }
   }

   async function eliminarDocumento(index) {
      const rowdata = $(jqxgridDocumentos).jqxGrid('getrowdata', index);
      const confirm = await showConfirmSweet('¿Está seguro de eliminar este documento?', 'question');
      if (confirm) {
         $.ajax({
            type: "POST",
            url: "<?= MODULO_URL ?>/registro-documentos/json/eliminar-doc",
            data: {
               coddoc: rowdata.coddoc,
               codcat: rowdata.codcat,
               extension: rowdata.extension
            },
            beforeSend: function() {
               $(jqxgridDocumentos).jqxGrid('showloadelement');
            },
            success: function(response) {
               if (response.listaDocumentos) {
                  jqxgridDocumentosSource.localdata = response.listaDocumentos;
                  $(jqxgridDocumentos).jqxGrid('updateBoundData');
                  $(jqxgridDocumentos).jqxGrid('hideloadelement');
                  showAlertSweet('Documento eliminado correctamente', 'success');
               }
            },
            error: function(jqXHR, status, error) {
               let errorMsg = error;
               if (jqXHR.responseJSON) {
                  errorMsg = jqXHR.responseJSON.message;
               }
               showAlertSweet(errorMsg, 'error');
               $(jqxgridDocumentos).jqxGrid('hideloadelement');
            }
         });
      }
   }

   $(document).ready(function() {

      $(jqxgridCategoria).jqxGrid({
         width: '100%',
         height: 640,
         source: jqxgridCategoriaAdapter,
         editable: true,
         editmode: 'dblclick',
         columns: [{
               text: "Código",
               datafield: 'codigo',
               align: 'center',
               width: "20%",
               editable: false,
               cellsalign: 'center'
            },
            {
               text: "Categoría",
               datafield: 'nombre',
               align: 'center',
               width: "70%",
            },
            {
               text: '',
               width: '10%',
               cellsrenderer: function(row, column, value) {
                  const rowdata = $(jqxgridCategoria).jqxGrid('getrowdata', row);
                  if (rowdata.codigo !== null) {
                     return `<div class="jqx-center-align" style="padding-top: 1.5px;">
                        <button class="btn btn-link text-danger" onclick="eliminarCategoria(${row})" title="Eliminar">
                           <i class="far fa-trash-alt"></i>
                        </button>
                     </div>`;
                  }
                  return '';
               }
            }
         ]
      });

      $(jqxgridDocumentos).jqxGrid({
         width: '100%',
         height: 640,
         source: jqxgridDocumentosAdapter,
         editmode: 'dblclick',
         columns: [{
               text: "Código",
               align: 'center',
               datafield: 'coddoc',
               cellsalign: 'center',
               width: "10%",
            },
            {
               text: "Documento",
               datafield: 'nombre',
               align: 'center',
               width: "49%",
            },
            {
               text: "Fecha Reg.",
               datafield: 'fecreg',
               align: 'center',
               cellsalign: 'center',
               width: "22%",
            },
            {
               text: 'Archivo',
               width: '14%',
               align: 'center',
               cellsrenderer: function(row, column, value) {
                  const rowdata = $(jqxgridDocumentos).jqxGrid('getrowdata', row);
                  return `
                  <div class="jqx-center-align" style="padding-top: 1.5px;">
                     <a href="/uploads/documentos/D${rowdata.coddoc + rowdata.extension} " download="${rowdata.nombre}" class="btn btn-link text-info">Descargar</a>
                  </div>`;
               }
            },
            {
               text: '',
               width: '5%',
               cellsrenderer: function(row, column, value) {
                  return `
                  <div class="jqx-center-align" style="padding-top: 1.5px;">
                     <button class="btn btn-link text-danger" onclick="eliminarDocumento(${row})" title="Eliminar">
                        <i class="far fa-trash-alt"></i>
                     </button>
                  </div>`;
               }
            }
         ]
      });

      $(jqxgridCategoria).on('cellendedit', function(event) {
         const args = event.args;
         const rowdata = args.row;
         const action = rowdata.action == 'I' ? 'I' : 'E';
         if (args.oldvalue !== args.value) {
            $.ajax({
               type: "POST",
               url: "<?= MODULO_URL ?>/registro-documentos/json/guardar-ctg",
               data: {
                  action: action,
                  codigo: rowdata.codigo,
                  nombre: args.value
               },
               success: function(response) {
                  if (response.listaCategorias) {
                     jqxgridCategoriaSource.localdata = response.listaCategorias;
                     $(jqxgridCategoria).jqxGrid('updateBoundData');
                  }
               }
            });
         }
      });

      $(jqxgridCategoria).on('rowselect', function(e) {
         const data = e.args.row;
         $.ajax({
            type: "POST",
            url: "<?= MODULO_URL ?>/registro-documentos/json/listar-doc",
            data: {
               codcat: data.codigo
            },
            beforeSend: function() {
               $(jqxgridDocumentos).jqxGrid('showloadelement');
            },
            success: function(response) {
               if (response.listaDocumentos) {
                  jqxgridDocumentosSource.localdata = response.listaDocumentos;
                  $(jqxgridDocumentos).jqxGrid('updateBoundData');
               }
            }
         });
      });

      $('#btnAdd').click(function(e) {
         $(jqxgridCategoria).jqxGrid('addrow', 'first', {
            codigo: null,
            descripcion: null,
            action: 'I'
         });
      });

      $('#btnAddDoc').click(function(e) {
         openModalDocumento('I');
      });

      $(jqxgridCategoria).jqxGrid('selectrow', 0);

   });
</script>
<?= $this->endSection() ?>