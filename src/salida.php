<?php 
if(isset($_POST['txtbusca'])):
  include "conexion.php";
   $alumno=new ApptivaDB();  
    $u=$alumno->buscar("alumno"," nombre like '%".$_POST['txtbusca']."%'");
    $html="";
 foreach ($u as $v)
      $html.="<p>".$v['nombre']."</p>";
 echo $html;
else:
    echo "Error";
endif;
 ?>