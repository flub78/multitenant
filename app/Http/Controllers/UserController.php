<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
	
	/**
	 * Display the resource table view
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$users = User::all ();

		return view ( 'users/index', compact ( 'users' ) );
	}

	/**
	 * Show the form to create a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		return view ( 'users/create' );
	}

	/**
	 * Store a new resource in database.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(UserRequest $request) {
		$validatedData = $request->validated ();

		$validatedData ['password'] = Hash::make ( $validatedData ['password'] );
		if ($request->has('admin')) {
			$validatedData['admin'] = true;
		} else {
			$validatedData['admin'] = false;
		}
		$validatedData['active'] = ($request->has('active'));
		User::create ( $validatedData );

		return redirect ( '/users' )->with ( 'success', 'User ' . $validatedData ['name'] . ' has been created' );
	}

	/**
	 * Display the specified resource.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	public function show($id) {
	}
	 */

	/**
	 * Show the edit form
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		$user = User::findOrFail ( $id );
		return view ( 'users/edit' )->with ( compact ( 'user' ) );
	}

	/**
	 * Update the resource in database.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(UserRequest $request, $id) {
		$validatedData = $request->validated ();
		if ($validatedData ['password']) {
			$validatedData ['password'] = Hash::make ( $validatedData ['password'] );
		} else {
			// keep the same password
			$user = User::findOrFail ( $id );
			$validatedData ['password'] = $user->password;
		}
		if ($request->has('admin')) {
			$validatedData['admin'] = true;
		} else {
			$validatedData['admin'] = false;
		}
		$validatedData['active'] = ($request->has('active'));
		// var_dump($validatedData); exit;
		
		User::whereId ( $id )->update ( $validatedData );

		$name = $validatedData ['name'];
		return redirect ( '/users' )->with ( 'success', "User $name has been updated" );
	}

	/**
	 * Delete a resource from database.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$user = User::findOrFail ( $id );
		$name = $user->name;
		$user->delete ();

		return redirect ( '/users' )->with ( 'success', "User $name has been deleted" );
	}

}
