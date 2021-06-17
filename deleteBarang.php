<?php
    require 'fungsi.php';

    $id = $_GET['id_barang'];

    if(deleteBarang($id) > 0){
        echo "<script>
                alert('data berhasil dihapus!');
                document.location.href = 'daftarBarang.php';    
        </script>";
    } else {
        echo "<script>
                alert('data gagal dihapus!');
                document.location.href = 'daftarBarang.php';    
        </script>";
    }
?>