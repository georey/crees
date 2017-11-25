<?php namespace App\Http\Controllers;

use App\User;
use Hash;

class WelcomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('welcome');
	}

	public function testConnection()
	{
		$user = user::findOrFail(1);
		print_r($user->username);
		echo "<br>";
		print_r($user->password);
		echo "<br>";
		print_r(bcrypt('rootroot'));
		echo "<br>";
		if(Hash::check('rootroot', $user->password)) 
    		echo "match";		
		else
			echo "missmatch";
	}

}
