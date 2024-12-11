<?php
/*
 * Code generated from a template, if modifications are required, carefully consider if they should be done
 * in the generated code or in the template.
 */

namespace App\Http\Controllers\Tenants;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenants\PersonalAccessTokenRequest;
use App\Models\Tenants\PersonalAccessToken;
use App\Helpers\DateFormat;
use Illuminate\Http\Request;
use Redirect;


/**
 * Controller for personal_access_token
 * 
 * @author frederic
 *
 */
class PersonalAccessTokenController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        $query = PersonalAccessToken::query();

        // Show only user's own tokens if not admin
        if (!auth()->user()->isAdmin()) {
            $query->where('tokenable_id', auth()->id())
                ->where('tokenable_type', get_class(auth()->user()));
        }

        $filter_open = ($request->has('filter_open')) ? "-show" : "";
        if ($request->has('filter')) {
            $this->applyFilter($query, $request->input('filter'));
        }
        $personal_access_tokens = $query->with('tokenable')->get();

        return view('tenants/personal_access_token/index', compact('personal_access_tokens'))
            ->with('filter_open', $filter_open);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('tenants/personal_access_token/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\Tenants\PersonalAccessTokenRequest
     * @return \Illuminate\Http\Response
     */
    public function store(PersonalAccessTokenRequest $request) {
        $validatedData = $request->validated(); // Only retrieve the data, the validation is done

        PersonalAccessToken::create($validatedData);
        return redirect('/personal_access_token')->with('success', __('general.creation_success', ['elt' => __("personal_access_token.elt")]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $personal_access_token = PersonalAccessToken::find($id);
        return view('tenants/personal_access_token/show')
            ->with(compact('personal_access_token'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tenants\PersonalAccessToken  $personal_access_token
     * @return \Illuminate\Http\Response
     */
    public function edit(PersonalAccessToken $personal_access_token) {
        return view('tenants/personal_access_token/edit')
            ->with(compact('personal_access_token'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param App\Http\Requests\Tenants\PersonalAccessTokenRequest;
     * @param String $id
     * @return \Illuminate\Http\Response
     * 
     * @SuppressWarnings("PMD.ShortVariable")
     */
    public function update(PersonalAccessTokenRequest $request, $id) {
        $personal_access_token = PersonalAccessToken::findOrFail($id);

        if (
            !auth()->user()->isAdmin() &&
            ($personal_access_token->tokenable_id !== auth()->id() ||
                $personal_access_token->tokenable_type !== get_class(auth()->user()))
        ) {
            return redirect('personal_access_token')
                ->with('error', __('general.unauthorized_action'));
        }

        $validatedData = $request->validated();
        $previous = PersonalAccessToken::find($id);

        PersonalAccessToken::where(['id' => $id])->update($validatedData);

        return redirect('/personal_access_token')->with('success', __('general.modification_success', ['elt' => __("personal_access_token.elt")]));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tenants\PersonalAccessToken  $personal_access_token
     * @return \Illuminate\Http\Response
     */
    public function destroy(PersonalAccessToken $personal_access_token) {
        if (
            !auth()->user()->isAdmin() &&
            ($personal_access_token->tokenable_id !== auth()->id() ||
                $personal_access_token->tokenable_type !== get_class(auth()->user()))
        ) {
            return redirect('personal_access_token')
                ->with('error', __('general.unauthorized_action'));
        }

        $id = $personal_access_token->id;
        $personal_access_token->delete();
        return redirect('personal_access_token')->with('success', __('general.deletion_success', ['elt' => $id]));
    }

    /**
     * This method is called by the filter form submit buttons
     * Generate a filter parameter according to the filter form and call the index view
     * 
     * @param Request $request
     */
    public function filter(Request $request) {
        $inputs = $request->input();

        // When it is not the filter button redirect to index with no filtering
        if ($request->input('button') != __('general.filter')) {
            return redirect('/personal_access_token');
        }

        /*
	     * Generate a filter string from the form inputs fields
	     * 
	     * Checkboxes and enumerates need an additonal checkbox to detemine if they must be taken into account
	     * by the filter
	     */
        $filters_array = [];
        $fields = [];
        foreach ($fields as $field) {
            if (array_key_exists($field, $inputs) && $inputs[$field]) {
                $filters_array[] = $field . ':' . $inputs[$field];
            }
        }
        $filter = implode(",", $filters_array);


        return redirect("/personal_access_token?filter=$filter&filter_open=1")->withInput();
    }
}
