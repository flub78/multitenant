<?php
/*
 * Code generated from a template, if modifications are required, carefully consider if they should be done
 * in the generated code or in the template.
 */

namespace App\Http\Controllers\Tenants;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenants\AttachmentRequest;
use App\Models\Tenants\Attachment;
use App\Helpers\DateFormat;
use Illuminate\Http\Request;
use Redirect;


/**
 * Controller for attachment
 * 
 * @author frederic
 *
 */
class AttachmentController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        $query = Attachment::query();

        $filter_open = ($request->has('filter_open')) ? "-show" : "";
        if ($request->has('filter')) {
            $this->applyFilter($query, $request->input('filter'));
        }
        $attachments = $query->get();

        return view('tenants/attachment/index', compact('attachments'))
            ->with('filter_open', $filter_open);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('tenants/attachment/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\Tenants\AttachmentRequest
     * @return \Illuminate\Http\Response
     */
    public function store(AttachmentRequest $request) {
        $validatedData = $request->validated(); // Only retrieve the data, the validation is done

        $this->store_file($validatedData, "file", $request, "attachment");
        Attachment::create($validatedData);
        return redirect('/attachment')->with('success', __('general.creation_success', ['elt' => __("attachment.elt")]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $attachment = Attachment::find($id);
        return view('tenants/attachment/show')
            ->with(compact('attachment'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tenants\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function edit(Attachment $attachment) {

        return view('tenants/attachment/edit')
            ->with(compact('attachment'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param App\Http\Requests\Tenants\AttachmentRequest;
     * @param String $id
     * @return \Illuminate\Http\Response
     * 
     * @SuppressWarnings("PMD.ShortVariable")
     */
    public function update(AttachmentRequest $request, $id) {
        $validatedData = $request->validated();
        $previous = Attachment::find($id);

        $this->update_file($validatedData, "file", $request, "attachment", $previous);
        Attachment::where(['id' => $id])->update($validatedData);

        return redirect('/attachment')->with('success', __('general.modification_success', ['elt' => __("attachment.elt")]));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tenants\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attachment $attachment) {
        $id = $attachment->id;
        if ($attachment->file) $this->destroy_file($attachment->file);
        $attachment->delete();
        return redirect('attachment')->with('success', __('general.deletion_success', ['elt' => $id]));
    }

    /**
     * Download a previously uploaded file
     * 
     * @param unknown $id
     * @param unknown $field
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function download($id, $field) {
        $elt = Attachment::find($id);
        if ($elt) {
            $filename = $elt->$field;
            return $this->download_file($filename);
        }
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
            return redirect('/attachment');
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


        return redirect("/attachment?filter=$filter&filter_open=1")->withInput();
    }
}
