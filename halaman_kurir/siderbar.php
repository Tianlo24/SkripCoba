 <div class="page-content d-flex align-items-stretch"> 
        <!-- Side Navbar -->
        <nav class="side-navbar">
          <!-- Sidebar Header-->
          <div class="sidebar-header d-flex align-items-center">
            <div class="avatar"><img src="img/user.png" alt="..." class="img-fluid rounded-circle"></div>
            <div class="title">
            <?php
               /* tes kalo bikin kelola akun nanti
               $data = query("select * from user where id_user=$id"); */
               $data = query("select * from kurir where username='$sesi'");
               $dt = mysql_fetch_array($data);
               ?>
              <h1 class="h4"><?php echo  $dt['nama_Kurir']?></h1>
            </div>
          </div>
          <!-- Sidebar Navidation Menus--><span class="heading">MENU</span>
          <ul class="list-unstyled">
            <li><a href="index.php"> <i class="icon-home"></i>Dashboard</a></li>
            <li><a href="delivery.php"> <i class="icon-home"></i>Delivery</a></li>
        </nav>