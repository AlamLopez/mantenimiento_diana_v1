@extends('principal')

@section('contenido')

    <template v-if="menu==0">
        <principal-pantalla></principal-pantalla>
    </template>

    <keep-alive include="inventario-flota">
        <template v-if="menu==1">
            <inventario-flota></inventario-flota>
        </template>
    </keep-alive>

    <keep-alive include="base-datos-rendimiento">
        <template v-if="menu==2">
            <base-datos-rendimiento></base-datos-rendimiento>
        </template>
    </keep-alive>

    <keep-alive include="reporte-mensual">
        <template v-if="menu==3">
            <reporte-mensual></reporte-mensual>
        </template>
    </keep-alive>

    <keep-alive include="reporte-anual">
        <template v-if="menu==4">
            <reporte-anual></reporte-anual>
        </template>
    </keep-alive>

    <keep-alive include="reporte-recorrido">
        <template v-if="menu==5">
            <reporte-recorrido></reporte-recorrido>
        </template>
    </keep-alive>

    <keep-alive include="reporte-recorrido">
        <template v-if="menu==6">
            <reporte-grafico></reporte-grafico>
        </template>
    </keep-alive>

    <keep-alive include="indicadores-globales">
        <template v-if="menu==7">
            <indicadores-globales></indicadores-globales>
        </template>
    </keep-alive>

    <template v-if="menu==8">
        <semaforo-mantenimiento></semaforo-mantenimiento>
    </template>

    <keep-alive include="indicadores-globales">
        <template v-if="menu==9">
            <reporte-presupuestos></reporte-presupuestos>
        </template>
    </keep-alive>

    <template v-if="menu==10">
        <costos-rutinas></costos-rutinas>
    </template>

    <keep-alive include="reporte-cumplimientos">
        <template v-if="menu==11">
            <reporte-cumplimientos></reporte-cumplimientos>
        </template>
    </keep-alive>

    <template v-if="menu==12">
        <h1>Este texto es para el mneu 12</h1>
    </template>

    <template v-if="menu==13">
        <historial-mantenimientos></historial-mantenimientos>
    </template>

    <template v-if="menu==14">
        <historial-historial></historial-historial>
    </template>

    <template v-if="menu==15">
        <h1>Este texto es para el mneu 15</h1>
    </template>

    <template v-if="menu==16">
        <h1>Este texto es para el mneu 16</h1>
    </template>

    <template v-if="menu==17">
        <acceso-users></acceso-users>
    </template>

    <template v-if="menu==18">
        <acceso-roles></acceso-roles>
    </template>

    <template v-if="menu==19">
        <crud-distribuidoras></crud-distribuidoras> 
    </template>

    <template v-if="menu==20">
        <crud-combustibles></crud-combustibles>
    </template>

    <template v-if="menu==21">
        <crud-talleres></crud-talleres> 
    </template>

    <template v-if="menu==22">
        <costos-repuestos></costos-repuestos>
    </template>
    
@endsection