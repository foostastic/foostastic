<?php

namespace App\Http\Controllers\Auth;


use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;



class AuthController extends Controller
{

	protected $redirectTo = '/';


	public function redirectToGoogle()
	{
		return Socialite::build('google')->redirect();
	}

	public function handleGoogleCallback()
	{
		try {
			$user = Socialite::build('google')->user();

			$userModel = new User;
			$createdUser = $userModel->addNew($user);
			Auth::loginUsingId($createdUser->id);
			return redirect()->route('home');
		} catch (Exception $e) {
			var_dump($e);
		}
	}
}