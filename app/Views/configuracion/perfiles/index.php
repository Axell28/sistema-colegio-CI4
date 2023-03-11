<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
   <div class="row mt-1 mb-3">
      <div class="col-12">
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?= MODULO_URL ?>"><?= MODULO_NAME ?></a></li>
               <li class="breadcrumb-item active" aria-current="page">Perfiles de Sistema</li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="row">
      <div class="col-lg-6">
         <div class="card card-main">
            <div class="card-body">
               <div class="d-flex gap-2 mb-3">
                  <button class="btn btn-primary" id="btnAdd">
                     <i class="fas fa-plus-circle"></i>
                     <span>&nbsp;Agregar perfil</span>
                  </button>
                  <button class="btn btn-danger" id="btnDelete">
                     <i class="fas fa-minus-circle"></i>
                     <span>&nbsp;Eliminar perfil</span>
                  </button>
               </div>
               <div id="jqxgridPerfiles"></div>
            </div>
         </div>
      </div>
      <div class="col-lg-6">
         <div class="card card-main">
            <div class="card-body">
               <div class="row mb-3">
                  <label class="col-auto col-form-label">Módulo:</label>
                  <div class="col-sm-6">
                     <select class="form-select" id="cmbmodulo">
                        <?php foreach (@$listaModulos as $mod) { ?>
                           <option value="<?= $mod['codmod'] ?>"><?= $mod['nombre'] ?></option>
                        <?php } ?>
                     </select>
                  </div>
                  <div class="col-sm-4">
                     <button class="btn btn-success" id="btnUpdateRoles">
                        <i class="far fa-sync"></i>
                        <span>&nbsp;Actualizar</span>
                     </button>
                  </div>
               </div>
               <div id="jqxgridTreeMenu"></div>
            </div>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="<?= base_url('js/jqwidgets/jqxcheckbox.js') ?>"></script>
