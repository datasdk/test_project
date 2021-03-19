@extends('layouts.app')

@section('content')

<div class="container">
    

    <a href="/company/create" class="btn btn-primary">Opret firma</a>

    <hr>

    <h1>Firmaer</h1>


    <table class="table table-stripped">

        @foreach($companies as $val)

            <tr>
                <td>
                    
                    {{ $val->name }} / {{ $val->vat }} / {{ $val->zipcode }} / {{ $val->city }} 
                
                </td>
                <td align="right"> 

                    <a href="/company/{{ $val->id }}/edit" class="btn btn-primary">Ret firma</a> 

                </td>
            </tr>

        @endforeach

    </table>


</div>

@endsection



