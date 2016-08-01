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
Route::get('/', [
    'uses' => 'UserController@getLogin',
    'middleware' => 'guest',
    'as' => 'login'
]);

Route::get('/login', [
    'uses' => 'UserController@getLogin',
    'middleware' => 'guest',
    'as' => 'login'
]);

Route::post('/login', [
    'uses' => 'UserController@postLogin',
    'middleware' => 'guest',
    'as' => 'login'
]);

Route::get('/password/email', [
    'uses' => 'Auth\PasswordController@getEmail',
    'as' => 'auth.password'
]);

Route::post('/password/email', [
    'uses' => 'Auth\PasswordController@postEmail',
    'as' => 'auth.password'
]);

Route::get('/password/reset/{token}', [
    'uses' => 'Auth\PasswordController@getReset',
    'as' => 'auth.reset'
]);

Route::post('/password/reset', [
    'uses' => 'Auth\PasswordController@postReset',
    'as' => 'auth.reset'
]);

Route::get('/logout', [
    'uses' => 'UserController@getLogout',
    'middleware' => 'auth',
    'as' => 'logout'
]);

Route::get('/admin/home', [
    'uses' => 'AdminController@getAdminHome',
    'middleware' => 'roles',
    'roles' => '1',
    'as' => 'admin.home'
]);

Route::post('/admin/changerole', [
    'uses' => 'AdminController@changeRole',
    'middleware' => 'roles',
    'roles' => '1',
    'as' => 'admin.changerole'
]);

Route::get('/initiator/home', [
    'uses' => 'InitiatorController@getInitiatorHome',
    'middleware' => 'roles',
    'roles' => '2',
    'as' => 'initiator.home'
]);

Route::get('/voter/home', [
    'uses' => 'VoterController@getVoterHome',
    'middleware' => 'roles',
    'roles' => '3',
    'as' => 'voter.home'
]);

Route::get('/register', [
    'uses' => 'UserController@getRegister',
    'middleware' => 'guest',
    'as' => 'register'
]);

Route::post('/register', [
    'uses' => 'UserController@postRegister',
    'middleware' => 'guest',
    'as' => 'register'
]);

Route::get('/register/verify/{confirmation_code}', [
    'uses' => 'UserController@verifyRegister',
    'as' => 'auth.verify'
]);

Route::get('/error', function() {
    return view('errors.error');
})->name('error');

Route::get('/admin/users', [
    'uses' => 'AdminController@getUsers',
    'middleware' => 'roles',
    'roles' => '1',
    'as' => 'admin.users'
]);

Route::get('/admin/changepassword', [
    'uses' => 'AdminController@getChangePassword',
    'middleware' => 'roles',
    'roles' => '1',
    'as' => 'admin.changepassword'
]);

Route::post('/admin/changepassword', [
    'uses' => 'AdminController@postChangePassword',
    'middleware' => 'roles',
    'roles' => '1',
    'as' => 'admin.changepassword'
]);

Route::post('/admin/changeactive', [
    'uses' => 'AdminController@changeActive',
    'middleware' => 'roles',
    'roles' => '1',
    'as' => 'admin.changeactive'
]);

Route::get('/admin/getpostojeci', [
    'uses' => 'AdminController@getPostojeci',
    'middleware' => 'roles',
    'roles' => '1',
    'as' => 'admin.getpostojeci'
]);

Route::get('/admin/getneodobreni', [
    'uses' => 'AdminController@getNeodobreni',
    'middleware' => 'roles',
    'roles' => '1',
    'as' => 'admin.getneodobreni'
]);

Route::post('/admin/adduser', [
    'uses' => 'AdminController@addUser',
    'middleware' => 'roles',
    'roles' => '1',
    'as' => 'admin.adduser'
]);

Route::get('/initiator/changepassword', [
    'uses' => 'InitiatorController@getChangePassword',
    'middleware' => 'roles',
    'roles' => '2',
    'as' => 'initiator.changepassword'
]);

Route::post('/initiator/changepassword', [
    'uses' => 'InitiatorController@postChangePassword',
    'middleware' => 'roles',
    'roles' => '2',
    'as' => 'initiator.changepassword'
]);