<script src="<?= base_url('js/jqwidgets/jqxdatatable.js') ?>"></script>
<script src="<?= base_url('js/jqwidgets/jqxtreegrid.js') ?>"></script>
<script>
   const jqxgridPerfiles = '#jqxgridPerfiles';
   const jqxgridTreeMenu = "#jqxgridTreeMenu";

   const jqxgridPerfilesSource = {
      datatype: 'json',
      dataFields: [{
            name: 'perfil',
            type: 'string'
         },
         {
            name: 'nombre',
            type: 'string'
         },
         {
            name: 'activo',
            type: 'bool'
         }
      ],
      localdata: `<?= json_encode(@$listaPerfiles) ?>`
   };

   const jqxgridTreeMenuSource = {
      dataType: "json",
      dataFields: [{
            name: 'codmenu',
            type: 'string'
         },
         {
            name: 'parent',
            type: 'string'
         },
         {
            name: 'nombre',
            type: 'string'
         },
         {
            name: 'submenu',
            type: 'array'
         },
         {
            name: 'checked',
            type: 'bool'
         },
         {
            name: 'expanded',
            type: 'bool'
         }
      ],
      hierarchy: {
         root: 'submenu',
         keyDataField: {
            name: 'codmenu'
         },
         parentDataField: {
            name: 'parent'
         }
      },
      id: 'codmenu',
      localdata: '[]'
   };

   const jqxgridPerfilesAdapter = new $.jqx.dataAdapter(jqxgridPerfilesSource);

   const jqxgridTreeMenuAdapter = new $.jqx.dataAdapter(jqxgridTreeMenuSource);

   function validacionGrid(cell, value) {
      if (value.length == 0 || value == '') {
         return {
            result: false,
            message: "Campo requerido"
         };
      }
      return true;
   }

   function eliminarDuplicadosArray(lista) {
      let nuevoArray = lista.map(item => {
         return [JSON.stringify(item), item]
      });
      return [...new Map(nuevoArray).values()];
   }

   async function eliminarPerfil(index) {
      let respt = await showConfirmSweet('¿Esta seguro de eliminar el perfil?', 'question');
      if (respt) {
         const perfil = $(jqxgridPerfiles).jqxGrid('getcellvalue', index, 'perfil');
         $.ajax({
            type: "post",
            url: "<?= MODULO_URL ?>/perfiles/json/delete-perfil",
            data: {
               perfil
            },
            success: function(resp) {
               if (resp.listaPerfiles) {
                  jqxgridPerfilesSource.localdata = resp.listaPerfiles;
                  $(jqxgridPerfiles).jqxGrid('updateBoundData');
                  showAlertSweet('Perfil eliminado correctamente', 'success');
               }
            },
            error: function(jqXHR, status, error) {
               let errorMsg = error;
               if (jqXHR.responseJSON) {
                  erroMsg = jqXHR.responseJSON.message;
               }
               showAlertSweet(erroMsg, 'error');
            }
         });
      }
   }

   $(document).ready(function() {

      $(jqxgridPerfiles).jqxGrid({
         width: '100%',
         height: 530,
         source: jqxgridPerfilesAdapter,
         editable: true,
         editmode: 'dblclick',
         columns: [{
               text: "Código",
               datafield: "perfil",
               align: 'center',
               cellsalign: 'center',
               width: "20%",
               editable: false
            },
            {
               text: "Perfil",
               datafield: "nombre",
               validation: validacionGrid,
               align: 'center',
               width: "80%",
               editable: true
            }
         ]
      });

      $(jqxgridTreeMenu).jqxTreeGrid({
         width: '100%',
         height: 530,
         source: jqxgridTreeMenuAdapter,
         ready: function() {
            $(jqxgridTreeMenu).jqxTreeGrid('expandRow', '1');
         },
         editable: false,
         checkboxes: true,
         hierarchicalCheckboxes: true,
         columns: [{
               text: 'Menu de opciones',
               align: 'center',
               dataField: 'nombre',
               width: 'auto'
            },
            {
               text: 'Agregar',
               align: 'center',
               width: '14%'
            },
            {
               text: 'Editar',
               align: 'center',
               width: '14%'
            },
            {
               text: 'Eliminar',
               align: 'center',
               width: '14%'
            }
         ]
      });

      $(jqxgridPerfiles).on('rowselect', function(e) {
         const perfil = e.args.row.perfil;
         if (perfil == null || perfil == '') return;
         $.ajax({
            type: "POST",
            url: "<?= MODULO_URL ?>/perfiles/json/list-menu",
            data: {
               modulo: $('#cmbmodulo').val(),
               perfil: perfil
            },
            success: function(resp) {
               if (resp.listaMenus) {
                  jqxgridTreeMenuSource.localdata = resp.listaMenus;
                  $(jqxgridTreeMenu).jqxTreeGrid('updateBoundData');
               }
            },
            error: function(jqXHR, status, error) {
               let errorMsg = error;
               if (jqXHR.responseJSON) {
                  erroMsg = jqXHR.responseJSON.message;
               }
               showAlertSweet(erroMsg, 'error');
            }
         });
      });

      $(jqxgridPerfiles).on('cellvaluechanged', function(event) {
         let data = event.args;
         if (data.value == data.oldvalue) return;
         let params = {
            perfil: $(jqxgridPerfiles).jqxGrid('getcellvalue', data.rowindex, 'perfil')
         };
         params.nombre = data.value;
         $.ajax({
            type: "post",
            url: "<?= MODULO_URL ?>/perfiles/json/save-perfil",
            data: params,
            success: function(resp) {
               if (resp.listaPerfiles) {
                  jqxgridPerfilesSource.localdata = resp.listaPerfiles;
                  $(jqxgridPerfiles).jqxGrid('updateBoundData');
               }
            },
            error: function(jqXHR, status, error) {
               let errorMsg = error;
               if (jqXHR.responseJSON) {
                  erroMsg = jqXHR.responseJSON.message;
               }
               showAlertSweet(erroMsg, 'error');
            }
         });
      });

      $('#cmbmodulo').change(function(e) {
         e.preventDefault();
         const index = $(jqxgridPerfiles).jqxGrid('getselectedrowindex');
         const perfil = $(jqxgridPerfiles).jqxGrid('getrowdata', index).perfil;
         $.ajax({
            type: "POST",
            url: "<?= MODULO_URL ?>/perfiles/json/list-menu",
            data: {
               modulo: $('#cmbmodulo').val(),
               perfil: perfil
            },
            success: function(resp) {
               if (resp.listaMenus) {
                  jqxgridTreeMenuSource.localdata = resp.listaMenus;
                  $(jqxgridTreeMenu).jqxTreeGrid('updateBoundData');
               }
            },
            error: function(jqXHR) {
               if (jqXHR.responseJSON) {
                  let erroMsg = jqXHR.responseJSON.message;
                  showAlertSweet(erroMsg, 'error');
               }
            }
         });
      });

      $('#btnUpdateRoles').on('click', function() {
         const index = $(jqxgridPerfiles).jqxGrid('getselectedrowindex');
         const perfil = $(jqxgridPerfiles).jqxGrid('getrowdata', index).perfil;
         let listaMenu = [];
         let seleccionados = $(jqxgridTreeMenu).jqxTreeGrid('getCheckedRows');
         $.each(seleccionados, function(index, row) {
            listaMenu.push({
               codmenu: row.codmenu,
            });
            if (row.parent) {
               listaMenu.push({
                  codmenu: row.parent.codmenu
               });
            }
         });
         let nuevaLista = eliminarDuplicadosArray(listaMenu);
         $.ajax({
            type: "post",
            url: "<?= MODULO_URL ?>/perfiles/json/save-menu",
            data: {
               perfil: perfil,
               modulo: $('#cmbmodulo').val(),
               listaMenus: JSON.stringify(nuevaLista)
            },
            success: function(resp) {
               if (resp.message == 'OK') {
                  showAlertSweet('Cambios realizados correctamente', 'success', null, true);
               }
            },
            error: function(jqXHR, status, error) {
               let errorMsg = error;
               if (jqXHR.responseJSON) {
                  erroMsg = jqXHR.responseJSON.message;
               }
               showAlertSweet(erroMsg, 'error');
            }
         });
      });

      $('#btnAdd').click(function(e) {
         e.preventDefault();
         $(jqxgridPerfiles).jqxGrid('addrow', "first", {
            perfil: null,
            nombre: null
         });
      });

      $('#btnDelete').click(function(e) {
         e.preventDefault();
         let index = $(jqxgridPerfiles).jqxGrid('getselectedrowindex');
         if (index < 0) {
            showAlertSweet('Debe seleccionar el perfil a eliminar', 'warning');
            return;
         }
         eliminarPerfil(index);
      });

      $(jqxgridPerfiles).jqxGrid('selectrow', 0);

   });
</script>
<?= $this->endSection() ?>