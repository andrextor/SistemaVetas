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
               <i class="fa fa-align-justify"></i> Ingresos
               <button
                  type="button"
                  class="btn btn-success"
                  @click="mostrarDetalle()">
                  <i class="icon-plus"></i>&nbsp;Nuevo
               </button>
            </div>
            <spinner v-show="loading"></spinner>
            <template  v-show="!loading">               
                  <!-- Listado -->
            <template  v-if="listado == 1">
               <div class="card-body">
                  <div class="form-group row">
                     <div class="col-md-6">
                        <div class="input-group">
                           <select class="form-control col-md-3" v-model="criterio">
                              <option value="tipo_comprobante">Tipo Comprobante</option>
                              <option value="num_comprobante">Numero Comprobante</option>
                              <option value="fech_hora">Fecha</option>
                           </select>

                           <input type="text"  class="form-control" placeholder="Texto a buscar"  v-model="buscar"
                              @keyup.enter="listarIngreso(1,buscar,criterio)">
                           <button   type="submit"  @click="listarIngreso(1,buscar,criterio)"  class="btn btn-primary">
                              <i class="fa fa-search"></i> Buscar
                           </button>
                        </div>
                     </div>
                  </div>
               
                  <div class="table-responsive">
                     <table v-show="!loading" class="table table-bordered table-striped ">
                        <thead>
                           <tr>
                              <th>Opciones</th>
                              <th>Usuario</th>
                              <th>Proveedor</th>
                              <th>Tipo Comprobante</th>
                              <th>Serie Comprobante</th>
                              <th>Numero Comprobante</th>
                              <th>Fecha y hora</th>
                              <th>Total</th>
                              <th>Impuesto</th>
                              <th>Estado</th>
                           </tr>

                        </thead>
                        <tbody>
                           <tr v-for="ingreso  in ingresos" :key="ingreso.id">
                              <td>
                                 <button type="button" class="btn btn-success btn-sm" @click="verIngreso(ingreso.id)">
                                    <i class="icon-eye"></i>
                                 </button> &nbsp;
                                 <button  title="Anular" type="button" v-bind:class="[ ingreso.estado=='Registrado' ? 'btn btn-danger btn-sm' : '']" v-if="ingreso.estado=='Registrado'"
                                    @click="anularIngreso(ingreso.id)">
                                    <i v-if="ingreso.estado=='Registrado'" class="icon-trash"></i>
                                 </button>
                                 <!-- <button  type="button" @click="eliminarCategoria(categoria.id)">Eliminar</button> -->
                              </td>
                              <td v-text="ingreso.usuario"></td>
                              <td v-text="ingreso.nombre"></td>
                              <td v-text="ingreso.tipo_comprobante"></td>
                              <td v-text="ingreso.serie_comprobante"></td>
                              <td v-text="ingreso.num_comprobante"></td>
                              <td v-text="ingreso.fecha_hora"></td>
                              <td v-text="ingreso.total"></td>
                              <td v-text="ingreso.impuesto"></td>
                              <td>
                                 <span v-bind:class="[ ingreso.estado=='Registrado' ? 'badge badge-success' : 'badge  badge-warning']">
                                    {{ingreso.estado=='Registrado' ? 'Registrado' : 'Anulado'}}
                                 </span>
                              </td>
                           </tr>
                        </tbody>
                     </table>
               
                  </div>
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
            </template>
            <!-- / Fin Listado -->      
            <!-- Deatalle -->
            <template v-else-if="listado==0">
               <div class="card-body" >
                        <div class="form-group row border">
                           <div class="col-md-9">
                              <div class="form-group">
                                    <label for="">Proveedor(*)</label>
                                     <v-select   
                                     @search="selectProveedores"
                                      v-model="selected" 
                                      label="nombre"
                                      :filterable="false"
                                      :options="proveedores"
                                      placeholder="Buscar Proveedores"
                                      @input="getDatosProveedor">
                                      
                                    <template slot="no-options">
                                      Busqueda por nombre o nit..
                                     </template>
                                      </v-select>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <label for="">Impuesto(*)</label>
                              <input type="text" class="form-control" v-model="impuesto">
                           </div>
                           <div class="col-md-4">
                                 <div class="form-group">
                                       <label for="">Tipo Comprobante(*)</label>
                                       <select class="form-control" v-model="tipo_comprobante">
                                          <option value="">Seleccione</option>
                                          <option value="BOLETA">Boleta</option>
                                          <option value="FACTURA">Factura</option>
                                          <option value="TIKECT">Ticket</option>
                                       </select>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <label for="">Serie Comprobante</label>
                                 <input type="text" class="form-control" v-model="serie_comprobante" placeholder="000x">
                              </div>
                              <div class="col-md-4">
                                 <label for="">Nùmero Comprobante</label>
                                 <input type="text" class="form-control" v-model="num_comprobante" placeholder="000xx">
                              </div>                              
                        </div>
                        <div class="form-group row border">
                           <div  class="col-md-6">
                              <div class="form-group">
                                 <label >Articulo <span style="color:red" v-show="articulo==0">(*seleccione)</span></label>
                                 <div class="form-inline">
                                    <input type="text" class="form-control" v-model="codigo" placeholder="Ingrese Articulo" @keyup.enter="buscarArticulo" @blur="buscarArticulo">
                                    <button @click="abrirModal()" class="btn btn-primary">...</button>
                                    <input type="text" readonly class="form-control" v-model="nombre_articulo">
                                 </div>
                              </div>
                           </div>
                           <div  class="col-md-2">
                              <div class="form-group">
                                 <span style="color:red" v-show="precio_articulo==0">(*ingresa)</span>
                                 <label >Precio </label>
                                 <input type="number" value="0" min="0" step="any" class="form-control" v-model="precio_articulo" >
                              </div>
                           </div>
                           <div  class="col-md-2">
                              <div class="form-group">
                                 <span style="color:red" v-show="cantidad_articulo==0">(*ingresa)</span>
                                 <label >Cantidad </label>
                                 <input type="number"  value="0" min="0" class="form-control" v-model="cantidad_articulo" >
                              </div>
                           </div>
                           <div  class="col-md-2">
                              <div class="form-group">
                                 <button class="btn btn-success form-control btnagregar" @click="agregarDetalle">
                                    <i class="icon-plus"></i>
                                 </button>
                              </div>
                           </div>
                           <div  class="col-md-12">
                              <div v-show="errorIngreso" class="form-group-row div-error">
                                 <div class="text-center text-error">
                                    <div v-for="error in mensajesError" :key="error" v-text="error"></div>
                                 </div>
                              </div>
                           </div>
                  </div>
                     <div class="form-group row">
                        <div class="table-responsive  col-md-12">
                           <table class="table table-bordered table-striped table-sm">
                              <thead>
                                 <tr>
                                    <th>Opciones</th>
                                    <th>Artículo</th>
                                    <th>Precion</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal</th>
                                 </tr>

                              </thead>
                              <tbody v-if="detalleIngresos.length">
                                 <tr v-for="(detalle,index) in detalleIngresos" :key="detalle.id"> 
                                    <td >
                                       <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                          <i class="icon-close"></i>  
                                       </button>
                                    </td>
                                    <td v-text="detalle.nombre_articulo">Nombre Artículo </td>
                                    <td><input type="number" value="3" min="0" class="form-control" v-model="detalle.precio_articulo"></td>
                                    <td><input type="number" value="1" step="any" min="0" class="form-control" v-model="detalle.cantidad_articulo"></td>
                                    <td>{{ detalle.precio_articulo * detalle.cantidad_articulo}}</td>
                                 </tr>                            
                                 <tr style="background-color: #CEECF5;">
                                    <td colspan="4" align="right"><strong> Total Parcial:</strong></td>
                                    <td>$ {{ total_parcial = ((total - total_impuesto)).toFixed(2)}}</td>
                                 </tr>
                                 <tr style="background-color: #CEECF5;">
                                    <td colspan="4" align="right"><strong> Total Impuesto:</strong></td>
                                    <td>$ {{total_impuesto = ((total*impuesto)/(1+impuesto)).toFixed(2)}}</td>
                                 </tr>
                                 <tr style="background-color: #CEECF5;">
                                    <td colspan="4" align="right"><strong> Total:</strong></td>
                                    <td>$ {{total = calcularTotal}}</td>
                                 </tr>
                              </tbody>
                              <tbody v-else>
                                 <tr>
                                    <td colspan="5">No Hay articulos agregados</td>
                                 </tr>
                              </tbody>
                           </table>
                        </div>
                     </div>
                  
                     <div class="form-group row">
                        <div class="col-md-12">
                           <button type="button" class="btn btn-secondary" @click="ocultarDetalle()">Cerrar</button>
                           <button type="button" class="btn btn-primary" @click="registrarIngreso()">Registrar Comprar</button>
                        </div>
                     </div>
               </div>
            </template>
             <!-- Fin Deatalle -->
            <!-- Ver ingreso -->
            <template v-else-if="listado==2">
               <div class="card-body" >
                    <div v-if="this.esatdo =='Anulado'" class="col-md-12"> <h2 style="color:red;text-align: center;">Ingreso Anulado {{diaAnulado}}></h2></div>
                  <div class="form-group row border">
                        <div class="col-md-9">
                           <label for="">Proveedor(*)</label>
                           <p v-text="proveedor"></p>
                        </div>
                        <div class="col-md-3">
                           <label for="">Impuesto(*)</label>
                           <p v-text="impuesto"></p>
                        </div>
                     
                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="">Tipo Comprobante(*)</label>
                              <p v-text="tipo_comprobante"></p>  
                           </div>
                        </div>
                        <div class="col-md-4">
                           <label for="">Serie Comprobante</label>
                           <p v-text="serie_comprobante"></p>
                        </div>
                        <div class="col-md-4">
                           <label for="">Nùmero Comprobante</label>
                           <p v-text="num_comprobante"></p>
                        </div>                              
                     </div>
                     <div class="form-group row">
                        <div class="table-responsive  col-md-12">
                           <table class="table table-bordered table-striped table-sm">
                              <thead>
                                 <tr>
                                    <th>Artículo</th>
                                    <th>Precion</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal</th>
                                 </tr>
                              </thead>
                              <tbody v-if="detalleIngreso.length">
                                 <tr v-for="deta in detalleIngreso" :key="deta.id"> 
                                    <td v-text="deta.nombre">Nombre Artículo </td>
                                    <td v-text="deta.precio"></td>
                                    <td v-text="deta.cantidad"></td>
                                    <td>{{ deta.precio * deta.cantidad}}</td>
                                 </tr>                            
                                 <tr style="background-color: #CEECF5;">
                                    <td colspan="3" align="right"><strong> Total Parcial:</strong></td>
                                    <td>$ {{ total_parcial = ((total - total_impuesto)).toFixed(2)}}</td>
                                 </tr>
                                 <tr style="background-color: #CEECF5;">
                                    <td colspan="3" align="right"><strong> Total Impuesto:</strong></td>
                                    <td>$ {{total_impuesto = ((total*impuesto)/(1+impuesto)).toFixed(2)}}</td>
                                 </tr>
                                 <tr style="background-color: #CEECF5;">
                                    <td colspan="3" align="right"><strong> Total:</strong></td>
                                    <td>$ {{total}}</td>
                                 </tr>
                              </tbody>
                              <tbody v-else>
                                 <tr>
                                    <td colspan="4">No Hay articulos agregados</td>
                                 </tr>
                              </tbody>
                           </table>
                        </div>
                     </div>
                     <div class="form-group row">
                        <div class="col-md-12">
                           <button type="button" class="btn btn-secondary" @click="ocultarDetalle()">Cerrar</button>
                        </div>
                     </div>
                  </div>
               <!-- </div> -->
            </template>
            <!-- Fin ver ingreso -->
            </template>
         </div>
         <!-- Fin ejemplo de tabla Listado -->
      </div>
      <!--Inicio del modal agregar/actualizar-->
      <div class="modal fade"  tabindex="-1" :class="{'mostrar' : modal  }" aria-labelledby="myModalLabel"
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
                     <div class="form-group row">
                        <div class="col-md-6">
                           <div class="input-group">
                              <select class="form-control col-md-3" v-model="criterioA">
                                 <option value="nombre">Nombre</option>
                                 <option value="descripcion">Descripción</option>
                                 <option value="codigo">Codigo</option>
                              </select>
                              <input type="text" class="form-control" placeholder="Texto a buscar" v-model="buscarA"
                                 @keyup.enter="listarArticulo(buscarA,criterioA)">
                              <button
                                 type="submit"  @click="listarArticulo(buscarA,criterioA)"  class="btn btn-primary">
                                 <i class="fa fa-search"></i> Buscar
                              </button>
                           </div>
                        </div>
                     </div>
                  <div class="table-responsive">
                     <table class="table table-bordered table-striped table-sm">
                        <thead>
                           <tr>
                              <th>Opciones</th>
                              <th>Código</th>
                              <th>Nombre</th>
                              <th>Previo Venta</th>
                              <th>Categoria</th>
                              <th>Stock</th>
                              <th>Estado</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr v-for="arti  in articulos" :key="arti.id">
                              <td>
                                 <button  type="button" class="btn btn-success btn-sm" @click="agregarDetalleModal(arti)"><i class="icon-plus"></i></button>
                              </td>
                              <td v-text="arti.codigo"></td>
                              <td v-text="arti.nombre"></td>
                              <td v-text="arti.precio_venta"></td>                              
                              <td v-text="arti.nombre_categoria"></td>
                              <td v-text="arti.stock"></td>
                              <td>
                                 <span class="badge badge-success" >Activo                                    
                                 </span>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" @click="cerrarModal">Cerrar</button>
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
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';
import { constants } from 'crypto';

