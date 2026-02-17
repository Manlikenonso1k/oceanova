@extends('layouts.app')

@section('content')
<section class="hero-wrap hero-wrap-2" style="background-image: url('{{ asset('assets/template/images/bg_5.jpg') }}');" data-stellar-background-ratio="0.5">
  <div class="overlay"></div>
  <div class="container">
    <div class="row no-gutters slider-text align-items-end justify-content-center">
      <div class="col-md-9 ftco-animate text-center mb-5">
        <h1 class="mb-2 bread">Menu</h1>
        <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">Home <i class="fa fa-chevron-right"></i></a></span> <span>Menu <i class="fa fa-chevron-right"></i></span></p>
      </div>
    </div>
  </div>
</section>

<section class="ftco-section">
  <div class="container">
    <div class="row justify-content-center mb-5 pb-2">
      <div class="col-md-7 text-center heading-section ftco-animate">
        <span class="subheading">Specialties</span>
        <h2 class="mb-4">Our Menu</h2>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 col-lg-4">
        <div class="menu-wrap">
          <div class="heading-menu text-center ftco-animate">
            <h3>Breakfast</h3>
          </div>
          @section('content')
          @php
              use Illuminate\Support\Str;

              $tiers = [
                  [
                      'tier' => 'Budget',
                      'price' => '₦5,000 - ₦7,000',
                      'example' => 'Pap & Moi Moi, Sides (Fries)',
                      'tags' => ['V', 'L'],
                  ],
                  [
                      'tier' => 'Breakfast',
                      'price' => '₦8,900 - ₦21,500',
                      'example' => 'Oatmeal to Traditional Omelette',
                      'tags' => ['V', 'L'],
                  ],
                  [
                      'tier' => 'Standard Mains',
                      'price' => '₦11,000 - ₦18,000',
                      'example' => 'Beef Pepper Soup, Seafood Fried Rice',
                      'tags' => ['P', 'S'],
                  ],
                  [
                      'tier' => 'Premium',
                      'price' => '₦19,000 - ₦25,000',
                      'example' => 'Potato Soup, Grilled Salmon, Platters',
                      'tags' => ['S'],
                  ],
                  [
                      'tier' => 'Luxury/Specialty',
                      'price' => '₦30,000 - ₦35,000',
                      'example' => 'Chicken Alfredo, Porterhouse Steak',
                      'tags' => ['P', 'L'],
                  ],
                  [
                      'tier' => 'Elite Seafood',
                      'price' => '₦37,000 - ₦38,000+',
                      'example' => 'Prawn & Snail Pepper Soups',
                      'tags' => ['S'],
                  ],
              ];
          @endphp

          <script>
              window.tailwind = window.tailwind || {};
              window.tailwind.config = {
                  corePlugins: {
                      preflight: false,
                  },
              };
          </script>
          <script src="https://cdn.tailwindcss.com"></script>

          <section class="min-h-screen bg-slate-50">
              <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-8 pb-6">
                  <div class="flex flex-col gap-3">
                      <span class="text-xs uppercase tracking-[0.3em] text-slate-500">Oceanova Digital Menu</span>
                      <div class="flex flex-wrap items-center justify-between gap-4">
                          <h1 class="text-2xl sm:text-3xl font-semibold text-slate-900">Curated dining tiers</h1>
                          <div class="flex items-center gap-3 text-xs text-slate-500">
                              <span class="inline-flex items-center gap-2">
                                  <svg class="h-4 w-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                      <path d="M12 2c3.5 4.5 5 7 5 9a5 5 0 0 1-10 0c0-2 1.5-4.5 5-9z" />
                                  </svg>
                                  V Vegetarian
                              </span>
                              <span class="inline-flex items-center gap-2">
                                  <svg class="h-4 w-4 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                      <path d="M12 2v20" />
                                      <path d="M8 6h8" />
                                  </svg>
                                  L Lactose
                              </span>
                              <span class="inline-flex items-center gap-2">
                                  <svg class="h-4 w-4 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                      <path d="M3 11h18" />
                                      <path d="M5 7h14" />
                                      <path d="M7 15h10" />
                                  </svg>
                                  P Pork
                              </span>
                              <span class="inline-flex items-center gap-2">
                                  <svg class="h-4 w-4 text-sky-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                      <path d="M6 10c1-2 4-3 7-3 2.5 0 5 .7 5 2.5S16 13 13.5 13H10" />
                                      <path d="M3 13c0 3 3 5 7 5 5 0 8-2 8-5" />
                                  </svg>
                                  S Seafood
                              </span>
                          </div>
                      </div>
                      <p class="text-sm text-slate-600 max-w-2xl">
                          Browse our price tiers for a sleek, mobile-first menu experience inspired by ServeWithTabul.
                      </p>
                  </div>
              </div>

              <div class="sticky top-0 z-30 border-y border-slate-200 bg-white/95 backdrop-blur">
                  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                      <div class="flex gap-3 overflow-x-auto md:overflow-visible md:flex-wrap md:justify-center py-3">
                          @foreach($tiers as $tier)
                              <a href="#{{ Str::slug($tier['tier']) }}" class="whitespace-nowrap rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 hover:border-slate-900 hover:text-slate-900 transition">
                                  {{ $tier['tier'] }}
                              </a>
                          @endforeach
                      </div>
                  </div>
              </div>

              <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
                  <div class="flex items-center justify-center pb-6">
                      <div class="h-9 w-9 rounded-full border-2 border-slate-200 border-t-slate-900 animate-spin"></div>
                  </div>
                  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                      @foreach($tiers as $tier)
                          <x-menu-item
                              :id="Str::slug($tier['tier'])"
                              :tier="$tier['tier']"
                              :price-range="$tier['price']"
                              :example="$tier['example']"
                              :tags="$tier['tags']"
                          />
                      @endforeach
                  </div>
              </div>
          </section>
          @endsection
              <p><span>Meat</span>, <span>Potatoes</span>, <span>Rice</span>, <span>Tomatoe</span></p>
            </div>
          </div>
          <div class="menus d-flex ftco-animate">
            <div class="menu-img img" style="background-image: url({{ asset('assets/template/images/dessert-3.jpg') }});"></div>
            <div class="text">
              <div class="d-flex">
                <div class="one-half">
                  <h3>Beef Roast Source</h3>
                </div>
                <div class="one-forth">
                  <span class="price">$29</span>
                </div>
              </div>
              <p><span>Meat</span>, <span>Potatoes</span>, <span>Rice</span>, <span>Tomatoe</span></p>
            </div>
          </div>
          <div class="menus d-flex ftco-animate">
            <div class="menu-img img" style="background-image: url({{ asset('assets/template/images/dessert-4.jpg') }});"></div>
            <div class="text">
              <div class="d-flex">
                <div class="one-half">
                  <h3>Beef Roast Source</h3>
                </div>
                <div class="one-forth">
                  <span class="price">$29</span>
                </div>
              </div>
              <p><span>Meat</span>, <span>Potatoes</span>, <span>Rice</span>, <span>Tomatoe</span></p>
            </div>
          </div>
          <div class="menus border-bottom-0 d-flex ftco-animate">
            <div class="menu-img img" style="background-image: url({{ asset('assets/template/images/dessert-5.jpg') }});"></div>
            <div class="text">
              <div class="d-flex">
                <div class="one-half">
                  <h3>Beef Roast Source</h3>
                </div>
                <div class="one-forth">
                  <span class="price">$29</span>
                </div>
              </div>
              <p><span>Meat</span>, <span>Potatoes</span>, <span>Rice</span>, <span>Tomatoe</span></p>
            </div>
          </div>
          <span class="flat flaticon-cupcake" style="left: 0;"></span>
          <span class="flat flaticon-ice-cream" style="right: 0;"></span>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="menu-wrap">
          <div class="heading-menu text-center ftco-animate">
            <h3>Wine Card</h3>
          </div>
          <div class="menus d-flex ftco-animate">
            <div class="menu-img img" style="background-image: url({{ asset('assets/template/images/wine-1.jpg') }});"></div>
            <div class="text">
              <div class="d-flex">
                <div class="one-half">
                  <h3>Beef Roast Source</h3>
                </div>
                <div class="one-forth">
                  <span class="price">$29</span>
                </div>
              </div>
              <p><span>Meat</span>, <span>Potatoes</span>, <span>Rice</span>, <span>Tomatoe</span></p>
            </div>
          </div>
          <div class="menus d-flex ftco-animate">
            <div class="menu-img img" style="background-image: url({{ asset('assets/template/images/wine-2.jpg') }});"></div>
            <div class="text">
              <div class="d-flex">
                <div class="one-half">
                  <h3>Beef Roast Source</h3>
                </div>
                <div class="one-forth">
                  <span class="price">$29</span>
                </div>
              </div>
              <p><span>Meat</span>, <span>Potatoes</span>, <span>Rice</span>, <span>Tomatoe</span></p>
            </div>
          </div>
          <div class="menus d-flex ftco-animate">
            <div class="menu-img img" style="background-image: url({{ asset('assets/template/images/wine-3.jpg') }});"></div>
            <div class="text">
              <div class="d-flex">
                <div class="one-half">
                  <h3>Beef Roast Source</h3>
                </div>
                <div class="one-forth">
                  <span class="price">$29</span>
                </div>
              </div>
              <p><span>Meat</span>, <span>Potatoes</span>, <span>Rice</span>, <span>Tomatoe</span></p>
            </div>
          </div>
          <div class="menus d-flex ftco-animate">
            <div class="menu-img img" style="background-image: url({{ asset('assets/template/images/wine-4.jpg') }});"></div>
            <div class="text">
              <div class="d-flex">
                <div class="one-half">
                  <h3>Beef Roast Source</h3>
                </div>
                <div class="one-forth">
                  <span class="price">$29</span>
                </div>
              </div>
              <p><span>Meat</span>, <span>Potatoes</span>, <span>Rice</span>, <span>Tomatoe</span></p>
            </div>
          </div>
          <div class="menus d-flex ftco-animate">
            <div class="menu-img img" style="background-image: url({{ asset('assets/template/images/wine-5.jpg') }});"></div>
            <div class="text">
              <div class="d-flex">
                <div class="one-half">
                  <h3>Beef Roast Source</h3>
                </div>
                <div class="one-forth">
                  <span class="price">$29</span>
                </div>
              </div>
              <p><span>Meat</span>, <span>Potatoes</span>, <span>Rice</span>, <span>Tomatoe</span></p>
            </div>
          </div>
          <div class="menus d-flex ftco-animate">
            <div class="menu-img img" style="background-image: url({{ asset('assets/template/images/wine-6.jpg') }});"></div>
            <div class="text">
              <div class="d-flex">
                <div class="one-half">
                  <h3>Beef Roast Source</h3>
                </div>
                <div class="one-forth">
                  <span class="price">$29</span>
                </div>
              </div>
              <p><span>Meat</span>, <span>Potatoes</span>, <span>Rice</span>, <span>Tomatoe</span></p>
            </div>
          </div>
          <div class="menus d-flex ftco-animate">
            <div class="menu-img img" style="background-image: url({{ asset('assets/template/images/wine-7.jpg') }});"></div>
            <div class="text">
              <div class="d-flex">
                <div class="one-half">
                  <h3>Beef Roast Source</h3>
                </div>
                <div class="one-forth">
                  <span class="price">$29</span>
                </div>
              </div>
              <p><span>Meat</span>, <span>Potatoes</span>, <span>Rice</span>, <span>Tomatoe</span></p>
            </div>
          </div>
          <div class="menus border-bottom-0 d-flex ftco-animate">
            <div class="menu-img img" style="background-image: url({{ asset('assets/template/images/wine-8.jpg') }});"></div>
            <div class="text">
              <div class="d-flex">
                <div class="one-half">
                  <h3>Beef Roast Source</h3>
                </div>
                <div class="one-forth">
                  <span class="price">$29</span>
                </div>
              </div>
              <p><span>Meat</span>, <span>Potatoes</span>, <span>Rice</span>, <span>Tomatoe</span></p>
            </div>
          </div>
          <span class="flat flaticon-wine" style="left: 0;"></span>
          <span class="flat flaticon-wine-1" style="right: 0;"></span>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="menu-wrap">
          <div class="heading-menu text-center ftco-animate">
            <h3>Drinks &amp; Tea</h3>
          </div>
          <div class="menus d-flex ftco-animate">
            <div class="menu-img img" style="background-image: url({{ asset('assets/template/images/drink-1.jpg') }});"></div>
            <div class="text">
              <div class="d-flex">
                <div class="one-half">
                  <h3>Beef Roast Source</h3>
                </div>
                <div class="one-forth">
                  <span class="price">$29</span>
                </div>
              </div>
              <p><span>Meat</span>, <span>Potatoes</span>, <span>Rice</span>, <span>Tomatoe</span></p>
            </div>
          </div>
          <div class="menus d-flex ftco-animate">
            <div class="menu-img img" style="background-image: url({{ asset('assets/template/images/drink-2.jpg') }});"></div>
            <div class="text">
              <div class="d-flex">
                <div class="one-half">
                  <h3>Beef Roast Source</h3>
                </div>
                <div class="one-forth">
                  <span class="price">$29</span>
                </div>
              </div>
              <p><span>Meat</span>, <span>Potatoes</span>, <span>Rice</span>, <span>Tomatoe</span></p>
            </div>
          </div>
          <div class="menus d-flex ftco-animate">
            <div class="menu-img img" style="background-image: url({{ asset('assets/template/images/drink-3.jpg') }});"></div>
            <div class="text">
              <div class="d-flex">
                <div class="one-half">
                  <h3>Beef Roast Source</h3>
                </div>
                <div class="one-forth">
                  <span class="price">$29</span>
                </div>
              </div>
              <p><span>Meat</span>, <span>Potatoes</span>, <span>Rice</span>, <span>Tomatoe</span></p>
            </div>
          </div>
          <div class="menus d-flex ftco-animate">
            <div class="menu-img img" style="background-image: url({{ asset('assets/template/images/drink-4.jpg') }});"></div>
            <div class="text">
              <div class="d-flex">
                <div class="one-half">
                  <h3>Beef Roast Source</h3>
                </div>
                <div class="one-forth">
                  <span class="price">$29</span>
                </div>
              </div>
              <p><span>Meat</span>, <span>Potatoes</span>, <span>Rice</span>, <span>Tomatoe</span></p>
            </div>
          </div>
          <div class="menus d-flex ftco-animate">
            <div class="menu-img img" style="background-image: url({{ asset('assets/template/images/drink-5.jpg') }});"></div>
            <div class="text">
              <div class="d-flex">
                <div class="one-half">
                  <h3>Beef Roast Source</h3>
                </div>
                <div class="one-forth">
                  <span class="price">$29</span>
                </div>
              </div>
              <p><span>Meat</span>, <span>Potatoes</span>, <span>Rice</span>, <span>Tomatoe</span></p>
            </div>
          </div>
          <div class="menus d-flex ftco-animate">
            <div class="menu-img img" style="background-image: url({{ asset('assets/template/images/drink-6.jpg') }});"></div>
            <div class="text">
              <div class="d-flex">
                <div class="one-half">
                  <h3>Beef Roast Source</h3>
                </div>
                <div class="one-forth">
                  <span class="price">$29</span>
                </div>
              </div>
              <p><span>Meat</span>, <span>Potatoes</span>, <span>Rice</span>, <span>Tomatoe</span></p>
            </div>
          </div>
          <div class="menus border-bottom-0 d-flex ftco-animate">
            <div class="menu-img img" style="background-image: url({{ asset('assets/template/images/drink-7.jpg') }});"></div>
            <div class="text">
              <div class="d-flex">
                <div class="one-half">
                  <h3>Beef Roast Source</h3>
                </div>
                <div class="one-forth">
                  <span class="price">$29</span>
                </div>
              </div>
              <p><span>Meat</span>, <span>Potatoes</span>, <span>Rice</span>, <span>Tomatoe</span></p>
            </div>
          </div>
          <span class="flat flaticon-wine" style="left: 0;"></span>
          <span class="flat flaticon-wine-1" style="right: 0;"></span>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="ftco-section ftco-wrap-about bg-primary ftco-no-pb ftco-no-pt">
  <div class="container">
    <div class="row no-gutters">
      <div class="col-sm-12 p-4 p-md-5 d-flex align-items-center justify-content-center bg-primary">
        <form action="{{ route('booking.store') }}" method="POST" class="appointment-form">
          @csrf
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
                <input type="text" name="tel" class="form-control" placeholder="Phone">
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
                    <select name="noofv" class="form-control">
                      <option value="">Guest</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
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
@endsection
