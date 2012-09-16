<?php
ob_start ();
define ( 'INSIDE', true );
$ugamela_root_path = '../../';
include ($ugamela_root_path . 'extension.inc');
include ($ugamela_root_path . 'includes/common.' . $phpEx);
include ($ugamela_root_path . 'includes/db_connect.' . $phpEx);

if(!check_user()){ header("Location: login.php"); }
if($user['authlevel']!=5){ header("Location: login.php"); }

function rand_map($x0,$y0,$x1,$y1,$max_villa_1,$max_villa_2,$max_villa_3,$max_villa_4,$max_villa_5,$max_villa_6,$max_villa_7,$max_villa_8,$max_villa_9,$max_villa_10,$max_villa_11,$max_villa_12,$max_villa_13,$max_villa_14) 
{
//==================================================================================
//Vừa tạo và lưu xuống database với toạ độ $j là x và $i là y
			global $db;
			
//Giá trị loại Villa muốn xuất ra
	$valu_villa_name='No name';
	$valu_villa_1=1;//'<a style="color:red">1</a>';//"<img src='t3.png'  />";
	$valu_villa_2=2;//'<a style="color:red">2</a>';//"<img src='t6.png'  />";
	$valu_villa_3=3;//'<a style="color:red">3</a>';//"<img src='t15.png'  />";
	$valu_villa_4=4;//'<a style="color:red">4</a>';//"<img src='t12.png'  />";
	$valu_villa_5=5;//'<a style="color:red">5</a>';//"<img src='t13.png'  />";
	$valu_villa_6=6;//'<a style="color:red">6</a>';//"<img src='t14.png'  />";
	$valu_villa_7=7;//'<a style="color:red">7</a>';//"<img src='t5.png'  />";
	$valu_villa_8=8;//'<a style="color:red">8</a>';//"<img src='t4.png'  />";
	$valu_villa_9=9;//'<a style="color:red">9</a>';//"<img src='t3.png'  />";
	$valu_villa_10=10;//'<a style="color:red">10</a>';//"<img src='t2.png'  />";
	$valu_villa_11=11;//'<a style="color:red">11</a>';//"<img src='t5.png'  />";
	$valu_villa_12=12;//'<a style="color:red">12</a>';//"<img src='t4.png'  />";
	$valu_villa_13=13;//'<a style="color:red">13</a>';//"<img src='t5.png'  />";
	$valu_villa_14=14;//'<a style="color:red">14</a>';//"<img src='t2.png'  />";
	$valu_villa_15=1;//'<a style="color:red">1</a>';//"<img src='t2.png'  />";
	//$valu_villa_sp='</td>';
	//$bgcolor_map_1="style='background-color: rgb(255, 255, 255);'";
	//$bgcolor_map_2="style='background-color: rgb(228, 228, 228);'";
	$sum_max_villa_loai_1=$max_villa_1+$max_villa_2+$max_villa_3+$max_villa_4+$max_villa_5+$max_villa_6;
	$sum_max_villa_loai_2=$max_villa_7+$max_villa_8+$max_villa_9+$max_villa_10+$max_villa_11+$max_villa_12+$max_villa_13+$max_villa_14;
	//Vi trí các làng lưu vào đây
	//echo "<table border='1' cellpadding='0' cellspacing='0' ><tbody>";
	
	for ($i=$x0;$i<=$x1;$i++)
	{
		//echo "<tr>";		
		for ($j=$y0;$j<=$y1;$j++)
		{
			$sql = "INSERT INTO `wg_villages` (`id`,`name`,`x`,`y`,`kind_id`,`user_id`)";
			/*if(($j%2)-($i%2))
				$bgcolor_map=$bgcolor_map_1;
			else
				$bgcolor_map=$bgcolor_map_2; */
			////echo "<td align='center' valign='middle' width='40' height='40' ".$bgcolor_map.">";
			//Cho chỉ số random nhiều vào để tỉ lệ dc phân bố điều
			$rand__villa=rand(1,2);
	//Đánh dấu đã lấy dược làng 
	$flag=0;
	//Nếu là Villa loại 1------------------------------------------------------------------------
			if($rand__villa==1)
			{
				if($sum_max_villa_loai_1!=0)//Còn Villa loại 1 thì xuất ra từ 1->6
				{
	//Kiểm tra làng từ 1->6 -------------------------------------------------------
					if($max_villa_1!=0 && $flag==0)
					{
						////echo  $valu_villa_1 . $valu_villa_sp ;
						$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_1 . "', '0');";
						//echo "<td>";
						//echo "(".$j.",".$valu_villa_1.",".$i.")";
						//echo "</td>";
						$max_villa_1--;
						$sum_max_villa_loai_1--;
						$flag=1;
					}
					if($max_villa_2!=0 && $flag==0)
					{
						////echo  $valu_villa_2 . $valu_villa_sp ;
						$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_2 . "', '0');";
						//echo "<td>";
						//echo "(".$j.",".$valu_villa_2.",".$i.")";
						//echo "</td>";
						$max_villa_2--;
						$sum_max_villa_loai_1--;
						$flag=1;
					}
					if($max_villa_3!=0 && $flag==0)
						{
							////echo  $valu_villa_3 . $valu_villa_sp ;
							$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_3 . "', '0');";
							//echo "<td>";
							//echo "(".$j.",".$valu_villa_3.",".$i.")";
							//echo "</td>";
							$max_villa_3--;
							$sum_max_villa_loai_1--;
							$flag=1;
						}
					if($max_villa_4!=0 && $flag==0)
						{
							////echo  $valu_villa_4 . $valu_villa_sp ;
							$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_4 . "', '0');";
							//echo "<td>";
							//echo "(".$j.",".$valu_villa_4.",".$i.")";
							//echo "</td>";
							$max_villa_4--;
							$sum_max_villa_loai_1--;
							$flag=1;
						}
					if($max_villa_5!=0 && $flag==0)
						{
							////echo  $valu_villa_5 . $valu_villa_sp ;
							$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_5 . "', '0');";
							//echo "<td>";
							//echo "(".$j.",".$valu_villa_5.",".$i.")";
							//echo "</td>";
							$max_villa_5--;
							$sum_max_villa_loai_1--;
							$flag=1;
						}
					if($max_villa_6!=0 && $flag==0)
						{
							////echo  $valu_villa_6 . $valu_villa_sp ;
							$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_6 . "', '0');";
							//echo "<td>";
							//echo "(".$j.",".$valu_villa_6.",".$i.")";
							//echo "</td>";
							$max_villa_6--;
							$sum_max_villa_loai_1--;
							$flag=1;
						}
				}
				else //Nếu không còn Villa loại 1 thì xuất ra loại 2 thay thế
				{
					if($sum_max_villa_loai_2!=0)//Nếu còn Villa loại 2 thì xuất ra
					{
						if($max_villa_7!=0 && $flag==0)
							{
								////echo  $valu_villa_7 . $valu_villa_sp ;
								$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_7 . "', '0');";
								//echo "<td>";
								//echo "(".$j.",".$valu_villa_7.",".$i.")";
								//echo "</td>";
								$max_villa_7--;
								$sum_max_villa_loai_2--;
								$flag=1;
							}
						if($max_villa_8!=0 && $flag==0)
							{
								////echo  $valu_villa_8 . $valu_villa_sp ;
								$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_8 . "', '0');";
								//echo "<td>";
								//echo "(".$j.",".$valu_villa_8.",".$i.")";
								//echo "</td>";
								$max_villa_8--;
								$sum_max_villa_loai_2--;
								$flag=1;
							}
						if($max_villa_9!=0 && $flag==0)
							{
								////echo  $valu_villa_9 . $valu_villa_sp ;
								$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_9 . "', '0');";
								//echo "<td>";
								//echo "(".$j.",".$valu_villa_9.",".$i.")";
								//echo "</td>";
								$max_villa_9--;
								$sum_max_villa_loai_2--;
								$flag=1;
							}
						if($max_villa_10!=0 && $flag==0)
							{
								////echo  $valu_villa_10 . $valu_villa_sp ;
								$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_10 . "', '0');";
								//echo "<td>";
								//echo "(".$j.",".$valu_villa_10.",".$i.")";
								//echo "</td>";
								$max_villa_10--;
								$sum_max_villa_loai_2--;
								$flag=1;
							}
						if($max_villa_11!=0 && $flag==0)
							{
								////echo  $valu_villa_11 . $valu_villa_sp ;
								$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_11 . "', '0');";
								//echo "<td>";
								//echo "(".$j.",".$valu_villa_11.",".$i.")";
								//echo "</td>";
								$max_villa_11--;
								$sum_max_villa_loai_2--;
								$flag=1;
							}
						if($max_villa_12!=0 && $flag==0)
							{
								////echo  $valu_villa_12 . $valu_villa_sp ;
								$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_12 . "', '0');";
								//echo "<td>";
								//echo "(".$j.",".$valu_villa_12.",".$i.")";
								//echo "</td>";
								$max_villa_12--;
								$sum_max_villa_loai_2--;
								$flag=1;
							}
						if($max_villa_13!=0 && $flag==0)
							{
								////echo  $valu_villa_13 . $valu_villa_sp ;
								$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_13 . "', '0');";
								//echo "<td>";
								//echo "(".$j.",".$valu_villa_13.",".$i.")";
								//echo "</td>";
								$max_villa_13--;
								$sum_max_villa_loai_2--;
								$flag=1;
							}
						if($max_villa_14!=0 && $flag==0)
							{
								////echo  $valu_villa_14 . $valu_villa_sp ;
								$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_14 . "', '0');";
								//echo "<td>";
								//echo "(".$j.",".$valu_villa_14.",".$i.")";
								//echo "</td>";
								$max_villa_14--;
								$sum_max_villa_loai_2--;
								$flag=1;
							}
							
					}
				};
			};// kết thúc if loại 1
	//Nếu là Villa loại 2--------------------------------------------------------------------------
			if($rand__villa==2)
			{
				if($sum_max_villa_loai_2!=0)//Còn Villa loại 2 thì xuất ra
				{
	//Từ 7->14
						if($max_villa_7!=0 && $flag==0)
							{
								////echo  $valu_villa_7 . $valu_villa_sp ;
								$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_7 . "', '0');";
								//echo "<td>";
								//echo "(".$j.",".$valu_villa_7.",".$i.")";
								//echo "</td>";
								$max_villa_7--;
								$sum_max_villa_loai_2--;
								$flag=1;
							}
						if($max_villa_8!=0 && $flag==0)
							{
								////echo  $valu_villa_8 . $valu_villa_sp ;
								$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_8 . "', '0');";
								//echo "<td>";
								//echo "(".$j.",".$valu_villa_8.",".$i.")";
								//echo "</td>";
								$max_villa_8--;
								$sum_max_villa_loai_2--;
								$flag=1;
							}
						if($max_villa_9!=0 && $flag==0)
							{
								////echo  $valu_villa_9 . $valu_villa_sp ;
								$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_9 . "', '0');";
								//echo "<td>";
								//echo "(".$j.",".$valu_villa_9.",".$i.")";
								//echo "</td>";
								$max_villa_9--;
								$sum_max_villa_loai_2--;
								$flag=1;
							}
						if($max_villa_10!=0 && $flag==0)
							{
								////echo  $valu_villa_10 . $valu_villa_sp ;
								$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_10 . "', '0');";
								//echo "<td>";
								//echo "(".$j.",".$valu_villa_10.",".$i.")";
								//echo "</td>";
								$max_villa_10--;
								$sum_max_villa_loai_2--;
								$flag=1;
							}
						if($max_villa_11!=0 && $flag==0)
							{
								////echo  $valu_villa_11 . $valu_villa_sp ;
								$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_11 . "', '0');";
								//echo "<td>";
								//echo "(".$j.",".$valu_villa_11.",".$i.")";
								//echo "</td>";
								$max_villa_11--;
								$sum_max_villa_loai_2--;
								$flag=1;
							}
						if($max_villa_12!=0 && $flag==0)
							{
								////echo  $valu_villa_12 . $valu_villa_sp ;
								$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_12 . "', '0');";
								//echo "<td>";
								//echo "(".$j.",".$valu_villa_12.",".$i.")";
								//echo "</td>";
								$max_villa_12--;
								$sum_max_villa_loai_2--;
								$flag=1;
							}
						if($max_villa_13!=0 && $flag==0)
							{
								////echo  $valu_villa_13 . $valu_villa_sp ;
								$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_13 . "', '0');";
								//echo "<td>";
								//echo "(".$j.",".$valu_villa_13.",".$i.")";
								//echo "</td>";
								$max_villa_13--;
								$sum_max_villa_loai_2--;
								$flag=1;
							}
						if($max_villa_14!=0 && $flag==0)
							{
								////echo  $valu_villa_14 . $valu_villa_sp ;
								$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_14 . "', '0');";
								//echo "<td>";
								//echo "(".$j.",".$valu_villa_14.",".$i.")";
								//echo "</td>";
								$max_villa_14--;
								$sum_max_villa_loai_2--;
								$flag=1;
							}
				}
				else //Nếu không còn Villa loại 2 thì xuất ra loại 3 thay thế
				{
					if($sum_max_villa_loai_1!=0)//Nếu còn Villa loại 1 thì xuất ra
					{
							//Kiểm tra làng từ 1->6 -------------------------------------------------------
							if($max_villa_1!=0 && $flag==0)
							{
								////echo  $valu_villa_1 . $valu_villa_sp ;
								$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_1 . "', '0');";
								//echo "<td>";
								//echo "(".$j.",".$valu_villa_1.",".$i.")";
								//echo "</td>";
								$max_villa_1--;
								$sum_max_villa_loai_1--;
								$flag=1;
							}
							if($max_villa_2!=0 && $flag==0)
							{
								////echo  $valu_villa_2 . $valu_villa_sp ;
								$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_2 . "', '0');";
								//echo "<td>";
								//echo "(".$j.",".$valu_villa_2.",".$i.")";
								//echo "</td>";
								$max_villa_2--;
								$sum_max_villa_loai_1--;
								$flag=1;
							}
							if($max_villa_3!=0 && $flag==0)
								{
									////echo  $valu_villa_3 . $valu_villa_sp ;
									$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_3 . "', '0');";
									//echo "<td>";
									//echo "(".$j.",".$valu_villa_3.",".$i.")";
									//echo "</td>";
									$max_villa_3--;
									$sum_max_villa_loai_1--;
									$flag=1;
								}
							if($max_villa_4!=0 && $flag==0)
								{
									////echo  $valu_villa_4 . $valu_villa_sp ;
									$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_4 . "', '0');";
									//echo "<td>";
									//echo "(".$j.",".$valu_villa_4.",".$i.")";
									//echo "</td>";
									$max_villa_4--;
									$sum_max_villa_loai_1--;
									$flag=1;
								}
							if($max_villa_5!=0 && $flag==0)
								{
									////echo  $valu_villa_5 . $valu_villa_sp ;
									$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_5 . "', '0');";
									//echo "<td>";
									//echo "(".$j.",".$valu_villa_5.",".$i.")";
									//echo "</td>";
									$max_villa_5--;
									$sum_max_villa_loai_1--;
									$flag=1;
								}
							if($max_villa_6!=0 && $flag==0)
								{
									////echo  $valu_villa_6 . $valu_villa_sp ;
									$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_6 . "', '0');";
									//echo "<td>";
									//echo "(".$j.",".$valu_villa_6.",".$i.")";
									//echo "</td>";
									$max_villa_6--;
									$sum_max_villa_loai_1--;
									$flag=1;
								}	
					}
				};
			}
				if($flag==0)
				{
					////echo  $valu_villa_15 . $valu_villa_sp ;
					$sql .= "VALUES ('', '" . $valu_villa_name . "', '" . $j . "', '" . $i . "', '" . $valu_villa_15 . "', '0');";
					//echo "<td>";
					//echo "(".$j.",".$valu_villa_15.",".$i.")";
					//echo "</td>";
				};
			//Lưu vào database
			$db->setQuery ( $sql );
			$db->query ();
			
		};//end for 1
		////echo "<br>";
		//echo  "</tr>";
		
	}//end for 2
	//echo "</tbody></table>";
	
}
function rand_map_big($x,$y,$arr_villa_map) 
{
	//Nếu là 800x800 thì có 1600 ô
	//
	//Quy định villa_1 ko tham gia vào hàm rand này
	//vì nó dùng để đền bù những vị trí thieu trong map
	set_time_limit ( 10000 );
//Với toàn bộ map thì có thể có bao nhiêu phần 25 ô
	$ti_le_x=ceil(($x)/5);
	$ti_le_y=ceil(($y)/5);
	$ti_le_x_2=$ti_le_x*$ti_le_x;
	////echo $ti_le_x_2;
	//$ti_le_y_2=$ti_le_y*$ti_le_y*4;
	$dem_v_1=1;//max 25600 
	$dem_v_2=1;//max 25600 
	$dem_v_3=1;//max 25600 
	$dem_v_4=1;//max 25600 
	$buoc_nhay=$ti_le_x_2;//25600;
//Bước nhảy
	$buoc_nhay_villa_v_1=array();//Nhảy ở vùng thứ nhất
	$buoc_nhay_villa_v_2=array();
	$buoc_nhay_villa_v_3=array();
	$buoc_nhay_villa_v_4=array();
	for($mk=1;$mk<=14;$mk++)
	{
		$buoc_nhay_villa_v_1[$mk]=ceil($buoc_nhay/($arr_villa_map[$mk]/4));
	}
	////echo $buoc_nhay_villa_v_1[1];
	//Tự chuyển ra cho 3 vùng còn lại
	
	$buoc_nhay_villa_v_2=$buoc_nhay_villa_v_1;//Nhảy ở vùng thứ hai
	$buoc_nhay_villa_v_3=$buoc_nhay_villa_v_1;//Nhảy ở vùng thứ ba
	$buoc_nhay_villa_v_4=$buoc_nhay_villa_v_1;//Nhảy ở vùng thứ tư
//Lưu tạm vị trí nhảy để cộng lại trong vòng for  vì không thực hiện x2 dc
	$buoc_nhay_tam_villa_v_1=$buoc_nhay_villa_v_1;
	$buoc_nhay_tam_villa_v_2=$buoc_nhay_villa_v_2;
	$buoc_nhay_tam_villa_v_3=$buoc_nhay_villa_v_3;
	$buoc_nhay_tam_villa_v_4=$buoc_nhay_villa_v_4;
	//print_r($buoc_nhay_villa_v_3);
//Số làng cần xuất ra khi nhảy xuất hiện
	$max_villa_xuat_hien_v_1=array();//Khi nhảy đến thì xuất ra bao nhiêu làng
	for($mk=1;$mk<=14;$mk++)
	{
		$max_villa_xuat_hien_v_1[$mk]=ceil(($arr_villa_map[$mk]/4)/$ti_le_x_2);
	}
	//Tự chuyển ra cho 3 vùng còn lại
	
	$max_villa_xuat_hien_v_2=$max_villa_xuat_hien_v_1;
	$max_villa_xuat_hien_v_3=$max_villa_xuat_hien_v_1;
	$max_villa_xuat_hien_v_4=$max_villa_xuat_hien_v_1;
	
//Phân chia điều các làng trên tỉ lệ đó
	
	//echo "<table border='1' cellpadding='0' cellspacing='0'><tbody>";
	$end_for_mk_x=0;
	$end_for_mk_y=0;
	$send_villa=array();
	for ($j=-$ti_le_x;$j<$ti_le_x;$j++)
	{
		//echo "<tr>";
		if($j==($ti_le_x-1))
			{
				$end_for_mk_x=1;
				////echo  "minhthanh";
			}
		for ($i=-$ti_le_y;$i<$ti_le_y;$i++)
		{
			if($i==($ti_le_y-1))
			{
				$end_for_mk_y=1;
				////echo  "minhthanh";
			}
//Phân chia điều 4 phần map
			if($j<=0 && $i<=0)//Vùng 1
			{
				for($mk=1;$mk<=14;$mk++)
				{
					if($arr_villa_map[$mk]>=0 && $dem_v_1==$buoc_nhay_villa_v_1[$mk])//Đạt yêu cầu nhảy
					{
						$send_villa[$mk]=$max_villa_xuat_hien_v_1[$mk];//Khôi phục lại trị ban đầu cần xuất hiện của làng
						$arr_villa_map[$mk]=$arr_villa_map[$mk]-$send_villa[$mk];
						$buoc_nhay_villa_v_1[$mk]=$buoc_nhay_villa_v_1[$mk]+$buoc_nhay_tam_villa_v_1[$mk];
					}
					else 
						$send_villa[$mk]=0;
				}
				$dem_v_1++;
			}

			if($j<0 && $i>0)//Vùng 2
			{
				for($mk=1;$mk<=14;$mk++)
				{
					if($arr_villa_map[$mk]>=0 && $dem_v_2==$buoc_nhay_villa_v_2[$mk])//Đạt yêu cầu nhảy
					{
						$send_villa[$mk]=$max_villa_xuat_hien_v_2[$mk];//Khôi phục lại trị ban đầu cần xuất hiện của làng
						$arr_villa_map[$mk]=$arr_villa_map[$mk]-$send_villa[$mk];
						$buoc_nhay_villa_v_2[$mk]=$buoc_nhay_villa_v_2[$mk]+$buoc_nhay_tam_villa_v_2[$mk];
					}
					else 
						$send_villa[$mk]=0;
				}
				$dem_v_2++;
			}

			if($j>0 && $i<0)//Vùng 3
			{
				for($mk=1;$mk<=14;$mk++)
				{
					if($arr_villa_map[$mk]>=0 && $dem_v_3==$buoc_nhay_villa_v_3[$mk])//Đạt yêu cầu nhảy
					{
						$send_villa[$mk]=$max_villa_xuat_hien_v_3[$mk];//Khôi phục lại trị ban đầu cần xuất hiện của làng
						$arr_villa_map[$mk]=$arr_villa_map[$mk]-$send_villa[$mk];
						$buoc_nhay_villa_v_3[$mk]=$buoc_nhay_villa_v_3[$mk]+$buoc_nhay_tam_villa_v_3[$mk];
					}
					else 
						$send_villa[$mk]=0;
				}
				$dem_v_3++;
			}

			if($j>0 && $i>0)//Vùng 4
			{
				for($mk=1;$mk<=14;$mk++)
				{
					if($arr_villa_map[$mk]>=0 && $dem_v_4==$buoc_nhay_villa_v_4[$mk])//Đạt yêu cầu nhảy
					{
						$send_villa[$mk]=$max_villa_xuat_hien_v_4[$mk];//Khôi phục lại trị ban đầu cần xuất hiện của làng
						$arr_villa_map[$mk]=$arr_villa_map[$mk]-$send_villa[$mk];
						$buoc_nhay_villa_v_4[$mk]=$buoc_nhay_villa_v_4[$mk]+$buoc_nhay_tam_villa_v_4[$mk];
					}
					else 
						$send_villa[$mk]=0;
				}
				$dem_v_4++;
			}
//Xét xem khả năng cho xuất hiện Villa------------------------------------------------------------------------------------------
			//echo "<td>";
			//print_r($send_villa);
			rand_map( ($j*5) , ($i*5) , (($j+1)*5-1+$end_for_mk_x) , (($i+1)*5-1+$end_for_mk_y) ,$send_villa[1], $send_villa[2],$send_villa[3],$send_villa[4],$send_villa[5],$send_villa[6],$send_villa[7],$send_villa[8],$send_villa[9],$send_villa[10],$send_villa[11],$send_villa[12],$send_villa[13],$send_villa[14]);
			//echo "</td>";
			$end_for_mk_y=0;
		};
		//echo "</tr>";
		$end_for_mk_x=0;
	};
	//echo "</tbody></table>";
}
$arr_vila_map=array();
$sql = "select * from wg_game_configs where name='villa_1';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$arr_vila_map[1]=$map_manager->value;

