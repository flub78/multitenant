<?php

namespace app\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;


class UserController extends Controller {
	
	/**
	 * Display the resource table view
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$users = User::all();

		return view('users/index', compact('users'));
	}

	/**
	 * Show the form to create a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		return view('users/create');
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

		return redirect ( '/user' )->with ( 'success', __('user.created', ['name' =>  $validatedData ['name']] ) );
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
     *
     * @SuppressWarnings("PMD.ShortVariable")
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
     *
     * @SuppressWarnings("PMD.ShortVariable")
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
		
		User::whereId ( $id )->update ( $validatedData );

		$name = $validatedData ['name'];
		return redirect ( '/user' )->with ( 'success', __('user.updated', ['name' =>  $name] ) );
	}

	/**
	 * Delete a resource from database.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
     *
     * @SuppressWarnings("PMD.ShortVariable")
	 */
	public function destroy($id) {
		$user = User::findOrFail ( $id );
		$name = $user->name;
		$user->delete ();

		return redirect ( '/user' )->with ( 'success', __('user.deleted', ['name' =>  $name] ) );
	}
	
	/**
	 * Create a token to access to the API.
	 * 
	 * Token should be generated per user and a user token should have exactly the same
	 * rights as the user.
	 */
	public function token(Request $request) {
		
		$token = $request->user()->createToken("user_token", ['api-access']);
		echo "token=" . $token->plainTextToken . "\n";
		
		echo "All tokens:\n";
		foreach ($request->user()->tokens as $token) {
			echo "token=" . $token->plainTextToken . "\n";
		}
	}

}
