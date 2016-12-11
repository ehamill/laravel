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

/*Get home page */
Route::get('/', 'WelcomeController@index');

// TUTORIAL ROUTES
Route::GET('Tutorial/{pageName}', 'TutorialController@index');
Route::GET('Tutorial/create/{pageName}', 'TutorialController@create');
Route::POST('Tutorial/create/{pageName}', 'TutorialController@store');
Route::GET('Tutorial/edit/{id}', 'TutorialController@edit');
Route::POST('Tutorial/edit/{id}', 'TutorialController@update');

Route::DELETE('Tutorial/delete/{id}', 'TutorialController@destroy');
//Route::POST('addCommentToCodeBlog', 'TutorialController@addComment');
//Route::POST('deleteCodeBlogComment/{id}', 'TutorialController@deleteComment');


// CODE BLOG ROUTING//
//Route::resource('codeBlogs', 'CodeBlogController');
/* Resource creates many routes with one simple line of code **
 Verb								URI	Action	Route Name
GET	/codeBlogs						index		codeBlogs.index
GET	/codeBlogs/create				create		codeBlogs.create
POST /codeBlogs						store		codeBlogs.store
GET	/codeBlogs/{codeBlog}			show		codeBlogs.show
GET	/codeBlogs/{codeBlog}/edit		edit		codeBlogs.edit
PUT/PATCH	/codeBlogs/{codeBlog}	update		codeBlogs.update
DELETE	/codeBlogs/{codeBlog}		destroy		codeBlogs.destroy
 * */

//   	SITES ROUTING      //
Route::get('/sites', 'SiteController@index');
Route::get('/CreateSite', 'SiteController@create');
Route::POST('/CreateSite', 'SiteController@store');
Route::POST('addCountry', 'SiteController@addCountry');
Route::POST('editSite/addCountry', 'SiteController@addCountry');
Route::GET('addCountry', 'SiteController@create');
Route::POST('add_new_state', 'SiteController@add_new_state');
Route::POST('editSite/add_new_state', 'SiteController@add_new_state');
Route::GET('add_new_state', 'SiteController@create'); //if addState fails, need this route
Route::POST('add_city', 'SiteController@add_city');
Route::GET('add_city', 'SiteController@create');//if add_city fails, need this route
Route::GET('editSite/{id}', 'SiteController@edit');
Route::POST('EditSite', 'SiteController@update');
Route::GET('deleteSite/{id}', 'SiteController@destroy');
Route::POST('check_site_number', 'SiteController@check_site_number');
Route::POST('filter_sites', 'SiteController@filter_sites');


// CUSTOMER ROUTING  //
Route::get('addCustomer', 'CustomerController@create');
Route::post('addCustomer', 'CustomerController@store');

//      ADMIN BLOG ROUTING!!!!!!!!!! ///
/*Get adminBlog page */
Route::get('/adminBlog', 'AdminBlogController@index');

//Go to create new Admin blog form
Route::get('createAdminBlog', 'AdminBlogController@create');

/*Get admin blog form for editing */
Route::get('adminBlog/{id}', 'AdminBlogController@edit');

//Post to create new Admin blog
Route::post('createAdminBlog', 'AdminBlogController@store');

//Post to update Admin blog
Route::post('updateAdminBlog', 'AdminBlogController@update');

/*Delete admin blogs */
Route::delete('/adminBlog/{adminBlog}', 'AdminBlogController@destroy');


// ITEMS  ROUTING...ITEMS  ITEMS ITEMS ITEMS  ITEMS ITEMS ITEMS ///
//Get various item pages: devices, phones, etc
Route::get('devices', 'ItemController@devices');
Route::get('phones', 'ItemController@phones');
Route::get('ap', 'ItemController@aps');
Route::get('switches', 'ItemController@switches');

//Get Create Item Form
Route::get('create/{type}', 'ItemController@create');

//Create new Admin Blog..form submission
Route::post('createItem', 'ItemController@store');

/*Get an item for editing */
Route::get('item/{id}', 'ItemController@edit');

//update item..form submission..update/device or update/phone
Route::post('updateItem', 'ItemController@update');

/*Delete item */
Route::delete('deleteItem/{id}', 'ItemController@destroy');

/* Search Items */
Route::POST('search', 'SearchController@search');

/*
Route::get('/', 'WelcomeController@index');
 */
Route::get('home', 'HomeController@index');

/* USER PROFILES, get and update */
Route::get('showProfile/', 'ProfileController@show');
Route::get('editProfile/', 'ProfileController@edit');
Route::Post('updateProfile/', 'ProfileController@update');
Route::get('showProfile/adminPage', 'ProfileController@admin');
Route::get('showProfile/pending', 'ProfileController@pending');
Route::POST('showProfile/approve_post', 'ProfileController@approve_post');
Route::POST('showProfile/delete_post', 'ProfileController@delete_post');
Route::get('pendingList', 'ProfileController@pending');
Route::POST('showProfile/change_role', 'ProfileController@change_role');
Route::POST('showProfile/change_password', 'ProfileController@change_password');


Route::controllers([
	'auth' => 'Auth\AuthController', //auth is for login, register, logout, etc
	'password' => 'Auth\PasswordController', //password controller only resets passwords
]);


//AJAX ROUTING
Route::POST('load_site_types', 'AjaxController@load_site_types');
Route::POST('add_site_type', 'AjaxController@add_site_type');
Route::POST('load_states', 'AjaxController@load_states');
Route::POST('load_cities', 'AjaxController@load_cities');
Route::POST('editSite/load_site_types', 'AjaxController@load_site_types');
Route::POST('editSite/load_states', 'AjaxController@load_states');
Route::POST('editSite/add_site_type', 'AjaxController@add_site_type');
Route::POST('load_states2', 'AjaxController@load_states2');