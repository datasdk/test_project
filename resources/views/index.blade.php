
@extends('layouts.app')

@section('content')


<div class="container">

    <h1>Velkommen</h1>

</div>


  <?php
    
    //dd(Company::find(1)->get_clients()->get());

    //dd(User::find(1)->has_company());

    /*
    dd(Company::add([
        "company"    => "1",
        "vat"        => "2",
        "address"    => "3",
        "houseno"    => "4",
        "zipcode"    => "5",
        "city"       => "6",
        "country"    => "7",
        "phone"      => "8",
    ]));
    */


  //  dd(User::role('writer')->get());

 //   $users = User::permission('edit articles')->get();



   // dd($users );

  
  ?>


@endsection
