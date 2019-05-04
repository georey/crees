<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
Route::get('testconnection', 'WelcomeController@testConnection');

Route::group(['middleware' => 'auth'], function() {
	Route::get('/', 'HomeController@index');

	Route::controller('reportes', 'reportes\prestamosController');

	Route::post('clientes/anular_prestamo', 'principal\clienteController@anularPrestamo');
	Route::post('clientes/actualizar_garantia', 'principal\clienteController@actualizarGarantia');	
	Route::get('clientes/verificarDui', 'principal\clienteController@verificarDui');
	Route::get('clientes/getGastos', 'principal\clienteController@getGastos');
	Route::get('clientes/historial/{id}', 'principal\clienteController@getHistorial');
	Route::get('clientes/pdf_pagare_sin_protesto/{id}', 'principal\clienteController@pdfPagareSinProtesto');
	Route::get('clientes/pdf_hoja_liquidacion/{id}', 'principal\clienteController@pdfHojaLiquidacion');
	Route::get('clientes/garantias/{id}', 'principal\clienteController@getGarantias');
	Route::get('clientes/ficha/{id}', 'principal\clienteController@pdfFicha');
	Route::get('clientes/getMunicipios', 'principal\clienteController@getMunicipios');
	Route::get('clientes/datatable', 'principal\clienteController@getDataTable');
	Route::post('clientes/negocioSave', 'principal\clienteController@negocioSave');
	Route::get('clientes/negocioDelete/{id}', 'principal\clienteController@negocioDelete');
	Route::get('clientes/negocios/{id}', 'principal\clienteController@getNegocio');
	Route::post('clientes/prestamoSave', 'principal\clienteController@prestamoSave');
	Route::get('clientes/prestamos/{id}', 'principal\clienteController@getPrestamo');
    Route::get('clientes/restore/{id}', 'principal\clienteController@restore');
    Route::resource('clientes', 'principal\clienteController');
	Route::get('clientes/delete/{id}', [
	    'as' => 'clientes.delete',
	    'uses' => 'principal\clienteController@destroy',
	]);

	Route::get('cumpleaneros/{mes?}', 'principal\clienteController@cumpleaneros');


	Route::get('caja/corte_caja', 'principal\variosController@corteCaja');
	Route::get('cobros/colectas_saldos', 'principal\variosController@getColectasSaldos');
	Route::post('cobros/colectas_saldos', 'principal\variosController@postColectasSaldos');
	Route::get('cobros/cartera_asesor', 'principal\variosController@getCarteraAsesor');
	Route::post('cobros/cartera_asesor', 'principal\variosController@postCarteraAsesor');



	Route::get('pagos/datatable', 'principal\pagoController@getDataTable');	
	Route::get('pagos/calculadora', [
	    'as' => 'pagos.calculadora',
	    'uses' => 'principal\pagoController@getCalculadora',
	]);
	Route::get('pagos/historial/{id}', [
	    'as' => 'pagos.historial',
	    'uses' => 'principal\pagoController@getHistorial',
	]);
	Route::get('pagos/revertir/{prestamo_id}/{pago_id}', [
	    'as' => 'pagos.revertir',
	    'uses' => 'principal\pagoController@getRevertir',
	]);
    Route::resource('pagos', 'principal\pagoController');
	Route::get('pagos/delete/{id}', [
	    'as' => 'pagos.delete',
	    'uses' => 'principal\pagoController@destroy',
	]);
	Route::get('pagos/pdf_nota_cobro/{id}', 'principal\pagoController@pdfNotaCobro');
	Route::get('pagos/pdf_recibo/{id}', [
	    'as' => 'pagos.recibo',
	    'uses' => 'principal\pagoController@pdfRecibo',
	]);

	Route::get('acuerdos_pagos/datatable', 'principal\acuerdoController@getDataTable');
    Route::get('acuerdos_pagos/restore/{id}', 'principal\acuerdoController@restore');
    Route::resource('acuerdos_pagos', 'principal\acuerdoController');
	Route::get('acuerdos_pagos/delete/{id}', [
	    'as' => 'acuerdos_pagos.delete',
	    'uses' => 'principal\acuerdoController@destroy',
	]);

	Route::get('asesores/datatable', 'catalogos\asesorController@getDataTable');
    Route::get('asesores/restore/{id}', 'catalogos\asesorController@restore');
    Route::resource('asesores', 'catalogos\asesorController');
	Route::get('asesores/delete/{id}', [
	    'as' => 'asesores.delete',
	    'uses' => 'catalogos\asesorController@destroy',
	]);

	Route::get('cobradores/datatable', 'catalogos\cobradorController@getDataTable');
    Route::get('cobradores/restore/{id}', 'catalogos\cobradorController@restore');
    Route::resource('cobradores', 'catalogos\cobradorController');
	Route::get('cobradores/delete/{id}', [
	    'as' => 'cobradores.delete',
	    'uses' => 'catalogos\cobradorController@destroy',
	]);

	Route::get('linea/datatable', 'catalogos\lineaController@getDataTable');
    Route::get('linea/restore/{id}', 'catalogos\lineaController@restore');
    Route::resource('linea', 'catalogos\lineaController');
	Route::get('linea/delete/{id}', [
	    'as' => 'linea.delete',
	    'uses' => 'catalogos\lineaController@destroy',
	]);

	Route::get('profesiones/datatable', 'catalogos\profesionController@getDataTable');
    Route::get('profesiones/restore/{id}', 'catalogos\profesionController@restore');
    Route::resource('profesiones', 'catalogos\profesionController');
	Route::get('profesiones/delete/{id}', [
	    'as' => 'profesiones.delete',
	    'uses' => 'catalogos\profesionController@destroy',
	]);

	Route::get('zonas/datatable', 'catalogos\zonaController@getDataTable');
    Route::get('zonas/restore/{id}', 'catalogos\zonaController@restore');
    Route::resource('zonas', 'catalogos\zonaController');
	Route::get('zonas/delete/{id}', [
	    'as' => 'zonas.delete',
	    'uses' => 'catalogos\zonaController@destroy',
	]);

	Route::get('estados/datatable', 'catalogos\estado_civilController@getDataTable');
    Route::get('estados/restore/{id}', 'catalogos\estado_civilController@restore');
    Route::resource('estados', 'catalogos\estado_civilController');
	Route::get('estados/delete/{id}', [
	    'as' => 'estados.delete',
	    'uses' => 'catalogos\estado_civilController@destroy',
	]);

	Route::get('tipo_negocio/datatable', 'catalogos\tipo_negocioController@getDataTable');
    Route::get('tipo_negocio/restore/{id}', 'catalogos\tipo_negocioController@restore');
    Route::resource('tipo_negocio', 'catalogos\tipo_negocioController');
	Route::get('tipo_negocio/delete/{id}', [
	    'as' => 'tipo_negocio.delete',
	    'uses' => 'catalogos\tipo_negocioController@destroy',
	]);

	Route::get('tipo_gasto/datatable', 'catalogos\tipo_gastoController@getDataTable');
    Route::get('tipo_gasto/restore/{id}', 'catalogos\tipo_gastoController@restore');
    Route::resource('tipo_gasto', 'catalogos\tipo_gastoController');
	Route::get('tipo_gasto/delete/{id}', [
	    'as' => 'tipo_gasto.delete',
	    'uses' => 'catalogos\tipo_gastoController@destroy',
	]);

	Route::get('usuarios/datatable', 'sistema\usuariosController@getDataTable');
    Route::get('usuarios/restore/{id}', 'sistema\usuariosController@restore');
    Route::resource('usuarios', 'sistema\usuariosController');
	Route::get('usuarios/delete/{id}', [
	    'as' => 'usuarios.delete',
	    'uses' => 'sistema\usuariosController@destroy',
	]);

	Route::get('roles/datatable', 'sistema\rolesController@getDataTable');
    Route::get('roles/restore/{id}', 'sistema\rolesController@restore');
    Route::resource('roles', 'sistema\rolesController');
	Route::get('roles/delete/{id}', [
	    'as' => 'roles.delete',
	    'uses' => 'sistema\rolesController@destroy',
	]);
	Route::get('roles/permisosxrol/{id}', 'sistema\rolesController@permisosxrol');
	Route::post('roles/setPermisosRol', 'sistema\rolesController@setPermisosRol');	

	Route::get('permisos/datatable', 'sistema\permisosController@getDataTable');
    Route::get('permisos/restore/{id}', 'sistema\permisosController@restore');
    Route::resource('permisos', 'sistema\permisosController');
	Route::get('permisos/delete/{id}', [
	    'as' => 'permisos.delete',
	    'uses' => 'sistema\permisosController@destroy',
	]);	

	Route::get('configuracion/prestamos', 'configuracion\prestamosController@getIndex');
	Route::post('configuracion/prestamos', 'configuracion\prestamosController@postIndex');

	Route::get('configuracion/perfil', 'configuracion\perfilController@getIndex');
	Route::post('configuracion/perfil', 'configuracion\perfilController@postIndex');
});