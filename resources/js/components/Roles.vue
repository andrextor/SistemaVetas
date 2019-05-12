<template>
   <main class="main" >
      <!-- Breadcrumb -->
      <ol class="breadcrumb">
         <li class="breadcrumb-item active">
            <a href="/">Escritorio</a>
         </li>
      </ol>
      <div class="container-fluid">
         <!-- Ejemplo de tabla Listado -->
        
         <div class="card">
            <div class="card-header">
               <i class="fa fa-align-justify"></i> Roles
               <button
                  type="button"
                  class="btn btn-secondary"
                  @click="abrirModal('rol','registrar')">
                  <i class="icon-plus"></i>&nbsp;Nuevo
               </button>
            </div>
            <div class="card-body">
               <div class="form-group row">
                  <div class="col-md-6">
                     <div class="input-group">
                        <select class="form-control col-md-3" v-model="criterio">
                           <option value="nombre">Nombre</option>
                           <option value="descripcion">Descripción</option>
                        </select>
                        <input
                           type="text"
                           class="form-control"
                           placeholder="Texto a buscar"
                           v-model="buscar"
                           @keyup.enter="listarRol(1,buscar,criterio)"
                        >
                        <button
                           type="submit"
                           @click="listarRol(1,buscar,criterio)"
                           class="btn btn-primary"
                        >
                           <i class="fa fa-search"></i> Buscar
                        </button>
                     </div>
                  </div>
               </div>
                <spinner v-show="loading"></spinner>
               <table class="table table-bordered table-striped table-sm">
                  <thead>
                     <tr>
                        <th>Acciones</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr v-for="rol  in roles" :key="rol.id">
                        <td>
                           <button
                              type="button"
                              class="btn btn-warning btn-sm"
                              @click="abrirModal('rol','actualizar',rol)">
                              <i class="icon-pencil"></i>
                           </button> &nbsp;
                           <button
                              type="button"
                              v-bind:class="[ rol.condicion ? 'btn btn-danger btn-sm' : 'btn btn-success btn-sm']"
                              @click="cambiarEstadoRol(rol.id)">
                              <i v-if="rol.condicion" class="icon-trash"></i>
                              <i v-else class="icon-check"></i>
                           </button>
                           <!-- <button  type="button" @click="eliminarCategoria(categoria.id)">Eliminar</button> -->
                        </td>
                        <td v-text="rol.nombre"></td>
                        <td v-text="rol.descripcion"></td>
   
                        <td>
                            <span
                              v-bind:class="[ rol.condicion == 1 ? 'badge badge-success' : 'badge  badge-warning']">
                              {{rol.condicion == 1 ? 'Activo' : 'Desactivada'}}
                            </span>
                        </td>
                     </tr>
                  </tbody>
               </table>
               <nav>
                  <ul class="pagination">
                     <li class="page-item" v-if="pagination.current_page > 1">
                        <a
                           class="page-link"
                           href="#"
                           @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">
                           Ant
                        </a>
                     </li>
                     <li  class="page-item"  v-for="page in pagesNumber"   :key="page" v-bind:class="[ page == isActived ? 'active' : '']">
                        <a class="page-link"  href="#"  @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"> </a>
                     </li>
                     <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                        <a class="page-link"  href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)" >Sig</a>
                     </li>
                  </ul>
               </nav>
            </div>
         </div>
         <!-- Fin ejemplo de tabla Listado -->
      </div>
      <!--Inicio del modal agregar/actualizar-->
      <div
         class="modal fade"
         tabindex="-1"
         :class="{'mostrar' : modal  }"
         aria-labelledby="myModalLabel"
         style="display: none;"
         aria-hidden="true">
         <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h4 class="modal-title" v-text="tituloModal"></h4>
                  <button type="button" class="close" @click="cerrarModal" aria-label="Close">
                     <span aria-hidden="true">×</span>
                  </button>
               </div>
               <div class="modal-body">
                  <form action method="post" enctype="multipart/form-data" class="form-horizontal">
   
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                        <div class="col-md-9">
                           <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del producto">
                           <!-- <span class="help-block">(*) Ingrese el nombre de la producto</span> -->
                        </div>
                    </div>
                     <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                        <div class="col-md-9">
                           <input type="text" v-model="descripcion" class="form-control" placeholder="Ingrese Una descripcion">                      
                        </div>
                     </div>
                     <div v-show="errorRol" class="form-group-row div-error">
                        <div class="text-center text-error">
                           <div v-for="error in mensajesError" :key="error" v-text="error"></div>
                        </div>
                     </div>
                  </form>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" @click="cerrarModal">Cerrar</button>
                  <button
                     type="button"
                     v-if="tipoAccion == 1"
                     class="btn btn-primary"
                     @click="registrarRol"
                  >Guardar</button>
                  <button
                     type="button"
                     v-if="tipoAccion == 2"
                     class="btn btn-primary"
                     @click="actualizarRol"
                  >Actualizar</button>
               </div>
            </div>
            <!-- /.modal-content -->
         </div>
         <!-- /.modal-dialog -->
      </div>
      <!--Fin del modal-->
   </main>
</template>

