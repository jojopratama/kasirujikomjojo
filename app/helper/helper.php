<?php
if(!function_exists('rupiah')){
    function rupiah($angka){
        $hasil_rupiah - "Rp" . number_format($angka,'-');
        return $hasil_rupiah;
    }

}