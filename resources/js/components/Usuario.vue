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
               <i class="fa fa-align-justify"></i> Usuario
               <button
                  type="button"
                  class="btn btn-success"
                  @click="abrirModal('usuario','registrar')">
                  <i class="icon-plus"></i>&nbsp;Nuevo
               </button>
            </div>
            <div class="card-body">
               <div class="form-group row">
                  <div class="col-md-6">
                     <div class="input-group">
                        <select class="form-control col-md-3" v-model="criterio">
                           <option v-for="ctrb in criterioBusquedad" :key="ctrb.id" :value="ctrb" v-text="ctrb">  </option>
                        </select>

                        <input type="text"  class="form-control" placeholder="Texto a buscar"  v-model="buscar"
                           @keyup.enter="listarUsuario(1,buscar,criterio)">
                        <button   type="submit"  @click="listarUsuario(1,buscar,criterio)"  class="btn btn-primary">
                           <i class="fa fa-search"></i> Buscar
                        </button>
                     </div>
                  </div>
               </div>
                <spinner v-show="loading"></spinner>
               <table v-show="!loading" class="table table-bordered table-striped table-sm">
                  <thead>
                     <tr>
                        <th>Opciones</th>
                        <th>Nombre</th>
                        <th>Tipo Docum.</th>
                        <th>Num Docum.</th>
                        <th>Direccion</th>
                        <th>Telefono</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Condicion</th>
                     </tr>

                  </thead>
                  <tbody>
                     <tr v-for="usuario  in usuarios" :key="usuario.id">
                        <td>
                           <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('usuario','actualizar',usuario)">
                              <i class="icon-pencil"></i>
                           </button> &nbsp;
                              <button  type="button" v-bind:class="[ usuario.condicion ? 'btn btn-danger btn-sm' : 'btn btn-success btn-sm']"
                              @click="cambiarEstadoUsuario(usuario.id)">
                              <i v-if="usuario.condicion" class="icon-trash"></i>
                              <i v-else class="icon-check"></i>
                           </button>
                           <!-- <button  type="button" @click="eliminarCategoria(categoria.id)">Eliminar</button> -->
                        </td>
                        <td v-text="usuario.nombre"></td>
                        <td v-text="usuario.tipo_documento"></td>
                        <td v-text="usuario.numero_documento"></td>
                        <td v-text="usuario.direccion"></td>
                        <td v-text="usuario.telefono"></td>
                        <td v-text="usuario.email"></td>
                        <td v-text="usuario.rol"></td>
                        <td>
                           <span v-bind:class="[ usuario.condicion == 1 ? 'badge badge-success' : 'badge  badge-warning']">
                              {{usuario.condicion == 1 ? 'Activo' : 'Desactivada'}}
                           </span>
                        </td>
                     </tr>
                  </tbody>
               </table>
               <nav>
                  <ul class="pagination">
                     <li class="page-item" v-if="pagination.current_page > 1">
                        <a class="page-link"  href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">
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
                        <label class="col-md-3 form-control-label" for="text-input">Usuario</label>
                        <div class="col-md-9">
                           <input type="text" v-model="usuario" class="form-control" placeholder="Nombre usuario">
                           <!-- <span class="help-block">(*) Ingrese el nombre de la producto</span> -->
                        </div>
                    </div>
                     <div class="form-group row">
                       <label class="col-md-3 form-control-label" for="text-input">Rol</label>
                        <div class="col-md-9">
                          <select class="form-control" v-model="rol_id">
                              <option value="0" disabled>Seleccione</option>
                              <option v-for="rol in roles" :key="rol.id" :value="rol.id" v-text="rol.nombre">  </option>
                          </select> 
                        </div>
                     </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                        <div class="col-md-9">
                           <input type="text" v-model="nombre" class="form-control" placeholder="Nombre completo">
                           <!-- <span class="help-block">(*) Ingrese el nombre de la producto</span> -->
                        </div>
                    </div>
                     <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Tipo Documento</label>
                        <div class="col-md-9">
                           <!-- <input type="text" v-model="tipo_documento" class="form-control" placeholder="Tipo Documento "> -->
                        <select class="form-control col-md-3" v-model="tipo_documento">
                           <!-- <option value="0" disabled>Seleccione</option> -->
                           <option v-for="tpc in tipoDocumento" :key="tpc.id" :value="tpc" v-text="tpc">  </option>
                        </select>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Numero Documento</label>
                        <div class="col-md-9">
                           <input type="text" v-model="numero_documento" class="form-control" placeholder="Numero Documento ">
                        </div>
                     </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Direccion</label>
                        <div class="col-md-9">
                           <input type="text" v-model="direccion" class="form-control"  placeholder="Dirección">
                           <!-- <span class="help-block">(*) Ingrese el nombre de la producto</span> -->
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Telefono</label>
                        <div class="col-md-9">
                           <input type="text" v-model="telefono" class="form-control"  placeholder="Telefono">
                           <!-- <span class="help-block">(*) Ingrese el nombre de la producto</span> -->
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Email</label>
                        <div class="col-md-9">
                           <input type="email" v-model="email" class="form-control"  placeholder="Email">
                           <!-- <span class="help-block">(*) Ingrese el nombre de la producto</span> -->
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Contraseña</label>
                        <div class="col-md-9">
                           <input type="text" v-model="password" class="form-control"  placeholder="Ingresa una contraseña">
                           <!-- <span class="help-block">(*) Ingrese el nombre de la producto</span> -->
                        </div>
                    </div>
                     <div v-show="errorUsuario" class="form-group-row div-error">
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
                     @click="registrarUsuario">Guardar</button>
                  <button
                     type="button"
                     v-if="tipoAccion == 2"
                     class="btn btn-primary"
                     @click="actualizarCliente">Actualizar</button>
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
export default {
   data() {
      return {
         nombre: "",
         usuario: "",
         rol_id: 0,
         tipo_documento: "",
         numero_documento: "",
         direccion: "",
         telefono: "",
         email:"",
         password:"",
         loading: true,
         usuario_id: 0,
         usuarios: [],
         modal: 0,
         tituloModal: "",
         tipoAccion: 0,
         errorUsuario: 0,
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
         roles:[],
         criterioBusquedad:['nombre','tipo_documento','numero_documento','direccion','telefono','email'],
         tipoDocumento:['C.C','Pasaporte','Cedula extranjera'],
      };
   },
     components: {
   //  'barcode': VueBarcode
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
   listarUsuario(page, buscar, criterio) {
      var url = "/user?page=" + page + "&buscar=" +  buscar + "&criterio=" + criterio;
      axios.get(url).then((res) => {
         var res = res.data;
         this.usuarios = res.usuarios.data;
         this.pagination = res.pagination;
         this.loading = false;
      });
   },
   selectRol(){
      axios.get("/user/select").then(response => {
         var res = response.data;
         this.roles = res.roles;
          
      });

   },
   cambiarPagina(page, buscar, criterio) {
      //Avtualizar la pagina actual
      this.pagination.current_page = page;
      //enviar la peticion para visualizar la data de la pagina
      this.listarUsuario(page, buscar, criterio);
   },
   registrarUsuario() {
      if (this.validacionCliente()) {
         return;
      }
      axios.post("/user/registrar", {
            'nombre': this.nombre,
            'tipo_documento': this.tipo_documento,
            'numero_documento': this.numero_documento,
            'direccion': this.direccion,
            'telefono': this.telefono,
            'email': this.email,
            'rol_id': this.rol_id,
            'usuario': this.usuario,
            'password': this.password,
         }).then(response => {
            this.cerrarModal();
            this.alertaSuccess('Usuario Creado Exitosamente');
            this.listarUsuario(1, "", "nombre");
            this.usuarios = response.data;
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
   actualizarCliente() {
      if (this.validacionCliente()) {
      return;
      }

      axios.put("/user/actualizar", {
         'id': this.usuario_id,
         'nombre': this.nombre,
         'tipo_documento': this.tipo_documento,
         'numero_documento': this.numero_documento,
         'direccion': this.direccion,
         'telefono': this.telefono,
         'email': this.email,
         'rol_id': this.rol_id,
         'usuario': this.usuario,
      }).then(response => {
         this.cerrarModal();
         this.alertaSuccess('Usuario actualizado Exitosamente');
         this.listarUsuario(this.pagination.current_page, "", "nombre");
         this.usuarios = response.data;
      })
      .catch(error => {
         console.log(error);
      });
   },
   cambiarEstadoUsuario(id) {
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
               axios.put("/user/estado", { id: id }).then(response => {
                  var res = response.data;
                  this.alertaSuccess('El usuario '+ res.persona.nombre +' a cambiado de estado')
                  this.listarUsuario( this.pagination.current_page,"","nombre");
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
   validacionCliente() {
      this.errorUsuario = 0;
      this.mensajesError = [];
      if (!this.nombre)   this.mensajesError.push("El nombre del usuario no puede estar vacio.");
      if (this.rol_id == 0)   this.mensajesError.push("Debes Seleccionar un Rol.");
      if (!this.tipo_documento)   this.mensajesError.push("Seleccione un tipo de documento.");
      if (!this.numero_documento)   this.mensajesError.push("El numero de documento no puede estar vacio.");
      if (!this.direccion)   this.mensajesError.push("Ingrese una direcion porfavor.");
      if (!this.telefono)   this.mensajesError.push("Ingrese un numero telefonico porfavor.");
      if (!this.email)   this.mensajesError.push("Ingresa un email valido.");
 

      if (this.mensajesError.length) this.errorUsuario = 1;

      return this.errorUsuario;
   },
   cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
      this.nombre = "";
      this.tipo_documento = 0;
      this.numero_documento = "";
      this.direccion = 0;
      this.telefono = 0;
      this.email = "";
      this.descripcion = "";
      this.rol_id = 0;
      this.usuario = "";
      this.errorUsuario = 0;
      this.mensajesError = [];

   },
   abrirModal(modelo, accion, data = []) {
      switch (modelo) {
      case "usuario": {
         switch (accion) {
               case "registrar": {
               this.tituloModal = "Registrar Usuario";
               this.modal = 1;
               this.tipoAccion = 1;
               this.nombre = "";
               this.tipo_documento = "C.C";
               this.numero_documento = "";
               this.direccion = "";
               this.telefono = "";
               this.email = "";
               this.rol_id = 0;
               this.usuario = "";
               this.password = "";
               break;
               }
               case "actualizar": {
               this.tituloModal = "Actualizar Usuario";
               this.modal = 1;
               this.usuario_id = data.id;
               this.tipoAccion = 2;
               this.nombre = data.nombre;
               this.tipo_documento = data.tipo_documento;
               this.numero_documento = data.numero_documento;
               this.direccion = data.direccion;
               this.telefono = data.telefono;
               this.email = data.email;
               this.rol_id = data.rol_id;
               this.usuario = data.usuario;
               this.password = data.password;        
               break;
               }
         }
      }   
      }
   this.selectRol();
   },
   },
   mounted() {
      this.listarUsuario(1, this.buscar, this.criterio); 
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
