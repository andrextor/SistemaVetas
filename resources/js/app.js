/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('categoria-component', require('./components/Categoria.vue').default);
Vue.component('articulo', require('./components/Articulo.vue').default);
Vue.component('clientes-component', require('./components/Cliente.vue').default);
Vue.component('proveedor-component', require('./components/Proveedor.vue').default);
Vue.component('roles-component', require('./components/Roles.vue').default);
Vue.component('usuario-component', require('./components/Usuario.vue').default);
Vue.component('ingreso-component', require('./components/Ingreso.vue').default);
Vue.component('spinner', require('./components/spinner.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data:{
        menu : 0
    }
});