<script>
import Swal from "sweetalert2";
import VueBarcode from 'vue-barcode';
export default {
   data() {
      return {
         loading: true,
         rol_id: 0,
         nombre: "",
         descripcion: "",
         roles: [],
         modal: 0,
         tituloModal: "",
         tipoAccion: 0,
         errorRol: 0,
         mensajesError: [],
         pagination: {
            total: 0,
            current_page: 0,
            per_page: 0,
            last_page: 0,
            from: 0,
            to: 0
         },
         offset: 1,
         criterio: "nombre",
         buscar: "",
      };
   },
     components: {
    'barcode': VueBarcode
  },
   computed: {
      isActived() {
         return this.pagination.current_page;
      },
      //calcular elementos de la pagina
      pagesNumber() {
         if (!this.pagination.to) {
            return [];
         }
         var from = this.pagination.current_page - this.offset;
         if (from < 1) {
            from = 1;
         }

         var to = from + this.offset * 2;
         if (to >= this.pagination.last_page) {
            to = this.pagination.last_page;
         }

         var pageArray = [];

         while (from <= to) {
            pageArray.push(from);
            from++;
         }
         return pageArray;
      }
   },
   methods: {
      listarRol(page, buscar, criterio) {
         var url = "/rol?page=" + page + "&buscar=" +  buscar + "&criterio=" + criterio;
         axios.get(url).then((res) => {
            var res = res.data;
            this.roles = res.roles.data;
            this.pagination = res.pagination;
            this.loading = false;
         });
      },

      cambiarPagina(page, buscar, criterio) {
         //Avtualizar la pagina actual
         this.pagination.current_page = page;
         //enviar la peticion para visualizar la data de la pagina
         this.listarRol(page, buscar, criterio);
      },
    registrarRol() {
         if (this.validacionRol()) {
            return;
         }
         axios.post("/rol/registrar", {
               'nombre': this.nombre,
               'descripcion': this.descripcion
            }).then(response => {
               this.cerrarModal();
               this.alertaSuccess('Rol Creado Exitosamente');
               this.listarRol(1, "", "nombre");
               this.roles = response.data;
            });
    },
    alertaSuccess(mensaje){
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 6000
                });
                Toast.fire({
                type: 'success',
                title: mensaje
                });
    },
    actualizarRol() {
        if (this.validacionRol()) {
        return;
        }
        axios.put("/rol/actualizar", {
            'id': this.rol_id,
            'nombre': this.nombre,
            'descripcion': this.descripcion
        })
        .then(response => {
            this.cerrarModal();
            this.alertaSuccess('Rol actualizado Exitosamente');
            this.listarRol(this.pagination.current_page, "", "nombre");
            this.roles = response.data;
        })
        .catch(error => {
            console.log(error);
        });
    },
    cambiarEstadoRol(id) {
        const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger"
        },
        buttonsStyling: true
        });

        swalWithBootstrapButtons
        .fire({
            title: "Estas Seguro?",
            text: "Estas seguro que deseas cambiar el estado!",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Si, Cambiar!",
            cancelButtonText: "No, Cancelar!",
            reverseButtons: true
        })
        .then(result => {
            if (result.value) {
                axios.put("/rol/estado", { id: id }).then(response => {
                    this.alertaSuccess('El rol '+ response.data.nombre +' a cambiado de estado')
                    this.listarRol( this.pagination.current_page,"","nombre");
                    }).catch(error => {
                    console.log(error);
                    });
                    // Read more about handling dismissals
            }else if (result.dismiss === Swal.DismissReason.cancel){
                swalWithBootstrapButtons.fire(
                    "Cancelado",
                    "No se cambio el estado del articulo",
                    "error"
                );
            }
        });
    },
    validacionRol() {
        this.errorRol = 0;
        this.mensajesError = [];
        if (!this.nombre)   this.mensajesError.push("El nombre del articulo no puede estar vacio.");
        if (this.mensajesError.length) this.errorRol = 1;

        return this.errorRol;
    },
    cerrarModal() {
        this.modal = 0;
        this.tituloModal = "";
        this.nombre = "";
        this.descripcion = "";
        this.errorRol = 0;
        this.mensajesError = [];

    },
    abrirModal(modelo, accion, data = []) {
        switch (modelo) {
        case "rol": {
            switch (accion) {
                case "registrar": {
                this.tituloModal = "Registrar Articulo";
                this.modal = 1;
                this.tipoAccion = 1;
                this.nombre = "";
                this.descripcion = "";
                break;
                }
                case "actualizar": {
                this.tituloModal = "Actualizar Articulo";
                this.modal = 1;
                this.tipoAccion = 2;
                this.rol_id = data.id;
                this.nombre = data.nombre;
                this.descripcion = data.descripcion;
                // console.log(data.categoria_id)
                break;
                }
            }
        }
        }
  
    },
   },
   mounted() {
      this.listarRol(1, this.buscar, this.criterio); 
   }
};
</script>
<style>
.modal-content {
   width: 100% !important;
   position: absolute !important;
   transition: 2s;
}
.mostrar {
   display: list-item !important;
   opacity: 1 !important;
   position: absolute !important;
   background-color: #3c29297a !important;
   transition: 2s;
}
.div-error {
   display: flex;
   justify-content: center;
}
.text-error {
   color: red !important;
   font-weight: bold;
}
</style>
