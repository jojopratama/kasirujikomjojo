<?php

use Carbon\Carbon;

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

if(!function_exists('date_formater')){
  function date_formater($datetime, $timezone = 'Asia/Jakarta'){
    return Carbon::parse($datetime, $timezone)
        ->translatedFormat('d-m-Y H:i:s');
  }
}

if(!function_exists('badge_role')){
  function badge_role($role)
  {
    if ($role === 'admin') {
      return '<span class="badge badge-danger">Admin</span>';
    } elseif ($role === 'petugas') {
      return '<span class="badge badge-primary">Petugas</span>';
    } else {
      return '<span class="badge badge-secondary">Unknown</span>';
    }
  }
}

