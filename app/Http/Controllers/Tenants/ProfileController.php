<?php
/*
 * Code generated from a template, if modifications are required, carefully consider if they should be done
 * in the generated code or in the template.
 */
namespace App\Http\Controllers\Tenants;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenants\ProfileRequest;
use App\Models\Tenants\Profile;
use App\Models\User;

/**
 * Controller for profile
 * 
 * @author frederic
 *
 */
class ProfileController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
    	$profiles = Profile::all();
    	return view ( 'tenants/profile/index', compact ( 'profiles' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {     	
    	$user_list = User::selector();
    	return view ('tenants/profile/create')
    		->with('user_list', $user_list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\Tenants\ProfileRequest
     * @return \Illuminate\Http\Response
     */
    public function store(ProfileRequest $request) {
        $validatedData = $request->validated(); // Only retrieve the data, the validation is done

        
        Profile::create($validatedData);
       return redirect('/profile')->with('success', __('general.creation_success', [ 'elt' => __("profile.elt")]));
     }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tenants\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile) {
        // echo "Profile.show\n";
    }

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tenants\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit(Profile $profile) {
        $user_list = User::selector();
        return view('tenants/profile/edit')->with(compact('profile'))
            
			->with('user_list', $user_list)
;
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param App\Http\Requests\Tenants\ProfileRequest;
     * @param String $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProfileRequest $request, $id) {
        $validatedData = $request->validated();
        $previous = Profile::find($id);

        $this->update_date($validatedData, 'birthday');
        Profile::where([ 'id' => $id])->update($validatedData);

        return redirect('/profile')->with('success', __('general.modification_success', [ 'elt' => __("profile.elt") ]));
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tenants\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile) {
    	$id = $profile->id;
        
    	$profile->delete();
    	return redirect ( 'profile' )->with ( 'success', __('general.deletion_success', ['elt' => $id]));
    }
}
