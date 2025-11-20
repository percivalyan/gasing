 <header id="header" class="header d-flex align-items-center sticky-top">
     <div class="container-fluid container-xl position-relative d-flex align-items-center">

         <a href="{{ url('/') }}" class="logo d-flex align-items-center me-auto">
             <img src="{{ asset('landingpage/assets/img/logo_gasing.jpg') }}" alt="Logo Gasing" class="me-2"
                 style="max-height: 60px; width: auto;">
         </a>

         <nav id="navmenu" class="navmenu">
             <ul>
                 <li><a href="{{ url('/') }}">Home<br></a></li>
                 <li><a href="about.html">About</a></li>
                 <li><a href="courses.html">Courses</a></li>
                 <li><a href="trainers.html">Trainers</a></li>
                 <li><a href="events.html">Events</a></li>
                 <li><a href="pricing.html">Pricing</a></li>
                 <li class="dropdown"><a href="#"><span>Dropdown</span> <i
                             class="bi bi-chevron-down toggle-dropdown"></i></a>
                     <ul>
                         <li><a href="#">Dropdown 1</a></li>
                         <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i
                                     class="bi bi-chevron-down toggle-dropdown"></i></a>
                             <ul>
                                 <li><a href="#">Deep Dropdown 1</a></li>
                                 <li><a href="#">Deep Dropdown 2</a></li>
                                 <li><a href="#">Deep Dropdown 3</a></li>
                                 <li><a href="#">Deep Dropdown 4</a></li>
                                 <li><a href="#">Deep Dropdown 5</a></li>
                             </ul>
                         </li>
                         <li><a href="#">Dropdown 2</a></li>
                         <li><a href="#">Dropdown 3</a></li>
                         <li><a href="#">Dropdown 4</a></li>
                     </ul>
                 </li>
                 <li><a href="contact.html">Contact</a></li>
             </ul>
             <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
         </nav>

         <a class="btn-getstarted" href="courses.html">Get Started</a>

     </div>
 </header>