$sql = "select * from wg_game_configs where name='villa_2';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$arr_vila_map[2]=$map_manager->value;

$sql = "select * from wg_game_configs where name='villa_3';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$arr_vila_map[3]=$map_manager->value;

$sql = "select * from wg_game_configs where name='villa_4';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$arr_vila_map[4]=$map_manager->value;

$sql = "select * from wg_game_configs where name='villa_5';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$arr_vila_map[5]=$map_manager->value;

$sql = "select * from wg_game_configs where name='villa_6';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$arr_vila_map[6]=$map_manager->value;

$sql = "select * from wg_game_configs where name='villa_7';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$arr_vila_map[7]=$map_manager->value;

$sql = "select * from wg_game_configs where name='villa_8';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$arr_vila_map[8]=$map_manager->value;

$sql = "select * from wg_game_configs where name='villa_9';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$arr_vila_map[9]=$map_manager->value;

$sql = "select * from wg_game_configs where name='villa_10';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$arr_vila_map[10]=$map_manager->value;

$sql = "select * from wg_game_configs where name='villa_11';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$arr_vila_map[11]=$map_manager->value;

$sql = "select * from wg_game_configs where name='villa_12';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$arr_vila_map[12]=$map_manager->value;

$sql = "select * from wg_game_configs where name='villa_13';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$arr_vila_map[13]=$map_manager->value;

$sql = "select * from wg_game_configs where name='villa_14';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$arr_vila_map[14]=$map_manager->value;

$sql = "select * from wg_game_configs where name='max_x';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$arr_vila_map_max_x=$map_manager->value;

$sql = "select * from wg_game_configs where name='max_y';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$arr_vila_map_max_y=$map_manager->value;

EmptyTable();
rand_map_big($arr_vila_map_max_x, $arr_vila_map_max_y, $arr_vila_map);

header("Location: ../createmap.php?s=1");

function EmptyTable(){
	global $db;
	$sql="TRUNCATE TABLE `wg_villages`";
	$db->setQuery($sql);
	if(!$db->query()){
		die("error1!!!");
	}
	return true;
}
?>