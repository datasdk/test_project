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


    <form action="/api/companies" method="post">

        @csrf


        <div class="row form-group">
            
            <div class="col">
                <div>Company</div>
                <input type="text" name="name" value="" class="form-control">
            </div>

            <div class="col">
                <div>Vat</div>
                <input type="text" name="vat" value="" class="form-control">
            </div>
            
        </div>


        <div class="row form-group">
            
            <div class="col">
                <div>Adresse</div>
                <input type="text" name="address" value="" class="form-control">
            </div>

            <div class="col">
                <div>Hus nr.</div>
                <input type="text" name="houseno" value="" class="form-control">
            </div>
            
        </div>


        <div class="row form-group">
            
            <div class="col">
                <div>Post nr.</div>
                <input type="text" name="zipcode" value="" class="form-control">
            </div>

            <div class="col">
                <div>By</div>
                <input type="text" name="city" value="" class="form-control">
            </div>
            
        </div>


        <div class="row form-group">
            
            <div class="col">
                <div>country</div>
                <input type="text" name="country" value="DK" class="form-control">
            </div>

            <div class="col">
                <div>Phone</div>
                <input type="text" name="phone" value="" class="form-control">
            </div>
            
        </div>


        <button class="btn btn-primary">Opret firma</button>
  

    </form>


</div>

@endsection



