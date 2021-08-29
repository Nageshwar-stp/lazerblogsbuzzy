<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Admin
Route::middleware('Admin')->namespace('Admin')->prefix('admin')->group(
    function () {

        Route::get('/', 'DashboardController@index');
        Route::get('/reports/{type}', 'ReportsController@index');

        Route::post('/handle-download', 'UpdateController@handle');

        Route::get('plugins', 'PluginsController@show');
        Route::post('activate-plugin', 'PluginsController@handleActivation');

        Route::get('themes/{theme}', 'ThemesController@settings');
        Route::get('themes', 'ThemesController@show');
        Route::post('activate-theme', 'ThemesController@handleActivation');

        Route::post('add-new-category', 'CategoriesController@addnew');
        Route::get('categories/delete/{id}', 'CategoriesController@delete');
        Route::get('categories', 'CategoriesController@index');
        Route::get('test-mail-config', 'ConfigController@check_mail');
        Route::get('config', 'ConfigController@index');
        Route::post('config', 'ConfigController@setconfig');

        Route::get('/tools', 'ToolsController@index');
        Route::get('/removeTmpFolder', 'ToolsController@removeTmpFolder');

        Route::get('post-approve', 'PostsController@approvePost');
        Route::get('post-send-to-trash', 'PostsController@deletePost');
        Route::get('post-delete-perma', 'PostsController@forceDeletePost');
        Route::get('post-set-for-homepage', 'PostsController@setForHomepage');
        Route::get('post-set-featured', 'PostsController@setFeatured');

        Route::get('features', 'PostsController@features');
        Route::get('unapprove', 'PostsController@unapprove');
        Route::get('all', 'PostsController@all');
        Route::get('/cat/{name}', 'PostsController@showcatposts');
        Route::get('postlist', 'PostsController@getdata');

        Route::get('users', 'UsersController@users');
        Route::get('userlist', 'UsersController@getdata');

        Route::post('pages/addnew', 'PagesController@addnew');

        Route::get('pages/edit/{id}', 'PagesController@edit');
        Route::get('pages/delete/{id}', 'PagesController@delete');
        Route::get('pages/add', 'PagesController@add');
        Route::get('pages', 'PagesController@index');


        Route::post('widgets/addwidget', 'WidgetsController@addnew');
        Route::get('widgets/delete/{id}', 'WidgetsController@delete');
        Route::get('widgets', 'WidgetsController@index');

        Route::post('reactions/addnew', 'ReactionController@addnew');
        Route::get('reactions/delete/{id}', 'ReactionController@delete');
        Route::get('reactions', 'ReactionController@index');

        Route::prefix('mailbox')->group(
            function () {
                Route::post('getmails', 'ContactController@getdata');
                Route::post('newmailsent', 'ContactController@newmailsent');
                Route::post('doaction', 'ContactController@doaction');
                Route::post('dostar', 'ContactController@dostar');
                Route::post('doimportant', 'ContactController@doimportant');
                Route::post('addcat', 'ContactController@addcat');

                Route::get('new', 'ContactController@newmail');
                Route::get('mailcatdelete/{id}', 'ContactController@mailcatdelete');
                Route::get('maillabeldelete/{id}', 'ContactController@maillabeldelete');
                Route::get('read/{id}', 'ContactController@read');
                Route::get('/{type?}', 'ContactController@index');
                Route::get('/', 'ContactController@index');
            }
        );

        Route::prefix('menus')->group(
            function () {
                Route::get('{id}', 'MenuController@show');
                Route::get('/', 'MenuController@index');
                Route::get('menu/builder/{id}', 'MenuItemController@showMenuItems')->name('menu.builder');

                /*
                * Helpers Route
                */
                Route::get('assets', 'MenuController@assets')->name('menu.asset');

                // Menus
                Route::get('getMenus', 'MenuController@getMenus');
                Route::post('menu', 'MenuController@store');
                Route::post('menu/sort', 'MenuController@sort');
                Route::put('menu', 'MenuController@update');
                Route::get('menu/delete/{id}', 'MenuController@destroy');

                // Menu Items
                Route::get('menu/items/{menu_id}', 'MenuItemController@getMenuItems');
                Route::get('menu/{menu_id}/item/{id}', 'MenuItemController@getMenuItem');
                Route::post('menu/item/sort', 'MenuItemController@sort');
                Route::post('menu/item', 'MenuItemController@store');
                Route::post('category-menu/item', 'MenuItemController@storeFromCategory');
                Route::put('menu/item', 'MenuItemController@update');
                Route::get('/menu/item/{id}', 'MenuItemController@destroy');
            }
        );

        Route::prefix('feeds')->group(
            function () {
                Route::get('{id}', 'FeedController@show');
                Route::get('/', 'FeedController@index');
                Route::post('/feed', 'FeedController@store');
                Route::put('/feed', 'FeedController@update');
                Route::get('delete/{id}', 'FeedController@destroy');
            }
        );

        Route::prefix('translations')->group(
            function () {
                Route::post('sort', 'TranslationController@sort');
                Route::get('{locale}/lock', 'TranslationController@lock');
                Route::get('{locale}/send', 'TranslationController@send');
                Route::get('{locale?}', 'TranslationController@index');
                Route::post('{locale}', 'TranslationController@update');
            }
        );
    }
);

