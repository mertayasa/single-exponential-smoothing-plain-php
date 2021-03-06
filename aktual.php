<?php
    session_start();
    require "koneksi.php";
    include "fungsi.php";

    if(!isset($_SESSION['login'])){
        header("Location: login.php");
    }
    
    if( isset($_GET['id_barang']) ) {
        $id_barang = mysqli_real_escape_string($conn, $_GET['id_barang']);
        $aktual = mysqli_query($conn, "SELECT a.nama_barang, a.id_barang, c.data_aktual, c.id_aktual, c.bulan, c.tahun FROM tb_barang a, tb_aktual c WHERE a.id_barang = c.id_barang AND (c.id_barang = '$id_barang')");
    }
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords"
        content="wrappixel, admin dashboard, html css dashboard, web dashboard, bootstrap 4 admin, bootstrap 4, css3 dashboard, bootstrap 4 dashboard, Ample lite admin bootstrap 4 dashboard, frontend, responsive bootstrap 4 admin template, Ample admin lite dashboard bootstrap 4 dashboard template">
    <meta name="description"
        content="Ample Admin Lite is powerful and clean admin dashboard template, inpired from Bootstrap Framework">
    <meta name="robots" content="noindex,nofollow">
    <title>Peramalan Produksi Chandra Collection</title>
    <link rel="canonical" href="https://www.wrappixel.com/templates/ample-admin-lite/" />
    <!-- Custom CSS -->
   <link href="css/style.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full"
        data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar" data-navbarbg="skin5">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header" data-logobg="skin6">
                    <!-- ============================================================== -->
                    <!-- Logo -->
                    <!-- ============================================================== -->
                    <a class="navbar-brand" href="dashboard.html">
                        <!-- Logo text -->
                        <span class="logo-text">
                        <img src="img/logo 1.jpg" alt="homepage" width="190" height="45"/>
                        </span>
                    </a>
                    <!-- ============================================================== -->
                    <!-- End Logo -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <a class="nav-toggler waves-effect waves-light text-dark d-block d-md-none"
                        href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
                    <ul class="navbar-nav d-none d-md-block d-lg-none">
                        <li class="nav-item">
                            <a class="nav-toggler nav-link waves-effect waves-light text-white"
                                href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
                        </li>
                    </ul>
                    <!-- ============================================================== -->
                    <!-- Right side toggle and nav items -->
                    <!-- ============================================================== -->
         
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar" data-sidebarbg="skin6">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <!-- User Profile-->
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="index.php" aria-expanded="false"><i class="fas fa-home fa-fw"
                                    aria-hidden="true"></i><span class="hide-menu">Beranda</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="daftarBarang.php" aria-expanded="false"><i class="fas fa-clipboard-list"
                                    aria-hidden="true"></i><span class="hide-menu">Data Barang</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="aktual.php" aria-expanded="false"><i class="fas fa-list-alt"
                                    aria-hidden="true"></i><span class="hide-menu">Data Aktual</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="hitungPeramalan.php" aria-expanded="false"><i class="fas fa-calculator"
                                    aria-hidden="true"></i><span class="hide-menu">Hitung Peramalan</span></a></li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="logout.php"
                                aria-expanded="false">
                                <i class="fas fa-power-off" aria-hidden="true"></i>
                                <span class="hide-menu">Keluar</span>
                            </a>
                        </li>
                    </ul>

                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb bg-white">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title text-uppercase font-medium font-14">Data Aktual</h4>
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <div class="d-md-flex">
                            <ol class="breadcrumb ml-auto">
                                <li><a href="#"></a></li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Data Aktual Barang</h4>
                                <h6 class="card-subtitle">Silahkan Pilih Barang!</h6>
                                <form action="" method="get">
                                    <div class="row">
                                        <div class="input-group col-md-4">
                                        <span class="input-group-text"><i class="fas fa-clipboard-list"></i></span>
                                            <select type="submit" name="id_barang" class="form-control" onchange="this.form.submit()">
                                            <option>Pilih barang ..</option>
                                            <?php 
                                                $pil = mysqli_query($conn, "SELECT * FROM tb_barang");
                                                while($p = mysqli_fetch_assoc($pil)){ ?>
                                                <option value="<?= $p['id_barang'] ?>"><?php echo $p['nama_barang'] ?></option>
                                            <?php } ?>			
                                            </select>
                                        <a class="btn btn-primary" href="aktual.php" role="button">Kembali</a> 
                                        </div>
                                    </div>
                                </form>
                                
                                <?php
                                    if(isset($_GET['id_barang'])){
                                        echo "<a href='tambahAktual.php?id_barang=$id_barang' class='btn btn-primary mt-4'>Tambah</a>";
                                ?>
                                <div class="table-responsive mt-4"> 
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                        <tr class="text-center">
                                            <th scope="col">No</th>
                                            <th scope="col">Nama Barang</th>
                                            <th scope="col">Bulan</th>
                                            <th scope="col">Tahun</th>
                                            <th scope="col">Data Aktual</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i = 1;
                                        
                                        while($row = mysqli_fetch_array($aktual)){?>
                                        <tr class="text-center">
                                            <td><?= $i; ?></td>
                                            <td><?= $row['nama_barang']; ?></td>
                                            <td><?= getNamaBulan($row['bulan']); ?></td>
                                            <td><?= $row['tahun']; ?></td>
                                            <td><?= $row['data_aktual']; ?></td>
                                            <td>
                                                <a class="btn btn-warning" href="editAktual.php?id_barang=<?=$id_barang?>&id_barang=<?= $row['id_barang'] ?>&id_aktual=<?= $row['id_aktual'] ?>" role="button">Edit</a> 
                                                <a class="btn btn-danger" href="deleteAktual.php?id_barang=<?=$id_barang?>&id_aktual=<?= $row['id_aktual'] ?>" role="button" onclick="return confirm('apakah anda yakin ingin menghapus data ini?');">Hapus</a>  
                                            </td>
                                        </tr> 
                                        <?php $i++; } ?>
                                        </tbody>
                                        <?php } else { 
                                            
                                        }?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer text-center"> 2021 ?? Chandra Collection
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="plugins/bower_components/popper.js/dist/umd/popper.min.js"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="js/app-style-switcher.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="js/custom.js"></script>
</body>

</html>