<header>
    <nav class="navbar navbar-expand-lg center-nav transparent position-absolute navbar-light caret-none">
        <div class="container flex-lg-row flex-nowrap align-items-center">
          <div class="navbar-brand w-100">
            <ul class="navbar-nav flex-row align-items-center ms-auto">
              <li class="nav-item d-none d-md-block">
                <a href="https://wa.me/message/PX2KIAS47DZXD1" target="_blank" class="btn btn-sm btn-primary rounded-pill">تواصل معنا</a>
              </li>
              <li class="nav-item d-lg-none">
                <button class="hamburger offcanvas-nav-btn"><span></span></button>
              </li>
            </ul>
          </div>
          <div class="navbar-collapse offcanvas offcanvas-nav offcanvas-start">
            <div class="offcanvas-header d-lg-none">
              <h3 class="text-white fs-30 mb-0">سُكنة</h3>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body ms-lg-auto d-flex flex-column h-100">
              <ul class="navbar-nav p-0" dir="rtl">
                <li class="nav-item"><a class="nav-link" href="{{ route('front.home') }}">الرئيسية</a></li>
                <li class="nav-item"><a class="nav-link" href="#">عن سُكنة</a></li>
                <li class="nav-item"><a class="nav-link" href="#">الخدمات</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('front.developer.contact') }}">سجل كمطور عقاري</a></li>
              </ul>
              <!-- /.navbar-nav -->
              <div class="offcanvas-footer d-lg-none">
                <div>
                  <a href="mailto:first.last@email.com" class="link-inverse">info@sukna.app</a>
                  <nav class="nav social social-white mt-4">
                    <a href="https://www.linkedin.com/company/suknaapp/"><i class="uil uil-linkedin"></i></a>
                    <a href="https://www.instagram.com/sukna_app/"><i class="uil uil-instagram"></i></a>
                    <a href="https://x.com/sukna_app"><i class="uil uil-twitter"></i></a>
                    <a href="https://snapchat.com/t/Y49D2Qtn"><i class="uil uil-snapchat-ghost"></i></a>
                    <a href="https://www.youtube.com/@sukna_app"><i class="uil uil-youtube"></i></a>
                  </nav>
                  <!-- /.social -->
                </div>
              </div>
              <!-- /.offcanvas-footer -->
            </div>
            <!-- /.offcanvas-body -->
          </div>
          <!-- /.navbar-collapse -->
          <div class="navbar-other w-100 d-flex ms-auto">
              <a href="./index.html" class="navbar-nav flex-row align-items-center ms-auto">
                <img src="{{ asset('frontend/assets/img/logodark2.svg') }}" srcset="{{ asset('frontend/assets/img/logodark2.svg') }} 2x" width="100px" alt="" />
              </a>
            <!-- /.navbar-nav -->
          </div>
          <!-- /.navbar-other -->
        </div>
        <!-- /.container -->
    </nav>
</header>
<!-- /header -->