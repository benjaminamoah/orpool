<?php
class mobile_redirect{

function checkMobile(){

if(isset($_SERVER["HTTP_X_WAP_PROFILE"])) return true;

if(preg_match("/wap\.|\.wap/i",$_SERVER["HTTP_ACCEPT"])) return true;

if(isset($_SERVER["HTTP_USER_AGENT"])){

// Quick Array to kill out matches in the user agent
// that might cause false positives

$badmatches = array("OfficeLiveConnector","MSIE\ 8\.0","OptimizedIE8","MSN\ Optimized","Creative\ AutoUpdate","Swapper");

foreach($badmatches as $badstring){
 if(preg_match("/".$badstring."/i",$_SERVER["HTTP_USER_AGENT"])) return false;
}

// Now we'll go for positive matches

$uamatches = array("midp", "j2me", "avantg", "docomo", "novarra", "palmos", "palmsource", "240x320", "opwv", "chtml", "pda", "windows\ ce", "mmp\/", "blackberry", "mib\/", "symbian", "wireless", "nokia", "hand", "mobi", "phone", "cdm", "up\.b", "audio", "SIE\-", "SEC\-", "samsung", "HTC", "mot\-", "mitsu", "sagem", "sony", "alcatel", "lg", "erics", "vx", "NEC", "philips", "mmm", "xx", "panasonic", "sharp", "wap", "sch", "rover", "pocket", "benq", "java", "pt", "pg", "vox", "amoi", "bird", "compal", "kg", "voda", "sany", "kdd", "dbt", "sendo", "sgh", "gradi", "jb", "\d\d\di", "moto","webos");

foreach($uamatches as $uastring){
 if(preg_match("/".$uastring."/i",$_SERVER["HTTP_USER_AGENT"])) return true;
 }

}
return false;
}

}
//Read more: http://www.brainhandles.com/techno-thoughts/detecting-mobile-browsers#ixzz2NjQmYFOV
?>