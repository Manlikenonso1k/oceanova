@extends('layouts.app')

@section('content')
<section class="hero-wrap hero-wrap-2" style="background-image: url('{{ asset('assets/template/images/bg_5.jpg') }}');" data-stellar-background-ratio="0.5">
  <div class="overlay"></div>
  <div class="container">
    <div class="row no-gutters slider-text align-items-end justify-content-center">
      <div class="col-md-9 ftco-animate text-center mb-5">
        <h1 class="mb-2 bread">Oceanova Grand Opening</h1>
        <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">Home <i class="fa fa-chevron-right"></i></a></span> <span class="mr-2"><a href="{{ route('blog') }}">Blog <i class="fa fa-chevron-right"></i></a></span> <span>Oceanova Grand Opening <i class="fa fa-chevron-right"></i></span></p>
      </div>
    </div>
  </div>
</section>

<section class="ftco-section">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 ftco-animate">
        <h1 class="mb-3">Oceanova Grand Opening: A New Food Landmark in Ajah</h1>

        <p class="lead">Ajah just gained a new culinary beacon — Oceanova Restaurant (Fine Dining &amp; Seafood) teams up with neighborhood favorite Moritho’s Pizza for an unforgettable grand opening. Expect refined seafood, artisan pizza craft, and community warmth rolled into one day-long celebration.</p>

        <ul class="mb-3">
          <li><strong>Event:</strong> Official Grand Opening Celebration</li>
          <li><strong>Offer:</strong> 50% DISCOUNT on the entire menu — one day only</li>
          <li><strong>When:</strong> Saturday, February 25, 2026 — 9:00 AM to 9:00 PM</li>
          <li><strong>Where:</strong> 7/8 Okun-Ajah Community Road, Off New Coastal Road, Ajah, Lagos</li>
          <li><strong>RSVP &amp; Menu:</strong> <a href="tel:07082820267">0708 282 0267</a> — <a href="https://bit.ly/3ZVlzGw" target="_blank" rel="noopener">View the menu</a></li>
        </ul>

        <p>Oceanova brings polished, coastal fine dining to Ajah with show-stopping seafood platters and delicate pastries, while Moritho’s Pizza serves artisan hand-stretched pies that many locals call the <em>Best Pizza in Lagos</em>. From rich, buttery lobster to wood-fired Margheritas and creamy house-made ice cream, the menu is built for sharing and celebration.</p>

        <p>This one-day 50% DISCOUNT is a rare chance to taste Oceanova’s elevated seafood alongside Moritho’s community-minded pizza at half the price. It’s perfect for food lovers searching for the best <strong>Restaurant in Ajah</strong> or families planning a special weekend outing. Expect warm hospitality, quick service, and menus that highlight ocean-fresh ingredients and artisanal baking.</p>

        <p>Bring friends and family — there will be something for everyone: Seafood Platters, Artisan Pizzas, Pastries, Ice Cream, and Fine Dining options. Spaces may fill fast — RSVP now at <a href="tel:07082820267">0708 282 0267</a>. Join us at the Oceanova Grand Opening and make Saturday a delicious Ajah memory.</p>
        <div class="tag-widget post-tag-container mb-5 mt-5">
          <div class="tagcloud">
            <a href="#" class="tag-cloud-link">Food</a>
            <a href="#" class="tag-cloud-link">Wine</a>
            <a href="#" class="tag-cloud-link">Drink</a>
            <a href="#" class="tag-cloud-link">Dish</a>
          </div>
        </div>

        <div class="about-author d-flex p-4 bg-light">
          <div class="bio mr-5">
            <img src="{{ asset('assets/template/images/oceanova-testimony.png') }}" alt="Author portrait" class="img-fluid mb-4">
          </div>
          <div class="desc">
            <h3>George Washington</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus itaque, autem necessitatibus voluptate quod mollitia delectus aut, sunt placeat nam vero culpa sapiente consectetur similique, inventore eos fugit cupiditate numquam!</p>
          </div>
        </div>

        <div class="pt-5 mt-5">
          <h3 class="mb-5 h4 font-weight-bold p-4 bg-light">07 Feedbacks</h3>
          <ul class="comment-list">
            <li class="comment">
              <div class="vcard bio">
                <img src="{{ asset('assets/template/images/oceanova-testimony.png') }}" alt="Commenter avatar">
              </div>
              <div class="comment-body">
                <h3>John Doe</h3>
                <div class="meta mb-2">August 3, 2020 at 2:21pm</div>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Pariatur quidem laborum necessitatibus, ipsam impedit vitae autem, eum officia, fugiat saepe enim sapiente iste iure! Quam voluptas earum impedit necessitatibus, nihil?</p>
                <p><a href="#" class="reply">Reply</a></p>
              </div>
            </li>

            <li class="comment">
              <div class="vcard bio">
                <img src="{{ asset('assets/template/images/oceanova-testimony.png') }}" alt="Commenter avatar">
              </div>
              <div class="comment-body">
                <h3>John Doe</h3>
                <div class="meta mb-2">August 3, 2020 at 2:21pm</div>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Pariatur quidem laborum necessitatibus, ipsam impedit vitae autem, eum officia, fugiat saepe enim sapiente iste iure! Quam voluptas earum impedit necessitatibus, nihil?</p>
                <p><a href="#" class="reply">Reply</a></p>
              </div>

              <ul class="children">
                <li class="comment">
                  <div class="vcard bio">
                    <img src="{{ asset('assets/template/images/oceanova-testimony.png') }}" alt="Commenter avatar">
                  </div>
                  <div class="comment-body">
                    <h3>John Doe</h3>
                    <div class="meta mb-2">August 3, 2020 at 2:21pm</div>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Pariatur quidem laborum necessitatibus, ipsam impedit vitae autem, eum officia, fugiat saepe enim sapiente iste iure! Quam voluptas earum impedit necessitatibus, nihil?</p>
                    <p><a href="#" class="reply">Reply</a></p>
                  </div>

                  <ul class="children">
                    <li class="comment">
                      <div class="vcard bio">
                        <img src="{{ asset('assets/template/images/oceanova-testimony.png') }}" alt="Commenter avatar">
                      </div>
                      <div class="comment-body">
                        <h3>John Doe</h3>
                        <div class="meta mb-2">August 3, 2020 at 2:21pm</div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Pariatur quidem laborum necessitatibus, ipsam impedit vitae autem, eum officia, fugiat saepe enim sapiente iste iure! Quam voluptas earum impedit necessitatibus, nihil?</p>
                        <p><a href="#" class="reply">Reply</a></p>
                      </div>

                      <ul class="children">
                        <li class="comment">
                          <div class="vcard bio">
                            <img src="{{ asset('assets/template/images/oceanova-testimony.png') }}" alt="Commenter avatar">
                          </div>
                          <div class="comment-body">
                            <h3>John Doe</h3>
                            <div class="meta mb-2">August 3, 2020 at 2:21pm</div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Pariatur quidem laborum necessitatibus, ipsam impedit vitae autem, eum officia, fugiat saepe enim sapiente iste iure! Quam voluptas earum impedit necessitatibus, nihil?</p>
                            <p><a href="#" class="reply">Reply</a></p>
                          </div>
                        </li>
                      </ul>
                    </li>
                  </ul>
                </li>
              </ul>
            </li>

            <li class="comment">
              <div class="vcard bio">
                <img src="{{ asset('assets/template/images/oceanova-testimony.png') }}" alt="Commenter avatar">
              </div>
              <div class="comment-body">
                <h3>John Doe</h3>
                <div class="meta mb-2">August 3, 2020 at 2:21pm</div>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Pariatur quidem laborum necessitatibus, ipsam impedit vitae autem, eum officia, fugiat saepe enim sapiente iste iure! Quam voluptas earum impedit necessitatibus, nihil?</p>
                <p><a href="#" class="reply">Reply</a></p>
              </div>
            </li>
          </ul>

          <div class="comment-form-wrap pt-5">
            <h3 class="mb-5 h4 font-weight-bold p-4 bg-light">Leave a comment</h3>
            <form action="#" class="p-4 p-md-5 bg-light">
              <div class="form-group">
                <label for="name">Name *</label>
                <input type="text" class="form-control" id="name">
              </div>
              <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" class="form-control" id="email">
              </div>
              <div class="form-group">
                <label for="website">Website</label>
                <input type="url" class="form-control" id="website">
              </div>

              <div class="form-group">
                <label for="message">Message</label>
                <textarea name="" id="message" cols="30" rows="10" class="form-control"></textarea>
              </div>
              <div class="form-group">
                <input type="submit" value="Post Comment" class="btn py-3 px-4 btn-primary">
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="col-lg-4 sidebar ftco-animate">
        <div class="sidebar-box">
          <form action="#" class="search-form">
            <div class="form-group">
              <span class="icon icon-search"></span>
              <input type="text" class="form-control" placeholder="Type a keyword and hit enter">
            </div>
          </form>
        </div>
        <div class="sidebar-box ftco-animate">
          <h3>Category</h3>
          <ul class="categories">
            <li><a href="#">Breakfast <span>(6)</span></a></li>
            <li><a href="#">Lunch <span>(8)</span></a></li>
            <li><a href="#">Dinner <span>(2)</span></a></li>
            <li><a href="#">Desserts <span>(2)</span></a></li>
            <li><a href="#">Drinks <span>(2)</span></a></li>
            <li><a href="#">Wine <span>(2)</span></a></li>
          </ul>
        </div>

        <div class="sidebar-box ftco-animate">
          <h3>Popular Articles</h3>
          <div class="block-21 mb-4 d-flex">
            <a class="blog-img mr-4" style="background-image: url({{ asset('assets/template/images/image_1.jpg') }});"></a>
            <div class="text">
              <h3 class="heading"><a href="#">Get 50% off all orders this Saturday only!</a></h3>
              <div class="meta">
                <div><a href="#"><span class="icon-calendar"></span> Aug. 3, 2020</a></div>
                <div><a href="#"><span class="icon-person"></span> Dave Lewis</a></div>
                <div><a href="#"><span class="icon-chat"></span> 19</a></div>
              </div>
            </div>
          </div>
          <div class="block-21 mb-4 d-flex">
            <a class="blog-img mr-4" style="background-image: url({{ asset('assets/template/images/image_2.jpg') }});"></a>
            <div class="text">
              <h3 class="heading"><a href="#">Join us for our Grand Opening this weekend!</a></h3>
              <div class="meta">
                <div><a href="#"><span class="icon-calendar"></span> Aug. 3, 2020</a></div>
                <div><a href="#"><span class="icon-person"></span> Dave Lewis</a></div>
                <div><a href="#"><span class="icon-chat"></span> 19</a></div>
              </div>
            </div>
          </div>
          <div class="block-21 mb-4 d-flex">
            <a class="blog-img mr-4" style="background-image: url({{ asset('assets/template/images/image_3.jpg') }});"></a>
            <div class="text">
              <h3 class="heading"><a href="#">Taste the best pizza and seafood in Ajah!!</a></h3>
              <div class="meta">
                <div><a href="#"><span class="icon-calendar"></span> Aug. 3, 2020</a></div>
                <div><a href="#"><span class="icon-person"></span> Dave Lewis</a></div>
                <div><a href="#"><span class="icon-chat"></span> 19</a></div>
              </div>
            </div>
          </div>
        </div>

        <div class="sidebar-box ftco-animate">
          <h3>Tag Cloud</h3>
          <ul class="tagcloud m-0 p-0">
            <a href="#" class="tag-cloud-link">Dish</a>
            <a href="#" class="tag-cloud-link">Food</a>
            <a href="#" class="tag-cloud-link">Lunch</a>
            <a href="#" class="tag-cloud-link">Menu</a>
            <a href="#" class="tag-cloud-link">Dessert</a>
            <a href="#" class="tag-cloud-link">Drinks</a>
            <a href="#" class="tag-cloud-link">Sweets</a>
          </ul>
        </div>

        <div class="sidebar-box ftco-animate">
          <h3>Archives</h3>
          <ul class="categories">
            <li><a href="#">January 2020 <span>(20)</span></a></li>
            <li><a href="#">February 2020 <span>(30)</span></a></li>
            <li><a href="#">March 2020 <span>(20)</span></a></li>
            <li><a href="#">April 2020 <span>(6)</span></a></li>
            <li><a href="#">May 2020 <span>(8)</span></a></li>
          </ul>
        </div>

        <div class="sidebar-box ftco-animate">
          <h3>Paragraph</h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus itaque, autem necessitatibus voluptate quod mollitia delectus aut, sunt placeat nam vero culpa sapiente consectetur similique, inventore eos fugit cupiditate numquam!</p>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
