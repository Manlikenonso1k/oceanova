@extends('layouts.app')

@section('content')
<section class="hero-wrap hero-wrap-2" style="background-image: url('{{ asset('assets/template/images/bg_5.jpg') }}');" data-stellar-background-ratio="0.5">
  <div class="overlay"></div>
  <div class="container">
    <div class="row no-gutters slider-text align-items-end justify-content-center">
      <div class="col-md-9 ftco-animate text-center mb-5">
        <h1 class="mb-2 bread">About</h1>
        <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">Home <i class="fa fa-chevron-right"></i></a></span> <span>About <i class="fa fa-chevron-right"></i></span></p>
      </div>
    </div>
  </div>
</section>

<section class="ftco-section ftco-no-pt ftco-no-pb">
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
          <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
          <p><a href="#" class="btn btn-primary">Learn more</a></p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="ftco-section ftco-counter img" id="section-counter" style="background-image: url({{ asset('assets/template/images/bg_4.jpg') }});" data-stellar-background-ratio="0.5">
  <div class="container">
    <div class="row d-md-flex align-items-center justify-content-center">
      <div class="col-lg-10">
        <div class="row d-md-flex align-items-center">
          <div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
            <div class="block-18">
              <div class="text">
                <strong class="number" data-number="100">0</strong>
                <span>Tasty Dishes</span>
              </div>
            </div>
          </div>
          <div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
            <div class="block-18">
              <div class="text">
                <strong class="number" data-number="4000">0</strong>
                <span>Dishes Served</span>
              </div>
            </div>
          </div>
          <div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
            <div class="block-18">
              <div class="text">
                <strong class="number" data-number="10">0</strong>
                <span>Restaurants</span>
              </div>
            </div>
          </div>
          <div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
            <div class="block-18">
              <div class="text">
                <strong class="number" data-number="10000">0</strong>
                <span>Happy Customers</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="ftco-section ftco-no-pt ftco-no-pb ftco-intro bg-primary">
  <div class="container py-5">
    <div class="row py-2">
      <div class="col-md-12 text-center">
        <h2>We Make Delicious &amp; Nutritious Food</h2>
        <a href="{{ route('reservation') }}" class="btn btn-white btn-outline-white">Book A Table Now</a>
      </div>
    </div>
  </div>
</section>

<section class="ftco-section testimony-section" style="background-image: url({{ asset('assets/template/images/bg_5.jpg') }});">
  <div class="overlay"></div>
  <div class="container">
    <div class="row justify-content-center mb-3 pb-2">
      <div class="col-md-7 text-center heading-section heading-section-white ftco-animate">
        <span class="subheading">Testimony</span>
        <h2 class="mb-4">Happy Customer</h2>
      </div>
    </div>
    <div class="row ftco-animate justify-content-center">
      <div class="col-md-7">
        <div class="carousel-testimony owl-carousel ftco-owl">
          <div class="item">
            <div class="testimony-wrap text-center">
              <div class="text p-3">
                <p class="mb-4">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
                <div class="user-img mb-4" style="background-image: url({{ asset('assets/template/images/person_1.jpg') }});">
                  <span class="quote d-flex align-items-center justify-content-center">
                    <i class="fa fa-quote-left"></i>
                  </span>
                </div>
                <p class="name">John Gustavo</p>
                <span class="position">Customer</span>
              </div>
            </div>
          </div>
          <div class="item">
            <div class="testimony-wrap text-center">
              <div class="text p-3">
                <p class="mb-4">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
                <div class="user-img mb-4" style="background-image: url({{ asset('assets/template/images/person_1.jpg') }});">
                  <span class="quote d-flex align-items-center justify-content-center">
                    <i class="fa fa-quote-left"></i>
                  </span>
                </div>
                <p class="name">John Gustavo</p>
                <span class="position">Customer</span>
              </div>
            </div>
          </div>
          <div class="item">
            <div class="testimony-wrap text-center">
              <div class="text p-3">
                <p class="mb-4">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
                <div class="user-img mb-4" style="background-image: url({{ asset('assets/template/images/person_1.jpg') }});">
                  <span class="quote d-flex align-items-center justify-content-center">
                    <i class="fa fa-quote-left"></i>
                  </span>
                </div>
                <p class="name">John Gustavo</p>
                <span class="position">Customer</span>
              </div>
            </div>
          </div>
          <div class="item">
            <div class="testimony-wrap text-center">
              <div class="text p-3">
                <p class="mb-4">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
                <div class="user-img mb-4" style="background-image: url({{ asset('assets/template/images/person_1.jpg') }});">
                  <span class="quote d-flex align-items-center justify-content-center">
                    <i class="fa fa-quote-left"></i>
                  </span>
                </div>
                <p class="name">John Gustavo</p>
                <span class="position">Customer</span>
              </div>
            </div>
          </div>
          <div class="item">
            <div class="testimony-wrap text-center">
              <div class="text p-3">
                <p class="mb-4">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
                <div class="user-img mb-4" style="background-image: url({{ asset('assets/template/images/person_1.jpg') }});">
                  <span class="quote d-flex align-items-center justify-content-center">
                    <i class="fa fa-quote-left"></i>
                  </span>
                </div>
                <p class="name">John Gustavo</p>
                <span class="position">Customer</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
