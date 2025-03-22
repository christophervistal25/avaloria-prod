<script>
import { Link, usePage } from "@inertiajs/vue3";

export default {
  props: [],
  components: {
    Link,
  },
  setup() {
    const page = usePage();

    return {
      page,
    };
  },
};
</script>
<template>
  <div class="left-sidebar bg-dark">
    <!-- LOGO -->
    <div class="brand bg-dark">
      <a href="/" class="logo">
        <div class="d-flex align-items-center p-2">
          <h4 class="fw-bolder text-white text-uppercase">
            <small> {{ page.props.app_name }} ADMIN PANEL</small>
          </h4>
        </div>
      </a>
    </div>
    <!--end logo-->
    <div class="menu-content h-100" data-simplebar>
      <div class="menu-body navbar-vertical tab-content">
        <div class="collapse navbar-collapse" id="sidebarCollapse">
          <!-- Navigation -->
          <ul class="navbar-nav">
            <li class="menu-label mt-0">M<span>ain</span></li>
            <li class="nav-item">
              <a href="/" class="nav-link">
                <div class="text-white">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    class="bi bi-house-fill"
                    viewBox="0 0 16 16"
                  >
                    <path
                      d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z"
                    />
                    <path
                      d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293z"
                    />
                  </svg>
                </div>
                <span class="mx-2 mt-1 text-white">Home</span>
              </a>
            </li>
            <li class="nav-item">
              <Link href="/administrator-panel/" class="nav-link">
                <div class="text-white">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    class="bi bi-speedometer"
                    viewBox="0 0 16 16"
                  >
                    <path
                      d="M8 2a.5.5 0 0 1 .5.5V4a.5.5 0 0 1-1 0V2.5A.5.5 0 0 1 8 2M3.732 3.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707M2 8a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 8m9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5m.754-4.246a.39.39 0 0 0-.527-.02L7.547 7.31A.91.91 0 1 0 8.85 8.569l3.434-4.297a.39.39 0 0 0-.029-.518z"
                    />
                    <path
                      fill-rule="evenodd"
                      d="M6.664 15.889A8 8 0 1 1 9.336.11a8 8 0 0 1-2.672 15.78zm-4.665-4.283A11.95 11.95 0 0 1 8 10c2.186 0 4.236.585 6.001 1.606a7 7 0 1 0-12.002 0"
                    />
                  </svg>
                </div>
                <span class="mx-2 mt-1 text-white">Dashboard</span>
              </Link>
            </li>
            <li class="menu-label mt-0">D<span>onations</span></li>
            <li class="nav-item">
              <Link href="/administrator-panel/gcash-donate-setup" class="nav-link">
                <div class="text-white">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    class="bi bi-currency-dollar"
                    viewBox="0 0 16 16"
                  >
                    <path
                      d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73z"
                    />
                  </svg>
                </div>
                <span class="mx-2 mt-1 text-white">GCash</span>
              </Link>
            </li>
            <li class="nav-item">
              <Link href="/administrator-panel/paypal-donate-setup" class="nav-link">
                <div class="text-white">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    class="bi bi-currency-dollar"
                    viewBox="0 0 16 16"
                  >
                    <path
                      d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73z"
                    />
                  </svg>
                </div>
                <span class="mx-2 mt-1 text-white">Paypal</span>
              </Link>
            </li>
            <li class="menu-label mt-0">M<span>aintenance</span></li>
            <li class="nav-item">
              <Link href="/administrator-panel/news" class="nav-link">
                <div class="text-white">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    class="bi bi-cloud-arrow-down-fill"
                    viewBox="0 0 16 16"
                  >
                    <path
                      d="M8 2a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 6.095 0 7.555 0 9.318 0 11.366 1.708 13 3.781 13h8.906C14.502 13 16 11.57 16 9.773c0-1.636-1.242-2.969-2.834-3.194C12.923 3.999 10.69 2 8 2m2.354 6.854-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 1 1 .708-.708L7.5 9.293V5.5a.5.5 0 0 1 1 0v3.793l1.146-1.147a.5.5 0 0 1 .708.708"
                    />
                  </svg>
                </div>
                <span class="mx-2 mt-1 text-white">News</span>
              </Link>
            </li>
            <li class="nav-item">
              <Link href="/administrator-panel/downloads" class="nav-link">
                <div class="text-white">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    class="bi bi-cloud-arrow-down-fill"
                    viewBox="0 0 16 16"
                  >
                    <path
                      d="M8 2a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 6.095 0 7.555 0 9.318 0 11.366 1.708 13 3.781 13h8.906C14.502 13 16 11.57 16 9.773c0-1.636-1.242-2.969-2.834-3.194C12.923 3.999 10.69 2 8 2m2.354 6.854-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 1 1 .708-.708L7.5 9.293V5.5a.5.5 0 0 1 1 0v3.793l1.146-1.147a.5.5 0 0 1 .708.708"
                    />
                  </svg>
                </div>
                <span class="mx-2 mt-1 text-white">Downloads</span>
              </Link>
            </li>
            <li class="nav-item">
              <Link href="/administrator-panel/accounts" class="nav-link">
                <div class="text-white">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    class="bi bi-person-check-fill"
                    viewBox="0 0 16 16"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M15.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L12.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0"
                    />
                    <path
                      d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"
                    />
                  </svg>
                </div>
                <span class="mx-2 mt-1 text-white">Accounts</span>
              </Link>
            </li>
            <li class="nav-item">
              <Link href="/administrator-panel/characters" class="nav-link">
                <div class="text-white">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    class="bi bi-person-vcard-fill"
                    viewBox="0 0 16 16"
                  >
                    <path
                      d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5M9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8m1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5m-1 2C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 0 2 13h6.96q.04-.245.04-.5M7 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0"
                    />
                  </svg>
                </div>
                <span class="mx-2 mt-1 text-white">Characters</span>
              </Link>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- end left-sidenav-->

  <!-- Top Bar Start -->
  <!-- Top Bar Start -->
  <div class="topbar">
    <!-- Navbar -->
    <nav class="navbar-custom" id="navbar-custom">
      <ul
        class="list-unstyled topbar-nav d-flex justify-content-between align-items-center mb-0"
      >
        <h5 class="ms-2 fw-bolder">
          <slot name="title"></slot>
        </h5>

        <li class="dropdown">
          <a
            class="nav-link dropdown-toggle nav-user"
            data-bs-toggle="dropdown"
            href="#"
            role="button"
            aria-haspopup="false"
            aria-expanded="false"
          >
          </a>
        </li>
        <!--end topbar-profile-->
      </ul>
      <!--end topbar-nav-->
    </nav>
    <!-- end navbar-->
  </div>
  <!-- Top Bar End -->

  <div class="page-wrapper">
    <!-- Page Content-->
    <div class="page-content-tab">
      <div class="container-fluid">
        <slot name="content"></slot>
      </div>
      <!-- container -->

      <!--Start Rightbar-->
      <!--end Rightbar-->

      <!--Start Footer-->
      <!-- Footer Start -->
      <footer class="footer text-center text-sm-start">&nbsp;</footer>
      <!-- end Footer -->
      <!--end footer-->
    </div>
    <!-- end page content -->
  </div>
  <!-- end page-wrapper -->

  <!-- Javascript  -->
  <!-- vendor js -->
</template>
