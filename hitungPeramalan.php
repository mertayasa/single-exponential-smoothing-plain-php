<?php
    session_start();
    require "koneksi.php";
    include "fungsi.php";
    if(!isset($_SESSION['login'])){
       header("Location: login.php");
    }
?>

<?php
    if(isset($_POST["hitungPeramalan"])){
        $id_barang = mysqli_real_escape_string($conn, $_POST['id_barang']);
        $tahun = mysqli_real_escape_string($conn, $_POST['tahun']);

        $barang = mysqli_query($conn, "SELECT * FROM tb_aktual WHERE id_barang='$id_barang' AND tb_aktual.tahun='$tahun'");
        $query_barang = mysqli_query($conn, "SELECT * FROM tb_barang WHERE id_barang='$id_barang'");
        $result_barang = mysqli_fetch_assoc($query_barang);

        if($barang->num_rows>0){
            $array = [];
            $actual = [];
            while($result = mysqli_fetch_assoc($barang)){
                $obj = [
                    'data_aktual' => $result['data_aktual'],
                    'bulan' => $result['bulan'],
                    'tahun' => $result['tahun'],
                    'id_barang' => $result['id_barang']
                ];
                array_push($array,$obj);
                array_push($actual,$obj['data_aktual']);
            }

            $count_array = count($array);
            if($count_array < 2){
                echo "<script>
                    alert('Jumlah data belum mencukup untuk diramal. Minimal terdapat 2 data aktual untuk produk ". $result_barang['nama_barang'] ." di database!');
                    window.history.back();    
                </script>";
            }else{
                $peramalan = peramalan($array);
    
                $bulan = [];
                foreach($peramalan['best_forecast'] as $forecast){
                    array_push($bulan, getNamaBulan($forecast['bulan']));
                }
    
                $forecast = [
                    '01' => [],    
                    '02' => [],    
                    '03' => [],    
                    '04' => [],    
                    '05' => [],    
                    '06' => [],    
                    '07' => [],    
                    '08' => [],    
                    '09' => [],    
                ];
    
                foreach($peramalan['forecast'] as $key => $value){
                    foreach($value as $val){
                        array_push($forecast[$key], $val['forecast']);
                    }
                }
    
                $json_bulan = json_encode($bulan, true);
                $forecast01 = json_encode($bulan, true);
                
    
                $encoded_forecast = json_encode($forecast);
            }

        }
        else{
            echo "<script>alert('Tidak ada data produksi...')</script>";
        }
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
    <!-- Favicon icon -->
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
                        <h4 class="page-title text-uppercase font-medium font-14">Hitung Peramalan</h4>
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
                                <h4 class="card-title">Peramalan</h4>
                                <h6 class="card-subtitle">Hitung Ramalan Disini!
                                    <code></code></h6>
                                <form action="" method="POST">
                                    <div class="row">
                                        <div class="input-group col-md-4">
                                        <span class="input-group-text"><i class="fas fa-clipboard-list"></i></span>
                                            <select type="submit" name="id_barang" class="form-control" required>
                                            <option>Pilih barang ..</option>
                                            <?php 
                                                $pil = mysqli_query($conn, "SELECT * FROM tb_barang");
                                                while($p = mysqli_fetch_assoc($pil)){
                                                    echo "<option value=".$p["id_barang"].">".$p["nama_barang"]."</option>";
                                                }
                                             ?>	
                                            </select>
                                        </div>
                                    </div> 
                                    <div class="row mt-3">
                                  
                                        <div class="input-group col-md-4">
                                            <span class="input-group-text"><i class="fas fa-clipboard-list"></i></span>
                                            <select type="submit" name="tahun" class="form-control" required>
                                               
                                                <option>Pilih Tahun Produksi ...</option>
                                                <?php
                                                    $currentYear=date("Y")-1;
                                                    for($x=0;$x<10;$x++){
                                                        $t = (int)$currentYear+($x);
                                                        echo "<option value=".$t.">".$t."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                  
                                  <!-- <div class="input-group col-md-4">
                                      <span class="input-group-text"><i class="fas fa-clipboard-list"></i></span>
                                      <input type="text" class="form-control" placeholder="Masukkan nilai alpha" name="alpha" required>
                                  </div> -->
                              </div>
                                    <div class="row mt-3">
                                        <button type="submit" name="hitungPeramalan" class="btn btn-primary ml-3">Hitung</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php
                            if(isset($peramalan)){
                                $mad_key = array_search($peramalan["best_forecast"][0]["alpha"], array_column($peramalan['mad'], 'alpha'));
                                // <td scope="col">',number_format(array_sum(array_column($peramalan['best_forecast'], 'error'))/count($peramalan['best_forecast']), 2),'</td>
                                // <td scope="col">',number_format(array_sum(array_column($peramalan['best_forecast'], 'absolute'))/count($peramalan['best_forecast']), 2),'</td>

                                echo '<div class="card">';
                                    $end_best = end($peramalan['best_forecast']);
                                    echo "<h1 class='text-center mt-3'>Peramalan Terbaik</h1>";
                                    echo "<p class='text-center'>Peramalan terbaik untuk bulan ". getNamaBulan($end_best['bulan']) ."</p>"; 

                                    echo '
                                        <div class="card-body">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th colspan="7" class="text-center">Peramalan Dengan Alpha ',$peramalan["best_forecast"][0]["alpha"],'</th>
                                                    </tr>
                                                    <tr>
                                                    <th scope="col">Bulan</th>
                                                    <th scope="col">Tahun</th>
                                                    <th scope="col">Aktual (At)</th>
                                                    <th scope="col">Alpha (At)</th>
                                                    <th scope="col">Forecast (Ft)</th>
                                                    <th scope="col">At-Ft</th>
                                                    <th scope="col">|At-Ft|</th>
                                                    
                                                    </tr>
                                                </thead>
                                                <tbody>';

                                                foreach ($peramalan['best_forecast'] as $value) {
                                                    echo '
                                                        <tr>
                                                        <th scope="row">',$value["bulan"] == $end_best["bulan"] ? 'Next Month ('. getNamaBulan($value["bulan"]) .')' : getNamaBulan($value["bulan"]),'</th>
                                                        <td>',$value["tahun"],'</td>
                                                        <td>',$value["data_aktual"],'</td>
                                                        <td>',$value["alpha"],'</td>
                                                        <td style="font-weight: bold;">',number_format($value["forecast"], 2),'</td>
                                                        <td>',number_format(floatval($value["error"]), 2),'</td>
                                                        <td>',number_format(floatval($value["absolute"]), 2),'</td>
                                                        </tr>                      
                                                    ';
                                                }

                                                echo '
                                                    <tr>
                                                    <td scope="col"></td>
                                                    <td scope="col"></td>
                                                    <td scope="col"></td>
                                                    <td scope="col"></td>
                                                    <td scope="col">Standar Error</td>
                                                    <td scope="col">',number_format(array_sum(array_column($peramalan['best_forecast'], 'error')), 2),'</td>
                                                    <td scope="col">',number_format(array_sum(array_column($peramalan['best_forecast'], 'absolute')), 2),'</td>
                                                    </tr>
                                                ';

                                                echo '
                                                    <tr>
                                                    <td colspan="4"></td>
                                                    <td colspan="1" style="font-weight: bold;">Nilai MAD</td>
                                                    <td colspan="2" style="font-weight: bold;">', $peramalan['mad'][$mad_key]['mad'] ,'</td>
                                                    </tr>
                                                ';

                                    echo '</tbody>
                                        </table>
                                    </div>';
                                    echo '<p class="px-4">Berdasarkan perhitungan rumus Single Exponential Smoothing. Didapatkan nilai error terkecil pada alpha '. $end_best["alpha"] .', <br> maka prediksi jumlah <strong>'. $result_barang['nama_barang'] .'</strong> untuk bulan <strong>'. getNamaBulan($end_best['bulan']) .'</strong> adalah <strong>'. number_format($end_best["forecast"], 2) .' Pcs </strong> </p>';
                                echo '</div>';
                            }
                        ?>
                        <div class="card">
                            <div class="card-body">
                                <canvas id="myChart"></canvas>
                            </div>
                        </div>
                        <div class="card">
                           
                               <?php
                                if(isset($peramalan)){
                                    echo "<h1 class='text-center mt-3'>Detail Peramalan</h1>";
                                    echo "<p class='text-center'>Berdasarkan alpha 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, dan 0.9</p>";
                                    foreach($peramalan['forecast'] as $key => $ramal){
                                        $mad_key = array_search($ramal[0]["alpha"], array_column($peramalan['mad'], 'alpha'));

                                        echo '
                                        <div class="card-body">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th colspan="7" class="text-center">Peramalan Dengan Alpha ',$key,'</th>
                                                    </tr>
                                                    <tr>
                                                    <th scope="col">Bulan</th>
                                                    <th scope="col">Tahun</th>
                                                    <th scope="col">Aktual (At)</th>
                                                    <th scope="col">Alpha (At)</th>
                                                    <th scope="col">Forecast (Ft)</th>
                                                    <th scope="col">At-Ft</th>
                                                    <th scope="col">|At-Ft|</th>
                                                    
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                   
                                        ';
                                        $end_forecast = end($ramal);
                                        foreach ($ramal as $asd => $value) {
                                            echo '
                                                <tr>
                                                <th scope="row">',$value["bulan"] == $end_forecast["bulan"] ? 'Next Month ('. getNamaBulan($value["bulan"]) .')' : getNamaBulan($value["bulan"]),'</th>
                                                <td>',$value["tahun"],'</td>
                                                <td>',$value["data_aktual"],'</td>
                                                <td>',$value["alpha"],'</td>
                                                <td style="font-weight: bold;">',number_format($value["forecast"], 2),'</td>
                                                <td>',number_format(floatval($value["error"]), 2),'</td>
                                                <td>',number_format(floatval($value["absolute"]), 2),'</td>
                                                </tr>                      
                                            ';
                                        }
    
                                        echo '
                                                <tr>
                                                <td scope="col"></td>
                                                <td scope="col"></td>
                                                <td scope="col"></td>
                                                <td scope="col"></td>
                                                <td scope="col">Standar Error</td>
                                                <td scope="col">',number_format(array_sum(array_column($ramal, 'error')), 2),'</td>
                                                <td scope="col">',number_format(array_sum(array_column($ramal, 'absolute')), 2),'</td>
                                                </tr>
                                        ';
    
                                        echo '
                                            <tr>
                                                <td colspan="4"></td>
                                                <td colspan="1" style="font-weight: bold;">Nilai MAD</td>
                                                <td colspan="2" style="font-weight: bold;">', $peramalan['mad'][$mad_key]['mad'] ,'</td>
                                            </tr>
                                        ';
    
                                        echo '
                                            </tbody>
                                            </table>
                                        </div>';
                                    }
                                }
                               ?>
            
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer text-center"> 2021 © Chandra Collection
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="js/custom.js"></script>
    <?php
    if(isset($peramalan)){
        // let bulan = payload.map((val)=>val.bulan);
        // let actual = payload.map((val)=>val.actual).filter((item)=>item!=='');
        // let predicted = payload.map((val)=>val.predicted);
        $forecast01 = json_encode($forecast['01']);
        $forecast02 = json_encode($forecast['02']);
        $forecast03 = json_encode($forecast['03']);
        $forecast04 = json_encode($forecast['04']);
        $forecast05 = json_encode($forecast['05']);
        $forecast06 = json_encode($forecast['06']);
        $forecast07 = json_encode($forecast['07']);
        $forecast08 = json_encode($forecast['08']);
        $forecast09 = json_encode($forecast['09']);
        $actual = json_encode($actual);
        // let forecast04 = $forecast04
        // let forecast05 = $forecast05
        // let forecast06 = $forecast06
        // let forecast07 = $forecast07
        // let forecast08 = $forecast08
        // let forecast09 = $forecast09

        echo "
        <script>
        let bulan = $json_bulan
        let actual = $actual
        let forecast01 = $forecast01
        let forecast02 = $forecast02
        let forecast03 = $forecast03
        let forecast04 = $forecast04
        let forecast05 = $forecast05
        let forecast06 = $forecast06
        let forecast07 = $forecast07
        let forecast08 = $forecast08
        let forecast09 = $forecast09

        function getRandomColor() {
            var letters = '0123456789ABCDEF'.split('');
            var color = '#';
            for (var i = 0; i < 6; i++ ) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        var ctx = document.getElementById('myChart').getContext('2d');
        var config = {
			type: 'line',
			data: {
				labels: bulan,
				datasets: [
                    {
                        label: 'Data Aktual',
                        backgroundColor: getRandomColor(),
                        borderColor: getRandomColor(),
                        data: actual,
                        fill: false,
                    },{
                        label: 'Alpha 0.1',
                        backgroundColor: getRandomColor(),
                        borderColor: getRandomColor(),
                        data: forecast01,
                        fill: false,
                    },{
                        label: 'Alpha 0.2',
                        fill: false,
                        backgroundColor: getRandomColor(),
                        borderColor: getRandomColor(),
                        data: forecast02
				    },{
                        label: 'Alpha 0.3',
                        fill: false,
                        backgroundColor: getRandomColor(),
                        borderColor: getRandomColor(),
                        data: forecast03
				    },{
                        label: 'Alpha 0.4',
                        fill: false,
                        backgroundColor: getRandomColor(),
                        borderColor: getRandomColor(),
                        data: forecast04
				    },{
                        label: 'Alpha 0.5',
                        fill: false,
                        backgroundColor: getRandomColor(),
                        borderColor: getRandomColor(),
                        data: forecast05
				    },{
                        label: 'Alpha 0.6',
                        fill: false,
                        backgroundColor: getRandomColor(),
                        borderColor: getRandomColor(),
                        data: forecast06
				    },{
                        label: 'Alpha 0.7',
                        fill: false,
                        backgroundColor: getRandomColor(),
                        borderColor: getRandomColor(),
                        data: forecast07
				    },{
                        label: 'Alpha 0.8',
                        fill: false,
                        backgroundColor: getRandomColor(),
                        borderColor: getRandomColor(),
                        data: forecast08
				    },{
                        label: 'Alpha 0.9',
                        fill: false,
                        backgroundColor: getRandomColor(),
                        borderColor: getRandomColor(),
                        data: forecast09
				    }
                ]
			},
			options: {
				responsive: true,
				title: {
					display: true,
					text: 'Forecast Single Exponential Smoothing'
				},
				tooltips: {
					mode: 'index',
					intersect: false,
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Bulan'
						}
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Jumlah Produksi'
						}
					}]
				}
			}
		};
        var ctx = document.getElementById('myChart').getContext('2d');
		var myLine = new Chart(ctx, config);
        </script>
        ";
    }

    ?>
</body>

</html>