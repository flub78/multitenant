<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
	
	// TODO move rules into a request
	
	protected $create_rules = [ 
			'name' => [ 
					'required',
					'string',
					'max:255'
			],
			'email' => [ 
					'required',
					'string',
					'email',
					'max:255',
					'unique:users'
			],
			'password' => [ 
					'required',
					'string',
					'min:8',
					'confirmed'
			]
	];
	protected $edit_rules = [ 
			'name' => [ 
					'required',
					'string',
					'max:255'
			],
			'email' => [ 
					'required',
					'string',
					'email',
					'max:255'
			],
			'password' => [ 
					'nullable',
					'string',
					'min:8',
					'confirmed'
			]
	];

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
	public function store(Request $request) {
		$validatedData = $request->validate ( $this->create_rules );

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
	public function update(Request $request, $id) {
		$validatedData = $request->validate ( $this->edit_rules );
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
