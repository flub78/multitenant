@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        
        @if (session('error'))
       <div class="alert alert-danger">
         <ul>
              <li>{{ session('error') }}</li>
         </ul>
        </div><br />
        @endif
        
            <h1>Resouce Manual Test Checklist</h1>
            <div class="card mb-3">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}                    
                </div>
            </div>
            
  
  <fieldset class="form-group border d-sm-flex flex-column flex-wrap mb-3 p-1">
             <div>
               <label for="" class="form-label">Resource creation</label>
               <input type="checkbox" name="qualifications_boxes[]" value="0" class="form-check-input me-3" />
             </div>
             <div>
               <label for="" class="form-label">Resource edit</label>
               <input type="checkbox" name="qualifications_boxes[]" value="1" class="form-check-input me-3" />
             </div>
             <div>
               <label for="" class="form-label">Resource list</label>
               <input type="checkbox" name="qualifications_boxes[]" value="2" class="form-check-input   me-3" />
             </div>
             <div>
               <label for="" class="form-label">Resource delete</label>
               <input type="checkbox" name="qualifications_boxes[]" value="3" class="form-check-input   me-3" />
             </div>
             <div>
               <label for="" class="form-label">Bad input error messages</label>
               <input type="checkbox" name="qualifications_boxes[]" value="4" class="form-check-input   me-3" />
             </div>
</fieldset>
            
            
        </div>
    </div>
</div>
@endsection
