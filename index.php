<?php
/*
assetviewer 

21.Aug.2017 - sven.pohl@zen-systems.de
*/
?>
<html>
<head>
<meta charset="UTF-8">

	<style>
  		body     {  font-family: verdana,sans-serif; }
 		.divcell  {  padding:2px;float:left;border:none; }
	</style>
    
</head>


<body>
<?php


if ( isset($_REQUEST['asset']) )
   {
   $asset = trim($_REQUEST['asset']);
   } 
   else
      {
      $asset = 'example';
      }   

if ( isset($_REQUEST['i']) )
   {
   $filei = $_REQUEST['i']; 
   }
   else
      {
      $filei = -1;
      }
   
printf("<h3>Crypto Asset Viewer ($asset) </h3>");

$dateindex = 1;
$index     = 0;
$comment   = "";
$dir_to_scan = "assets/" . $asset;



$allfiles = scandir($dir_to_scan); // Sortierung A-Z
unset($array_filetime);
$array_filetime_i = 0;

foreach ($allfiles as $singlefile) 
        {
        $singlefileinfo = pathinfo($dir_to_scan."/".$singlefile); 
        $extension = $singlefileinfo['extension'];
        $filename = $dir_to_scan."/".$singlefileinfo['filename'] . "." . $singlefileinfo['extension'];

        $filetime_timestamp = filemtime($filename);
        $filetime = date ("F d Y H:i:s", $filetime_timestamp);
        
        if ($extension == 'txt')
           {
           $array_filetime[$array_filetime_i]['name'] = $filename;
           $array_filetime[$array_filetime_i]['time'] = $filetime_timestamp;
           $array_filetime_i++;     
           $index++;
           } // if ($extension == 'txt')
        } // foreach...



//
// Sort by Timestamp (Quick and dirty)
//
$SORT = 1;
while ($SORT == 1)
      {
      $size = count($array_filetime);      
      $SORT = 0;
      for ($i=0; $i<($size-1); $i++)
          {          
          if ( $array_filetime[$i]['time'] > $array_filetime[$i+1]['time'] )
             {
             $swap = $array_filetime[$i];
             $array_filetime[$i] = $array_filetime[$i+1];
             $array_filetime[$i+1] = $swap;
             $SORT = 1;
             }          
          } // for i..
      } // while SORT
 
 
    
    

$newest_time  = -1;
$newest_index = -1;
$size = count($array_filetime);

for ($i=0; $i<$size; $i++)
    {
    if ( $array_filetime[$i]['time'] > $newest_time) 
       {
       $newest_time  = $array_filetime[$i]['time'];
       $newest_index = $i;
       }
    } // for ...
    
  
$filename = $array_filetime[$newest_index]['name'];
if ($filei > -1)
   {
   $filename = $array_filetime[$filei]['name'];
   }


//
// Show the data-file.
//
show_file($filename,  $comment); 






//
// Menus
//

printf("<hr>");

$dir_to_scan = "assets";
$allfiles = scandir($dir_to_scan); // Sortierung A-Z

//
// Assets
//
foreach ($allfiles as $singlefile) 
        {
        $singlefileinfo = pathinfo($dir_to_scan."/".$singlefile); 
        $filename = $singlefileinfo['filename'] . ""; 
        
        if (
           trim($filename) != ""  &&
           trim($filename) != "."
           )
           {
           $href = "index.php?asset=".$filename."";
           printf("<small>");
           printf( "<a href='".$href."'>".$filename."</a> | ");
           printf("</small>");
           } // if..

        } // for ...


printf("<hr>");
//
// Dates
//

    $href = "index.php?asset=".$asset."";
    printf("<small>");
    printf( "<a href='".$href."'>Latest</a> | ");
    printf("</small>");
    
$size = count($array_filetime);

for ($i=0; $i<$size; $i++)
    {    
    $filetime_timestamp  = $array_filetime[$i]['time'];
    $filetime = date ("F d Y H:i:s", $filetime_timestamp);
    
    $buffer = "".$filetime;
    $href = "index.php?asset=".$asset."&i=".$i;
    printf("<small>");
    printf( "<a href='".$href."'>". $buffer . "</a> | ");
    printf("</small>");      
    } // for 

printf("<br>");    





