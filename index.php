<!DOCTYPE html>
<html lang="en">

<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>Pahuna</title>

<?php require("pages/links.php")
  //date_default_timezone_set("Asia/Kolkata or KTM");   if ever error with TIME 
  ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
  .availability {
    margin-top: -100px;
    z-index: 2;
    position: relative;
  }

  @media screen and (max-width: 550px) {
    .availability {
      margin-top: 10px;
      z-index: 2;
      position: relative;
      padding: 0 25px;
    }
  }

  .heading {
    position: absolute;
    z-index: 2;
    background: transparent;
    color: white;
    margin: 100px 100px;
    background-color: rgba(0, 0, 0, 0.5);
    padding: 30px;
    border-radius: 30px;
  }
</style>
</head>

<body class="bg-light">
  <?php require("pages/header.php") ?>


  <!-- <div class="heading">
    <h1>
      SAY HELLO TO YOUR <br />
      LOCAL HOTEL GUIDE!
    </h1>
  </div> -->

  <!-- Images swiperjs -->
  <div class="container-fluid px-lg-3 mt-3">
    <div class="swiper swiper-container">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <img src="images/1.png" height="500px" class="w-100 d-block" />
        </div>
        <div class="swiper-slide">
          <img src="images/2.png" height="500px" class="w-100 d-block" />
        </div>
        <div class="swiper-slide">
          <img src="images/hotel3.jpg" height="500px" class="w-100 d-block" />
        </div>
        <div class="swiper-slide">
          <img src="images/hotel-exterior.jpg" height="500px" class="w-100 d-block" />
        </div>
        <div class="swiper-slide">
          <img src="images/hotel5.jpg" height="500px" class="w-100 d-block" />
        </div>
        <div class="swiper-slide">
          <img src="images/nepali-ghar.jpg" height="500px" class="w-100 d-block" />
        </div>
      </div>
    </div>
  </div>

  <!-- Available Bookings -->
  <div class="container availability">
    <div class="row">
      <div class="col-lg-17 bg-white shadow p-4 rounded">
        <h5>CHECK BOOKING AVAILABILITY</h5>
        <form action="hotels.php">
          <div class="row align-items-end justify-content-between">
            <div class="col-lg-3 mb-3">
              <label class="form-label" style="font-weight: 500">Check-In</label>
              <input type="date" class="form-control shadow-none" name="checkin" required />
            </div>
            <div class="col-lg-3 mb-3">
              <label class="form-label" style="font-weight: 500">Check-Out</label>
              <input type="date" class="form-control shadow-none" name="checkout" required />
            </div>
            <input type="hidden" name="check_availability">
            <div class="col-lg-1 mb-lg-4 mt-2 ">
              <button type="submit" class="btn-primary text-white shadow-none custom-bg">
                Search
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Rooms -->

  <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font" id="Hotel">
    Rooms Available
  </h2>
  <div class="container">
    <div class="row">

      <?php
      $room_res = select('SELECT * FROM `rooms` WHERE `status`=? and `remove`=? ORDER by `id` DESC LIMIT 6', [1, 0], 'ii');
      while ($room_data = mysqli_fetch_assoc($room_res)) {

        $choices_q = mysqli_query($con, "SELECT c.name FROM `choices` c INNER join `room_choices` rc 
        on c.id = rc.choice_id where rc.room_id = '$room_data[id]'");

        $choices_data = "";
        while ($cho_row = mysqli_fetch_assoc($choices_q)) {
          $choices_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>
               $cho_row[name]
               </span>";
        }

        $facilities_q = mysqli_query($con, "SELECT f.name FROM `facilities` f INNER join `room_facilities` rf
        on f.id = rf.facilities_id where rf.room_id = '$room_data[id]'");

        $facilities_data = "";

        while ($cho_row = mysqli_fetch_assoc($facilities_q)) {
          $facilities_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>
               $cho_row[name]
               </span>";
        }

        $book_btn = "";

        if (!$settings_r['shutdown']) {
          $login = 0;
          if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
            $login = 1;
          }
          $book_btn = "<button onclick = 'checkLogin($login,$room_data[id])' class='btn btn-sm text-white shadow-none custom-bg btn-outline-dark'>Book Now</button>";
        }

        echo <<<data
      <div class="col-lg-4 col-md-6 my-3">
        <div class="card border-0 shadow" style="max-width: 360px; margin: auto">
          <img src="images/images/rooms/1.jpg" class="card-imp-top" />
          <div class="card-body">
            <h5>$room_data[name]</h5>
            <h6 class="mb-4">Rs. $room_data[price]</h6>
            <div class="choices mb-4">
              <h6 class="mb-1">Choices</h6>
              $choices_data
             
            </div>
            <div class="facilities mb-4">
            <h6 class="mb-1">Facilities</h6>
            $facilities_data
              
            </div>
            <div class="guests mb-4">
              <h6 class="mb-1">Guests</h6>
              <span class="badge rounded-pill bg-light text-dark text-wrap">
                $room_data[adult] Adults
              </span>
              <span class="badge rounded-pill bg-light text-dark text-wrap">
                $room_data[children] Children
              </span>
            </div>
            <div class="rating mb-4">
              <h6 class="mb-1">Rating</h6>
              <span class="badge rounded-pill bg-light">
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
              </span>
            </div>
            <div class="d-flex justify-content-evenly mb-2">
            $book_btn
            </div>
          </div>
        </div>
      </div>
      data;
      }
      ?>



      <div class="col-lg-12 text-center mt-5">
        <a href="about.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">Know more>>></a>
      </div>
    </div>
  </div>

  <!-- Facilities -->

  <!-- Testimonials -->

  <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Testimonials</h2>

  <div class="container mt-5">
    <div class="swiper swiper-testimonials">
      <div class="swiper-wrapper mb-5">
        <div class="swiper-slide bg-white shadow p-4">
          <div class="profile d-flex align-items-center mb-2">
            <img src="https://swiperjs.com/demos/images/nature-1.jpg" width="30px" />
            <h6 class="m-0 ms-2">Random User1</h6>
          </div>
          <p>
            Lorem ipsum, dolor sit amet consectetur adipisicing elit. Rem
            excepturi enim fugit.
          </p>
          <div class="rating">
            <i class="bi bi-star-fill text-warning"></i>
            <i class="bi bi-star-fill text-warning"></i>
            <i class="bi bi-star-fill text-warning"></i>
            <i class="bi bi-star-fill text-warning"></i>
          </div>
        </div>
        <div class="swiper-slide bg-white shadow p-4">
          <div class="profile d-flex align-items-center mb-2">
            <img src="https://swiperjs.com/demos/images/nature-1.jpg" width="30px" />
            <h6 class="m-0 ms-2">Random User2</h6>
          </div>
          <p>
            Lorem ipsum dolor, sit amet consectetur adipisicing elit.
            Similique recusandae ullam maxime, est unde enim voluptas debitis
            officiis nobis minima? Autem sunt reiciendis aliquid dolores.
          </p>
          <div class="rating">
            <i class="bi bi-star-fill text-warning"></i>
            <i class="bi bi-star-fill text-warning"></i>
            <i class="bi bi-star-fill text-warning"></i>
            <i class="bi bi-star-fill text-warning"></i>
          </div>
        </div>
        <div class="swiper-slide bg-white shadow p-4">
          <div class="profile d-flex align-items-center mb-2">
            <img src="https://swiperjs.com/demos/images/nature-1.jpg" width="30px" />
            <h6 class="m-0 ms-2">Random User3</h6>
          </div>
          <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Illum
            sunt neque est tempore harum autem rem distinctio, provident
            itaque? Et explicabo consequatur voluptas nesciunt optio laborum
            earum, inventore non, ut, deserunt blanditiis repellat.
          </p>
          <div class="rating">
            <i class="bi bi-star-fill text-warning"></i>
            <i class="bi bi-star-fill text-warning"></i>
            <i class="bi bi-star-fill text-warning"></i>
            <i class="bi bi-star-fill text-warning"></i>
          </div>
        </div>

        <div class="swiper-slide bg-white shadow p-4">
          <div class="profile d-flex align-items-center mb-2">
            <img src="https://swiperjs.com/demos/images/nature-1.jpg" width="30px" />
            <h6 class="m-0 ms-2">Random User4</h6>
          </div>
          <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Illum
            sunt neque est tempore harum autem rem distinctio, provident
            itaque? Et explicabo consequatur voluptas nesciunt optio laborum
            earum, inventore non, ut, deserunt blanditiis repellat.
          </p>
          <div class="rating">
            <i class="bi bi-star-fill text-warning"></i>
            <i class="bi bi-star-fill text-warning"></i>
            <i class="bi bi-star-fill text-warning"></i>
            <i class="bi bi-star-fill text-warning"></i>
          </div>
        </div>
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </div>

  <!-- Reach me -->

  <?php
  $contact_q = "SELECT * FROM `contact_details` WHERE `s_no`=?";
  $values = [1];
  $contact_r = mysqli_fetch_assoc(select($contact_q, $values, 'i'));
  // print_r($contact_r);
  ?>

  <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Reach Us</h2>

  <div class="container">
    <div class="row">
      <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white rounded">
        <iframe class="w-100 rounded" src="<?php echo $contact_r['gmap'] ?>" height="320" style="border: 0"
          allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
      <div class="col-lg-4 col-md-4">
        <div class="bg-white p-4 rounded mb-4">
          <h5>Call Us</h5>
          <a href="tel:+977983393332" class="d-inline-block mb-2 text-decoration-none text-dark"><i
              class="bi bi-phone me-2"></i>+<?php echo $contact_r['ph1'] ?></a><br />
          <a href="tel:+977983393332" class="d-inline-block mb-2 text-decoration-none text-dark"><i
              class="bi bi-phone me-2"></i>+<?php echo $contact_r['ph2'] ?></a>
        </div>

        <div class="bg-white p-4 rounded mb-4">
          <h5>Follow Us</h5>
          <a href="#" class="d-inline-block mb-3">
            <i class="bi bi-twitter me-1"></i>Twitter / X</a><br />
          <a href="tel:+977983393332" class="d-inline-block mb-2 text-decoration-none text-dark"><i
              call></i>+977983393332</a>
        </div>
      </div>
    </div>
  </div>

  <?php require("pages/footer.php") ?>

  <!-- JS scripts -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <script>
    var swiper = new Swiper(".swiper-container", {
      spaceBetween: 30,
      effect: "fade",
      loop: true,
      autoplay: {
        delay: 3000,
        disableonInteraction: false,
      },
    });
  </script>

  <script>
    var swiper = new Swiper(".swiper-testimonials", {
      effect: "coverflow",
      grabCursor: true,
      centeredSlides: true,
      slidesPerView: 3,
      loop: true,
      coverflowEffect: {
        slideShadows: false,
      },
      pagination: {
        el: ".swiper-pagination",
      },
      breakpoints: {
        320: {
          slidesPerView: 1,
        },
        640: {
          slidesPerView: 1,
        },
        768: {
          slidesPerView: 3,
        },
        1024: {
          slidesPerView: 3,
        },
      },
    });
  </script>
</body>

</html>