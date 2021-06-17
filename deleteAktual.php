<?php
    require 'fungsi.php';

    $id_barang = $_GET['id_barang'];
    $id = $_GET['id_aktual'];

    if(deleteAktual($id) > 0){
        echo "<script>
                alert('data berhasil dihapus!');
                document.location.href = 'aktual.php?id_barang=$id_barang';    
        </script>";
    } else {
        echo "<script>
                alert('data gagal dihapus!');
                document.location.href = 'aktual.php?id_barang=$id_barang';    
        </script>";
    }
?>