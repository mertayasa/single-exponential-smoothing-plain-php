<?php

$servername = "localhost";
$database = "db_peramalan";
$username = "root";
$password = "root";

$conn = mysqli_connect($servername, $username, $password, $database);

// barang
function tambahBarang($data){
    global $conn;

    $nama_barang = htmlspecialchars($data['nama_barang']);
    $detail_barang = htmlspecialchars($data['detail_barang']);

    $ak = mysqli_query($conn, "SELECT count('id') FROM tb_barang WHERE nama_barang LIKE '%$nama_barang%'");
    $count = mysqli_fetch_assoc($ak);

    if($count["count('id')"] > 0){
        echo "<script>alert('Data sudah ada!');</script>";
        return 0;
    }

    if($_FILES['gambar']){
        $ekstensi_diperbolehkan	= array('png','jpg', 'jpeg');
        $nama = date("h:i:s").$_FILES['gambar']['name'];
        $x = explode('.', $nama);
        $ekstensi = strtolower(end($x));
        $ukuran	= $_FILES['gambar']['size'];
        $file_tmp = $_FILES['gambar']['tmp_name'];	

        if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
            if($ukuran < 1044070){			
                move_uploaded_file($file_tmp, 'img/'.$nama);
            }else{
                echo 'UKURAN FILE TERLALU BESAR';
            }
        }else{
            echo 'EKSTENSI FILE YANG DI UPLOAD TIDAK DI PERBOLEHKAN';
        }
    }

    mysqli_query($conn, "INSERT INTO tb_barang VALUES(NULL, '$nama_barang', '$detail_barang', '$nama')");
    return mysqli_affected_rows($conn);
}

function editBarang($data){
    global $conn;

    $id = $data['id_barang'];
    $nama_barang = htmlspecialchars($data['nama_barang']);
    $detail_barang = htmlspecialchars($data['detail_barang']);

    $ak = mysqli_query($conn, "SELECT count('id') FROM tb_barang WHERE nama_barang LIKE '%$nama_barang%' AND NOT id_barang = '$id' ");
    $count = mysqli_fetch_assoc($ak);

    if($count["count('id')"] > 0){
        echo "<script>alert('Data sudah ada!');</script>";
        return 0;
    }

    // var_dump($_FILES['gambar']);
    // die();

    if($_FILES['gambar']['name'] != ''){
        $ekstensi_diperbolehkan	= array('png','jpg', 'jpeg');
        $nama = date("h:i:s").$_FILES['gambar']['name'];
        $x = explode('.', $nama);
        $ekstensi = strtolower(end($x));
        $ukuran	= $_FILES['gambar']['size'];
        $file_tmp = $_FILES['gambar']['tmp_name'];	

        if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
            if($ukuran < 1044070){			
                move_uploaded_file($file_tmp, 'img/'.$nama);
                mysqli_query($conn, "UPDATE tb_barang SET nama_barang = '$nama_barang', detail_barang = '$detail_barang', gambar = '$nama' WHERE tb_barang.id_barang = '$id'");
            }else{
                echo 'UKURAN FILE TERLALU BESAR';
            }
        }else{
            echo 'EKSTENSI FILE YANG DI UPLOAD TIDAK DI PERBOLEHKAN';
        }
    }else{
        mysqli_query($conn, "UPDATE tb_barang SET nama_barang = '$nama_barang', detail_barang = '$detail_barang' WHERE tb_barang.id_barang = '$id'");
    }


    return mysqli_affected_rows($conn);
}

function deleteBarang($id){
    global $conn;
    mysqli_query($conn, "DELETE FROM tb_barang WHERE id_barang = '$id'");
    return mysqli_affected_rows($conn);
}

// aktual
function tambahAktual($data){
    global $conn;

    $id_barang = htmlspecialchars($data['id_barang']);
    $bulan = htmlspecialchars($data['bulan']);
    $tahun = htmlspecialchars($data['tahun']);
    $data_aktual = htmlspecialchars($data['data_aktual']);

    $ak = mysqli_query($conn, "SELECT count('id') FROM tb_aktual WHERE id_barang = '$id_barang' AND bulan = '$bulan' AND tahun = '$tahun'");
    $count = mysqli_fetch_assoc($ak);

    if($count["count('id')"] > 0){
        echo "<script>alert('Data sudah ada!');</script>";
        return 0;
    } else {
        $query = mysqli_query($conn, "INSERT INTO `tb_aktual` (`id_aktual`, `id_barang`, `bulan`, `tahun`, `data_aktual`) VALUES(NULL, '$id_barang', '$bulan', '$tahun', '$data_aktual')");
        return mysqli_affected_rows($conn);
    }
}