Route::get('/initiator/votinginfo/{voting_id}', [
    'uses' => 'InitiatorController@getVotingInfo',
    'middleware' => 'roles',
    'roles' => '2',
    'as' => 'initiator.votinginfo'
]);

Route::post('/initiator/createreviewedvoting', [
    'uses' => 'InitiatorController@createReviewedVoting',
    'middleware' => 'roles',
    'roles' => '2',
    'as' => 'initiator.createreviewedvoting'
]);

Route::post('/initiator/accessvote', [
    'uses' => 'InitiatorController@getAccessVote',
    'middleware' => 'roles',
    'roles' => '2',
    'as' => 'initiator.accessvote'
]);

Route::post('/initiator/deletevoting', [
    'uses' => 'InitiatorController@deleteVoting',
    'middleware' => 'roles',
    'roles' => '2',
    'as' => 'initiator.deletevoting'
]);

Route::get('/initiator/vote/{ticket_id}', [
    'uses' => 'InitiatorController@getVote',
    'middleware' => 'roles',
    'roles' => '2',
    'as' => 'initiator.vote'
]);

Route::post('/initiator/vote', [
    'uses' => 'InitiatorController@postVote',
    'middleware' => 'roles',
    'roles' => '2',
    'as' => 'initiator.vote'
]);

Route::get('/voter/changepassword', [
    'uses' => 'VoterController@getChangePassword',
    'middleware' => 'roles',
    'roles' => '3',
    'as' => 'voter.changepassword'
]);

Route::post('/voter/changepassword', [
    'uses' => 'VoterController@postChangePassword',
    'middleware' => 'roles',
    'roles' => '3',
    'as' => 'voter.changepassword'
]);

Route::post('/voter/accessvote', [
    'uses' => 'VoterController@getAccessVote',
    'middleware' => 'roles',
    'roles' => '3',
    'as' => 'voter.accessvote'
]);

Route::get('/voter/votinginfo/{voting_id}', [
    'uses' => 'VoterController@getVotingInfo',
    'middleware' => 'roles',
    'roles' => '3',
    'as' => 'voter.votinginfo'
]);

Route::get('/voter/vote/{ticket_id}', [
    'uses' => 'VoterController@getVote',
    'middleware' => 'roles',
    'roles' => '3',
    'as' => 'voter.vote'
]);

Route::post('/voter/vote', [
    'uses' => 'VoterController@postVote',
    'middleware' => 'roles',
    'roles' => '3',
    'as' => 'voter.vote'
]);

Route::get('/initiator/createvoting', [
    'uses' => 'InitiatorController@getCreateVoting',
    'middleware' => 'roles',
    'roles' => '2',
    'as' => 'initiator.createvoting'
]);

Route::post('/initiator/createvoting', [
    'uses' => 'InitiatorController@createVoting',
    'middleware' => 'roles',
    'roles' => '2',
    'as' => 'initiator.createvoting'
]);

Route::get('/initiator/votings', [
    'uses' => 'InitiatorController@getVotings',
    'middleware' => 'roles',
    'roles' => '2',
    'as' => 'initiator.votings'
]);

Route::get('/initiator/getzvanja', [
    'uses' => 'InitiatorController@getZvanja',
    'middleware' => 'roles',
    'roles' => '2',
    'as' => 'initiator.getzvanja'
]);

Route::get('/initiator/getkorisnici', [
    'uses' => 'InitiatorController@getKorisnici',
    'middleware' => 'roles',
    'roles' => '2',
    'as' => 'initiator.getkorisnici'
]);

Route::get('/initiator/getkorisnicizk', [
    'uses' => 'InitiatorController@getKorisniciPoZK',
    'middleware' => 'roles',
    'roles' => '2',
    'as' => 'initiator.getkorisnicizk'
]);

Route::get('/initiator/getkorisniciz', [
    'uses' => 'InitiatorController@getKorisniciPoZ',
    'middleware' => 'roles',
    'roles' => '2',
    'as' => 'initiator.getkorisniciz'
]);

Route::get('/initiator/getkorisnicik', [
    'uses' => 'InitiatorController@getKorisniciPoK',
    'middleware' => 'roles',
    'roles' => '2',
    'as' => 'initiator.getkorisnicik'
]);