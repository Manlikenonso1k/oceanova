@extends('layouts.app')

@section('content')
<section class="hero-wrap hero-wrap-2" style="background-image: url('{{ asset('assets/template/images/bg_5.jpg') }}');" data-stellar-background-ratio="0.5">
  <div class="overlay"></div>
  <div class="container">
    <div class="row no-gutters slider-text align-items-end justify-content-center">
      <div class="col-md-9 ftco-animate text-center mb-5">
        <h1 class="mb-2 bread">Book A Table Now</h1>
        <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">Home <i class="fa fa-chevron-right"></i></a></span> <span>Reservation <i class="fa fa-chevron-right"></i></span></p>
      </div>
    </div>
  </div>
</section>

<section class="ftco-section ftco-wrap-about ftco-no-pb ftco-no-pt">
  <div class="container">
    <div class="row no-gutters">
      <div class="col-sm-12 p-4 p-md-5 d-flex align-items-center justify-content-center bg-primary text-white" style="background-color:#000 !important;">
        <form action="{{ route('booking.store') }}" method="POST" class="appointment-form">
          @csrf
          @if(session('success'))
            <div class="alert alert-success mb-4" role="alert">
              {{ session('success') }}
            </div>
          @endif
          @if($errors->any())
            <div class="alert alert-danger mb-4" role="alert">
              <ul class="mb-0 pl-3">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          <h3 class="mb-3">Book your Table</h3>
          <div class="row justify-content-center">
            <div class="col-md-4">
              <div class="form-group">
                <input type="text" name="full_name" class="form-control" placeholder="Name">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Email">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <input type="text" name="tel" class="form-control" placeholder="Whatsapp Number">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <div class="input-wrap">
                  <div class="icon"><span class="fa fa-calendar"></span></div>
                  <input type="text" name="signin" class="form-control book_date" placeholder="Check-In">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <div class="input-wrap">
                  <div class="icon"><span class="fa fa-clock-o"></span></div>
                  <input type="text" name="signout" class="form-control book_time" placeholder="Check-Out">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <div class="form-field">
                  <div class="select-wrap">
                    <div class="icon"><span class="fa fa-chevron-down"></span></div>
                    <select name="noofv" class="form-control text-white" required style="background-color:#000 !important; color:#fff !important;">
                      <option value="" disabled style="background-color:#000; color:#fff;">>Table 01 - 4 Guests</option>
                      <option value="1" selected style="background-color:#000; color:#fff;">Table 02 - 4 Guests</option>
                      <option value="2" style="background-color:#000; color:#fff;">Table 03 - 4 Guests</option>
                      <option value="3" style="background-color:#000; color:#fff;">Table 04 - 4 Guests</option>
                      <option value="4" style="background-color:#000; color:#fff;">Table 05 - 4 Guests</option>
                      <option value="5" style="background-color:#000; color:#fff;">Table 06 - 2 Guests</option>
                      <option value="6" style="background-color:#000; color:#fff;">Table 07 - 2 Guests</option>
                      <option value="7" style="background-color:#000; color:#fff;">Table 08 - 2 Guests</option>
                      <option value="8" style="background-color:#000; color:#fff;">Table 09 - 2 Guests</option>
                      <option value="9" style="background-color:#000; color:#fff;">Table 10 - 4 Guests(outdoor)</option>
                      <option value="10" style="background-color:#000; color:#fff;">Table 11 - 4 Guests(outdoor)</option>
                      <option value="11" style="background-color:#000; color:#fff;">Table 12 - 4 Guests(outdoor)</option>
                      <option value="12" style="background-color:#000; color:#fff;">Table 13 - 4 Guests(outdoor)</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <input type="submit" value="Book Your Table Now" class="btn btn-white py-3 px-4">
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<section class="ftco-section">
  <div class="container">
    <div class="row d-flex">
      <div class="col-md-6 d-flex">
        <div class="img img-2 w-100 mr-md-2" style="background-image: url({{ asset('assets/template/images/bg_6.jpg') }});"></div>
        <div class="img img-2 w-100 ml-md-2" style="background-image: url({{ asset('assets/template/images/bg_4.jpg') }});"></div>
      </div>
      <div class="col-md-6 ftco-animate makereservation p-4 p-md-5">
        <div class="heading-section ftco-animate mb-5">
          <span class="subheading">This is our secrets</span>
          <h2 class="mb-4">Perfect Ingredients</h2>
          <p>“Great dishes begin with great ingredients—fresh, seasonal, and treated with respect.”</p>
          <p><a href="#" class="btn btn-primary">Learn more</a></p>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
