@extends('layouts.layouts')

@section('content')

   @if(Auth::check())
      @if(Auth::user()->rol_id ==1)

         <template  v-if="menu==0">
               <h1>Escritorio</h1>
         </template>
         <template v-if="menu==1" >  
               <categoria-component ></categoria-component>
         </template>
         <template v-if="menu==2">
            <articulo></articulo>
         </template>
         <template v-if="menu==3">
            <ingreso-component></ingreso-component>
         </template>
         <template v-if="menu==4">
            <proveedor-component></proveedor-component>
         </template>
         <template v-if="menu==5">
            <h1>Ventas</h1>
         </template>
         <template v-if="menu==6">
               <clientes-component></clientes-component>
         </template>
         <template v-if="menu==7">
            <usuario-component></usuario-component>
         </template>
         <template v-if="menu==8">
            <roles-component></roles-component>
         </template>
         <template v-if="menu==9">
            <h1>Reporte Ingreso </h1>
         </template>
         <template v-if="menu==10">
            <h1>Reporte venta </h1>
         </template>
         <template v-if="menu==11">
            <h1>Ayuda</h1>
         </template>
         <template v-if="menu==12">
            <h1>Acerca de</h1>
         </template> 

      @elseif(Auth::user()->rol_id ==2)
      <template  v-if="menu==0">
            <h1>Escritorio</h1>
      </template>
         <template v-if="menu==5">
            <h1>Ventas</h1>
         </template>
         <template v-if="menu==6">
               <clientes-component></clientes-component>
         </template>
         <template v-if="menu==10">
            <h1>Reporte venta </h1>
         </template>
         <template v-if="menu==11">
            <h1>Ayuda</h1>
         </template>
         <template v-if="menu==12">
            <h1>Acerca de</h1>
         </template> 

      @elseif(Auth::user()->rol_id ==3)
         <template  v-if="menu==0">
               <h1>Escritorioaaaaaaaaaaa</h1>
         </template>
         <template v-if="menu==1" >  
               <categoria-component ></categoria-component>
         </template>
         <template v-if="menu==2">
            <articulo></articulo>
         </template>
         <template v-if="menu==3">
            <ingreso-component></ingreso-component>
         </template>
         <template v-if="menu==4">
            <proveedor-component></proveedor-component>
         </template>
         <template v-if="menu==11">
               <h1>Ayuda</h1>
            </template>
            <template v-if="menu==12">
               <h1>Acerca de</h1>
            </template> 

      @endif
   @endif
   

@endsection
