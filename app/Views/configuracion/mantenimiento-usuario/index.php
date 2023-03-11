<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
   <div class="row mt-1 mb-3">
      <div class="col-12">
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?= MODULO_URL ?>"><?= MODULO_NAME ?></a></li>
               <li class="breadcrumb-item active" aria-current="page">Mantenimiento Usuario</li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="row">
      <div class="col-lg-12">
         <div class="card card-main">
            <div class="card-body">
               <div class="row mb-3">
                  <label for="cmbperfilF" class="col-auto col-form-label my-2">Perfil:</label>
                  <div class="col-sm-4 my-2">
                     <select class="form-select filter" id="cmbperfilF">
                        <option value="">-Todos-</option>
                        <?php foreach (@$listaPerfiles as $value) { ?>
                           <option value="<?= $value['perfil'] ?>"><?= $value['nombre'] ?></option>
                        <?php } ?>
                     </select>
                  </div>
                  <label for="cmbestadoF" class="col-auto col-form-label my-2">Estado:</label>
                  <div class="col-sm-2 my-2">
                     <select class="form-select filter" id="cmbestadoF">
                        <option value="">-Todos-</option>
                        <option value="A" selected>Activo</option>
                        <option value="I">Inactivo</option>
                     </select>
                  </div>
                  <div class="col-sm text-end my-2">
                     <button class="btn btn-primary me-2" id="btnAdd" type="button">
                        <i class="fas fa-plus-circle"></i>
                        <span>&nbsp;Agregar usuario</span>
                     </button>
                     <button id="btnReporte" class="btn btn-danger" type="button">
                        <i class="fas fa-file-pdf"></i>
                        <span>&nbsp;Exportar pdf</span>
                     </button>
                  </div>
               </div>
               <div id="jqxgridUsuarios"></div>
               <div class="pt-3">
                  <p class="mb-0" id="totalReg">Total de Usuarios : &nbsp; 0</p>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<form id="frmReporte" action="<?= MODULO_URL ?>/reporte/generate" target="_blank" method="POST">
   <input type="hidden" name="codrep" value="0001">
   <input type="hidden" name="estado" id="rep_estado" value="">
   <input type="hidden" name="perfil" id="rep_perfil" value="">
</form>

