<?php
/*
 * Code generated from a template, if modifications are required, carefully consider if they should be done
 * in the generated code or in the template.
 */
namespace App\Http\Controllers\Tenants;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenants\MotdRequest;
use App\Models\Tenants\Motd;
use Illuminate\Http\Response;


/**
 * Controller for motd
 * 
 * @author frederic
 *
 */
class MotdController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
    	$motds = Motd::all();
    	return view ( 'tenants/motd/index', compact ( 'motds' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() { 
    	return view ('tenants/motd/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\Tenants\MotdRequest
     * @return \Illuminate\Http\Response
     */
    public function store(MotdRequest $request) {
        $validatedData = $request->validated(); // Only retrieve the data, the validation is done
        
        Motd::create($validatedData);
       return redirect('/motd')->with('success', __('general.creation_success', [ 'elt' => __("motd.elt")]));
     }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tenants\Motd  $motd
     * @return \Illuminate\Http\Response
     */
    public function show(Motd $motd) {
    }

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tenants\Motd  $motd
     * @return \Illuminate\Http\Response
     */
    public function edit(Motd $motd) {
        return view('tenants/motd/edit')
            ->with(compact('motd'));
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param App\Http\Requests\Tenants\MotdRequest;
     * @param String $id
     * @return \Illuminate\Http\Response
     * 
     * @SuppressWarnings("PMD.ShortVariable")
     */
    public function update(MotdRequest $request, $id) {
        $validatedData = $request->validated();
        $previous = Motd::find($id);

        $this->update_date($validatedData, 'publication_date');
        $this->update_date($validatedData, 'end_date');
        Motd::where([ 'id' => $id])->update($validatedData);

        return redirect('/motd')->with('success', __('general.modification_success', [ 'elt' => __("motd.elt") ]));
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tenants\Motd  $motd
     * @return \Illuminate\Http\Response
     */
    public function destroy(Motd $motd) {
    	$id = $motd->id;
        
        
    	$motd->delete();
    	return redirect ( 'motd' )->with ( 'success', __('general.deletion_success', ['elt' => $id]));
    }
    
    /**
     * Display the current messages
     *
     * @return \Illuminate\Http\Response
     */
    public function current() {
        $motds = Motd::currents();
        return view ( 'tenants/motd/current', compact ( 'motds' ) );
    }
    
    public function setCookie(MotdRequest $request){
        $minutes = 60;
        $response = new Response('Set Cookie');
        $response->withCookie(cookie('name', 'MyValue', $minutes));
        return $response;
    }
   
    public function getCookie(MotdRequest $request){
        $value = $request->cookie('name');
        echo $value;
    }
    
}