function editAktual($data){
    try{
        global $conn;
    
        $id = $data['id_aktual'];
        $bulan = htmlspecialchars($data['bulan']);
        $tahun = htmlspecialchars($data['tahun']);
        $data_aktual = htmlspecialchars($data['data_aktual']);
    
        mysqli_query($conn, "UPDATE tb_aktual SET data_aktual = '$data_aktual', bulan = '$bulan', tahun = '$tahun' WHERE id_aktual = '$id'");
    }catch(Exception $e){
        return 0;
    }
    return 1;
    // return mysqli_affected_rows($conn);
}

function deleteAktual($id){
    global $conn;
    mysqli_query($conn, "DELETE FROM tb_aktual WHERE id_aktual = '$id'");
    return mysqli_affected_rows($conn);
}

function getListBulan(){
    return [
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];
}

function getNamaBulan($bulan){
    switch($bulan){
        case 1:
            return 'Januari';
        break;
        case 2:
            return 'Februari';
        break;
        case 3:
            return 'Maret';
        break;
        case 4:
            return 'April';
        break;
        case 5:
            return 'Mei';
        break;
        case 6:
            return 'Juni';
        break;
        case 7:
            return 'Juli';
        break;
        case 8:
            return 'Agustus';
        break;
        case 9:
            return 'September';
        break;
        case 10:
            return 'Oktober';
        break;
        case 11:
            return 'November';
        break;
        case 12:
            return 'Desember';
        break;
    }
}



