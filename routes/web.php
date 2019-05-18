<?php
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::group(['middleware' => ['guest']], function () {
    Route::get('/','Auth\LoginController@showLoginForm')->name('home');
    Route::post('/login','Auth\LoginController@login')->name('login');    
});

Route::group(['middleware' => ['auth']], function () {

    Route::get('/main', function () { return view('contenido');})->name('main');    
    // Route::get('/home', 'HomeController@index')->name('home');


    Route::group(['middleware' => ['Bodeguero']], function () {
                                /** Categorias*/
        Route::get('/categoria', 'CategoriaController@index')->name('categoria.index');
        Route::post('/categoria/registrar', 'CategoriaController@store')->name('categoria.store');
        Route::put('/categoria/estado', 'CategoriaController@status')->name('categoria.status');
        Route::put('/categoria/actualizar', 'CategoriaController@update')->name('categoria.update');
        Route::get('/categoria/select', 'CategoriaController@selectCategoria')->name('categoria.select');
        // Route::delete('/categoria/eliminar/{id}', 'CategoriaController@destroy')->name('categoria.destroy');

                                    /** Articulos*/
        Route::get('/articulo', 'ArticuloController@index')->name('articulo.index');
        Route::get('/articulo/listar', 'ArticuloController@listarArticulos')->name('articulo.listar');
        Route::get('/articulo/buscar', 'ArticuloController@bucarArticulo')->name('articulo.buscar');
        Route::post('/articulo/registrar', 'ArticuloController@store')->name('articulo.store');
        Route::put('/articulo/estado', 'ArticuloController@status')->name('articulo.status');
        Route::put('/articulo/actualizar', 'ArticuloController@update')->name('articulo.update');

                                      /** Proveedor*/
        Route::get('/proveedor', 'ProveedorController@index')->name('proveedor.index');
        Route::get('/proveedor/select', 'ProveedorController@selectProveedor')->name('proveedor.select');
        Route::post('/proveedor/registrar', 'ProveedorController@store')->name('proveedor.store');
        Route::put('/proveedor/actualizar', 'ProveedorController@update')->name('proveedor.status');
                                          /** Ingreso*/
        Route::get('/ingreso', 'IngresoController@index')->name('ingreso.index');
        Route::post('/ingreso/registrar', 'IngresoController@store')->name('ingreso.store');
        Route::put('/ingreso/anular', 'IngresoController@anular')->name('ingreso.anular');   
        Route::get('/ingreso/cabecera', 'IngresoController@obtenerCabecera')->name('ingreso.cabecera');
        Route::get('/ingreso/detalle', 'IngresoController@obtenerDetalle')->name('ingreso.detalle');
    
    });

    Route::group(['middleware' => ['Vendedor']], function () {
                            /** Clientes*/
        Route::get('/cliente', 'ClienteController@index')->name('cliente.index');
        Route::post('/cliente/registrar', 'ClienteController@store')->name('cliente.store');
        Route::put('/cliente/actualizar', 'ClienteController@update')->name('cliente.status');
                              
    });

    
    Route::group(['middleware' => ['Administrador']], function () {

        /** Categorias*/
        Route::get('/categoria', 'CategoriaController@index')->name('categoria.index');
        Route::post('/categoria/registrar', 'CategoriaController@store')->name('categoria.store');
        Route::put('/categoria/estado', 'CategoriaController@status')->name('categoria.status');
        Route::put('/categoria/actualizar', 'CategoriaController@update')->name('categoria.update');
        Route::get('/categoria/select', 'CategoriaController@selectCategoria')->name('categoria.select');
        // Route::delete('/categoria/eliminar/{id}', 'CategoriaController@destroy')->name('categoria.destroy');

                                    /** Articulos*/
        Route::get('/articulo', 'ArticuloController@index')->name('articulo.index');
        Route::get('/articulo/buscar', 'ArticuloController@bucarArticulo')->name('articulo.buscar');
        Route::get('/articulo/listar', 'ArticuloController@listarArticulos')->name('articulo.listar');
        Route::post('/articulo/registrar', 'ArticuloController@store')->name('articulo.store');
        Route::put('/articulo/estado', 'ArticuloController@status')->name('articulo.status');
        Route::put('/articulo/actualizar', 'ArticuloController@update')->name('articulo.update');

                                    /** Proveedor*/
        Route::get('/proveedor', 'ProveedorController@index')->name('proveedor.index');
        Route::get('/proveedor/select', 'ProveedorController@selectProveedor')->name('proveedor.select');
        Route::post('/proveedor/registrar', 'ProveedorController@store')->name('proveedor.store');
        Route::put('/proveedor/actualizar', 'ProveedorController@update')->name('proveedor.status');

                                    /** Clientes*/
        Route::get('/cliente', 'ClienteController@index')->name('cliente.index');
        Route::post('/cliente/registrar', 'ClienteController@store')->name('cliente.store');
        Route::put('/cliente/actualizar', 'ClienteController@update')->name('cliente.status');

                                        /** Roles*/
        Route::get('/rol', 'RolController@index')->name('rol.index');
        Route::post('/rol/registrar', 'RolController@store')->name('rol.store');
        Route::put('/rol/estado', 'RolController@status')->name('rol.status');
        Route::put('/rol/actualizar', 'RolController@update')->name('rol.status');

                                    /** Users*/
        Route::get('/user', 'UserController@index')->name('user.index');
        Route::post('/user/registrar', 'UserController@store')->name('user.store');
        Route::put('/user/estado', 'UserController@status')->name('user.status');
        Route::put('/user/actualizar', 'UserController@update')->name('user.status');
        Route::get('/user/select', 'UserController@selectRol')->name('user.select');     

                                      /** Ingreso*/
        Route::get('/ingreso', 'IngresoController@index')->name('ingreso.index');
        Route::post('/ingreso/registrar', 'IngresoController@store')->name('ingreso.store');
        Route::put('/ingreso/anular', 'IngresoController@anular')->name('ingreso.anular');   
        Route::get('/ingreso/cabecera', 'IngresoController@obtenerCabecera')->name('ingreso.cabecera');
        Route::get('/ingreso/detalle', 'IngresoController@obtenerDetalle')->name('ingreso.detalle');
    });
});


  