<div class="modal fade" id="modalUsuario" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"></div>
</div>

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="<?= base_url('js/jqwidgets/jqxcheckbox.js') ?>"></script>
<script>
   const jqxgridUsuarios = "#jqxgridUsuarios";

   const modalUsuario = document.getElementById('modalUsuario');
   const modalUsuarioEvent = new bootstrap.Modal(modalUsuario, {
      keyboard: false,
      backdrop: 'static'
   });

   modalUsuario.addEventListener('hidden.bs.modal', event => {
      $('#modalUsuario .modal-dialog').html('');
   });

   const jqxgridUsuariosSource = {
      datatype: 'json',
      dataFields: [{
            name: 'usuario',
            type: 'string'
         },
         {
            name: 'perfil',
            type: 'string'
         },
         {
            name: 'nombre',
            type: 'string'
         },
         {
            name: 'codigo',
            type: 'string'
         },
         {
            name: 'entidad',
            type: 'string'
         },
         {
            name: 'estado',
            type: 'string'
         },
         {
            name: 'fecreg',
            type: 'string'
         },
         {
            name: 'fecmod',
            type: 'string'
         },
         {
            name: 'ultcon',
            type: 'string'
         },
         {
            name: 'perfil_nomb',
            type: 'string'
         },
         {
            name: 'estado_des',
            type: 'string'
         },
         {
            name: 'estado_bool',
            type: 'bool'
         }
      ],
      localdata: `<?= json_encode(@$listaUsuarios) ?>`
   };

   const jqxgridUsuariosAdapter = new $.jqx.dataAdapter(jqxgridUsuariosSource);

   function validacionGrid(cell, value) {
      if (value.length == 0 || value == '') {
         return {
            result: false,
            message: "Campo requerido"
         };
      }
      return true;
   }

   function editarUsuario(index) {
      let rowdata = $(jqxgridUsuarios).jqxGrid('getrowdata', index);
      $.ajax({
         type: "POST",
         url: "<?= MODULO_URL ?>/mantenimiento-usuario/usuario",
         data: {
            action: 'E',
            usuario: rowdata.usuario,
            nombre: rowdata.nombre,
            perfil: rowdata.perfil,
            estado: rowdata.estado
         },
         beforeSend: function() {
            $('#modalUsuario .modal-dialog').html(getLoadingModal());
            modalUsuarioEvent.show();
         },
         success: function(response) {
            $('#modalUsuario .modal-dialog').html(response);
         },
         error: function(jqXHr, status, error) {
            if (jqXHr.statusText) {
               showAlertSweet(jqXHr.statusText, 'error');
            }
         }
      });
   }

   function totalRegistros() {
      const info = $(jqxgridUsuarios).jqxGrid('getdatainformation');
      $('#totalReg').html(`Total de Usuarios : &nbsp; ` + (info.rowscount > 10 ? info.rowscount : ('0' + info.rowscount)));
   }

   $(document).ready(function() {

      $(jqxgridUsuarios).jqxGrid({
         width: '100%',
         height: 620,
         source: jqxgridUsuariosAdapter,
         showfilterrow: true,
         filterable: true,
         editable: true,
         columns: [{
               text: "Usuario",
               datafield: "usuario",
               align: 'center',
               cellsalign: 'center',
               width: "14%",
               editable: false
            },
            {
               text: "Nombre",
               datafield: "nombre",
               align: 'center',
               width: "23%",
               editable: false,
            },
            {
               text: "Perfil",
               datafield: "perfil_nomb",
               align: 'center',
               width: "23%",
               editable: false,
               filterable: false,
            },
            {
               text: "Fec. registro",
               datafield: "fecreg",
               cellsalign: 'center',
               align: 'center',
               width: "16%",
               editable: false,
               filterable: false,
            },
            {
               text: "Ultm. conexión",
               datafield: "ultcon",
               cellsalign: 'center',
               align: 'center',
               width: "14%",
               editable: false,
               filterable: false,
            },
            {
               text: "Activo",
               datafield: 'estado_bool',
               editable: false,
               align: 'center',
               width: "6%",
               filterable: false,
               editable: true,
               columntype: 'checkbox'
            },
            {
               text: '',
               width: '4%',
               filterable: false,
               cellsrenderer: function(row, column, value) {
                  return `<div class="jqx-center-align"><button class="btn btn-link text-success" onclick="editarUsuario(${row})" title="Ficha matrícula"><i class="fas fa-user-edit"></i></button></div>`;
               }
            },
         ]
      });

      $(jqxgridUsuarios).on('cellendedit', function(event) {
         const args = event.args;
         const rowdata = args.row;
         if (args.oldvalue !== args.value) {
            if (args.datafield == 'estado_bool') {
               $.ajax({
                  type: "POST",
                  url: "<?= MODULO_URL ?>/mantenimiento-usuario/json/update-estado",
                  data: {
                     usuario: rowdata.usuario,
                     estado: (args.value ? 'A' : 'I')
                  },
                  success: function(response) {}
               });
            }
         }
      });

      $('select.filter').change(function(e) {
         $.ajax({
            type: "POST",
            url: "<?= MODULO_URL ?>/mantenimiento-usuario/json/listar",
            data: {
               perfilF: $('#cmbperfilF').val(),
               estadoF: $('#cmbestadoF').val()
            },
            success: function(response) {
               if (response.listaUsuarios) {
                  jqxgridUsuariosSource.localdata = response.listaUsuarios;
                  $(jqxgridUsuarios).jqxGrid('updateBoundData');
                  totalRegistros();
               }
            }
         });
      });

      $('#btnReporte').click(function(e) {
         e.preventDefault();
         $('#rep_estado').val($('#cmbestadoF').val());
         $('#rep_perfil').val($('#cmbperfilF').val());
         $('#frmReporte').submit();
      });

      $('#btnAdd').click(function(e) {
         $.ajax({
            type: "POST",
            url: "<?= MODULO_URL ?>/mantenimiento-usuario/usuario",
            data: {
               action: 'I'
            },
            beforeSend: function() {
               $('#modalUsuario .modal-dialog').html(getLoadingModal());
               modalUsuarioEvent.show();
            },
            success: function(response) {
               $('#modalUsuario .modal-dialog').html(response);
            },
            error: function(jqXHr, status, error) {
               if (jqXHr.statusText) {
                  showAlertSweet(jqXHr.statusText, 'error');
               }
            }
         });
      });

      totalRegistros();

   });
</script>
<?= $this->endSection() ?>