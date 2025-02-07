<header class="header">
        <nav class="navbar">
          <!-- Search Box
          <div class="search-box">
            <button class="dismiss"><i class="icon-close"></i></button>
            <form id="searchForm" action="#" role="search">
              <input type="search" placeholder="What are you looking for..." class="form-control">
            </form>
          </div> -->
          <div class="container-fluid">
            <div class="navbar-holder d-flex align-items-center justify-content-between">
              <!-- Navbar Header-->
              <div class="navbar-header">
                <!-- Navbar Brand --><a href="index.php" class="navbar-brand d-none d-sm-inline-block">
                  <div class="brand-text d-none d-lg-inline-block"><strong>EXPRESS EXPEDITION</strong></div>
                <!-- Toggle Button --> <a id="toggle-btn" href="#" class="menu-btn"><span></span><span></span><span></span></a>
              </div>
              <!-- Navbar Menu -->
              <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
<!--                 <?php 

                 $waktu = query("select * from tbl_token_user where username='$sesi'");
                 $wt = mysql_fetch_array($waktu);
                ?>
                <li class="nav-item">[<?php echo $wt['time_modified'] ?>]</li> -->
                <li class="nav-item"><a href="logout.php" class="nav-link logout"> <span>Logout</span><i class="fa fa-sign-out"></i></a></li>
              </ul>
            </div>
          </div>
        </nav>
      </header>