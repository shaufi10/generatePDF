<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->group(['prefix' => 'api/v1','namespace' => 'App\Http\Controllers'], function($app)
{
	// USER related API
    $app->get('user','UserController@getUser');

	  $app->post('user/signup','UserController@signup');

    $app->post('user/login','UserController@login');

    $app->post('user/loginfb','UserController@loginFb');
    $app->post('user/loginfb','UserController@loginFb');

    $app->post('user/sendfcmtest','UserController@send_notification');

    $app->post('user/token','UserController@saveToken');

    $app->post('user/deletetoken','UserController@deleteToken');

    $app->get('user/sendfcmalldevice/{userid}/{jobid}','UserController@sendfcmtoalldevice');

    $app->post('user/forgot','UserController@forgot');
    $app->post('user/changepass/{token}','UserController@change_password');
    $app->get('user/profile/{userid}','UserController@getProfile');

    $app->post('user/profile','UserController@updateProfile');

    $app->get('user/applied/{userid}/{offsetid}/{num}','JobController@getAppliedJobs');

	// JOB MATCH related API
    $app->get('match/{userid}/{startid}/{num}','JobController@getJobMatches');
    $app->get('match/{userid}/{startid}/{num}/{lat}/{lon}','JobController@getJobMatchesLatLon');
    $app->get('match/{userid}/{startid}/{num}/list','JobController@getJobMatchesList');


    $app->get('matchtest1','JobController@getJobMatchesDummy');

	  $app->get('matchtest2/{startid}/{limit}','JobController@getJobMatchesDummy2');
    $app->get('match/{userid}/{startid}/{num}/list','JobController@getJobMatchesList');

	// JOB MATCH related API, for Development purposes
    $app->get('devmatch/{userid}/{startid}/{num}','JobController@getJobMatchesDev');
    $app->get('devmatch/{userid}/{startid}/{num}/{lat}/{lon}','JobController@getJobMatchesLatLonDev');

	// JOB related API
    $app->post('job/apply','JobController@applyJob');

    $app->post('job/reject','JobController@rejectJob');

    $app->get('job/{jobid}','JobController@getJobDetails');

    $app->get('job/{jobid}/{lat}/{lon}','JobController@getJobDetailsLatLon');
    $app->post('job/share','JobController@shareJob');

	// JOB related API, for Development purposes
    $app->get('devjob/{jobid}','JobController@getJobDetailsDev');

    $app->get('devjob/{jobid}/{lat}/{lon}','JobController@getJobDetailsLatLonDev');

    $app->post('devjob/applytoken','JobController@tokenHandler');

    $app->get('devjob/internaljob/{userid}','JobController@getJobInternal');

	// CHAT related API
    $app->get('chat/group/{userid}/{start}/{limit}','ChatController@getCaller');

	  $app->get('chat/detail/{job_application_id}/{last_ts}/{limit}', 'ChatController@getChat');

    $app->post('chat','ChatController@postChat');

	// BOOK
    $app->get('book','BookController@index');

    $app->get('book/{id}','BookController@getbook');

    $app->post('book','BookController@createBook');

    $app->put('book/{id}','BookController@updateBook');

    $app->delete('book/{id}','BookController@deleteBook');

	  // REGISTRATION DATA
    $app -> get('init/city', 'RegistrationController@getCities');
    $app -> get('init/city/{province_id}', 'RegistrationController@getCitiesOfProvince');
    $app -> get('init/province', 'RegistrationController@getProvinces');
    $app -> get('init/salary', 'RegistrationController@getSalaryRange');
	  $app -> get('init/education', 'RegistrationController@getEducation');
    $app -> get('init/experience', 'RegistrationController@getExperience');
    $app -> get('init/empstatus', 'RegistrationController@getEmpStatus');
    $app -> get('init/interest', 'RegistrationController@getInterests');
	  $app -> get('init/employmenttype', 'RegistrationController@getEmploymentTypes');
    $app -> get('init/employmentsector', 'RegistrationController@getEmploymentSectors');
    $app -> get('init/skill', 'RegistrationController@getSkills');
    $app->get('init/jurusan', 'RegistrationController@getJurusan');
    $app->get('init/kompetensi', 'RegistrationController@getKompetensi');

    // USER Photos, Certificates, Documents
	  $app->post('user/uploadphoto','UserController@uploadPhoto');
	  $app->post('user/uploaddoc','UserController@uploadDoc');
    $app->get('user/docs/{userid}','UserController@getDocs');
    $app->post('user/deletedoc','UserController@deleteDoc');

	  // ADS
    $app -> get('ads/list/{userid}/{num}', 'AdController@getAds');

    // Upload Image
	  $app->post('uploadya','ExampleController@upload');
    });

    $app->get('/', function() use ($app) {
    return "This is Lumen RESTful API By CoderExample (https://coderexample.com)";
});