function peramalan($actuals){
    $single_data = [];
    $forecast_temp = [];
    $forecast_01 = [];
    $forecast_02 = [];
    $forecast_03 = [];
    $forecast_04 = [];
    $forecast_05 = [];
    $forecast_06 = [];
    $forecast_07 = [];
    $forecast_08 = [];
    $forecast_09 = [];

    $final_forecast = [];

    // Group all alpha
    $alphas = array(0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9);
    // $alphas = array(0.2, 0.5, 0.8);

    if(count($actuals) > 1){
        $init_error = $actuals[1]['data_aktual'] - $actuals[0]['data_aktual'];
        $init_absolute = abs($init_error);

        array_push($forecast_01, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $actuals[0]['tahun'],'bulan' => $actuals[0]['bulan'] , 'alpha' => $alphas[0], 'data_aktual' => $actuals[0]['data_aktual'], 'forecast' => $actuals[0]['data_aktual'], 'error' => 0, 'absolute' => 0));
        array_push($forecast_01, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $actuals[1]['tahun'],'bulan' => $actuals[1]['bulan'] , 'alpha' => $alphas[0], 'data_aktual' => $actuals[1]['data_aktual'], 'forecast' => $actuals[0]['data_aktual'], 'error' => $init_error, 'absolute' => $init_absolute));
        
        array_push($forecast_02, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $actuals[0]['tahun'],'bulan' => $actuals[0]['bulan'] , 'alpha' => $alphas[1], 'data_aktual' => $actuals[0]['data_aktual'], 'forecast' => $actuals[0]['data_aktual'], 'error' => 0, 'absolute' => 0));
        array_push($forecast_02, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $actuals[1]['tahun'],'bulan' => $actuals[1]['bulan'] , 'alpha' => $alphas[1], 'data_aktual' => $actuals[1]['data_aktual'], 'forecast' => $actuals[0]['data_aktual'], 'error' => $init_error, 'absolute' => $init_absolute));
        
        array_push($forecast_03, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $actuals[0]['tahun'],'bulan' => $actuals[0]['bulan'] , 'alpha' => $alphas[2], 'data_aktual' => $actuals[0]['data_aktual'], 'forecast' => $actuals[0]['data_aktual'], 'error' => 0, 'absolute' => 0));
        array_push($forecast_03, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $actuals[1]['tahun'],'bulan' => $actuals[1]['bulan'] , 'alpha' => $alphas[2], 'data_aktual' => $actuals[1]['data_aktual'], 'forecast' => $actuals[0]['data_aktual'], 'error' => $init_error, 'absolute' => $init_absolute));
        
        array_push($forecast_04, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $actuals[0]['tahun'],'bulan' => $actuals[0]['bulan'] , 'alpha' => $alphas[3], 'data_aktual' => $actuals[0]['data_aktual'], 'forecast' => $actuals[0]['data_aktual'], 'error' => 0, 'absolute' => 0));
        array_push($forecast_04, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $actuals[1]['tahun'],'bulan' => $actuals[1]['bulan'] , 'alpha' => $alphas[3], 'data_aktual' => $actuals[1]['data_aktual'], 'forecast' => $actuals[0]['data_aktual'], 'error' => $init_error, 'absolute' => $init_absolute));
        
        array_push($forecast_05, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $actuals[0]['tahun'],'bulan' => $actuals[0]['bulan'] , 'alpha' => $alphas[4], 'data_aktual' => $actuals[0]['data_aktual'], 'forecast' => $actuals[0]['data_aktual'], 'error' => 0, 'absolute' => 0));
        array_push($forecast_05, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $actuals[1]['tahun'],'bulan' => $actuals[1]['bulan'] , 'alpha' => $alphas[4], 'data_aktual' => $actuals[1]['data_aktual'], 'forecast' => $actuals[0]['data_aktual'], 'error' => $init_error, 'absolute' => $init_absolute));
        
        array_push($forecast_06, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $actuals[0]['tahun'],'bulan' => $actuals[0]['bulan'] , 'alpha' => $alphas[5], 'data_aktual' => $actuals[0]['data_aktual'], 'forecast' => $actuals[0]['data_aktual'], 'error' => 0, 'absolute' => 0));
        array_push($forecast_06, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $actuals[1]['tahun'],'bulan' => $actuals[1]['bulan'] , 'alpha' => $alphas[5], 'data_aktual' => $actuals[1]['data_aktual'], 'forecast' => $actuals[0]['data_aktual'], 'error' => $init_error, 'absolute' => $init_absolute));
        
        array_push($forecast_07, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $actuals[0]['tahun'],'bulan' => $actuals[0]['bulan'] , 'alpha' => $alphas[6], 'data_aktual' => $actuals[0]['data_aktual'], 'forecast' => $actuals[0]['data_aktual'], 'error' => 0, 'absolute' => 0));
        array_push($forecast_07, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $actuals[1]['tahun'],'bulan' => $actuals[1]['bulan'] , 'alpha' => $alphas[6], 'data_aktual' => $actuals[1]['data_aktual'], 'forecast' => $actuals[0]['data_aktual'], 'error' => $init_error, 'absolute' => $init_absolute));
        
        array_push($forecast_08, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $actuals[0]['tahun'],'bulan' => $actuals[0]['bulan'] , 'alpha' => $alphas[7], 'data_aktual' => $actuals[0]['data_aktual'], 'forecast' => $actuals[0]['data_aktual'], 'error' => 0, 'absolute' => 0));
        array_push($forecast_08, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $actuals[1]['tahun'],'bulan' => $actuals[1]['bulan'] , 'alpha' => $alphas[7], 'data_aktual' => $actuals[1]['data_aktual'], 'forecast' => $actuals[0]['data_aktual'], 'error' => $init_error, 'absolute' => $init_absolute));
        
        array_push($forecast_09, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $actuals[0]['tahun'],'bulan' => $actuals[0]['bulan'] , 'alpha' => $alphas[8], 'data_aktual' => $actuals[0]['data_aktual'], 'forecast' => $actuals[0]['data_aktual'], 'error' => 0, 'absolute' => 0));
        array_push($forecast_09, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $actuals[1]['tahun'],'bulan' => $actuals[1]['bulan'] , 'alpha' => $alphas[8], 'data_aktual' => $actuals[1]['data_aktual'], 'forecast' => $actuals[0]['data_aktual'], 'error' => $init_error, 'absolute' => $init_absolute));

        // return $forecast_05;
        // Init forecast for index 2
        $init_forecast = $actuals[0]['data_aktual'];

        // Store temporary last farecast
        $last = [];
        foreach($alphas as $alpha){
            foreach($actuals as $key => $value){
                if($key !== 0 && $key !== 1){
                    if($key == 2){
                        $last = $init_forecast + ($alpha * (($actuals[$key-1]['data_aktual'] - $init_forecast)));
                        $error = $actuals[$key]['data_aktual']-$last;
                        $absolute = abs($error);
                        array_push($forecast_temp, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $value['tahun'],'bulan' => $value['bulan'] , 'alpha' => $alpha, 'forecast' => $last, 'data_aktual' => $actuals[$key]['data_aktual'], 'error' => $error, 'absolute' => $absolute));
                    }
                    else{
                        $last = $last + (($alpha * ($actuals[$key-1]['data_aktual'] - $last)));
                        $error = $actuals[$key]['data_aktual']-$last;
                        $absolute = abs($error);
                        array_push($forecast_temp, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $value['tahun'],'bulan' => $value['bulan'] , 'alpha' => $alpha, 'forecast' => $last, 'data_aktual' => $actuals[$key]['data_aktual'], 'error' => $error, 'absolute' => $absolute));
                    }
                }
            }
        }

        // Group forecast based on alpha used
        foreach($forecast_temp as $fore_temp){
            if($fore_temp['alpha'] == 0.1){
                array_push($forecast_01, $fore_temp);
            }

            if($fore_temp['alpha'] == 0.2){
                array_push($forecast_02, $fore_temp);
            }

            if($fore_temp['alpha'] == 0.3){
                array_push($forecast_03, $fore_temp);
            }

            if($fore_temp['alpha'] == 0.4){
                array_push($forecast_04, $fore_temp);
            }
            
            if($fore_temp['alpha'] == 0.5){
                array_push($forecast_05, $fore_temp);
            }
            
            if($fore_temp['alpha'] == 0.6){
                array_push($forecast_06, $fore_temp);
            }
            
            if($fore_temp['alpha'] == 0.7){
                array_push($forecast_07, $fore_temp);
            }
            
            if($fore_temp['alpha'] == 0.8){
                array_push($forecast_08, $fore_temp);
            }
            
            if($fore_temp['alpha'] == 0.9){
                array_push($forecast_09, $fore_temp);
            }
        }

        // return $forecast_05;

        $mad_01 = [];
        $mad_02 = [];
        $mad_03 = [];
        $mad_04 = [];
        $mad_05 = [];
        $mad_06 = [];
        $mad_07 = [];
        $mad_08 = [];
        $mad_09 = [];

        $all_forecast = array(
            '01' => $forecast_01, 
            '02' => $forecast_02, 
            '03' => $forecast_03, 
            '04' => $forecast_04, 
            '05' => $forecast_05, 
            '06' => $forecast_06, 
            '07' => $forecast_07, 
            '08' => $forecast_08,
            '09' => $forecast_09
        );

        // Count |At - Ft| / At
        foreach($all_forecast as $all_fore){
            foreach($all_fore as $key => $fore){
                $mad = abs(($fore['data_aktual'] - $fore['forecast']));
                
                if($fore['alpha'] ==  0.1){
                    if($key == 0){
                        $mad = 0;
                    }
                    array_push($mad_01, $mad);
                }
                
                if($fore['alpha'] ==  0.2){
                    if($key == 0){
                        $mad = 0;
                    }
                    array_push($mad_02, $mad);
                }

                if($fore['alpha'] ==  0.3){
                    if($key == 0){
                        $mad = 0;
                    }
                    array_push($mad_03, $mad);
                }

                if($fore['alpha'] ==  0.4){
                    if($key == 0){
                        $mad = 0;
                    }
                    array_push($mad_04, $mad);
                }

                if($fore['alpha'] ==  0.5){
                    if($key == 0){
                        $mad = 0;
                    }
                    array_push($mad_05, $mad);
                }
                
                if($fore['alpha'] ==  0.6){
                    if($key == 0){
                        $mad = 0;
                    }
                    array_push($mad_06, $mad);
                }
                
                if($fore['alpha'] ==  0.7){
                    if($key == 0){
                        $mad = 0;
                    }
                    array_push($mad_07, $mad);
                }
                
                if($fore['alpha'] ==  0.8){
                    if($key == 0){
                        $mad = 0;
                    }
                    array_push($mad_08, $mad);
                }
                
                if($fore['alpha'] ==  0.9){
                    if($key == 0){
                        $mad = 0;
                    }
                    array_push($mad_09, $mad);
                }
            }
        }

        // $asd = 0;

        // $sum = 0;
        // foreach ($all_forecast as $key => $forecast) {
        //     foreach($forecast as $data){
        //         $mad.'_'$key += abs($data['error']);
        //     }
        // }

        // return $mad_05;

        // return array_sum(array_column($forecast_05, 'error'));

        // Count Final mad
        $all_mad = array(
            array('alpha' => '0.1', 'alpha_format' => '01', 'mad' => (array_sum($mad_02) / count($mad_02))), 
            array('alpha' => '0.2', 'alpha_format' => '02', 'mad' => (array_sum($mad_02) / count($mad_02))), 
            array('alpha' => '0.3', 'alpha_format' => '03', 'mad' => (array_sum($mad_02) / count($mad_02))), 
            array('alpha' => '0.4', 'alpha_format' => '04', 'mad' => (array_sum($mad_05) / count($mad_05))), 
            array('alpha' => '0.5', 'alpha_format' => '05', 'mad' => (array_sum($mad_05) / count($mad_05))), 
            array('alpha' => '0.6', 'alpha_format' => '06', 'mad' => (array_sum($mad_05) / count($mad_05))), 
            array('alpha' => '0.7', 'alpha_format' => '07', 'mad' => (array_sum($mad_05) / count($mad_05))), 
            array('alpha' => '0.8', 'alpha_format' => '08', 'mad' => (array_sum($mad_08) / count($mad_08))),
            array('alpha' => '0.9', 'alpha_format' => '09', 'mad' => (array_sum($mad_08) / count($mad_08)))
        );

        // return $all_mad;

        // Find minimum value of mad
        $min = min(
            $all_mad[0]['mad'], 
            $all_mad[1]['mad'], 
            $all_mad[2]['mad'],
            $all_mad[3]['mad'],
            $all_mad[4]['mad'],
            $all_mad[5]['mad'],
            $all_mad[6]['mad'],
            $all_mad[7]['mad'],
            $all_mad[8]['mad']
        );

        // return $min;

        $end01 = end($forecast_01);
        $end02 = end($forecast_02);
        $end03 = end($forecast_03);
        $end04 = end($forecast_04);
        $end05 = end($forecast_05);
        $end06 = end($forecast_06);
        $end07 = end($forecast_07);
        $end08 = end($forecast_08);
        $end09 = end($forecast_09);

        // Forecast for next month
        $last = $end01['forecast'] + (($alphas[0] * ($end01['data_aktual'] - $end01['forecast'])));
        $future_month01 = $end01['bulan'] == 12 ? 1 : $end01['bulan']+1;
        $tahun02 = $end01['bulan'] == 12 ? $end01['tahun']+1 : $end01['tahun'];
        array_push($forecast_01, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $tahun02,'bulan' => $future_month01 , 'alpha' => $alphas[0], 'forecast' => $last, 'data_aktual' => '', 'error' => '', 'absolute' => ''));
        
        $last = $end02['forecast'] + (($alphas[1] * ($end02['data_aktual'] - $end02['forecast'])));
        $future_month02 = $end02['bulan'] == 12 ? 1 : $end02['bulan']+1;
        $tahun02 = $end02['bulan'] == 12 ? $end02['tahun']+1 : $end02['tahun'];
        array_push($forecast_02, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $tahun02,'bulan' => $future_month02 , 'alpha' => $alphas[1], 'forecast' => $last, 'data_aktual' => '', 'error' => '', 'absolute' => ''));
        
        $last = $end03['forecast'] + (($alphas[2] * ($end03['data_aktual'] - $end03['forecast'])));
        $future_month03 = $end03['bulan'] == 12 ? 1 : $end03['bulan']+1;
        $tahun03 = $end03['bulan'] == 12 ? $end03['tahun']+1 : $end03['tahun'];
        array_push($forecast_03, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $tahun03,'bulan' => $future_month03 , 'alpha' => $alphas[2], 'forecast' => $last, 'data_aktual' => '', 'error' => '', 'absolute' => ''));        
        
        $last = $end04['forecast'] + (($alphas[3] * ($end04['data_aktual'] - $end04['forecast'])));
        $future_month04 = $end04['bulan'] == 12 ? 1 : $end04['bulan']+1;
        $tahun04 = $end04['bulan'] == 12 ? $end04['tahun']+1 : $end04['tahun'];
        array_push($forecast_04, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $tahun04,'bulan' => $future_month04 , 'alpha' => $alphas[3], 'forecast' => $last, 'data_aktual' => '', 'error' => '', 'absolute' => ''));
        
        $last = $end05['forecast'] + (($alphas[4] * ($end05['data_aktual'] - $end05['forecast'])));
        $future_month05 = $end05['bulan'] == 12 ? 1 : $end05['bulan']+1;
        $tahun05 = $end05['bulan'] == 12 ? $end05['tahun']+1 : $end05['tahun'];
        array_push($forecast_05, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $tahun05,'bulan' => $future_month05 , 'alpha' => $alphas[4], 'forecast' => $last, 'data_aktual' => '', 'error' => '', 'absolute' => ''));
        
        $last = $end06['forecast'] + (($alphas[5] * ($end06['data_aktual'] - $end06['forecast'])));
        $future_month06 = $end06['bulan'] == 12 ? 1 : $end06['bulan']+1;
        $tahun06 = $end06['bulan'] == 12 ? $end06['tahun']+1 : $end06['tahun'];
        array_push($forecast_06, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $tahun06,'bulan' => $future_month06 , 'alpha' => $alphas[5], 'forecast' => $last, 'data_aktual' => '', 'error' => '', 'absolute' => ''));
        
        $last = $end07['forecast'] + (($alphas[6] * ($end07['data_aktual'] - $end07['forecast'])));
        $future_month07 = $end07['bulan'] == 12 ? 1 : $end07['bulan']+1;
        $tahun07 = $end07['bulan'] == 12 ? $end07['tahun']+1 : $end07['tahun'];
        array_push($forecast_07, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $tahun07,'bulan' => $future_month07 , 'alpha' => $alphas[6], 'forecast' => $last, 'data_aktual' => '', 'error' => '', 'absolute' => ''));
        
        $last = $end08['forecast'] + (($alphas[7] * ($end08['data_aktual'] - $end08['forecast'])));
        $future_month08 = $end08['bulan'] == 12 ? 1 : $end08['bulan']+1;
        $tahun08 = $end08['bulan'] == 12 ? $end08['tahun']+1 : $end08['tahun'];
        array_push($forecast_08, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $tahun08,'bulan' => $future_month08 , 'alpha' => $alphas[7], 'forecast' => $last, 'data_aktual' => '', 'error' => '', 'absolute' => ''));
        
        $last = $end09['forecast'] + (($alphas[8] * ($end09['data_aktual'] - $end09['forecast'])));
        $future_month09 = $end09['bulan'] == 12 ? 1 : $end02['bulan']+1;
        $tahun09 = $end09['bulan'] == 12 ? $end09['tahun']+1 : $end09['tahun'];
        array_push($forecast_09, array('id_barang' => $actuals[0]['id_barang'], 'tahun' => $tahun09,'bulan' => $future_month09 , 'alpha' => $alphas[8], 'forecast' => $last, 'data_aktual' => '', 'error' => '', 'absolute' => ''));

        // Group fixed forecast
        $all_forecast_fix = array(
            '01' => $forecast_01,
            '02' => $forecast_02,
            '03' => $forecast_03,
            '04' => $forecast_04,
            '05' => $forecast_05,
            '06' => $forecast_06,
            '07' => $forecast_07,
            '08' => $forecast_08,
            '09' => $forecast_09
        );

        
        $min = $all_mad[array_search($min, array_column($all_mad, 'mad'))]; // Find All mad Index using minimun value from min
        $index_fix = str_replace('.', '', $min['alpha']);
        // var_dump($index_fix);
        // die();
        
        // Return all forecast based on minimum mad error
        $forecast = [
            'forecast' => $all_forecast_fix,
            'best_forecast' => $all_forecast_fix[$index_fix],
            'mad' => $all_mad
        ];
    }

    return $forecast;
}
?>