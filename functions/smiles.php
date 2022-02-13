<?php

namespace App\functions;

use Illuminate\Support\Facades\Storage;

$patterns1 = "~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~";
$replacements1 = "<a class ='link_blue' target ='blank' href=\"\\0\">\\0</a>";
   
$text = preg_replace($patterns1,$replacements1,$text);

$smiles = Storage::disk('public')->files('smiles');

foreach ($smiles as  $smile) {
$split = substr($smile,7,-4);

$text = str_replace("($split)","<img src='".asset(Storage::url($smile))."'/>",$text);

}



