<?php

use mavoc\core\Route;

// Temp
Route::get('/tour1', ['TourController', 'tour1']);

Route::get('/', ['MainController', 'main']);
Route::get('pricing', ['MainController', 'pricing']);
Route::get('terms', ['MainController', 'terms']);
Route::get('privacy', ['MainController', 'privacy']);

Route::get('support', ['ContactController', 'support']);
Route::get('contact', ['ContactController', 'contact']);
Route::post('contact', ['ContactController', 'contactPost']);


// Private
Route::get('home', ['AppController', 'home'], 'private');
Route::get('bookmarks', ['AppController', 'bookmarks'], 'private');
Route::get('notifications', ['AppController', 'notifications'], 'private');
Route::get('todo', ['AppController', 'todo'], 'private');

Route::get('twitter/start', ['TwitterController', 'start'], 'private');
Route::get('twitter/redirect', ['TwitterController', 'redirect'], 'private');

Route::post('ajax/bookmark/add', ['AjaxController', 'bookmarkCreate'], 'private');
Route::post('ajax/interact/add', ['AjaxController', 'interactCreate'], 'private');
Route::post('ajax/todo/add', ['AjaxController', 'todoCreate'], 'private');
Route::post('ajax/like/add', ['AjaxController', 'likeCreate'], 'private');

Route::get('account', ['AuthController', 'account'], 'private');
Route::post('account', ['AuthController', 'accountPost'], 'private');
Route::get('change-password', ['AuthController', 'changePassword'], 'private');
Route::post('change-password', ['AuthController', 'changePasswordPost'], 'private');
Route::post('logout', ['AuthController', 'logout'], 'private');


// Public
Route::get('forgot-password', ['AuthController', 'forgotPassword'], 'public');
Route::post('forgot-password', ['AuthController', 'forgotPasswordPost'], 'public');
Route::get('login', ['AuthController', 'login'], 'public');
Route::post('login', ['AuthController', 'loginPost'], 'public');
Route::post('register', ['AuthController', 'registerPost'], 'public');
Route::get('reset-password', ['AuthController', 'resetPassword'], 'public');
Route::post('reset-password', ['AuthController', 'resetPasswordPost'], 'public');


Route::get('{username}/status/{tweet_id}', ['AppController', 'tweet'], 'private');
Route::get('{username}', ['AppController', 'username'], 'private');
