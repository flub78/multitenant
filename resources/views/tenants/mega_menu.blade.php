@extends('layouts.app')

@section('content')
<div class="container">

  <div class="row justify-content-center">
    <div class="col-md-8">

      @if(session()->get('success'))
      <div class="alert alert-success">
        {{ session()->get('success') }}
      </div><br />
      @endif
      @if (session('error'))
      <div class="alert alert-danger">
        <ul>
          <li>{{ session('error') }}</li>
        </ul>
      </div><br />
      @endif

      

    </div>
  </div>

  <div class="row">
    <h1>Membre du club </h1>
  </div>
  <div class="row">
      <div class="col">
        <img src="{{asset('icons/calendar.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Calendrier</a>
      </div>

      <div class="col">
        <img src="{{asset('icons/calendar.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Reserver un vol ULM</a>
      </div>

      <div class="col">
        <img src="{{asset('icons/calendar.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Reserver un vol Avion</a>
      </div>

      <div class="col">
        <img src="{{asset('icons/accounting.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Ma facture</a>
      </div>

      <div class="col">
        <img src="{{asset('icons/ULM.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Mes vols ULM</a>
      </div>

      <div class="col">
        <img src="{{asset('icons/airplane.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Mes vols avion</a>
      </div>

      <div class="col">
        <img src="{{asset('icons/glider.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Mes vols planeur</a>
      </div>


      <div class="col">
        <img src="{{asset('icons/profile.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Mon profil</a>
      </div>

      <div class="col">
        <img src="{{asset('icons/member.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Contacts</a>
      </div>

      <div class="col">
        <img src="{{asset('icons/board.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Discuter en ligne</a>
      </div>

      <div class="col">
        <img src="{{asset('icons/password.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Changer mon mot de passe</a>
      </div>
    </div>

  <div class="row">
    <h1>Les vols</h1>
  </div>
  <div class="row">
      <div class="col">
        <img src="{{asset('icons/calendar.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Calendrier</a>
      </div>

      <div class="col">
        <img src="{{asset('icons/calendar.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Reserver un vol ULM</a>
      </div>

      <div class="col">
        <img src="{{asset('icons/calendar.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Reserver un vol Avion</a>
      </div>

      <div class="col">
        <img src="{{asset('icons/accounting.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Ma facture</a>
      </div>

      <div class="col">
        <img src="{{asset('icons/ULM.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Mes vols ULM</a>
      </div>

      <div class="col">
        <img src="{{asset('icons/airplane.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Mes vols avion</a>
      </div>

      <div class="col">
        <img src="{{asset('icons/glider.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Mes vols planeur</a>
      </div>


      <div class="col">
        <img src="{{asset('icons/profile.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Mon profil</a>
      </div>

      <div class="col">
        <img src="{{asset('icons/member.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Contacts</a>
      </div>

      <div class="col">
        <img src="{{asset('icons/board.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Discuter en ligne</a>
      </div>

      <div class="col">
        <img src="{{asset('icons/password.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Changer mon mot de passe</a>
      </div>
    </div>

  <div class="row">
    <h1>Trésorier</h1>
  </div>
  <div class="row">
      <div class="col">
        <img src="{{asset('icons/calendar.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Calendrier</a>
      </div>

      <div class="col">
        <img src="{{asset('icons/calendar.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Reserver un vol ULM</a>
      </div>

      <div class="col">
        <img src="{{asset('icons/calendar.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Reserver un vol Avion</a>
      </div>

      <div class="col">
        <img src="{{asset('icons/accounting.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Ma facture</a>
      </div>

      <div class="col">
        <img src="{{asset('icons/ULM.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Mes vols ULM</a>
      </div>

      <div class="col">
        <img src="{{asset('icons/airplane.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Mes vols avion</a>
      </div>

      <div class="col">
        <img src="{{asset('icons/glider.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Mes vols planeur</a>
      </div>


      <div class="col">
        <img src="{{asset('icons/profile.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Mon profil</a>
      </div>

      <div class="col">
        <img src="{{asset('icons/member.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Contacts</a>
      </div>

      <div class="col">
        <img src="{{asset('icons/board.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Discuter en ligne</a>
      </div>

      <div class="col">
        <img src="{{asset('icons/password.PNG')}}" class="rounded-circle" alt="icon mechanique">
        <a href="http://localhost">Changer mon mot de passe</a>
      </div>
    </div>

    
  </div>
</div>
@endsection