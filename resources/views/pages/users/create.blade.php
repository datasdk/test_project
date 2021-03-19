@extends('layouts.app')

@section('content')

<div class="container">
    


    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form action="api/companies/add_user" method="post">

        @csrf


        <div class="row form-group">
            
            <div class="col">

                <div>Company</div>

                <select name="company_id" class="form-control">
                    
                    @foreach($companies as $val)

                        <option value="{{ $val->id }}">{{ $val->name }}</option>

                    @endforeach

                </select>

            </div>

        </div>


        <button class="btn btn-primary">Tilknyt bruger</button>
  

    </form>


</div>

@endsection



