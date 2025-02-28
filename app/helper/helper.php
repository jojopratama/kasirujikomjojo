<?php
if(!function_exists('rupiah')){
  function rupiah($nominal){
    return "Rp " . number_format($nominal, 0, ',', '.');
  }
}

if(!function_exists('number')){
  function number($value){
    return number_format($value, 0, ',', '.');
  }
}
