@extends('layouts.app')

@section('content')
	<style>
		/* Scoped About-section readability layer for tablet/mobile over chef background */
		.oceanova-about-panel {
			color: #fff;
			transition: background-color 0.3s ease, box-shadow 0.3s ease, backdrop-filter 0.3s ease;
		}

		@media (max-width: 1024px) {
			.oceanova-about-panel {
				padding: 1.5rem;
				border-radius: 14px;
				background: rgba(0, 0, 0, 0.46);
				border: 1px solid rgba(255, 255, 255, 0.16);
				box-shadow: 0 12px 30px rgba(0, 0, 0, 0.35);
				-webkit-backdrop-filter: blur(12px);
				backdrop-filter: blur(12px);
			}

			.oceanova-about-panel .subheading,
			.oceanova-about-panel .oceanova-about-title,
			.oceanova-about-panel .oceanova-about-copy {
				color: #fff;
			}
		}

		@media (max-width: 767.98px) {
			.oceanova-about-panel {
				padding: 1.1rem;
				border-radius: 12px;
				background: rgba(0, 0, 0, 0.56);
			}
		}

		@supports not ((-webkit-backdrop-filter: blur(1px)) or (backdrop-filter: blur(1px))) {
			@media (max-width: 1024px) {
				.oceanova-about-panel {
					background: rgba(0, 0, 0, 0.72);
				}
			}
		}
	</style>

	<section class="hero-wrap">
		<div class="home-slider owl-carousel js-fullheight">
			<div class="slider-item js-fullheight" style="background-image:url({{ asset('assets/template/images/bg_1.jpg') }});">
				<div class="overlay"></div>
				<div class="container">
					<div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center">
						<div class="col-md-12 ftco-animate">
							<div class="text w-100 mt-5 text-center">
								<span class="subheading">oceanova Restaurant</h2></span>
								<h2>Cooking Since</h2>
								<span class="subheading-2">1958</span>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="slider-item js-fullheight" style="background-image:url({{ asset('assets/template/images/bg_2.jpg') }});">
				<div class="overlay"></div>
				<div class="container">
					<div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center">
						<div class="col-md-12 ftco-animate">
							<div class="text w-100 mt-5 text-center">
								<span class="subheading">oceanova Restaurant</h2></span>
								<h2>Best Quality</h2>
								<span class="subheading-2 sub">Food</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="ftco-section ftco-wrap-about ftco-no-pb ftco-no-pt oceanova-about-section">
		<div class="container">
			<div class="row no-gutters">
				<div class="col-sm-4 p-4 p-md-5 d-flex align-items-center justify-content-center bg-primary">
					<form action="{{ route('booking.store') }}" method="POST" class="appointment-form">
						@csrf
						<h3 class="mb-3">Book your Table</h3>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<input type="text" name="full_name" class="form-control" placeholder="Name">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<input type="email" name="email" class="form-control" placeholder="Email">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<input type="text" name="tel" class="form-control" placeholder="Phone">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<div class="input-wrap">
										<div class="icon"><span class="fa fa-calendar"></span></div>
										<input type="text" name="signin" class="form-control book_date" placeholder="Check-In">
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<div class="input-wrap">
										<div class="icon"><span class="fa fa-clock-o"></span></div>
										<input type="text" name="signout" class="form-control book_time" placeholder="Check-Out">
									</div>
								</div>
							</div>
							<div class="col-md-12">
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
							<div class="col-md-12">
								<div class="form-group">
									<input type="submit" value="Book Your Table Now" class="btn btn-white py-3 px-4">
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="col-sm-8 wrap-about py-5 ftco-animate img" style="background-image: url({{ asset('assets/template/images/chef-fe.png') }});">
					<div class="row pb-5 pb-md-0">
						<div class="col-md-12 col-lg-7">
							<div class="heading-section mt-5 mb-4">
								<div class="pl-lg-3 ml-md-5 oceanova-about-panel">
									<span class="subheading">About</span>
									<h1 class="mb-4 oceanova-about-title">Welcome to Oceanova</h1>
									<p class="oceanova-about-copy mb-0">Located in Okun Ajah, Oceanova brings you a refined fine‑dining experience you have never seen before. From our international dishes crafted by professional chefs to our warm, attentive service, every visit is designed to feel special. Whether you are celebrating a milestone or enjoying a quiet evening, Oceanova blends flavor, comfort, and elegance to create an unforgettable culinary moment.</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="ftco-section ftco-intro" style="background-image: url({{ asset('assets/template/images/oceanova-bq.png') }});">
		<div class="overlay"></div>
		<div class="container">
			<div class="row">
				<div class="col-md-12 text-center">
					<span>Now Booking</span>
					<h2>Private Dinners &amp; Happy Hours</h2>
				</div>
			</div>
		</div>
	</section>

	<section class="ftco-section oceanova-menu-section">
		<div class="container">
			<div class="row justify-content-center mb-5 pb-2">
				<div class="col-md-7 text-center heading-section ftco-animate">
					<span class="subheading">Specialties</span>
					<h2 class="mb-4">Our Menu</h2>
				</div>
			</div>
			<div class="row">
				@forelse(($featuredMeals ?? []) as $index => $meal)
					<div class="col-md-6 col-lg-4">
						<div class="menu-wrap">
							<div class="menus border-bottom-0 d-flex ftco-animate">
								<div class="menu-img img" style="background-image: url('{{ $meal['image_url'] }}');"></div>
								<div class="text">
									<div class="d-flex">
										<div class="one-half">
											<h3>{{ $meal['name'] }}</h3>
										</div>
										<div class="one-forth">
											<span class="price">{{ $meal['price'] }}</span>
										</div>
									</div>
									@if(!empty($meal['description']))
										<p>{{ strlen($meal['description']) > 95 ? substr($meal['description'], 0, 95).'...' : $meal['description'] }}</p>
									@elseif(!empty($meal['section']))
										<p><span>{{ $meal['section'] }}</span></p>
									@endif
								</div>
							</div>
						</div>
					</div>
				@empty
					<div class="col-12 text-center">
						<p class="mb-0">Menu items are currently being prepared.</p>
					</div>
				@endforelse
				<div class="col-12 text-center mt-4">
					<a href="{{ route('menu') }}" class="btn btn-primary">View Full Menu</a>
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
									<p class="mb-4">The ambiance is calm and elegant, and the seafood platter was perfectly seasoned. We’ll definitely be back for another date night.</p>
									<div class="user-img mb-4" style="background-image: url({{ asset('assets/template/images/oceanova-testimony.png') }});">
										<span class="quote d-flex align-items-center justify-content-center">
											<i class="fa fa-quote-left"></i>
										</span>
									</div>
									<p class="name">Adaeze N.</p>
									<span class="position">Customer</span>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="testimony-wrap text-center">
								<div class="text p-3">
									<p class="mb-4">Service was fast and friendly, and the grilled prawns were the best I’ve had in Lagos. The view and music sealed it.</p>
									<div class="user-img mb-4" style="background-image: url({{ asset('assets/template/images/oceanova-testimony.png') }});">
										<span class="quote d-flex align-items-center justify-content-center">
											<i class="fa fa-quote-left"></i>
										</span>
									</div>
									<p class="name">Tobi A.</p>
									<span class="position">Customer</span>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="testimony-wrap text-center">
								<div class="text p-3">
									<p class="mb-4">Oceanova feels premium without being stiff. Every course was balanced, and the dessert tasting left us speechless.</p>
									<div class="user-img mb-4" style="background-image: url({{ asset('assets/template/images/oceanova-testimony.png') }});">
										<span class="quote d-flex align-items-center justify-content-center">
											<i class="fa fa-quote-left"></i>
										</span>
									</div>
									<p class="name">Chinedu K.</p>
									<span class="position">Customer</span>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="testimony-wrap text-center">
								<div class="text p-3">
									<p class="mb-4">We booked for a family celebration and everything was on point—from the starters to the main course. Highly recommended.</p>
									<div class="user-img mb-4" style="background-image: url({{ asset('assets/template/images/oceanova-testimony.png') }});">
										<span class="quote d-flex align-items-center justify-content-center">
											<i class="fa fa-quote-left"></i>
										</span>
									</div>
									<p class="name">Ifeoma R.</p>
									<span class="position">Customer</span>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="testimony-wrap text-center">
								<div class="text p-3">
									<p class="mb-4">Great flavors, clean plating, and the staff really paid attention to our preferences. Oceanova is now my go‑to.</p>
									<div class="user-img mb-4" style="background-image: url({{ asset('assets/template/images/oceanova-testimony.png') }});">
										<span class="quote d-flex align-items-center justify-content-center">
											<i class="fa fa-quote-left"></i>
										</span>
									</div>
									<p class="name">Seyi M.</p>
									<span class="position">Customer</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="ftco-section bg-light">
		<div class="container">
			<div class="row justify-content-center mb-5 pb-2">
				<div class="col-md-7 text-center heading-section ftco-animate">
					<span class="subheading">Chef</span>
					<h2 class="mb-4">Our Master Chef</h2>
				</div>
			</div>	
			<div class="row">
				<div class="col-md-6 col-lg-3 ftco-animate">
					<div class="staff">
						<div class="chef-img" style="background-image: url('{{ asset('assets/template/images/chef-femi.png') }}');"></div>
						<div class="text px-4 pt-2">
							<h3>Chef Asogba Oluwafemi</h3>
							<span class="position mb-2">Head Chef</span>
							<div class="faded">
									<p>Passionate about hospitality, John oversees the dining experience and ensures every guest feels welcomed and cared for.</p>
								<ul class="ftco-social d-flex">
									<li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
									<li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
									<li class="ftco-animate"><a href="#"><span class="icon-google-plus"></span></a></li>
									<li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-lg-3 ftco-animate">
					<div class="staff">
						<div class="chef-img" style="background-image: url('{{ asset('assets/template/images/Chef-Gold.png') }}');"></div>
						<div class="text px-4 pt-2">
							<h3>Chef Victoria</h3>
							<span class="position mb-2">Local Dish Chef</span>
							<div class="faded">
									<p>Michelle leads the kitchen with a focus on seasonal ingredients and bold flavors inspired by coastal cuisines.</p>
								<ul class="ftco-social d-flex">
									<li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
									<li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
									<li class="ftco-animate"><a href="#"><span class="icon-google-plus"></span></a></li>
									<li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-lg-3 ftco-animate">
					<div class="staff">
						<div class="chef-img" style="background-image: url('{{ asset('assets/template/images/Chef-Diamond.png') }}');"></div>
						<div class="text px-4 pt-2">
							<h3>Chef Sam</h3>
							<span class="position mb-2">International Cook</span>
							<div class="faded">
									<p>Chef Sam brings a global perspective to the kitchen, creating dishes that blend flavors from around the world.</p>
								<ul class="ftco-social d-flex">
									<li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
									<li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
									<li class="ftco-animate"><a href="#"><span class="icon-google-plus"></span></a></li>
									<li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-lg-3 ftco-animate">
					<div class="staff">
						<div class="chef-img" style="background-image: url('{{ asset('assets/template/images/chef-opeyemi.png') }}');"></div>
						<div class="text px-4 pt-2">
							<h3>Chef Akinwande</h3>
							<span class="position mb-2">Pastry Chef</span>
							<div class="faded">
									<p>Opeyemi Akinwande is a pastry chef who crafts delicate desserts and rich cakes for every occasion.</p>
								<ul class="ftco-social d-flex">
									<li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
									<li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
									<li class="ftco-animate"><a href="#"><span class="icon-google-plus"></span></a></li>
									<li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="ftco-section ftco-no-pt ftco-no-pb">
		<div class="container">
			<div class="row d-flex">
				<div class="col-md-6 d-flex">
					<div class="img img-2 w-100 mr-md-2" style="background-image: url({{ asset('assets/template/images/chef-f.png') }});"></div>
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

	<section class="ftco-section bg-light">
		<div class="container">
			<div class="row justify-content-center mb-5 pb-2">
				<div class="col-md-7 text-center heading-section ftco-animate">
					<span class="subheading">Blog</span>
					<h2 class="mb-4">Recent Blog</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4 ftco-animate">
					<div class="blog-entry">
						<a href="{{ route('blog.single') }}" class="block-20" style="background-image: url('{{ asset('assets/template/images/image_1.png') }}');">
						</a>
						<div class="text px-4 pt-3 pb-4">
							<div class="meta">
								<div><a href="#">August 3, 2020</a></div>
								<div><a href="#">Admin</a></div>
							</div>
							<h3 class="heading"><a href="#">Taste the best pizza and seafood in Ajah!!</a></h3>
							<p class="clearfix">
								<a href="{{ route('blog.single') }}" class="float-left read btn btn-primary">Read more</a>
								<a href="#" class="float-right meta-chat"><span class="fa fa-comment"></span> 3</a>
							</p>
						</div>
					</div>
				</div>
				<div class="col-md-4 ftco-animate">
					<div class="blog-entry">
						<a href="{{ route('blog.single') }}" class="block-20" style="background-image: url('{{ asset('assets/template/images/image_2.png') }}');">
						</a>
						<div class="text px-4 pt-3 pb-4">
							<div class="meta">
								<div><a href="#">July 29, 2026</a></div>
								<div><a href="#">Admin</a></div>
							</div>
							<h3 class="heading"><a href="#">Join us for our Special Independence Day</a></h3>
							<p class="clearfix">
								<a href="{{ route('blog.single') }}" class="float-left read btn btn-primary">Read more</a>
								<a href="#" class="float-right meta-chat"><span class="fa fa-comment"></span> 3</a>
							</p>
						</div>
					</div>
				</div>
				<div class="col-md-4 ftco-animate">
					<div class="blog-entry">
						<a href="{{ route('blog.single') }}" class="block-20" style="background-image: url('{{ asset('assets/template/images/image_3.png') }}');">
						</a>
						<div class="text px-4 pt-3 pb-4">
							<div class="meta">
								<div><a href="#">August 3, 2020</a></div>
								<div><a href="#">Admin</a></div>
							</div>
							<h3 class="heading"><a href="#">Get 50% off all orders this Saturday only!</a></h3>
							<p class="clearfix">
								<a href="{{ route('blog.single') }}" class="float-left read btn btn-primary">Read more</a>
								<a href="#" class="float-right meta-chat"><span class="fa fa-comment"></span> 3</a>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="ftco-section ftco-no-pt ftco-no-pb" id="find-us">
		<div class="container-fluid px-0">
			<div class="row justify-content-center mb-5 pt-5">
				<div class="col-md-7 text-center heading-section ftco-animate">
					<span class="subheading">Location</span>
					<h2 class="mb-2">Find Us</h2>
					<p class="text-muted">Plot 7, 8 Okun-Ajah Community Rd, Eti-Osa, Lekki 105102, Lagos</p>
				</div>
			</div>
			<div class="row no-gutters">
				<div class="col-12">
					<iframe
						src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d990.7!2d3.592447!3d6.427556!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x103bf9f5cced5d09%3A0x7357e78e27164837!2sOceanova%20restaurant!5e0!3m2!1sen!2sng!4v1"
						width="100%"
						height="450"
						style="border:0; display:block;"
						allowfullscreen=""
						loading="lazy"
						referrerpolicy="no-referrer-when-downgrade"
						title="Oceanova Restaurant Location">
					</iframe>
				</div>
			</div>
		</div>
	</section>

	<section class="ftco-section ftco-no-pt ftco-no-pb ftco-intro bg-primary">
		<div class="container py-5">
			<div class="row py-2">
				<div class="col-md-12 text-center">
					<h2>We Make Delicious &amp; Nutritious Food</h2>
					<a href="https://oceanova.ng/reservation" class="btn btn-white btn-outline-white">Book A Table Now</a>
				</div>
			</div>
		</div>
	</section>
@endsection
