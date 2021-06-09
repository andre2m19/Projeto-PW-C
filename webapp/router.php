<?php
/**
 * Created by PhpStorm.
 * User: smendes
 * Date: 02-05-2016
 * Time: 11:18
 */
use ArmoredCore\Facades\Router;

/****************************************************************************
 *  URLEncoder/HTTPRouter Routing Rules
 *  Use convention: controllerName/methodActionName
 ****************************************************************************/

Router::get('/',			'HomeController/index');
Router::get('home/',		'HomeController/index');
Router::get('home/index',	'HomeController/index');
Router::get('home/start',	'HomeController/start');



Router::get('test/index',  'TestController/index');
Router::post('finance/store','FinanceController/store');
Router::post('finance/update','FinanceController/update');
Router::get('finance/create','FinanceController/create');
Router::get('finance/edit','FinanceController/edit');
Router::get('finance/index', 'FinanceController/index');
Router::get('finance/show', 'FinanceController/show');
Router::post('finance/show', 'FinanceController/show');
Router::get('finance/destroy', 'FinanceController/destroy');
Router::post('User/login', 'LoginController');






/************** End of URLEncoder Routing Rules ************************************/