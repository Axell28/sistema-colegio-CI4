<?= $this->extend('template/layout') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
   <div class="row mt-1 mb-3">
      <div class="col-12">
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?= MODULO_URL ?>"><?= MODULO_NAME ?></a></li>
               <li class="breadcrumb-item active" aria-current="page">Nivel Académico</li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="row">
      <div class="col-lg-4">
         <div class="card card-main">
            <div class="card-body">
               <div class="d-flex gap-2 mb-3 pt-1">
                  <button class="btn btn-outline-primary" detcod="N" title="Agregar">
                     <i class="fas fa-plus-circle"></i>
                  </button>
                  <button class="btn btn-outline-danger" detcod="N" title="Eliminar">
                     <i class="fas fa-minus-circle"></i>
                  </button>
               </div>
               <div id="jqxgridNivel"></div>
            </div>
         </div>
      </div>
      <div class="col-lg-4">
         <div class="card card-main">
            <div class="card-body">
               <div class="d-flex gap-2 mb-3 pt-1">
                  <button class="btn btn-outline-primary" detcod="G" title="Agregar">
                     <i class="fas fa-plus-circle"></i>
                  </button>
                  <button class="btn btn-outline-danger" detcod="G" title="Eliminar">
                     <i class="fas fa-minus-circle"></i>
                  </button>
               </div>
               <div id="jqxgridGrado"></div>
            </div>
         </div>
      </div>
      <div class="col-lg-4">
         <div class="card card-main">
            <div class="card-body">
               <div class="d-flex gap-2 mb-3 pt-1">
                  <button class="btn btn-outline-primary" detcod="S" title="Agregar">
                     <i class="fas fa-plus-circle"></i>
                  </button>
                  <button class="btn btn-outline-danger" detcod="S" title="Eliminar">
                     <i class="fas fa-minus-circle"></i>
                  </button>
               </div>
               <div id="jqxgridSeccion"></div>
            </div>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="<?= base_url('js/jqwidgets/jqxcheckbox.js') ?>"></script>
