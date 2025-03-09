<?php

$router->get('/receiptdetail/(.*)','App\Controllers\CobrosReportController@getReceiptDetail');

$router->get('/reports/cobros','App\Controllers\ReportsController@cobros');

$router->get('/reports/cuentacobrar','App\Controllers\ReportsController@cuentacobrar');

$router->get('/reports/devoluciones','App\Controllers\ReportsController@devoluciones');

$router->get('/reports/horastrabajadas','App\Controllers\ReportsController@horasTrabajadas');

$router->post('/reports/horastrabajadas','App\Controllers\SellersReportsController@getReport');
$router->post('/getreport/hourlywork', 'App\Controllers\SellersReportsController@getDataReport');

$router->post('/reports/cuentacobrar','App\Controllers\CuentaCobrarReportController@getReport');

$router->get('/reports/depositos','App\Controllers\ReportsController@depositos');

$router->get('/orderdetail/(.*)','App\Controllers\VentasReportController@getOrderDetail');

$router->get('/returndetail/(.*)','App\Controllers\DevolucionesReportController@getDevolucionDetail');

$router->get('/reports/cobrosadelanto','App\Controllers\ReportsController@cobrosadelanto');

$router->post('/reports/cobros','App\Controllers\CobrosReportController@getReport');

$router->post('/reports/cobrosadelanto','App\Controllers\CobrosAdelantoReportController@getReport');

$router->get('/reports/ventas','App\Controllers\ReportsController@ventas');

$router->get('/reports/visitas','App\Controllers\ReportsController@visitas');
$router->get('/reports/visitas/no/ventas', 'App\Controllers\ReportsController@visitNoVentas');


$router->post('/reports/visitas','App\Controllers\EffectivenessReportController@visitasEfectivas');
$router->post('/reports/visitas/no/ventas', 'App\Controllers\EffectivenessReportController@getNoVentas');

$router->get('/reports/ventasduracion','App\Controllers\ReportsController@ventasDuracion');

$router->post('/reports/ventas','App\Controllers\VentasReportController@getSalesReport');
$router->post('/reports/ventas/change/status','App\Controllers\VentasReportController@changeStatusSalesReport');

$router->post('/reports/ventasduracion','App\Controllers\VentasReportController@SalesWithDurationAndAmount');

$router->post('/reports/depositos','App\Controllers\DepositosReportController@getReport');

$router->post('/reports/devoluciones','App\Controllers\DevolucionesReportController@getReport');

$router->get('/reports/saleswithduration','App\Controllers\SalesReportController@SalesWithDurationAndAmount');

$router->get('/reports/products','App\Controllers\ProductController@products');
$router->post('/reports/ventas/productos', 'App\Controllers\ProductController@getReport');


$router->get('/reports/activity','App\Controllers\ActivityController@index');

$router->post('/reports/activity/count', 'App\Controllers\ActivityController@getCountActivity');
$router->post('/reports/activity/sellers', 'App\Controllers\ActivityController@getSellersActivity');
$router->post('/reports/activity/detailed', 'App\Controllers\ActivityController@getActivityData');

$router->get('/reports/facturas/rancheras', 'App\Controllers\FacturasController@index');
$router->post('/reports/facturas/rancheras', 'App\Controllers\FacturasController@getReport');
$router->get('/reports/activity/data', 'App\Controllers\ActivityController@ActivityReportData');
$router->get('/reports/activity/print','App\Controllers\ActivityController@indexActivity');





