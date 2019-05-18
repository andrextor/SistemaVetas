<template>
 <main class="main">
  <!-- Breadcrumb -->
  <ol class="breadcrumb">
   <li class="breadcrumb-item">Home</li>
   <li class="breadcrumb-item">
    <a href="#">Admin</a>
   </li>
   <li class="breadcrumb-item active">Dashboard</li>
  </ol>
  <div class="container-fluid">
   <!-- Ejemplo de tabla Listado -->
   <div class="card">
    <div class="card-header">
     <i class="fa fa-align-justify"></i> Categorías
     <button type="button"  class="btn btn-secondary"   @click="abrirModal('categoria','registrar')"  >
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
        <input type="text" class="form-control" placeholder="Texto a buscar" v-model="buscar"
         @keyup.enter="listarCategoria(1,buscar,criterio)">
        <button type="submit" @click="listarCategoria(1,buscar,criterio)" class="btn btn-primary">
         <i class="fa fa-search"></i> Buscar
        </button>
       </div>
      </div>
     </div>
     <table class="table table-bordered table-striped table-sm">
      <thead>
       <tr>
        <th>Opciones</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Estado</th>
       </tr>
      </thead>
      <tbody>
       <tr v-for="categoria  in categorias" :key="categoria.id">
        <td>
         <button
          type="button"
          class="btn btn-warning btn-sm"
          @click="abrirModal('categoria','actualizar',categoria)">
          <i class="icon-pencil"></i>
         </button> &nbsp;
         <button type="button" v-bind:class="[ categoria.condicion ? 'btn btn-danger btn-sm' : 'btn btn-success btn-sm']"
          @click="cambiarEstadoCategoria(categoria.id)">
          <i v-if="categoria.condicion" class="icon-trash"></i>
          <i v-else class="icon-check"></i>
         </button>
         <!-- <button  type="button" @click="eliminarCategoria(categoria.id)">Eliminar</button> -->
        </td>
        <td v-text="categoria.nombre"></td>
        <td v-text="categoria.descripcion"></td>
        <td>
         <span
          v-bind:class="[ categoria.condicion == 1 ? 'badge badge-success' : 'badge  badge-warning']"
         >{{categoria.condicion == 1 ? 'Activo' : 'Desactivada'}}</span>
        </td>
       </tr>
      </tbody>
     </table>
     <nav>
      <ul class="pagination">
       <li class="page-item" v-if="pagination.current_page > 1">
        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)" >Ant</a>
       </li>
       <li class="page-item " 
       v-for="page in pagesNumber" 
       :key="page"
       v-bind:class="[ page == isActived ? 'active' : '']">
        <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
       </li>
       <li class="page-item" v-if="pagination.current_page < pagination.last_page">
        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
       </li>
      </ul>
     </nav>
    </div>
   </div>
   <!-- Fin ejemplo de tabla Listado -->
  </div>
  <!--Inicio del modal agregar/actualizar-->
  <div  class="modal fade"   tabindex="-1"   :class="{'mostrar' : modal  }"   aria-labelledby="myModalLabel"
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
         <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de categoría">
         <span class="help-block">(*) Ingrese el nombre de la categoría</span>
        </div>
       </div>
       <div class="form-group row">
        <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
        <div class="col-md-9">
         <input  type="text" v-model="descripcion" class="form-control"   placeholder="Ingrese Una descripcion">
        </div>
       </div>
       <div v-show="errorCategoria" class="form-group-row div-error">
        <div class="text-center text-error">
         <div v-for="error in mensajesError" :key="error" v-text="error"></div>
        </div>
       </div>
      </form>
     </div>
     <div class="modal-footer">
      <button type="button" class="btn btn-secondary" @click="cerrarModal">Cerrar</button>
      <button type="button" v-if="tipoAccion == 1"  class="btn btn-primary"   @click="registrarCategoria">
          Guardar
          </button>
      <button type="button" v-if="tipoAccion == 2" class="btn btn-primary"  @click="actualizarCategoria">Actualizar</button>
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
    import Swal from 'sweetalert2'
    export default {
        
        data() {
            return {
                categoria_id : 0,
                categorias:[],
                nombre: '',
                descripcion: '',
                modal:0,
                tituloModal: '',
                tipoAccion:0 ,
                errorCategoria:0,
                mensajesError:[],
                pagination : {
                "total" : 0 ,
                "current_page": 0 ,
                "per_page" : 0 ,
                "last_page": 0 ,
                "from" : 0 ,
                "to" : 0 ,
                    },
                offset: 1,
                criterio: 'nombre',
                buscar: ''
            }
        },
        computed:{
            isActived(){
                return this.pagination.current_page;
            },
            //calcular elementos de la pagina
            pagesNumber(){
                if(!this.pagination.to){
                    return[];
                }
                var from = this.pagination.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pageArray = [];

                while(from <= to){
                    pageArray.push(from);
                    from++;
                }
                return pageArray;
                
            } 
        },
        methods:{
            listarCategoria(page,buscar,criterio){
                var url = '/categoria?page=' + page + '&buscar=' + buscar + '&criterio='+ criterio ;
                 axios.get(url).then((response) => {
                    var res = response.data;
                  this.categorias = res.categorias.data;
                  this.pagination = res.pagination;
            });
            },
            cambiarPagina(page,buscar,criterio){
                //Avtualizar la pagina actual
                this.pagination.current_page = page;
                //enviar la peticion para visualizar la data de la pagina
                this.listarCategoria(page,buscar,criterio)
            },
            // eliminarCategoria(id){
            // // axios.delete("/round_category/" + $("#frmDeleteRoundCategory #id_round").val(), params: $(this).serializeArray()}})
            // // .then(function (response) {                         
            // //     toastr.success(response.data);
            // // });  
            //      axios.delete('/categoria/eliminar/'+id).then((response) => {
            //           console.log(response.data);
            //         this.listarCategoria(this.pagination.current_page,'','nombre');   
            //         }).catch((error)=>    {
            //             console.log(error);
            //         });          
            // },
            registrarCategoria(){
       
                if(this.validacionCategoria()){
                        return;
                }
                axios.post('/categoria/registrar',{
                'nombre' : this.nombre,
                'descripcion' : this.descripcion
                }).then((response) => {
                    
                this.cerrarModal();
                this.listarCategoria(1,'','nombre');
                this.categorias = response.data;
                });                
            },
            actualizarCategoria(){
                if(this.validacionCategoria()){
                        return;
                }
                axios.put('/categoria/actualizar',{
                'id' : this.categoria_id,
                'nombre' : this.nombre,
                'descripcion' : this.descripcion
                }).then((response) => {
                    
                this.cerrarModal();
                this.listarCategoria(this.pagination.current_page,'','nombre');
                this.categorias = response.data;
                }).catch((error)=>    {
                    console.log(error);
                });          

            },
            cambiarEstadoCategoria(id){

                const swalWithBootstrapButtons = Swal.mixin({

                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: true,
                })

                swalWithBootstrapButtons.fire({
                title: 'Estas Seguro?',
                text: "Estas seguro que deseas cambiar el estado!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Cambiar!',
                cancelButtonText: 'No, Cancelar!',
                reverseButtons: true
                }).then((result) => {
                    
                if (result.value) {
                    
                    axios.put('/categoria/estado',{
                    'id' : id,
                    }).then((response) => {
                       
                    swalWithBootstrapButtons.fire(
                    'Estado Cambiado!',
                    'La categoria '+ response.data.nombre +' a cambiado de estado.',
                    'success'
                    )
                    
                    this.listarCategoria(this.pagination.current_page,'','nombre');

                    }).catch((error)=>    {
                        console.log(error);
                    });          
              
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'No se cambio el estado de la categoria',
                    'error'
                    )
                }
                });
            },
            validacionCategoria(){
                this.errorCategoria = 0;
                this.mensajesError = []  ;
                if(!this.nombre)  this.mensajesError.push("El nombre de la categoria no puede estar vacio.");
                
                if(this.mensajesError.length) this.errorCategoria = 1;
                
                return this.errorCategoria;
            },
            cerrarModal(){
                this.modal = 0;
                this.nombre = "";
                this.tituloModal = "";
                this.descripcion = "";
                this.errorCategoria = 0;
                this.mensajesError = []  ;
            },
            abrirModal(modelo,accion,data =[]){
                switch(modelo){
                    case "categoria":{
                        switch(accion){
                            case "registrar":{
                                this.modal = 1;
                                this.nombre = "";
                                this.descripcion = "";
                                this.tituloModal = "Registrar Categoria";
                                this.tipoAccion = 1;
                                break;
                            }
                            case "actualizar":{
                                this.categoria_id = data.id;
                                this.modal = 1;
                                this.nombre = data.nombre;
                                this.descripcion = data.descripcion;
                                this.tituloModal = "Actualizar Categoria";
                                this.tipoAccion = 2;
                                break;
                            }
                        }
                    }
                }
            },
        },
        mounted() {
        
           this.listarCategoria(1,this.buscar,this.criterio);

        },
    }
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
