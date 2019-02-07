<?php
require($_SERVER['DOCUMENT_ROOT']."/shuffle/includes/manage_signup.php");

?>
<html>
<head>
</head>

<body>
<?php
$manage_db = new manage_db();
$query_url = $manage_db->return_query("select confirm_url from temp_register");
for($i=0; $i<mysql_num_rows($query_url); $i++){
$url = mysql_result($query_url, $i, "confirm_url");
echo "<a href='".$url."'>".$url."</a><br />";
}

echo substr("hello world", -4,4);
?>

<script type="text/javascript">
window.onload = function(){
document.getElementById('div2').onmousedown = function(){ increaseNum(0);}
document.getElementById('div2').onmouseup = function(){ increaseNum(1);}
}

function increaseNum(par){
if(par == 0){
document.getElementById('div1').innerHTML = parseInt(document.getElementById('div1').innerHTML)+3;
id = setTimeout("increaseNum(0)", 100);
}else if(par == 1){
clearTimeout(id);
}
}
</script>
<div id="div1">
1
</div>

<a id="div2" style="text-decoration: underline; cursor: pointer">click this</a>
</body>
</html>