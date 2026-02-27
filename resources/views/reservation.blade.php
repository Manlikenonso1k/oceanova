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
                      <!-- 2 tables for 4 persons -->
                      <option value="Table 01 - 4 Guests" style="background-color:#000; color:#fff;">1. Table 01 - 4 Guests</option>
                      <option value="Table 02 - 4 Guests" style="background-color:#000; color:#fff;">2. Table 02 - 4 Guests</option>
                      <!-- 3 tables for 2 persons -->
                      <option value="Table 03 - 2 Guests" style="background-color:#000; color:#fff;">3. Table 03 - 2 Guests</option>
                      <option value="Table 04 - 2 Guests" style="background-color:#000; color:#fff;">4. Table 04 - 2 Guests</option>
                      <option value="Table 05 - 2 Guests" style="background-color:#000; color:#fff;">5. Table 05 - 2 Guests</option>
                      <!-- 4 tables for 3 persons (inside) -->
                      <option value="Table 06 - 3 Guests (inside)" style="background-color:#000; color:#fff;">6. Table 06 - 3 Guests (inside)</option>
                      <option value="Table 07 - 3 Guests (inside)" style="background-color:#000; color:#fff;">7. Table 07 - 3 Guests (inside)</option>
                      <option value="Table 08 - 3 Guests (inside)" style="background-color:#000; color:#fff;">8. Table 08 - 3 Guests (inside)</option>
                      <option value="Table 09 - 3 Guests (inside)" style="background-color:#000; color:#fff;">9. Table 09 - 3 Guests (inside)</option>
                      <!-- 6 tables for 3 persons (outside) -->
                      <option value="Table 10 - 3 Guests (outside)" style="background-color:#000; color:#fff;">10. Table 10 - 3 Guests (outside)</option>
                      <option value="Table 11 - 3 Guests (outside)" style="background-color:#000; color:#fff;">11. Table 11 - 3 Guests (outside)</option>
                      <option value="Table 12 - 3 Guests (outside)" style="background-color:#000; color:#fff;">12. Table 12 - 3 Guests (outside)</option>
                      <option value="Table 13 - 3 Guests (outside)" style="background-color:#000; color:#fff;">13. Table 13 - 3 Guests (outside)</option>
                      <option value="Table 14 - 3 Guests (outside)" style="background-color:#000; color:#fff;">14. Table 14 - 3 Guests (outside)</option>
                      <option value="Table 15 - 3 Guests (outside)" style="background-color:#000; color:#fff;">15. Table 15 - 3 Guests (outside)</option>
                      <!-- 4 tables for 6 persons -->
                      <option value="Table 16 - 6 Guests" style="background-color:#000; color:#fff;">16. Table 16 - 6 Guests</option>
                      <option value="Table 17 - 6 Guests" style="background-color:#000; color:#fff;">17. Table 17 - 6 Guests</option>
                      <option value="Table 18 - 6 Guests" style="background-color:#000; color:#fff;">18. Table 18 - 6 Guests</option>
                      <option value="Table 19 - 6 Guests" style="background-color:#000; color:#fff;">19. Table 19 - 6 Guests</option>
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