//
// show_file - show the table of a given .txt-file.
// params:
//         $filename - Path to .txt-file.
//         $comment  - Comment to mark the .txt-file data.
//
function show_file( $filename,  $comment )
{
$handle = fopen ($filename , "r");

$PHASE = 0;
$line_count = 0;
unset($array_amount);
$array_amount_i = 0;
unset($array_prices);
$array_prices_i = 0;

$buffer = fgets($handle);    

$buffer_array = explode(";",$buffer);
$currency = $buffer_array[0];
       
        
if ($currency == "eur") $currency = "&euro;";
if ($currency == "usd") $currency = "$";
    


if (trim($buffer_array[0]) != 'amount:')
   {
   $comment = trim($buffer_array[1]);
   }

//
// Reading all lines.
//        
while (!feof($handle)) 
    {
    $buffer = fgets($handle);    
    $buffer_array = explode(";",$buffer);
    
    if ($PHASE == 0 && trim($buffer_array[0]) == 'prices:')
       {
       $PHASE = 1;
       $buffer = fgets($handle);    
       $buffer_array = explode(";",$buffer);

       $buffer = fgets($handle);    
       $buffer_array = explode(";",$buffer);
       } // if PHASE...

    $buffer_array_count = count($buffer_array);

       
    if ($buffer_array_count > 1)
    {   
    //   
    // Read  amounts 
    //
    if ( $PHASE == 0 )  
    if ($line_count >= 2)
       {       
       $array_amount[$array_amount_i]['name']  = trim($buffer_array[0]);
       $array_amount[$array_amount_i]['place'] = trim($buffer_array[1]);
       $array_amount[$array_amount_i]['count'] = trim($buffer_array[2]);
       $array_amount_i++;
       }  //  if ($line_count >= 2)

    //   
    // Read prices
    //
    if ( $PHASE == 1 && trim($buffer_array[0]) != 'prices:' )  
       {
       $array_prices[$array_prices_i]['name']  = trim($buffer_array[0]);
       $array_prices[$array_prices_i]['eur']   = trim($buffer_array[1]);
       $array_prices[$array_prices_i]['btc']   = trim($buffer_array[2]);
       $array_prices_i++;
       }  //  if ( $PHASE == 1 ) 
    } // if ($buffer_array_count > 1)

    
    $line_count++;
    } // while (!feof($handle)) 
        
fclose ($handle);


printf("<hr>");
$total = 0;
$size = count($array_prices);

//
// Get BTC price (EUR)
//
$btc_price_eur = 0;
for ($i=0; $i<$size; $i++)
    {
    if ($array_prices[$i]['name'] == 'BTC')
       {
       $btc_price_eur = $array_prices[$i]['eur'];
       break;
       }
    }
    
printf("Basispreis Bitcoin: " . $btc_price_eur . " $currency<br>");
printf("<br>");

for ($i=0; $i<$size; $i++)
    {
    $name = trim($array_prices[$i]['name']);
    $eur  = trim($array_prices[$i]['eur']);
    $btc  = trim($array_prices[$i]['btc']);
    
    $base_price = $eur;
    if ($btc > 0)
       {
       $base_price = $btc_price_eur * $btc;       
       }
    
    $sum = 0;
    
    $size2 = count($array_amount);
    $the_count = 0;
    $the_count_sum = 0;
    for ($i2=0; $i2<$size2; $i2++)
        {
        if ($array_amount[$i2]['name'] == $name)
           {
           $the_count = $array_amount[$i2]['count'];
           $add_value = $the_count * $base_price;
           
           $sum = $sum + $add_value;
           $the_count_sum =     $the_count_sum + $the_count;          
           } // if name
        } // for i2...
    
    $raw1_width = 200;
    $raw2_width = 160;
    $raw3_width = 190;
    $raw4_width = 190;


    $sum2 = number_format($sum,2,'.','')."";
    
    if ($i == 0)
       {
       $col = "#444444";
       $col2 = "#ffffff";
       printf("<div class='divcell' style='width:".$raw1_width."px;background:$col;color:$col2;'>");
       printf("<strong>Asset</strong>");
       printf("</div>"); 
    

       printf("<div class='divcell' style='width:".$raw2_width."px;background:$col;color:$col2;'>");
       printf("&nbsp;"." Anzahl");
       printf("</div>"); 

       printf("<div class='divcell' style='width:".$raw3_width."px;background:$col;color:$col2;'>");
       printf("&nbsp;"." Preis pro Einheit");
       printf("</div>"); 

       printf("<div class='divcell' style='width:".$raw4_width.";background:$col;color:$col2;'>");
       printf("Betrag (in $currency)");
       printf("</div>"); 



       printf("<div style='clear:both;'></div>");
       } // if i == 0
    
    $col = "#eeeeee";
    if ( $i % 2 == 0 ) $col = "#cccccc";
    
    printf("<div class='divcell' style='width:".$raw1_width."px;background:$col;'>");
    printf("<strong>".$name ."</strong>");
    printf("</div>"); 
    

    printf("<div class='divcell' style='width:".$raw2_width."px;background:$col;'>");
    printf("&nbsp;"." ".$the_count_sum);
    printf("</div>"); 

    printf("<div class='divcell' style='width:".$raw3_width."px;background:$col;'>");
    printf("&nbsp;"." $base_price $currency");
    printf("</div>"); 


    printf("<div class='divcell' style='width:".$raw4_width."px;background:$col;'>");

    $sum = number_format($sum,2,'.','')."";

    printf("&nbsp;"."$sum $currency");
    printf("</div>"); 


    printf("<div style='clear:both;'></div>");    
    $total = $total + $sum;
    } // for i..


printf("<br>");

$total = number_format($total,2,'.','')."";

printf("<h2 style='color:#000099'>&Sigma; ".$total." $currency</h2>");
printf("Kommentar: <em>" . $comment . "</em><br>");

} // function show_file( $filename )





?>
</body>
</html>