<script>
   const jqxgridNivel = '#jqxgridNivel';
   const jqxgridGrado = '#jqxgridGrado';
   const jqxgridSeccion = '#jqxgridSeccion';

   const jqxgridNivelSource = {
      datatype: 'json',
      dataFields: [{
            name: 'nivel',
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
            name: 'activo',
            type: 'bool'
         },
         {
            name: 'action',
            type: 'string'
         }
      ],
      localdata: `<?= json_encode(@$listaNiveles) ?>`
   };

   const jqxgridGradoSource = {
      datatype: 'json',
      dataFields: [{
            name: 'nivel',
            type: 'string'
         },
         {
            name: 'grado',
            type: 'string'
         },
         {
            name: 'descripcion',
            type: 'string'
         }
      ],
      localdata: '[]'
   };

   const jqxgridSeccionSource = {
      datatype: 'json',
      dataFields: [{
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
            name: 'descripcion',
            type: 'string'
         }
      ],
      localdata: '[]'
   };

   const jqxgridNivelAdapter = new $.jqx.dataAdapter(jqxgridNivelSource);

   const jqxgridGradoAdapter = new $.jqx.dataAdapter(jqxgridGradoSource);

   const jqxgridSeccionAdapter = new $.jqx.dataAdapter(jqxgridSeccionSource);

   function validacionGrid01(cell, value) {
      if (value.length == 0 || value == '') {
         return {
            result: false,
            message: "Campo requerido"
         };
      }
      if (value.length > 1) {
         return {
            result: false,
            message: "Maximo solo un caracter"
         };
      }
      return true;
   }

   function validacionGrid02(cell, value) {
      if (value.length == 0 || value == '') {
         return {
            result: false,
            message: "Campo requerido"
         };
      }
      return true;
   }

   function agregarRowGrid(grid) {
      let nivelIndex = $(jqxgridNivel).jqxGrid('getselectedrowindex');
      let gradoIndex = $(jqxgridGrado).jqxGrid('getselectedrowindex');
      let dataNivel = $(jqxgridNivel).jqxGrid('getcellvalue', nivelIndex, 'nivel');
      let dataGrado = $(jqxgridGrado).jqxGrid('getcellvalue', gradoIndex, 'grado');
      if (grid == 'N') {
         $(jqxgridNivel).jqxGrid('addrow', 'first', {
            nivel: null,
            descripcion: null,
            estado: null,
            action: 'I'
         });
      } else if (grid == 'G') {
         if (nivelIndex == -1) {
            showAlertSweet('Debe seleccionar el nivel', 'warning');
            return;
         }
         $(jqxgridGrado).jqxGrid('addrow', 'first', {
            nivel: dataNivel,
            grado: null,
            descripcion: null,
            action: 'I'
         });
      } else if (grid == 'S') {
         if (gradoIndex == -1) {
            showAlertSweet('Debe seleccionar el grado', 'warning');
            return;
         }
         $(jqxgridSeccion).jqxGrid('addrow', 'first', {
            nivel: dataNivel,
            grado: dataGrado,
            seccion: null,
            descripcion: null,
            action: 'I'
         });
      }
   }

   async function eliminarRowGrid(grid) {
      let nivelIndex = $(jqxgridNivel).jqxGrid('getselectedrowindex');
      let gradoIndex = $(jqxgridGrado).jqxGrid('getselectedrowindex');
      let seccionIndex = $(jqxgridSeccion).jqxGrid('getselectedrowindex');
      if (grid == 'N' && nivelIndex == -1) {
         showAlertSweet('Debe seleccionar el nivel a eliminar', 'warning');
         return;
      }
      if (grid == 'G' && gradoIndex == -1) {
         showAlertSweet('Debe seleccionar el grado a eliminar', 'warning');
         return;
      }
      if (grid == 'S' && seccionIndex == -1) {
         showAlertSweet('Debe seleccionar la sección a eliminar', 'warning');
         return;
      }

      // confirmación
      let confirm = await showConfirmSweet('Esta seguro de eliminar el registro', 'info');
      if (confirm) {
         let dataNivel = $(jqxgridNivel).jqxGrid('getcellvalue', nivelIndex, 'nivel');
         let dataGrado = $(jqxgridGrado).jqxGrid('getcellvalue', gradoIndex, 'grado');
         let dataSeccion = $(jqxgridSeccion).jqxGrid('getcellvalue', seccionIndex, 'seccion');
         $.ajax({
            type: "post",
            url: "<?= MODULO_URL ?>/nivel-academico/json/delete",
            data: {
               tabla: grid,
               nivel: dataNivel,
               grado: dataGrado,
               seccion: dataSeccion
            },
            success: function(response) {
               if (grid == 'N') {
                  jqxgridNivelSource.localdata = response.listaNiveles;
                  jqxgridGradoSource.localdata = '[]';
                  jqxgridSeccionSource.localdata = '[]';
                  $(jqxgridNivel).jqxGrid('updateBoundData');
                  $(jqxgridGrado).jqxGrid('updateBoundData');
                  $(jqxgridSeccion).jqxGrid('updateBoundData');
               } else if (grid == 'G') {
                  jqxgridGradoSource.localdata = response.listaGrados;
                  jqxgridSeccionSource.localdata = '[]';
                  $(jqxgridGrado).jqxGrid('updateBoundData');
                  $(jqxgridSeccion).jqxGrid('updateBoundData');
               } else if (grid == 'S') {
                  jqxgridSeccionSource.localdata = response.listaSecciones;
                  $(jqxgridSeccion).jqxGrid('updateBoundData');
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
            }
         });
      }
   }

   $(document).ready(function() {

      $(jqxgridNivel).jqxGrid({
         width: '100%',
         height: 360,
         source: jqxgridNivelAdapter,
         editable: true,
         editmode: 'dblclick',
         columns: [{
               text: "Nivel",
               datafield: "nivel",
               align: 'center',
               cellsalign: 'center',
               width: "18%",
               validation: validacionGrid01,
               cellbeginedit: function(row, datafield) {
                  let action = $(jqxgridNivel).jqxGrid('getcellvalue', row, 'action');
                  if (action !== 'I') return false;
               }
            },
            {
               text: 'Descripcion',
               datafield: 'descripcion',
               align: 'center',
               width: '62%',
               validation: validacionGrid02
            },
            {
               text: 'Activo',
               datafield: 'activo',
               align: 'center',
               width: '20%',
               editable: true,
               columntype: 'checkbox'
            }
         ]
      });

      $(jqxgridGrado).jqxGrid({
         width: '100%',
         height: 360,
         source: jqxgridGradoAdapter,
         editable: true,
         editmode: 'dblclick',
         columns: [{
               text: "Grado",
               datafield: "grado",
               align: 'center',
               cellsalign: 'center',
               width: "20%",
               validation: validacionGrid01,
               cellbeginedit: function(row, datafield) {
                  let action = $(jqxgridGrado).jqxGrid('getcellvalue', row, 'action');
                  if (action !== 'I') return false;
               }
            },
            {
               text: 'Descripcion',
               datafield: 'descripcion',
               align: 'center',
               width: '80%',
               validation: validacionGrid02
            }
         ]
      });

      $(jqxgridSeccion).jqxGrid({
         width: '100%',
         height: 360,
         source: jqxgridSeccionAdapter,
         editable: true,
         editmode: 'dblclick',
         columns: [{
               text: "Sección",
               datafield: "seccion",
               align: 'center',
               cellsalign: 'center',
               width: "20%",
               validation: validacionGrid01
            },
            {
               text: 'Descripcion',
               datafield: 'descripcion',
               align: 'center',
               width: '80%',
               validation: validacionGrid02
            }
         ]
      });

      $(jqxgridNivel).on('rowselect', function(e) {
         const data = e.args.row;
         if (data.nivel == null) return;
         $.ajax({
            type: "POST",
            url: "<?= MODULO_URL ?>/nivel-academico/json/listar",
            data: {
               tabla: 'G',
               nivel: data.nivel
            },
            beforeSend: function() {
               $(jqxgridGrado).jqxGrid('showloadelement');
            },
            success: function(response) {
               if (response.listaGrados) {
                  jqxgridGradoSource.localdata = response.listaGrados;
                  jqxgridSeccionSource.localdata = '[]';
                  $(jqxgridGrado).jqxGrid('hideloadelement');
                  $(jqxgridGrado).jqxGrid('clearselection');
                  $(jqxgridGrado).jqxGrid('updateBoundData');
                  $(jqxgridSeccion).jqxGrid('clearselection');
                  $(jqxgridSeccion).jqxGrid('updateBoundData');
               }
            }
         });
      });

      $(jqxgridGrado).on('rowselect', function(e) {
         const data = e.args.row;
         if (data.grado == null) return;
         $.ajax({
            type: "POST",
            url: "<?= MODULO_URL ?>/nivel-academico/json/listar",
            data: {
               tabla: 'S',
               nivel: data.nivel,
               grado: data.grado
            },
            beforeSend: function() {
               $(jqxgridSeccion).jqxGrid('showloadelement');
            },
            success: function(response) {
               if (response.listaSecciones) {
                  jqxgridSeccionSource.localdata = response.listaSecciones;
                  $(jqxgridSeccion).jqxGrid('hideloadelement');
                  $(jqxgridSeccion).jqxGrid('clearselection');
                  $(jqxgridSeccion).jqxGrid('updateBoundData');
               }
            }
         });
      });

      $(jqxgridNivel).on('cellendedit', function(event) {
         const args = event.args;
         const rowdata = args.row;
         const action = rowdata.action == 'I' ? 'I' : 'E';
         if (args.oldvalue !== args.value) {
            if (action == 'E') {
               $.ajax({
                  type: "POST",
                  url: "<?= MODULO_URL ?>/nivel-academico/json/update",
                  data: {
                     tabla: 'N',
                     nivel: rowdata.nivel,
                     descripcion: args.datafield == 'descripcion' ? args.value : rowdata.descripcion,
                     estado: args.datafield == 'activo' ? (args.value ? 'A' : 'I') : (rowdata.activo ? 'A' : 'I')
                  },
                  success: function(response) {
                     if (response.listaNiveles) {
                        jqxgridNivelSource.localdata = response.listaNiveles;
                        $(jqxgridNivel).jqxGrid('updateBoundData');
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
                  }
               });
            } else {
               if (args.datafield == 'nivel') {
                  $.ajax({
                     type: "post",
                     url: "<?= MODULO_URL ?>/nivel-academico/json/insert",
                     data: {
                        tabla: 'N',
                        nivel: args.value,
                        descripcion: rowdata.descripcion
                     },
                     success: function(response) {
                        if (response.listaNiveles) {
                           jqxgridNivelSource.localdata = response.listaNiveles;
                           jqxgridGradoSource.localdata = '[]';
                           jqxgridSeccionSource.localdata = '[]';
                           $(jqxgridNivel).jqxGrid('updateBoundData');
                           $(jqxgridGrado).jqxGrid('updateBoundData');
                           $(jqxgridSeccion).jqxGrid('updateBoundData');
                        }
                     },
                     error: function(jqXHr, status, error) {
                        let errorMsg = error
                        if (jqXHr.responseJSON) {
                           errorMsg = jqXHr.responseJSON.error;
                        } else {
                           errorMsg = jqXHr.responseText;
                        }
                        showAlertSweet(errorMsg, 'error');
                     }
                  });
               }
            }
         }
      });

      $(jqxgridGrado).on('cellendedit', function(event) {
         const args = event.args;
         const rowdata = args.row;
         const action = rowdata.action == 'I' ? 'I' : 'E';
         if (args.oldvalue !== args.value) {
            if (action == 'E') {
               $.ajax({
                  type: "post",
                  url: "<?= MODULO_URL ?>/nivel-academico/json/update",
                  data: {
                     tabla: 'G',
                     nivel: rowdata.nivel,
                     grado: rowdata.grado,
                     descripcion: args.value
                  },
                  success: function(response) {
                     if (response.listaGrados) {
                        jqxgridNivelSource.localdata = response.listaGrados;
                        $(jqxgridGrado).jqxGrid('updateBoundData');
                     }
                  },
                  error: function(jqXHr, status, error) {
                     let errorMsg = '';
                     if (jqXHr.responseJSON) {
                        errorMsg = jqXHr.responseJSON.error;
                     } else {
                        errorMsg = jqXHr.responseText;
                     }
                     showAlertSweet(errorMsg, 'error');
                  }
               });
            } else {
               if (args.datafield == 'grado') {
                  $.ajax({
                     type: "post",
                     url: "<?= MODULO_URL ?>/nivel-academico/json/insert",
                     data: {
                        tabla: 'G',
                        nivel: rowdata.nivel,
                        grado: args.value,
                        descripcion: rowdata.descripcion
                     },
                     success: function(response) {
                        if (response.listaGrados) {
                           jqxgridGradoSource.localdata = response.listaGrados;
                           jqxgridSeccionSource.localdata = '[]';
                           $(jqxgridGrado).jqxGrid('updateBoundData');
                           $(jqxgridSeccion).jqxGrid('updateBoundData');
                        }
                     },
                     error: function(jqXHr, status, error) {
                        let errorMsg = error;
                        if (jqXHr.responseJSON) {
                           errorMsg = jqXHr.responseJSON.error;
                        } else {
                           errorMsg = jqXHr.responseText;
                        }
                        showAlertSweet(errorMsg, 'error');
                     }
                  });
               }
            }
         }
      });

      $(jqxgridSeccion).on('cellendedit', function(event) {
         const args = event.args;
         const rowdata = args.row;
         const action = rowdata.action == 'I' ? 'I' : 'E';
         if (args.oldvalue !== args.value) {
            if (action == 'E') {
               $.ajax({
                  type: "post",
                  url: "<?= MODULO_URL ?>/nivel-academico/json/update",
                  data: {
                     tabla: 'S',
                     nivel: rowdata.nivel,
                     grado: rowdata.grado,
                     seccion: rowdata.seccion,
                     descripcion: args.value
                  },
                  success: function(response) {
                     if (response.listaSecciones) {
                        jqxgridSeccionSource.localdata = response.listaSecciones;
                        $(jqxgridNivel).jqxGrid('updateBoundData');
                     }
                  },
                  error: function(jqXHr, status, error) {
                     let errorMsg = '';
                     if (jqXHr.responseJSON) {
                        errorMsg = jqXHr.responseJSON.error;
                     } else {
                        errorMsg = jqXHr.responseText;
                     }
                     showAlertSweet(errorMsg, 'error');
                  }
               });
            } else {
               if (args.datafield == 'seccion') {
                  $.ajax({
                     type: "post",
                     url: "<?= MODULO_URL ?>/nivel-academico/json/insert",
                     data: {
                        tabla: 'S',
                        nivel: rowdata.nivel,
                        grado: rowdata.grado,
                        seccion: args.value,
                        descripcion: rowdata.descripcion
                     },
                     success: function(response) {
                        if (response.listaSecciones) {
                           jqxgridSeccionSource.localdata = response.listaSecciones;
                           $(jqxgridSeccion).jqxGrid('updateBoundData');
                        }
                     },
                     error: function(jqXHr, status, error) {
                        let errorMsg = '';
                        if (jqXHr.responseJSON) {
                           errorMsg = jqXHr.responseJSON.error;
                        } else {
                           errorMsg = jqXHr.responseText;
                        }
                        showAlertSweet(errorMsg, 'error');
                     }
                  });
               }
            }
         }
      });

      $('.btn-outline-primary').on('click', function() {
         let detcod = $(this).attr('detcod');
         if (detcod) {
            agregarRowGrid(detcod);
         }
      });

      $('.btn-outline-danger').on('click', function() {
         let detcod = $(this).attr('detcod');
         if (detcod) {
            eliminarRowGrid(detcod);
         }
      });

   });
</script>
<?= $this->endSection() ?>