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
               $data = query("select * from tbl_user where username='$sesi'");
               $dt = mysql_fetch_array($data);
               ?>
              <h1 class="h4"><?php echo  $dt['nama']?></h1>
              <p><?php echo $dt['level'] ?></p> </br>
            </div>
          </div>
          <!-- Sidebar Navidation Menus--><span class="heading">MENU</span>
          <ul class="list-unstyled">
            <li><a href="index.php"> <i class="icon-home"></i>Dashboard</a></li>
            <li><a href="inbound.php"> <i class="icon-screen"></i>Terima Paket</a></li>
            <li><a href="kirim.php"> <i class="icon-screen"></i>Kirim Paket</a></li>
            <li><a href="kurir.php"> <i class="icon-presentation"></i>Data Kurir</a></li>
            <li><a href="delivery.php"> <i class="icon-presentation"></i>Delivery</a></li>
        </nav>