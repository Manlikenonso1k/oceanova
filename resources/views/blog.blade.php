@extends('layouts.app')

@section('content')
<section class="hero-wrap hero-wrap-2" style="background-image: url('{{ asset('assets/template/images/bg_5.jpg') }}');" data-stellar-background-ratio="0.5">
  <div class="overlay"></div>
  <div class="container">
    <div class="row no-gutters slider-text align-items-end justify-content-center">
      <div class="col-md-9 ftco-animate text-center mb-5">
        <h1 class="mb-2 bread">Blog</h1>
        <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">Home <i class="fa fa-chevron-right"></i></a></span> <span>Blog <i class="fa fa-chevron-right"></i></span></p>
      </div>
    </div>
  </div>
</section>

<section class="ftco-section bg-light">
  <div class="container">
    <div class="row">
      <div class="col-md-4 ftco-animate">
        <div class="blog-entry">
          <a href="{{ route('blog.single') }}" class="block-20" style="background-image: url('{{ asset('assets/template/images/image_1.jpg') }}');"></a>
          <div class="text px-4 pt-3 pb-4">
            <div class="meta">
              <div><a href="#">August 3, 2020</a></div>
              <div><a href="#">Admin</a></div>
            </div>
            <h3 class="heading"><a href="{{ route('blog.single') }}">Even the all-powerful Pointing has no control about the blind texts</a></h3>
            <p class="clearfix">
              <a href="{{ route('blog.single') }}" class="float-left read btn btn-primary">Read more</a>
              <a href="#" class="float-right meta-chat"><span class="fa fa-comment"></span> 3</a>
            </p>
          </div>
        </div>
      </div>
      <div class="col-md-4 ftco-animate">
        <div class="blog-entry">
          <a href="{{ route('blog.single') }}" class="block-20" style="background-image: url('{{ asset('assets/template/images/image_2.jpg') }}');"></a>
          <div class="text px-4 pt-3 pb-4">
            <div class="meta">
              <div><a href="#">August 3, 2020</a></div>
              <div><a href="#">Admin</a></div>
            </div>
            <h3 class="heading"><a href="{{ route('blog.single') }}">Even the all-powerful Pointing has no control about the blind texts</a></h3>
            <p class="clearfix">
              <a href="{{ route('blog.single') }}" class="float-left read btn btn-primary">Read more</a>
              <a href="#" class="float-right meta-chat"><span class="fa fa-comment"></span> 3</a>
            </p>
          </div>
        </div>
      </div>
      <div class="col-md-4 ftco-animate">
        <div class="blog-entry">
          <a href="{{ route('blog.single') }}" class="block-20" style="background-image: url('{{ asset('assets/template/images/image_3.jpg') }}');"></a>
          <div class="text px-4 pt-3 pb-4">
            <div class="meta">
              <div><a href="#">August 3, 2020</a></div>
              <div><a href="#">Admin</a></div>
            </div>
            <h3 class="heading"><a href="{{ route('blog.single') }}">Even the all-powerful Pointing has no control about the blind texts</a></h3>
            <p class="clearfix">
              <a href="{{ route('blog.single') }}" class="float-left read btn btn-primary">Read more</a>
              <a href="#" class="float-right meta-chat"><span class="fa fa-comment"></span> 3</a>
            </p>
          </div>
        </div>
      </div>
      <div class="col-md-4 ftco-animate">
        <div class="blog-entry">
          <a href="{{ route('blog.single') }}" class="block-20" style="background-image: url('{{ asset('assets/template/images/image_4.jpg') }}');"></a>
          <div class="text px-4 pt-3 pb-4">
            <div class="meta">
              <div><a href="#">August 3, 2020</a></div>
              <div><a href="#">Admin</a></div>
            </div>
            <h3 class="heading"><a href="{{ route('blog.single') }}">Even the all-powerful Pointing has no control about the blind texts</a></h3>
            <p class="clearfix">
              <a href="{{ route('blog.single') }}" class="float-left read btn btn-primary">Read more</a>
              <a href="#" class="float-right meta-chat"><span class="fa fa-comment"></span> 3</a>
            </p>
          </div>
        </div>
      </div>
      <div class="col-md-4 ftco-animate">
        <div class="blog-entry">
          <a href="{{ route('blog.single') }}" class="block-20" style="background-image: url('{{ asset('assets/template/images/image_5.jpg') }}');"></a>
          <div class="text px-4 pt-3 pb-4">
            <div class="meta">
              <div><a href="#">August 3, 2020</a></div>
              <div><a href="#">Admin</a></div>
            </div>
            <h3 class="heading"><a href="{{ route('blog.single') }}">Even the all-powerful Pointing has no control about the blind texts</a></h3>
            <p class="clearfix">
              <a href="{{ route('blog.single') }}" class="float-left read btn btn-primary">Read more</a>
              <a href="#" class="float-right meta-chat"><span class="fa fa-comment"></span> 3</a>
            </p>
          </div>
        </div>
      </div>
      <div class="col-md-4 ftco-animate">
        <div class="blog-entry">
          <a href="{{ route('blog.single') }}" class="block-20" style="background-image: url('{{ asset('assets/template/images/image_6.jpg') }}');"></a>
          <div class="text px-4 pt-3 pb-4">
            <div class="meta">
              <div><a href="#">August 3, 2020</a></div>
              <div><a href="#">Admin</a></div>
            </div>
            <h3 class="heading"><a href="{{ route('blog.single') }}">Even the all-powerful Pointing has no control about the blind texts</a></h3>
            <p class="clearfix">
              <a href="{{ route('blog.single') }}" class="float-left read btn btn-primary">Read more</a>
              <a href="#" class="float-right meta-chat"><span class="fa fa-comment"></span> 3</a>
            </p>
          </div>
        </div>
      </div>
    </div>
    <div class="row no-gutters my-5">
      <div class="col text-center">
        <div class="block-27">
          <ul>
            <li><a href="#">&lt;</a></li>
            <li class="active"><span>1</span></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#">4</a></li>
            <li><a href="#">5</a></li>
            <li><a href="#">&gt;</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
