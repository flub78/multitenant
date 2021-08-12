<?php

namespace app\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/**
 * A controller to change the current user password
 * 
 * @author frederic
 *
 */
class ChangePasswordController extends Controller {
	

	/**
	 * Display a change password view
	 * for the current user
	 */
	public function change_password() {
		$user = Auth::user();
		return view ( 'users/change_password' )->with ( compact ( 'user' ) );
	}
	
	/**
	 * Change the user password
	 * 
	 * @param ChangePasswordRequest $request
	 * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	public function password(ChangePasswordRequest $request) {
		$user = Auth::user();
		$validatedData = $request->validated ();
		$validatedData ['password'] = Hash::make ( $validatedData ['new_password'] );
		unset($validatedData ['new_password']);
		User::whereId ( $user->id )->update ( $validatedData );
		return redirect ( '/home' )->with ( 'success', __("Password changed") );
	}
}