// Home
Route::get('/', 'IndexController@index')->name("home");
Route::get('404', 'PagesController@dort');

// Misc
Route::get('{type}.xml', 'RssController@index');
Route::get('fbinstant.rss', 'RssController@fbinstant');
Route::get('{type}.json', 'RssController@json');
Route::get('/selectlanguge/{locale}', 'IndexController@changeLanguage');

// Api
Route::post('register_product', 'Api\ActivationController@handle');


// Auth
Route::get('auth/social/{type}', 'Auth\SocialAuthController@socialConnect');
Route::get('auth/social/{type}/callback', 'Auth\SocialAuthController@socialCallback');
// Login Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
// Logout Routes...
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');
// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
// Password Confirmation Routes...
Route::get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');
// Email Verification Routes...
Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');


// Contact
Route::get('contact', 'ContactController@index');
Route::post('contact', 'ContactController@create');

// Amp
Route::get('amp/{catname}/{slug}', 'AmpController@post');
Route::get('amp', 'AmpController@index');

// User Profile
Route::get('profile/{userslug}', 'UsersController@index');

Route::middleware('auth')->prefix('profile/{userslug}')->group(
    function () {
        Route::get('messages/create', 'UserMessageController@create');
        Route::get('messages/{id}/read', 'UserMessageController@read');
        Route::get('messages/{id}/unread', 'UserMessageController@unread');
        Route::get('messages/{id}', 'UserMessageController@show');
        Route::put('messages/{id}', 'UserMessageController@update');
        Route::post('messages', 'UserMessageController@store');
        Route::get('messages', 'UserMessageController@index');

        Route::post('settings', 'UsersController@updatesettings');
        Route::post('follow', 'UsersController@follow');
        Route::get('settings', 'UsersController@settings');
        Route::get('following', 'UsersController@following');
        Route::get('followers', 'UsersController@followers');
        Route::get('feed', 'UsersController@followfeed');
        Route::get('draft', 'UsersController@draftposts');
        Route::get('trash', 'UsersController@deletedposts');
    }
);


// Frontend Posting
Route::post('upload-a-image',  'UploadController@newUpload')->name('upload_image_request');
Route::post('fetch-video',  'FormController@fetchVideoEmbed')->name('fetch_video_request');
Route::get('addnewform',  'FormController@addnewform');
Route::post('create-post',  'PostEditorController@createPost');
Route::post('edit/{post_id}',  'PostEditorController@editPost');
Route::get('create',  'PostEditorController@showPostCreate');
Route::get('edit/{post_id}',  'PostEditorController@showPostEdit');
Route::get('delete/{post_id}',  'PostEditorController@deletePost');

Route::get('get_content_data',  'FormController@get_content_data');
Route::post('shared', 'PollController@Shared');
Route::get('commentload',  'PostsController@commentload');
Route::get('reactions/{reaction}',  'PagesController@showReaction');
Route::get('search',  'SearchController@index');
Route::get('search-users', 'SearchController@searchUsers');

// Tax
Route::get('tag/{tag}',  'TagController@index');
Route::post('tag-search',  'TagController@search');

// Pages
Route::get('pages/{page}',  'PagesController@showpage');

// Posts
Route::get('ajax_previous',  'PostsController@ajax_previous');
Route::post('{catname}/{postname}/newvote', 'PollController@VoteANewPoll');
Route::post('{catname}/{postname}/vote', 'PollController@VoteAPoll');
Route::post('{catname}/{postname}/reaction', 'PollController@VoteReaction');
Route::get('{catname}/{slug}', 'PostsController@index');

// Search Category
Route::get('{catname}', 'PagesController@showCategory')->where('all', '.*');