export default {
   data() {
      return {
         ingresos:[],
         detalleIngresos:[],
         mensajesError: [],
         proveedores: [],
         articulo:[],
         articulos:[],
         detalleIngreso:[],
         arti:'',
         diaAnulado:'',
         proveedor:'',
         codigo:'',
         nombre_articulo:'',
         precio_articulo:0,
         cantidad_articulo:0,
         articulo_id:0,
         listado:1,
         ingreso_id :0,
         proveedor_id:0,
         usuario_id:0,
         tipo_comprobante: 'FACTURA',
         serie_comprobante: '',
         num_comprobante:'',
         selected:'',
         impuesto: 0.19,
         total:0.0,
         total_impuesto:0.0,
         total_parcial:0.0,
         cantidad:0,
         precio:0.0,
         loading: true,
         estado:'',
         modal: 0,
         tituloModal: "",
         tipoAccion: 0,
         errorIngreso: 0,
         offset: 1,
         criterio: "num_comprobante",
         criterioA: "codigo",
         buscar: "",
         buscarA: "",
         pagination: {
            total: 0,
            current_page: 0,
            per_page: 0,
            last_page: 0,
            from: 0,
            to: 0
         },
        
    
      };
   },
     components: {
   //  'barcode': VueBarcode
    'v-select': vSelect
  },
   computed: {
      calcularTotal(){
         var total = 0.0;
         for(var i=0 ; i < this.detalleIngresos.length ;i++){
     
          total= total +(this.detalleIngresos[i].precio_articulo * this.detalleIngresos[i].cantidad_articulo);
         }
         
         return total
      },
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
      agregarDetalleModal(articulo){
             
            if(this.encuentra(articulo.id)){              
                  this.alertaWarning("El articulo " +  articulo.nombre+ " ya se encuentra agregado");
            }else{

               this.detalleIngresos.push({
               articulo_id : articulo.id,
               precio_articulo: 1,
               nombre_articulo: articulo.nombre,
               cantidad_articulo : 1});            
            }
      },
      listarArticulo(buscar, criterio) {
         var url = "/articulo/listar?buscar="+buscar + "&criterio=" + criterio;
         axios.get(url).then((res) => {
            var res = res.data;
            this.articulos = res.articulos.data;
            
         });
       
      },
      listarIngreso(page, buscar, criterio) {
         var url = "/ingreso?page=" + page + "&buscar=" +  buscar + "&criterio=" + criterio;
         axios.get(url).then((res) => {
            var res = res.data;
            this.ingresos = res.ingresos.data;
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
         this.listarIngreso(page, buscar, criterio);
      },
      eliminarDetalle(index){
         this.detalleIngresos.splice(index,1)
      },
      agregarDetalle(){

         if(this.nombre_articulo == '' ||  this.precio_articulo ==0|| this.cantidad_articulo == 0 || this.articulo_id ==0){

         }else{
      
            if(this.encuentra(this.articulo_id)){              
                  this.alertaWarning("El articulo ya se encuentra agregado");
            }else{

               this.detalleIngresos.push({
               articulo_id : this.articulo_id,
               precio_articulo: this.precio_articulo,
               nombre_articulo: this.nombre_articulo,
               cantidad_articulo : this.cantidad_articulo,});

               this.articulo_id = 0,
               this.nombre_articulo = '',
               this.codigo = '',
               this.precio_articulo = 0,
               this.cantidad_articulo = 0              
            }
         
         }
         
      },
      encuentra(articulo_id){
            var sw=false;
      
            for(var i=0; i < this.detalleIngresos.length ;i++){
               if(this.detalleIngresos[i].articulo_id == articulo_id){
                  sw=true;
               }
            }
            return sw;
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
               this.listarIngreso(1, "", "nombre");
               this.usuarios = response.data;
            });
      },
      registrarIngreso(){
         if(this.validacionIngreso()){ 
            return;
         }

         axios.post('/ingreso/registrar',{
         'proveedor_id':this.proveedor_id,
         'tipo_comprobante':this.tipo_comprobante,
         'serie_comprobante':this.serie_comprobante,
         'numero_comprobante':this.num_comprobante,
         'impuesto':this.impuesto,
         'total':this.total,
         'data':this.detalleIngresos,


         }).then((res) => {
            this.listado=1;
            this.tipo_comprobante = 'FACTURA';
             this.serie_comprobante ='';
             this.num_comprobante ='';            
             this.impuesto =0.19;
             this.total=0.0;
             this.proveedor_id=0;
             this.articulo_id=0;
             this.articulo='';
             this.cantidad=0;
             this.precio=0;
              this.selected = '';
              this.detalleIngresos=[];
            this.listarIngreso(1,'','');

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
      alertaWarning(mensaje){
            const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 6000
                  });
                  Toast.fire({
                  type: 'warning',
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
            this.listarIngreso(this.pagination.current_page, "", "nombre");
            this.usuarios = response.data;
         })
         .catch(error => {
            console.log(error);
         });
      },
      anularIngreso(id) {
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
            text: "Estas seguro que deseas anular el ingreso!",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Si, Anular!",
            cancelButtonText: "No, Cancelar!",
            reverseButtons: true
         })
         .then(result => {
            if (result.value) {
                  axios.put("/ingreso/anular", { id: id }).then(response => {
                     var res = response.data;
                     this.alertaSuccess('El Ingreso ha sido anulado con Éxito')
                     this.listarIngreso( this.pagination.current_page,"","num_comprobante");
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
      validacionIngreso() {
         this.errorIngreso = 0;
         this.mensajesError = [];

         if (this.proveedor_id == 0)   this.mensajesError.push("Debes seleccionar un proveedor.");
         if (!this.tipo_comprobante)   this.mensajesError.push("Debes seleccionar un tipo de comprobante.");
         if (!this.num_comprobante)   this.mensajesError.push("Ingresa un numero de factura.");
         if (!this.impuesto)   this.mensajesError.push("Ingresa un valor de impuesto.");
         if (this.detalleIngresos.length <= 0)   this.mensajesError.push("Ingresa detalles.");
         
         if (this.mensajesError.length) this.errorIngreso = 1;

         return this.errorIngreso;
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
         this.errorIngreso = 0;
         this.mensajesError = [];

      },
      mostrarDetalle(){
         this.listado = 0;
            this.tipo_comprobante = 'FACTURA';
            this.serie_comprobante ='';
            this.num_comprobante ='';            
            this.impuesto =0.19;
            this.total=0.0;
            this.proveedor_id=0;
            this.articulo_id=0;
            this.articulo='';
            this.cantidad=0;
            this.precio=0;
            this.selected = '';
            this.detalleIngresos=[];
      
      },
      selectProveedores(search,loading){
         loading(true)

         axios.get('/proveedor/select?filtro='+search).then((response) => {
            var res = response.data;
            q: search
            this.proveedores = res.proveedores;
            loading(false)
         });
      },
      getDatosProveedor(val1){
         this.proveedor_id = val1.id; 
      },
      buscarArticulo(){
            var filtro = this.codigo;
            axios.get('/articulo/buscar?filtro='+filtro).then((response) => {
            var res = response.data;

               // console.log(this.articulo);
            if(res.articulo){
               this.articulo = res.articulo;
               this.nombre_articulo =   this.articulo.nombre;
               this.articulo_id =   this.articulo.id;

         
            }else{
               
               this.nombre_articulo = "No se encontro nigun articulo con el codigo";
               this.articulo_id =   0;
            }
         
         }).catch(error=>{
            console.log(error);
         });

      },
      ocultarDetalle(){
         this.listado = 1;
         this.proveedores=  [];
      },
      verIngreso(id){
         this.listado = 2;
      
         //Obtener los datos del ingreso
         var datosIngreso = [];
            var url = '/ingreso/cabecera?id=' + id;
            axios.get(url).then((res) => {
            var res = res.data;
            datosIngreso = res.datosIngreso;
            this.proveedor = datosIngreso.nombre;
            this.serie_comprobante = datosIngreso.serie_comprobante;
            this.num_comprobante = datosIngreso.num_comprobante;
            this.tipo_comprobante = datosIngreso.tipo_comprobante;
            this.total_impuesto = datosIngreso.impuesto;
            this.total = datosIngreso.total;
            this.diaAnulado = datosIngreso.updated_at;
            this.estado = datosIngreso.estado
            console.log(datosIngreso.update_at);
         }).catch((error)=>{
            console.log(error)
         });

         //Obtener los datos del detalle
            var url = '/ingreso/detalle?id=' + id;
            axios.get(url).then((res) => {
            var response = res.data;
            this.detalleIngreso = response.detalleIngreso;
 
         }).catch((error)=>{
            console.log(error)
         });
      },
      abrirModal() {
         this.articulos = [];
         this.buscarA = this.codigo;
         this.modal = 1;
         this.tituloModal = "Seleccione uno o varios articulos";

      },
      cerrarModal(){
         this.modal = 0;
         this.tituloModal = '';
      },
   },
   
   mounted() {
      this.listarIngreso(1, this.buscar, this.criterio); 
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
@media (min-width: 600px) {
   .btnagregar{
      margin-top: 2rem;
   }
}


</style>
