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

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('principal-pantalla', require('./components/acceso/Principal.vue').default);
Vue.component('crud-distribuidoras', require('./components/cruds/CrudDistribuidoras.vue').default);
Vue.component('crud-combustibles', require('./components/cruds/CrudCombustibles.vue').default);
Vue.component('crud-talleres', require('./components/cruds/CrudTalleres.vue').default);
Vue.component('acceso-roles', require('./components/acceso/AccesoRoles.vue').default);
Vue.component('acceso-users', require('./components/acceso/AccesoUsers.vue').default);
Vue.component('historial-historial', require('./components/historial/Historial.vue').default);
Vue.component('inventario-flota', require('./components/rendimiento-region/InventarioFlota.vue').default);
Vue.component('base-datos-rendimiento', require('./components/rendimiento-region/BaseDatosRendimiento.vue').default);
Vue.component('semaforo-mantenimiento', require('./components/mantenimiento-preventivo/SemaforoMantenimiento.vue').default);
Vue.component('reporte-mensual', require('./components/reportes/ReporteMensual.vue').default);
Vue.component('reporte-anual', require('./components/reportes/ReporteAnual.vue').default);
Vue.component('reporte-recorrido', require('./components/reportes/ReporteRecorrido.vue').default);
Vue.component('reporte-grafico', require('./components/ReporteGrafico.vue').default);
Vue.component('indicadores-globales', require('./components/IndicadoresGlobales.vue').default);
Vue.component('reporte-cumplimientos', require('./components/reportes/ReporteCumplimientos.vue').default);
Vue.component('historial-mantenimientos', require('./components/historial/HistorialMantenimientos.vue').default);
Vue.component('costos-mantenimientos', require('./components/mantenimiento-preventivo/Costos.vue').default);
Vue.component('costos-admin', require('./components/mantenimiento-preventivo/CostosAdmin.vue').default);
Vue.component('costos-rutinas', require('./components/mantenimiento-preventivo/CostoRutinas.vue').default);
Vue.component('costos-repuestos', require('./components/mantenimiento-preventivo/CostoRepuestos.vue').default);
Vue.component('reporte-presupuestos', require('./components/reportes/Presupuesto.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data: {
        menu : 0
    }
});
