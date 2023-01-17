<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
   <div class="row mt-1 mb-3">
      <div class="col-12">
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= MODULO_URL ?>"><?= MODULO_NAME ?></a></li>
               <li class="breadcrumb-item active" aria-current="page">Publicaciones</li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="row">
      <div class="col-lg-12">
         <div class="card card-main">
            <div class="card-body">
               <div class="row mb-3">
                  <label for="txtfecdesde" class="col-auto col-form-label my-1">Desde:</label>
                  <div class="col-sm-2 my-1">
                     <input type="date" class="form-control filter" value="<?= date('Y-m-d') ?>" id="txtfecdesde">
                  </div>
                  <label for="txtfechasta" class="col-auto col-form-label my-1">Hasta:</label>
                  <div class="col-sm-2 my-1">
                     <input type="date" class="form-control filter" value="<?= date('Y-m-d') ?>" id="txtfechasta">
                  </div>
                  <label for="cmbtipo" class="col-auto col-form-label my-1">Tipo:</label>
                  <div class="col-sm-2 my-1">
                     <select id="cmbtipo" class="form-select filter">
                        <option value="">-Todos-</option>
                        <?php foreach (@$listaTiposPub as $dato) { ?>
                           <option value="<?= $dato['codigo'] ?>"><?= $dato['descripcion'] ?></option>
                        <?php } ?>
                     </select>
                  </div>
                  <div class="col-sm my-1 text-end">
                     <button class="btn btn-primary" id="btnAddPub">
                        <i class="fas fa-plus-circle"></i>
                        <span>&nbsp;Nueva Publicación</span>
                     </button>
                  </div>
               </div>
               <div id="jqxgridPublicaciones"></div>
            </div>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script>
   const jqxgridPublicaciones = '#jqxgridPublicaciones';

   const jqxgridPublicacionesSource = {
      datatype: 'json',
      dataFields: [{
            name: 'fila',
            type: 'string'
         },
         {
            name: 'codpub',
            type: 'string'
         },
         {
            name: 'titulo',
            type: 'string'
         },
         {
            name: 'tipo',
            type: 'string'
         },
         {
            name: 'tipodes',
            type: 'string'
         },
         {
            name: 'fecpubini',
            type: 'string'
         },
         {
            name: 'fecpubfin',
            type: 'string'
         },
         {
            name: 'fecreg',
            type: 'string'
         },
         {
            name: 'usuario_nomb',
            type: 'string'
         },
         {
            name: 'es_visible',
            type: 'string'
         }
      ],
      localdata: `<?= json_encode(@$listaPublicaciones) ?>`
   };

   const jqxgridPublicacionesAdapter = new $.jqx.dataAdapter(jqxgridPublicacionesSource);

   function editarPublicacion(index) {
      let rowdata = $(jqxgridPublicaciones).jqxGrid('getrowdata', index);
      window.location.href = `<?= base_url(MODULO_URL) ?>/publicaciones/editor/${rowdata.codpub}`;
   }

   async function eliminarPublicacion(index) {
      let rowdata = $(jqxgridPublicaciones).jqxGrid('getrowdata', index);
      let confirm = await showConfirmSweet('Esta seguro de eliminar la publicación', 'question');
      if (confirm) {
         $.ajax({
            type: "get",
            url: "<?= MODULO_URL ?>/publicaciones/json/eliminar?codpub=" + rowdata.codpub,
            success: function(response) {
               if (response.listaPublicaciones) {
                  jqxgridPublicacionesSource.localdata = response.listaPublicaciones;
                  $(jqxgridPublicaciones).jqxGrid('updateBoundData', 'data');
               }
               showAlertSweet('Publicación eliminada correctamente', 'success', true);
            }
         });
      }
   }

   $(document).ready(function() {
      $(jqxgridPublicaciones).jqxGrid({
         width: '100%',
         height: 700,
         source: jqxgridPublicacionesAdapter,
         editable: false,
         columns: [{
               text: "Nro",
               datafield: "fila",
               align: 'center',
               width: "4%",
               cellsalign: 'center',
            },
            {
               text: "titulo",
               datafield: "titulo",
               align: 'center',
               width: "30%",
            },
            {
               text: "tipo",
               datafield: "tipodes",
               align: 'center',
               width: "10%",
               cellsalign: 'center',
            },
            {
               text: "Fecha publicación",
               datafield: "fecpubini",
               align: 'center',
               width: "16%",
               cellsalign: 'center',
            },
            {
               text: "Fecha Registro",
               datafield: "fecreg",
               align: 'center',
               width: "16%",
               cellsalign: 'center',
            },
            {
               text: "Registrado por",
               datafield: "usuario_nomb",
               align: 'center',
               width: "auto",
            },
            {
               text: '',
               width: '4%',
               cellsrenderer: function(row, column, value) {
                  return `<div class="jqx-center-align">
                     <button class="btn btn-link text-info" title="Editar" onclick="editarPublicacion(${row})">
                        <i class="fas fa-pencil-alt"></i>
                     </button>
                  </div>`;
               }
            },
            {
               text: '',
               width: '4%',
               cellsrenderer: function(row, column, value) {
                  return `<div class="jqx-center-align"><button class="btn btn-link text-danger" onclick="eliminarPublicacion(${row})" title="Eliminar"><i class="far fa-trash-alt"></i></button></div>`;
               }
            },
         ]
      });

      $('.filter').change(function(e) {
         e.preventDefault();
         $.ajax({
            type: "post",
            url: "<?= MODULO_URL ?>/publicaciones/json/listar",
            data: {
               fecdesde: $('#txtfecdesde').val(),
               fechasta: $('#txtfechasta').val(),
               tipopub: $('#cmbtipo').val()
            },
            beforeSend: function() {
               $(jqxgridPublicaciones).jqxGrid('showloadelement');
            },
            success: function(response) {
               if (response.listaPublicaciones) {
                  jqxgridPublicacionesSource.localdata = response.listaPublicaciones;
                  $(jqxgridPublicaciones).jqxGrid('updateBoundData', 'data');
               }
            }
         });
      });

      $('#btnAddPub').on('click', function() {
         window.location.href = '<?= base_url(MODULO_URL) ?>/publicaciones/editor';
      });
   });
</script>
<?= $this->endSection() ?>