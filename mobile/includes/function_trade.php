<?php
function Trade($building){
	global $db, $lang, $wg_village;	
	includeLang('trade');
	include_once("function_status.php");
	include_once("function_resource.php");
	
	if(isset($_GET['t'])){
		switch($_GET['t']){
			case 1:
				$parse['class_send']="";
				$parse['class_buy']="class='selected'";
				$parse['class_offer']="";
				$parse['class_npc']="";
				$parse['class_buyAsu']="";
				$parse['class_offerAsu']="";
				$parse['class_offerRS']="";
				$parse['class_buyRS']="";		
				$parse['task_content']=Buy($building);
				break;
			case 2:
				$parse['class_send']="";
				$parse['class_buy']="";
				$parse['class_offer']="class='selected'";
				$parse['class_npc']="";
				$parse['class_buyAsu']="";
				$parse['class_offerAsu']="";
				$parse['class_offerRS']="";
				$parse['class_buyRS']="";			
				$parse['task_content']=Offer($building);
				break;
			case 3:
				$parse['class_send']="";
				$parse['class_buy']="";
				$parse['class_offer']="";
				$parse['class_npc']="class='selected'";
				$parse['class_buyAsu']="";
				$parse['class_offerAsu']="";
				$parse['class_offerRS']="";
				$parse['class_buyRS']="";
				$parse['task_content']=NPCTrade($building);				
				break;
			case 4:
				$parse['class_send']="";
				$parse['class_buy']="";
				$parse['class_offer']="";
				$parse['class_npc']="";				
				$parse['class_buyAsu']="class='selected'";
				$parse['class_offerAsu']="";
				$parse['class_offerRS']="";
				$parse['class_buyRS']="";
				$parse['task_content']=BuyAsu($building);
				break;
			case 5:
				$parse['class_send']="";
				$parse['class_buy']="";
				$parse['class_offer']="";
				$parse['class_npc']="";
				$parse['class_buyAsu']="";
				$parse['class_offerAsu']="class='selected'";
				$parse['class_offerRS']="";
				$parse['class_buyRS']="";
				$parse['task_content']=OfferAsu($building);
				break;
			case 6:
				$parse['class_send']="";
				$parse['class_buy']="";
				$parse['class_offer']="";
				$parse['class_npc']="";
				$parse['class_buyAsu']="";
				$parse['class_offerAsu']="";
				$parse['class_offerRS']="class='selected'";
				$parse['class_buyRS']="";
				$parse['task_content']=OfferRS($building);
				break;
			case 7:
				$parse['class_send']="";
				$parse['class_buy']="";
				$parse['class_offer']="";
				$parse['class_npc']="";
				$parse['class_buyAsu']="";
				$parse['class_offerAsu']="";
				$parse['class_offerRS']="";
				$parse['class_buyRS']="class='selected'";
				$parse['task_content']=BuyRS($building);
				break;
			default:
				$parse['task_content']=SendSource($building);
				break;				
		}
	}else{
		$parse['task_content']=SendSource($building);
	}		
	
	
	$parse+=$lang;
	$parse['id']=$building->index;
	return parsetemplate(gettemplate('trade_body'), $parse);
}

function SendSource($building){
	global $wg_village;
	//xu ly su kien chuyen RS.
	if(isset($_POST['dname'])){
		if($_POST['dname']){
			$villageTo=CheckVillageName($_POST['dname']);
			if(!$villageTo || $villageTo->user_id==0){
				return DisplaySendResource1($building, -4);
			}			
		}else{
			if(is_numeric($_POST['x']) && is_numeric($_POST['y'])){
				$villageTo=CheckVillageLocation($_POST['x'], $_POST['y']);
				if(!$villageTo || $villageTo->user_id==0){
					return DisplaySendResource1($building ,-5);
				}
			}
		}
		
		if($villageTo){
			if($villageTo->id==$wg_village->id){
				//hai lang trung nhau.
				return DisplaySendResource1($building ,-7);
			}
			
			//check user has sent resource over production speed per a day
			$resCheck = checkMaxSendRsPerDay($village_id, $villageTo->id, $post['r1'],$post['r2'],$post['r3'],$post['r14'] );
			if(!$resCheck){
				return DisplaySendResource1($building ,-8);
			}
			
			if(isset($_POST['r1']) && isset($_POST['r2']) && isset($_POST['r3']) && isset($_POST['r4']) && ($_POST['r1']>0 || $_POST['r2']>0 || $_POST['r3']>0 || $_POST['r4']>0)){
				if($wg_village->rs1 >= $_POST['r1'] && $wg_village->rs2 >= $_POST['r2'] && $wg_village->rs3 >= $_POST['r3'] && $wg_village->rs4 >= $_POST['r4']){
					
					//chuyen qua buoc 2:
					$_POST['sum_merchant']=CheckMerchant($building, ($_POST['r1']+$_POST['r2']+$_POST['r3']+$_POST['r4']));
					
					if($_POST['sum_merchant']){
						$_POST['village_name']=$villageTo->name;
						$_POST['x']=$villageTo->x;
						$_POST['y']=$villageTo->y;
						$_POST['player_name']=GetPlayerName($villageTo->id);
						$_POST['village_to_id']=$villageTo->id;
						return DisplaySendResource2($building);
					}else{
						return DisplaySendResource1($building ,-3);	//thieu thuong nhan
					}
				}else{
					return DisplaySendResource1($building ,0);	//loi resource
				}
			}else{
				return DisplaySendResource1($building ,-2);	//chua nhap resoure.
			}
		}else{
				return DisplaySendResource1($building ,-1); //Thieu thong tin lang
		}
	}
	
	
	//buoc cuoi cung:
	if(isset($_POST['vtid'])){
		$villageTo=getVillage($_POST['vtid']);
		if($villageTo){
			if(isset($_POST['r1']) && isset($_POST['r2']) && isset($_POST['r3']) && isset($_POST['r4']) && ($_POST['r1']>0 || $_POST['r2']>0 || $_POST['r3']>0 || $_POST['r4']>0)){
				if($wg_village->rs1>=$_POST['r1'] && $wg_village->rs2>=$_POST['r2'] && $wg_village->rs3>=$_POST['r3'] && $wg_village->rs4>=$_POST['r4']){
					$sumMerchant=CheckMerchant($building, ($_POST['r1']+$_POST['r2']+$_POST['r3']+$_POST['r4']));
					if($sumMerchant){
						//Thuc hien Send RS:
						if(!$_POST['r1']){
							$_POST['r1']=0;
						}
						if(!$_POST['r2']){
							$_POST['r2']=0;
						}
						if(!$_POST['r3']){
							$_POST['r3']=0;
						}
						if(!$_POST['r4']){
							$_POST['r4']=0;
						}
						
						//Luu thong tin send xuong database:
						$objectID=InsertSendRS($wg_village->id, $villageTo->id, $_POST['r1'], $_POST['r2'], $_POST['r3'], $_POST['r4'], 0, $sumMerchant);
						
						if($objectID){
							$costTime=GetMerchantDuration($wg_village->id, $_POST['vtid']);
							
							//luu trang thai giao dich:
							InsertSendRSStatus($objectID, $wg_village->id, date("Y-m-d H:i:s"), date("Y-m-d H:i:s", time()+$costTime), $costTime, 23);
							
							//trừ rs cua làng gửi:
							$wg_village->rs1 -= intval($_POST['r1']);
							$wg_village->rs2 -= intval($_POST['r2']);
							$wg_village->rs3 -= intval($_POST['r3']);
							$wg_village->rs4 -= intval($_POST['r4']);
							
							//$wg_village->merchant_underaway += $sumMerchant;
							//ChangeMerchantUnderaway($wg_village->id, $sumMerchant);
							
							unset($_POST);
							//quay ve trang chính:
							return DisplaySendResource1($building ,1);
						}
						return DisplaySendResource2($building);						
					}
				}else{
					return DisplaySendResource1($building ,0);	//loi resource
				}
			}else{
				return DisplaySendResource1($building ,-2);	//chua resoure.
			}
		}
	}
	return DisplaySendResource1($building ,0);
}

function Buy($building){
	global $db, $lang, $wg_village, $user;
	//xu ly su kien mua RS.
	if(isset($_GET['o']) && is_numeric($_GET['o'])){
		$id = $db->getEscaped($_GET['o']);
		$sql="SELECT * FROM wg_resource_orders WHERE id=$id";
		$db->setQuery($sql);
		$rsOrder=null;
		$db->loadObject($rsOrder);
		if($rsOrder){
			//Ton tai offer.
			$sumMerchant = $rsOrder->merchants;//lay so thuong nhan cua nguoi ban
			//Tinh so thuong nhan van chuyen cho nguoi mua
			$Merchant = GetMerchantTransport($wg_village->id, $rsOrder->num2);
			if(CheckActionBuy($building, $rsOrder->id, $rsOrder->num2, $rsOrder->type2, $Merchant)==1){
				//Du dieu kien giao dich.
				//Nguoi ban chuyen cho nguoi mua:
				$r1=$r2=$r3=$r4=0;
				switch($rsOrder->type1){
					case 1:
						$r1=$rsOrder->num1;
						break;
					case 2:
						$r2=$rsOrder->num1;
						break;
					case 3:
						$r3=$rsOrder->num1;
						break;
					case 4:
						$r4=$rsOrder->num1;
						break;
				}				
				
				
				$objectID=InsertSendRS($rsOrder->village_id, $wg_village->id, $r1, $r2, $r3, $r4,0,$sumMerchant);
				if($objectID){
					$cost_time=GetMerchantDuration($rsOrder->village_id, $wg_village->id);
					$time_begin=time();
					$time_end=$time_begin+$cost_time;
					InsertSendRSStatus($objectID, $rsOrder->village_id, date("Y-m-d H:i:s"), date("Y-m-d H:i:s", $time_end), $cost_time);
				}
				
				//Nguoi mua chuyen cho nguoi ban:
				$r1=$r2=$r3=$r4=0;
				switch($rsOrder->type2){
					case 1:
						$r1=$rsOrder->num2;
						break;
					case 2:
						$r2=$rsOrder->num2;
						break;
					case 3:
						$r3=$rsOrder->num2;
						break;
					case 4:
						$r4=$rsOrder->num2;
						break;					
				}
				$mcBuy=CheckMerchant($building,$rsOrder->num2);
				$objectID=InsertSendRS($wg_village->id, $rsOrder->village_id, $r1, $r2, $r3, $r4,0,$mcBuy);
				if($objectID){					
					$cost_time=GetMerchantDuration($wg_village->id, $rsOrder->village_id);
					$time_begin=time();
					$time_end=$time_begin+$cost_time;
					InsertSendRSStatus($objectID, $wg_village->id, date("Y-m-d H:i:s"), date("Y-m-d H:i:s", $time_end), $cost_time);
					//Tru RS cua lang mua:
					$wg_village->rs1 -= $r1;
					$wg_village->rs2 -= $r2;
					$wg_village->rs3 -= $r3;
					$wg_village->rs4 -= $r4;
					
					//Tang so thuong nhan di giao dich cua lang mua:
					//$wg_village->merchant_underaway += $mcBuy;
					//ChangeMerchantUnderaway($wg_village->id, $mcBuy);
				}
				//Xoa offer:
				DeleteOffer($rsOrder->id);
				return DisplaySendResource1($building , 0);
			}
		}
	}
	return ShowBuy($building);
}

function Offer($building){
	global $db, $lang, $wg_village,$user;
	includeLang("trade");
	$parse=$lang;
	$parse['error_message']="";
	$parse['offer_status']="";
	$dataPost = $_POST;
	
	if(count($_POST)>0){
		if(is_numeric($_POST['m2']) && $_POST['m2']>0 && is_numeric($_POST['m1']) && $_POST['m1']>0 &&  $_POST['rid1'] != $_POST['rid2']){
				$rateChange = $dataPost['m2']/$dataPost['m1'];			
				if($rateChange >= 0.5 && $rateChange <= 2 ){	//Ti le cho phep			
					$r1=$r2=$r3=$r4=0;
					switch($_POST['rid1']){
						case 1:
							$r1=$_POST['m1'];
							break;
						case 2:
							$r2=$_POST['m1'];
							break;
						case 3:
							$r3=$_POST['m1'];
							break;
						case 4:
							$r4=$_POST['m1'];
							break;						
					}
					if($wg_village->rs1 >= $r1 && $wg_village->rs2 >= $r2 && $wg_village->rs3 >= $r3 && $wg_village->rs4 >= $r4){
						$sumMerchant	= CheckMerchant($building, $_POST['m1']);				
											
						if($sumMerchant ){//kiem tra so thuong nhan.							
							//du dieu kien.
							//	-cap nhat so thuong nhan.
							//	-chen thong tin vao bang wg_resource_oders.
							//$wg_village->merchant_underaway += $sumMerchant;
							//ChangeMerchantUnderaway($wg_village->id, $sumMerchant);
								
							//tru rs cua lang:
							$wg_village->rs1 -=$r1;
							$wg_village->rs2 -=$r2;
							$wg_village->rs3 -=$r3;
							$wg_village->rs4 -=$r4;						
							if($_POST['mt1'] && is_numeric($_POST['mt2'])){
								$time_transport=$_POST['mt2']*3600;
							}else{
								$time_transport=0;
							}
							
							InsertOder($wg_village->id, $_POST['m1'], $_POST['rid1'], $_POST['m2'], $_POST['rid2'], $sumMerchant, $time_transport);
							
						}else{
							//khong du thuong nhan.
							$parse['error_message']=$lang['error_offer_few_merchant'];
						}
					}else{
						//khong du RS.
						$parse['error_message']=$lang['error_offer_few_rs'];
					}
				}else{ //Else ti le cho phep				
					$parse['error_message']=$lang['error_offer_rate_rs'];
				}
		}else{
			//du lieu khong hop le.
			$parse['error_message']=$lang['error_offer_input'];
		}		
	}
/*	Co yeu cau xoa mot offer:
		- Tra lai RS cho lang.
		- Tra lai thuong nhan cho lang.
		- Xoa offer.
*/
	if(isset($_GET['c']) && is_numeric($_GET['c'])){
		$offer_id=$db->getEscaped($_GET['c']);
		$sql="SELECT * FROM wg_resource_orders WHERE id=$offer_id AND village_id=$wg_village->id";
		$db->setQuery($sql);
		$offer=null;
		$db->loadObject($offer);
		if($offer){
			$r1=$r2=$r3=$r4=0;
			switch($offer->type1){
				case 1:
					$r1=$offer->num1;
					break;
				case 2:
					$r2=$offer->num1;
					break;
				case 3:
					$r3=$offer->num1;
					break;
				case 4:
					$r4=$offer->num1;
					break;
			}
			$wg_village->rs1 += $r1;
			$wg_village->rs2 += $r2;
			$wg_village->rs3 += $r3;
			$wg_village->rs4 += $r4;
						
			//$wg_village->merchant_underaway -= $offer->merchants;
			//ChangeMerchantUnderaway($wg_village->id, -$offer->merchants);
			
			DeleteOffer($offer_id);
		}
	}
	$parse['merchant_available']= getMerchantAvailable($wg_village->id ,$building->level);
	$parse['sum_merchant']=$building->level;
	$parse['offer_status']=OfferStatus();	//lay trang thai cua RS dang rao ban.
	return parsetemplate(gettemplate('offer_body'), $parse);
}

function NPCTrade($building){
	global $db,$lang, $wg_village,$user;
	includelang("Trade");
	$parse=$lang;	
	$sql = "SELECT asu FROM wg_config_plus WHERE name = 'npctrade'";
	$db->setQuery($sql);
	$asu_npctrade=$db->loadResult();
	if($_POST['m1']){
		NPCTrading($building,$asu_npctrade);
	}
	if(TradeCheckGold($wg_village->user_id,$asu_npctrade))
	{
		$parse['check_gold_status']="<a href='javascript:document.snd.submit();'>".$parse['Trade resources at']." (".$parse['step']." 2 ".$parse['of']." 2)</a> <span class=\"c\">(".$parse['Costs'].": <b>".$asu_npctrade."</b> ".$parse['gold'].")";
	}else{
		$parse['check_gold_status']="<a href=\"#\"><span class=\"c t\">".$parse['not enough gold for trade available']."</span></a>";
	}
	
	$parse['level']=$building->level;
	$parse['rs1']=$wg_village->rs1;
	$parse['rs2']=$wg_village->rs2;
	$parse['rs3']=$wg_village->rs3;
	$parse['rs4']=$wg_village->rs4;
	$parse['sumrs']=$wg_village->rs1+$wg_village->rs2+$wg_village->rs3+$wg_village->rs4;
	$parse['capacity_123']=$wg_village->capa123;
	$parse['capacity_4']=$wg_village->capa4;
	
	return parsetemplate(gettemplate('npctrading_body'), $parse);
}

function NPCTrading($building,$asu_npctrade)
{
	global $db, $wg_village;	
	$m1=$_POST['m1'];	//resource cu
	$m2=$_POST['m2'];	//resource NPC trading
	
	$sumrsNPC=$m2[0]+$m2[1]+$m2[2]+$m2[3];
	$sumrs=$wg_village->rs1 + $wg_village->rs2 + $wg_village->rs3 + $wg_village->rs4;
	if($sumrsNPC<=$sumrs){
		if(TradeCheckGold($wg_village->user_id,$asu_npctrade))
		{
/*				- Tru gold cua user.
			- update RS			*/
			TradeSubGold($wg_village->user_id,$asu_npctrade);
			$wg_village->rs1 = $m2[0] + ($wg_village->rs1 - $m1[0]); 	//($village->rs1 - m1[0]) luong resource 
			$wg_village->rs2 = $m2[1] + ($wg_village->rs2 - $m1[1]);	//sinh ra trong khi nguoi choi thao tac
			$wg_village->rs3 = $m2[2] + ($wg_village->rs3 - $m1[2]);
			$wg_village->rs4 = $m2[3] + ($wg_village->rs4 - $m1[3]);			
			return true;
		}else{
			//Thieu gold.
			return "thieu gold";
		}
	}	
	return false;
}
//Giao thuong bang ASU
//author:Diep Luan
//Mua ban tai nguyen bang asu va nguoc lai
//Gia tri tang giam theo cung cau

//Ban tai nguyen doi lay ASU == Mua Asu bang tai nguyen;t==4
function BuyAsu($building){
	global $db, $lang, $wg_village, $wg_asuconfig;
	includeLang("trade");
	$parse=$lang;
	$parse['error_message']="";
	$parse['offer_status']="";
	$parse['contentBuyAsu']=$lang['content_buyasu'];
	$dataPost = $_POST;
	
	
	
	if(count($_POST)>0){
		if(is_numeric($_POST['rid_rs']) && $_POST['rid_rs']>0 && is_numeric($_POST['rid_lot']) && $_POST['rid_lot']>0){
			$r1=$r2=$r3=$r4=$rscan=0;
			$k_lumber=$k_clay=$k_iron=$k_crop=0;
			//Lay he so asuconfig
			$Set_k="Select * from wg_asuconfigs";
			$db->setQuery($Set_k);
			$wg_asuconfig=null;
			$db->loadObject($wg_asuconfig);
			
			switch($_POST['rid_rs']){
			case 1:
				$r1=(COST_BASIC + ($wg_asuconfig->k_lumber*10))* $_POST['rid_lot'];
				$parse['Resource']=$r1;
				$rscan=$r1;
				$k_lumber=$_POST['rid_lot'];
				break;
			case 2:
				$r2=(COST_BASIC + ($wg_asuconfig->k_clay*10))* $_POST['rid_lot'];
				$rscan=$r2;
				$k_clay=$_POST['rid_lot'];
				break;
			case 3:
				$r3=(COST_BASIC + ($wg_asuconfig->k_iron*10))* $_POST['rid_lot'];
				$rscan=$r3;
				$k_iron=$_POST['rid_lot'];
				break;
			case 4:
				$r4=(COST_BASIC + ($wg_asuconfig->k_crop*10))* $_POST['rid_lot'];
				$rscan=$r4;
				$k_crop=$_POST['rid_lot'];
				break;						
			}
			
			if($wg_village->rs1 >= $r1 && $wg_village->rs2 >= $r2 && $wg_village->rs3 >= $r3 && $wg_village->rs4 >= $r4){
				$sumMerchant=CheckMerchant($building, $rscan);
				if($sumMerchant){//kiem tra so thuong nhan.
					//du dieu kien.
					//	-cap nhat so thuong nhan.
					//	-chen thong tin vao bang wg_resource_oders.
//					$wg_village->merchant_underaway += $sumMerchant;
//					ChangeMerchantUnderaway($wg_village->id, $sumMerchant);
									
					//tru rs cua lang:
					$wg_village->rs1 -=$r1;
					$wg_village->rs2 -=$r2;
					$wg_village->rs3 -=$r3;
					$wg_village->rs4 -=$r4;
					
					$Sql_k=" UPDATE wg_asuconfigs SET k_lumber = k_lumber +$k_lumber, k_clay = k_clay +$k_clay, k_iron = k_iron +$k_iron, k_crop = k_crop +$k_crop";
					$db->setQuery($Sql_k);
					$db->query();
								
					InsertOder($wg_village->id, $rscan, $_POST['rid_rs'], $_POST['rid_lot'], 8, $sumMerchant);							
				}else{
					//khong du thuong nhan.
					$parse['error_message']=$lang['error_offer_few_merchant'];
				}
			}else{
				//khong du RS.
				$parse['error_message']=$lang['error_offer_few_rs'];
			}
		}else{
			//du lieu sai
			$parse['error_message']=$lang['error_offer_input'];
		}
	}
	
//Xoa buyAsu
//Giam he so asuconfig
	if(isset($_GET['c']) && is_numeric($_GET['c'])){
		$offer_id=$db->getEscaped($_GET['c']);
		$sql="SELECT * FROM wg_resource_orders WHERE id=$offer_id AND village_id=$wg_village->id";
		$db->setQuery($sql);
		$offer=null;
		$db->loadObject($offer);
		if($offer){
			$r1=$r2=$r3=$r4=0;
			$k_lumber=$k_clay=$k_iron=$k_crop=0;
			switch($offer->type1){
				case 1:
					$r1=$offer->num1;
					$k_lumber=-$offer->num2;
					break;
				case 2:
					$r2=$offer->num1;
					$k_clay=-$offer->num2;
					break;
				case 3:
					$r3=$offer->num1;
					$k_iron=-$offer->num2;
					break;
				case 4:
					$r4=$offer->num1;
					$k_crop=-$offer->num2;
					break;
			}
			$wg_village->rs1 += $r1;
			$wg_village->rs2 += $r2;
			$wg_village->rs3 += $r3;
			$wg_village->rs4 += $r4;
			
			//Giam he so asuconfig
			$Sql_k=" UPDATE wg_asuconfigs SET k_lumber = k_lumber +$k_lumber, k_clay = k_clay +$k_clay, k_iron = k_iron +$k_iron, k_crop = k_crop +$k_crop";
			$db->setQuery($Sql_k);
			$db->query();
			
//			$wg_village->merchant_underaway -= $offer->merchants;
//			ChangeMerchantUnderaway($wg_village->id, -$offer->merchants);
			
			DeleteOffer($offer_id);
		}
	}
	
	$parse['merchant_available']= getMerchantAvailable($wg_village->id, $building->level);
	$parse['sum_merchant']=$building->level;
	$parse['buyAsu_status']=buyAsuStatus();
	return parsetemplate(gettemplate('buyAsu_body'), $parse);
}

//Ban Asu doi lay tai nguyen == Mua tai nguyen bang Asu
function OfferAsu($building){
	global $db, $lang, $wg_village, $wg_asuconfig, $user;
	includeLang("trade");
	$parse=$lang;
	$parse['error_message']="";
	$parse['offer_status']="";
	$parse['contentOfferAsu']=$lang['content_offerasu'];
	$dataPost = $_POST;
	
	if(count($_POST)>0){
		if(is_numeric($_POST['rid_rs']) && $_POST['rid_rs']>0 && is_numeric($_POST['rid_lot']) && $_POST['rid_lot']>0){
			$r1=$r2=$r3=$r4=$rscan=$gold=0;
			$k_lumber=$k_clay=$k_iron=$k_crop=0;
			//Lay he so asuconfig
			$Set_k="Select * from wg_asuconfigs";
			$db->setQuery($Set_k);
			$wg_asuconfig=null;
			$db->loadObject($wg_asuconfig);
			
			switch($_POST['rid_rs']){
				case 1:
					$r1=(COST_BASIC + ($wg_asuconfig->k_lumber*10))* $_POST['rid_lot'];
					$rscan=$r1;
					$k_lumber=-$_POST['rid_lot'];
					break;
				case 2:
					$r2=(COST_BASIC + ($wg_asuconfig->k_clay*10))* $_POST['rid_lot'];
					$rscan=$r2;
					$k_clay=-$_POST['rid_lot'];
					break;
				case 3:
					$r3=(COST_BASIC + ($wg_asuconfig->k_iron*10))* $_POST['rid_lot'];
					$rscan=$r3;
					$k_iron=-$_POST['rid_lot'];
					break;
				case 4:
					$r4=(COST_BASIC + ($wg_asuconfig->k_crop*10))* $_POST['rid_lot'];
					$rscan=$r4;
					$k_crop=-$_POST['rid_lot'];
					break;						
			}
			// Lay so asu nap trong builling
			$asu_builling=get_gold_remote($user['username']);
			
			if($asu_builling >= $_POST['rid_lot']){//Kiem tra so Asu nap
			
				$sumMerchant=CheckMerchant($building, $_POST['rid_lot']);
				
				if($sumMerchant){//kiem tra so thuong nhan.
					//du dieu kien.
					//	-cap nhat so thuong nhan.
					//	-chen thong tin vao bang wg_resource_oders.
//					$wg_village->merchant_underaway += $sumMerchant;
//					ChangeMerchantUnderaway($wg_village->id, $sumMerchant);
					
					//tru asu nap cua lang:
					withdraw_gold_remote($user['username'],$_POST['rid_lot'],9);
							
					
					//Khi co su kien ban Asu thi tang gai tri tai nguyen;Giam he so asuconfig
					$Sql_k=" UPDATE wg_asuconfigs SET k_lumber = k_lumber +$k_lumber, k_clay = k_clay +$k_clay, k_iron = k_iron +$k_iron, k_crop = k_crop +$k_crop";
					$db->setQuery($Sql_k);
					$db->query();
								
					InsertOder($wg_village->id, $_POST['rid_lot'], 8, $rscan, $_POST['rid_rs'],  $sumMerchant);							
				}else{
					//khong du thuong nhan.
					$parse['error_message']=$lang['error_offer_few_merchant'];
				}
			}else{
				//khong du ASU nap.
				$parse['error_message']=$lang['error_offer_few_ASU'];
			}
		}else{
			//du lieu sai
			$parse['error_message']=$lang['error_offer_input'];
		}
	}
	
//Xoa offerAsu
//Tang he so asuconfig
	if(isset($_GET['c']) && is_numeric($_GET['c'])){
		$offer_id=$db->getEscaped($_GET['c']);
		$sql="SELECT * FROM wg_resource_orders WHERE id=$offer_id AND village_id=$wg_village->id";
		$db->setQuery($sql);
		$offer=null;
		$db->loadObject($offer);
		if($offer){
			$r1=$r2=$r3=$r4=$asu=0;
			$k_lumber=$k_clay=$k_iron=$k_crop=0;
			if($offer->type1==8){
				$asu=$offer->num1;
				switch($offer->type2){
					case 1:					
						$k_lumber=$offer->num1;
						break;
					case 2:
						$k_clay=$offer->num1;
						break;
					case 3:
						$k_iron=$offer->num1;
						break;
					case 4:
						$k_crop=$offer->num1;
						break;
				}
			
				//Tra asu cho lang ban
				deposit_gold_remote($user['username'],$asu,11);				
				
				//Tang he so asuconfig
				$Sql_k=" UPDATE wg_asuconfigs SET k_lumber = k_lumber +$k_lumber, k_clay = k_clay +$k_clay, k_iron = k_iron +$k_iron, k_crop = k_crop +$k_crop";
				$db->setQuery($Sql_k);
				$db->query();
				
//				$wg_village->merchant_underaway -= $offer->merchants;
//				ChangeMerchantUnderaway($wg_village->id, -$offer->merchants);
				
				DeleteOffer($offer_id);
			}
		}
	}
	
	$parse['merchant_available']=getMerchantAvailable($wg_village->id, $building->level);
	$parse['sum_merchant']=$building->level;
	$parse['offerAsu_status']=OfferAsuStatus();
	return parsetemplate(gettemplate('offerAsu_body'), $parse);
}

//Mua tai nguyen bang ASU
function BuyRS($building){
	global $db, $lang, $wg_village, $user;
	//xu ly su kien mua RS.
	if(isset($_GET['o']) && is_numeric($_GET['o'])){
		$sql="SELECT * FROM wg_resource_orders WHERE id=".$db->getEscaped($_GET['o']);
		$db->setQuery($sql);
		$rsOrder=null;
		$db->loadObject($rsOrder);
		
		if($rsOrder){
			//Ton tai offer.
			$sumMerchant = $rsOrder->merchants;//lay so thuong nhan cua nguoi ban
			//Tinh so thuong nhan van chuyen cho nguoi mua
			$Merchant = GetMerchantTransport($wg_village->id, $rsOrder->num2);
			if(CheckActionBuy($building, $rsOrder->id, $rsOrder->num2, $rsOrder->type2, $Merchant)==1){
				//Du dieu kien giao dich.
				//Nguoi ban chuyen cho nguoi mua:
				$r1=$r2=$r3=$r4=0;
				$k_lumber=$k_clay=$k_iron=$k_crop=0;
				switch($rsOrder->type1){
					case 1:
						$r1=$rsOrder->num1;
						$k_lumber=-$rsOrder->num2;
						break;
					case 2:
						$r2=$rsOrder->num1;
						$k_clay=-$rsOrder->num2;
						break;
					case 3:
						$r3=$rsOrder->num1;
						$k_iron=-$rsOrder->num2;
						break;
					case 4:
						$r4=$rsOrder->num1;
						$k_crop=-$rsOrder->num2;
						break;
				}
				//Giam he so Asuconfig
				$Sql_k=" UPDATE wg_asuconfigs SET k_lumber = k_lumber + $k_lumber, k_clay = k_clay + $k_clay, k_iron = k_iron + $k_iron, k_crop = k_crop + $k_crop";
				$db->setQuery($Sql_k);
				$db->query();		
				
				$objectID=InsertSendRS($rsOrder->village_id, $wg_village->id, $r1, $r2, $r3, $r4,0,$sumMerchant);
				if($objectID){					
					$cost_time=GetMerchantDuration($rsOrder->village_id, $wg_village->id);
					$time_begin=time();
					$time_end=$time_begin+$cost_time;
					InsertSendRSStatus($objectID, $rsOrder->village_id, date("Y-m-d H:i:s"), date("Y-m-d H:i:s", $time_end), $cost_time);
				}
				
				//Nguoi mua chuyen cho nguoi ban:
				$r1=$r2=$r3=$r4=$asu=0;	
				//Chuyen Asu cho nguoi ban			
				$asu=$rsOrder->num2;
				$mcChuyenAsu=CheckMerchant($building,$rsOrder->num2);
				$objectID=InsertSendRS($wg_village->id, $rsOrder->village_id, $r1, $r2, $r3, $r4, $asu,$mcChuyenAsu);
				if($objectID)
				{
					//Tru ASU nap cua lang mua
					withdraw_gold_remote($user['username'],$asu,10);					
					
					$cost_time=GetMerchantDuration($wg_village->id, $rsOrder->village_id);
					$time_begin=time();
					$time_end=$time_begin+$cost_time;
					InsertSendRSStatus($objectID, $wg_village->id, date("Y-m-d H:i:s"), date("Y-m-d H:i:s", $time_end), $cost_time);
					
					//Tang so thuong nhan di giao dich cua lang mua:
//					$wg_village->merchant_underaway += $mcChuyenAsu;
//					ChangeMerchantUnderaway($wg_village->id, $mcChuyenAsu);
				}
				//Xoa offer:
				DeleteOffer($rsOrder->id);
				return DisplaySendResource1($building , 0);
			}
		}
	}
	return ShowBuyAsu($building);
}
//Mua Asu bang tai nguyen
function OfferRS($building){
	global $db, $lang, $wg_village, $user;
	//xu ly su kien mua RS.
	if(isset($_GET['o']) && is_numeric($_GET['o'])){
		$sql="SELECT * FROM wg_resource_orders WHERE id=".$db->getEscaped($_GET['o'])." and type2!=8";
		$db->setQuery($sql);
		$rsOrder=null;
		$db->loadObject($rsOrder);
		if($rsOrder){
			//Ton tai offer.
			$sumMerchant = $rsOrder->merchants;//lays so thuong nhan cua nguoi ban
			//Tinh so thuong nhan van chuyen cho nguoi mua
			$Merchant = GetMerchantTransport($wg_village->id, $rsOrder->num2);
			
			if(CheckActionBuy($building, $rsOrder->id, $rsOrder->num2, $rsOrder->type2, $Merchant)==1){
				//Du dieu kien giao dich.
				//Nguoi ban chuyen cho nguoi mua:
				$r1=$r2=$r3=$r4=$asu=0;
				$asu=$rsOrder->num1;			
				
				$objectID=InsertSendRS($rsOrder->village_id, $wg_village->id, $r1, $r2, $r3, $r4, $asu,$sumMerchant);
				if($objectID){
					$cost_time=GetMerchantDuration($rsOrder->village_id, $wg_village->id);
					$time_begin=time();
					$time_end=$time_begin+$cost_time;
					InsertSendRSStatus($objectID, $rsOrder->village_id, date("Y-m-d H:i:s"), date("Y-m-d H:i:s", $time_end), $cost_time);
				}				
				//Nguoi mua chuyen cho nguoi ban:
				$r1=$r2=$r3=$r4=$asu=0;
				$k_lumber=$k_clay=$k_iron=$k_crop=0;
				switch($rsOrder->type2){
					case 1:
						$r1=$rsOrder->num2;
						$k_lumber=$rsOrder->num1;
						break;
					case 2:
						$r2=$rsOrder->num2;
						$k_clay=$rsOrder->num1;
						break;
					case 3:
						$r3=$rsOrder->num2;
						$k_iron=$rsOrder->num1;
						break;
					case 4:
						$r4=$rsOrder->num2;
						$k_crop=$rsOrder->num1;
						break;
				}
				//Tang he so Asuconfig
				$Sql_k=" UPDATE wg_asuconfigs SET k_lumber = k_lumber + $k_lumber, k_clay = k_clay + $k_clay, k_iron = k_iron + $k_iron, k_crop = k_crop + $k_crop";
				$db->setQuery($Sql_k);
				$db->query();
				//Tinh so thuong nhan nguoi mua
				$mcChuyenRS=CheckMerchant($building,$rsOrder->num2);
				
				$objectID=InsertSendRS($wg_village->id, $rsOrder->village_id, $r1, $r2, $r3, $r4, $asu,$mcChuyenRS);
				if($objectID){					
					$cost_time=GetMerchantDuration($wg_village->id, $rsOrder->village_id);
					$time_begin=time();
					$time_end=$time_begin+$cost_time;
					InsertSendRSStatus($objectID, $wg_village->id, date("Y-m-d H:i:s"), date("Y-m-d H:i:s", $time_end), $cost_time);
					//Tru RS cua lang mua:
					$wg_village->rs1 -= $r1;
					$wg_village->rs2 -= $r2;
					$wg_village->rs3 -= $r3;
					$wg_village->rs4 -= $r4;
					
					//Tang so thuong nhan di giao dich cua lang mua:
//					$wg_village->merchant_underaway += $mcChuyenRS;
//					ChangeMerchantUnderaway($wg_village->id, $mcChuyenRS);
					
				}
				//Xoa offer:
				DeleteOffer($rsOrder->id);
				return DisplaySendResource1($building , 0);
			}
		}
	}
	return ShowOfferAsu($building);
}

function DisplaySendResource1($building, $error){
	global $lang, $wg_village;
	includelang('trade');
	$parse=$lang;
	switch($error){
		case 1:
			$p['message']=$lang['error_+1'];
			$parse['error'] = parsetemplate(gettemplate('send_rs_error'), $p);
			break;
		case -1:
			$p['message']=$lang['error_1'];
			$parse['error'] = parsetemplate(gettemplate('send_rs_error'), $p);
			break;
		case -2:
			$p['message']=$lang['error_2'];
			$parse['error'] = parsetemplate(gettemplate('send_rs_error'), $p);
			break;
		case -3:
			$p['message']=$lang['error_3'];
			$parse['error'] = parsetemplate(gettemplate('send_rs_error'), $p);
			break;
		case -4:
			$p['message']=$lang['error_4_1']. $post['dname'] . $lang['error_4_2'];
			$parse['error'] = parsetemplate(gettemplate('send_rs_error'), $p);
			break;
		case -5:
			$p['message']=$lang['error_5'];
			$parse['error'] = parsetemplate(gettemplate('send_rs_error'), $p);
			break;
		case -7:
			$p['message']=$lang['error_7'];
			$parse['error'] = parsetemplate(gettemplate('send_rs_error'), $p);
			break;
		case -8:
			$p['message']=$lang['error_8'];
			$parse['error'] = parsetemplate(gettemplate('send_rs_error'), $p);
			break;
		default :
			$parse['error']="";
			break;
	}
	if(count($_POST)>0){
		$parse['r1']=$_POST['r1'];
		$parse['r2']=$_POST['r2'];
		$parse['r3']=$_POST['r3'];
		$parse['r4']=$_POST['r4'];
		$parse['village_name']=$_POST['dname'];
		$parse['x']=$_POST['x'];
		$parse['y']=$_POST['y'];
	}else{
		$parse['r1']="";
		$parse['r2']="";
		$parse['r3']="";
		$parse['r4']="";
		$parse['village_name']="";
		$parse['x']="";
		$parse['y']="";
	}
	
	if(isset($_GET['vtx'])){
		$parse['x']=$_GET['vtx'];
		$parse['y']=$_GET['vty'];
	}
	$parse['merchant_available']=getMerchantAvailable($wg_village->id, $building->level);
	$parse['sum_merchant']=$building->level;
	$parse['merchant_capacity']=GetMerchantCapacity($wg_village->id, $wg_village->nation_id);
	$parse['sum_merchant_capacity']=$parse['merchant_available'] * $parse['merchant_capacity'];
	$parse['send_rs_status']=TransportRSStatus();	//lay trang thai cua RS den va di.
	return parsetemplate(gettemplate('send_rs_body'), $parse);
}

function DisplaySendResource2($building){
	global $wg_village;
	$villageTo=getVillage($_POST['village_to_id']);
	if($villageTo){
		$parse['r1']=$_POST['r1'];
		$parse['r2']=$_POST['r2'];
		$parse['r3']=$_POST['r3'];
		$parse['r4']=$_POST['r4'];
		$parse['village_name']=$_POST['dname'];
		$parse['x']=$_POST['x'];
		$parse['y']=$_POST['y'];
		$parse['uid']=$villageTo->user_id;
		$parse['player_name']=$_POST['player_name'];
		$parse['village_to_id']=$_POST['village_to_id'];
		$parse['duration']=TimeToString(GetMerchantDuration($wg_village->id, $_POST['village_to_id']));
		$parse['merchant_available']=getMerchantAvailable($wg_village->id, $building->level);
		$parse['sum_merchant']=$building->level;
		$parse['sum_merchant_require']=CheckMerchant($building, $_POST['r1'] + $_POST['r2'] + $_POST['r3'] + $_POST['r4']);
		$parse['merchant_capacity']=GetMerchantCapacity($wg_village->id, $wg_village->nation_id);
		$parse['sum_merchant_capacity']=$parse['merchant_available'] * $parse['merchant_capacity'];
		$parse['send_rs_status']=TransportRSStatus();	//lay trang thai cua RS den va di.
		return parsetemplate(gettemplate('send_rs_body_2'), $parse);
	}else{
		globalError2("DisplaySendResource2");
	}		
}

//Hien thi danh sach orders.
function ShowBuy($building){

	global $db, $lang, $wg_village;
	includelang("trade");
	$parse=$lang;
	$parse['rows']='';
	$parse['first_page']="";
	$parse['pre_page']="";
	$parse['next_page']="";
	$parse['last_page']="";
	$parse['sum_offer']=0;
	$sumView=30;
	//Lay tong so record:
	$sql="SELECT COUNT(*) FROM wg_resource_orders WHERE village_id!=$wg_village->id and type1!=8 and type2!=8";
	$db->setQuery($sql);
	$totalRecord=$db->loadResult();
	if($totalRecord>0){
		$parse['sum_offer']=$totalRecord;
		$totalPage=ceil($totalRecord/$sumView);
		if(is_numeric($_GET['p']) && $_GET['p']>0 && $_GET['p']<=$totalPage){
			$page=intval($_GET['p']);
		}else{
			$page=1;
		}
		$begin=($page-1)*$sumView;
		
		$sql="SELECT * FROM wg_resource_orders WHERE village_id!=$wg_village->id and type1!=8 and type2!=8 ORDER BY `wg_resource_orders`.`id` ASC";	
		$db->setQuery($sql);
		$rsOrderList=null;
		$rsOrderList=$db->loadObjectList();
		
		if($rsOrderList){
			$row=gettemplate("buy_row");
			$i=0;
			foreach($rsOrderList as $rsOrder){
				//kiem tra duration co thoa man ko.
				$duration=GetMerchantDuration($wg_village->id, $rsOrder->village_id);
				$timeTransport=$rsOrder->time_transport; 
				
				switch($rsOrder->type1){ 
					case 1:
						$offer['title1']=$lang['Lumber'];
						break;
					case 2:
						$offer['title1']=$lang['Clay'];
						break;
					case 3:
						$offer['title1']=$lang['Iron'];
						break;
					case 4:
						$offer['title1']=$lang['Crop'];
						break;
				}
					
				switch($rsOrder->type2){
					case 1:
						$offer['title2']=$lang['Lumber'];
						break;
					case 2:
						$offer['title2']=$lang['Clay'];
						break;
					case 3:
						$offer['title2']=$lang['Iron'];
						break;
					case 4:
						$offer['title2']=$lang['Crop'];
						break;
				}
					
				$villageOffer=getVillage($rsOrder->village_id);
				$offer['x']=$villageOffer->x;
				$offer['y']=$villageOffer->y;
				$offer['num1']=$rsOrder->num1;
				$offer['num2']=$rsOrder->num2;
				
				$offer['player_name']=GetPlayerName($rsOrder->village_id);
				$offer['image_rs_1']="images/un/r/".$rsOrder->type1.".gif";
				$offer['image_rs_2']="images/un/r/".$rsOrder->type2.".gif";
				$offer['duration']=TimeToString($duration);
				//Kiem tra dieu kien hien thi.
				$sumMerchantRequire=GetMerchantTransport($wg_village->id, $rsOrder->num2);
				
				if($duration<=$timeTransport || $timeTransport==0){
					switch(CheckActionBuy($building, $rsOrder->id, $rsOrder->num2, $rsOrder->type2, $sumMerchantRequire)){					
						case 1:
							$parse['offer_id']=$rsOrder->id;
							$offer['action_buy']=parsetemplate(gettemplate("buy_action_accept"), $parse);
							break;
						case -1:
							$parse['error_message']=$lang['error_buy_few_merchant'];
							$offer['action_buy']=parsetemplate(gettemplate("buy_action_too_few"), $parse);
							break;
						case -2:
							$parse['error_message']=$lang['error_buy_few_rs'];
							$offer['action_buy']=parsetemplate(gettemplate("buy_action_too_few"), $parse);
							break;
					}
				}else{
					$parse['error_message']=$lang['error_buy_few_time'];
					$offer['action_buy']=parsetemplate(gettemplate("buy_action_too_few"), $parse);
				}
				$offerList[$i]=$offer;
				$i++;
			}
			if(count($offerList)>0){
				//sap xep rows tang dan theo duration.
				quickSort(&$offerList, 0, count($offerList)-1);
				$j = 0;
				foreach($offerList as $offer){
					if($j >=$begin && $j < ($begin+$sumView)){
						$rows.=parsetemplate($row, $offer);
						if(($j+1)==($sumView*$page)){
							break;
						}
					}
					$j++;
				}
				$parse['rows']=$rows;
				$page_pre=$page-1;
				$page_next=$page+1;
				$parse['building_index']=$building->index;$parse['t']=1;
				
				if($page>1){
					$parse['first_page']="&nbsp;&laquo;&nbsp;";					
					$parse['pre_page']="&nbsp;&lsaquo;&nbsp;";
					$parse['prepage']=$page_pre;
				}else{
					$parse['first_page']='';					
					$parse['pre_page']='';
				}
				
				if($page<$totalPage){
					$parse['next_page']="&nbsp;&rsaquo;&nbsp;";
					$parse['nextpage']=$page_next;
					$parse['last_page']="&nbsp;&raquo;&nbsp;";
					$parse['lastpage']=$totalPage;
				}else{
					$parse['next_page']='';
					$parse['last_page']='';
				}
			}			
		}
	}		
	return parsetemplate(gettemplate("buy_table"), $parse);
}

function OfferStatus(){
	global $db, $lang, $wg_village;
	$sql="SELECT * FROM wg_resource_orders WHERE village_id=$wg_village->id and type2!=8 and type1!=8";
	$db->setQuery($sql);
	$rsOrderList=null;
	$rsOrderList=$db->loadObjectList();
	if($rsOrderList){
		$row=gettemplate("offer_row");
		foreach($rsOrderList as $rsOrder){
			$parse['offer_id']=$rsOrder->id;
			$parse['num1']=$rsOrder->num1;
			$parse['num2']=$rsOrder->num2;
			$parse['image_rs_1']="images/un/r/".$rsOrder->type1.".gif";
			$parse['image_rs_2']="images/un/r/".$rsOrder->type2.".gif";
			$parse['merchants']=$rsOrder->merchants;
			$rows.=parsetemplate($row, $parse);
		}
			$parse['rows']=$rows;
			return parsetemplate(gettemplate("offer_table"), $parse);
	}
	return false;
}

function ShowBuyAsu($building){
	global $db, $lang, $wg_village;
	includelang("trade");
	$parse=$lang;
	$parse['rows']='';
	$parse['first_page']="";
	$parse['pre_page']="";
	$parse['next_page']="";
	$parse['last_page']="";
	$parse['sum_offer']=0;
	$sumView=30;
	//Lay tong so record:
	$sql="SELECT COUNT(*) FROM wg_resource_orders WHERE village_id!=$wg_village->id and type2=8";
	$db->setQuery($sql);
	$totalRecord=$db->loadResult();
	if($totalRecord>0){
		$parse['sum_offer']=$totalRecord;
		$totalPage=ceil($totalRecord/$sumView);
		if(is_numeric($_GET['p']) && $_GET['p']>0 && $_GET['p']<=$totalPage){
			$page=intval($_GET['p']);
		}else{
			$page=1;
		}
		$begin=($page-1)*$sumView;
		$sql="SELECT * FROM wg_resource_orders WHERE village_id!=$wg_village->id and type2=8 ORDER BY `type1` ASC , `num1` DESC";	
		$db->setQuery($sql);
		$rsOrderList=null;
		$rsOrderList=$db->loadObjectList();
		if($rsOrderList){
			$row=gettemplate("buy_row");
			$i=0;
			foreach($rsOrderList as $rsOrder){
				//kiem tra duration co thoa man ko.
				$duration=GetMerchantDuration($wg_village->id, $rsOrder->village_id);
				$timeTransport=$rsOrder->time_transport; 
				
				switch($rsOrder->type1){ 
					case 1:
						$offer['title1']=$lang['Lumber'];
						break;
					case 2:
						$offer['title1']=$lang['Clay'];
						break;
					case 3:
						$offer['title1']=$lang['Iron'];
						break;
					case 4:
						$offer['title1']=$lang['Crop'];
						break;
				}
					
				if($rsOrder->type2==8){					
					$offer['title2']=$lang['gold'];
				}
					
				$villageOffer=getVillage($rsOrder->village_id);
				$offer['x']=$villageOffer->x;
				$offer['y']=$villageOffer->y;
				$offer['num1']=$rsOrder->num1;
				$offer['num2']=$rsOrder->num2;
				
				$offer['player_name']=GetPlayerName($rsOrder->village_id);
				$offer['image_rs_1']="images/un/r/".$rsOrder->type1.".gif";
				$offer['image_rs_2']="images/un/r/".$rsOrder->type2.".gif";
				$offer['duration']=TimeToString($duration);
				//Kiem tra dieu kien hien thi.
				$sumMerchantRequire=GetMerchantTransport($wg_village->id, $rsOrder->num2);
				if($duration<=$timeTransport || $timeTransport==0){
					switch(CheckActionBuy($building, $rsOrder->id, $rsOrder->num2, $rsOrder->type2, $sumMerchantRequire)){					
						case 1:
							$parse['offer_id']=$rsOrder->id;
							$offer['action_buy']=parsetemplate(gettemplate("buyAsu_action_accept"), $parse);//t==7
							break;
						case -1:
							$parse['error_message']=$lang['error_buy_few_merchant'];
							$offer['action_buy']=parsetemplate(gettemplate("buy_action_too_few"), $parse);
							break;
						case -2:
							$parse['error_message']=$lang['error_buy_few_rs'];
							$offer['action_buy']=parsetemplate(gettemplate("buy_action_too_few"), $parse);
							break;
						case -3:
							$parse['error_message']=$lang['error_buy_few_Asu'];
							$offer['action_buy']=parsetemplate(gettemplate("buy_action_too_few"), $parse);
							break;
					}
				}else{
					$parse['error_message']=$lang['error_buy_few_time'];
					$offer['action_buy']=parsetemplate(gettemplate("buy_action_too_few"), $parse);
				}
				$offerList[$i]=$offer;
				$i++;
			}
			
			if(count($offerList)>0){
				$j = 0;
				foreach($offerList as $offer){
					if($j >=$begin && $j < count($offerList)){
						$rows.=parsetemplate($row, $offer);
						if(($j+1)==($sumView*$page)){
							break;
						}
					}
					$j++;
				}
				$parse['rows']=$rows;
				$page_pre=$page-1;
				$page_next=$page+1;
				$parse['building_index']=$building->index;$parse['t']=7;
				
				if($page>1){
					$parse['first_page']="&nbsp;&laquo;&nbsp;";					
					$parse['pre_page']="&nbsp;&lsaquo;&nbsp;";
					$parse['prepage']=$page_pre;
				}else{
					$parse['first_page']='';					
					$parse['pre_page']='';
				}
				
				if($page<$totalPage){
					$parse['next_page']="&nbsp;&rsaquo;&nbsp;";
					$parse['nextpage']=$page_next;
					$parse['last_page']="&nbsp;&raquo;&nbsp;";
					$parse['lastpage']=$totalPage;
				}else{
					$parse['next_page']='';
					$parse['last_page']='';
				}
			}		
		}
	}		
	return parsetemplate(gettemplate("buy_table"), $parse);
}

function ShowOfferAsu($building){
	global $db, $lang, $wg_village;
	includelang("trade");
	$parse=$lang;
	$parse['rows']='';
	$parse['first_page']="";
	$parse['pre_page']="";
	$parse['next_page']="";
	$parse['last_page']="";
	$parse['sum_offer']=0;
	$sumView=30;
	//Lay tong so record:
	$sql="SELECT COUNT(*) FROM wg_resource_orders WHERE village_id!=$wg_village->id and type1=8";
	$db->setQuery($sql);
	$totalRecord=$db->loadResult();
	if($totalRecord>0){
		$parse['sum_offer']=$totalRecord;
		$totalPage=ceil($totalRecord/$sumView);
		if(is_numeric($_GET['p']) && $_GET['p']>0 && $_GET['p']<=$totalPage){
			$page=intval($_GET['p']);
		}else{
			$page=1;
		}
		$begin=($page-1)*$sumView;
		$sql="SELECT * FROM wg_resource_orders WHERE village_id!=$wg_village->id and type1=8 ORDER BY `type1` ASC , `num2` DESC";		
		$db->setQuery($sql);
		$rsOrderList=null;
		$rsOrderList=$db->loadObjectList();
		if($rsOrderList){
			$row=gettemplate("buy_row");
			$i=0;
			foreach($rsOrderList as $rsOrder){
				//kiem tra duration co thoa man ko.
				$duration=GetMerchantDuration($wg_village->id, $rsOrder->village_id);
				$timeTransport=$rsOrder->time_transport; 
					
				if($rsOrder->type1==8){					
					$offer['title1']=$lang['gold'];
				}
				
				switch($rsOrder->type2){ 
					case 1:
						$offer['title2']=$lang['Lumber'];
						break;
					case 2:
						$offer['title2']=$lang['Clay'];
						break;
					case 3:
						$offer['title2']=$lang['Iron'];
						break;
					case 4:
						$offer['title2']=$lang['Crop'];
						break;
				}
					
				$villageOffer=getVillage($rsOrder->village_id);
				$offer['x']=$villageOffer->x;
				$offer['y']=$villageOffer->y;
				$offer['num1']=$rsOrder->num1;
				$offer['num2']=$rsOrder->num2;
				
				$offer['player_name']=GetPlayerName($rsOrder->village_id);
				$offer['image_rs_1']="images/un/r/".$rsOrder->type1.".gif";
				$offer['image_rs_2']="images/un/r/".$rsOrder->type2.".gif";
				$offer['duration']=TimeToString($duration);
				//Kiem tra dieu kien hien thi.
				$sumMerchantRequire=GetMerchantTransport($wg_village->id, $rsOrder->num2);
				
				if($duration<=$timeTransport || $timeTransport==0){
					switch(CheckActionBuy($building, $rsOrder->id, $rsOrder->num2, $rsOrder->type2, $sumMerchantRequire)){					
						case 1:
							$parse['offer_id']=$rsOrder->id;
							$offer['action_buy']=parsetemplate(gettemplate("offerAsu_action_accept"), $parse);//t==6
							break;
						case -1:
							$parse['error_message']=$lang['error_buy_few_merchant'];
							$offer['action_buy']=parsetemplate(gettemplate("buy_action_too_few"), $parse);
							break;
						case -2:
							$parse['error_message']=$lang['error_buy_few_rs'];
							$offer['action_buy']=parsetemplate(gettemplate("buy_action_too_few"), $parse);
							break;
						case -3:
							$parse['error_message']=$lang['error_buy_few_Asu'];
							$offer['action_buy']=parsetemplate(gettemplate("buy_action_too_few"), $parse);
							break;
					}
				}else{
					$parse['error_message']=$lang['error_buy_few_time'];
					$offer['action_buy']=parsetemplate(gettemplate("buy_action_too_few"), $parse);
				}
				$offerList[$i]=$offer;
				$i++;
			}
			
			if(count($offerList)>0){
				$j = 0;
				foreach($offerList as $offer){
					if($j >=$begin && $j < count($offerList)){
						$rows.=parsetemplate($row, $offer);
						if(($j+1)==($sumView*$page)){
							break;
						}
					}
					$j++;
				}
				$parse['rows']=$rows;
				$page_pre=$page-1;
				$page_next=$page+1;
				
				$parse['building_index']=$building->index;$parse['t']=6;
				
				if($page>1){
					$parse['first_page']="&nbsp;&laquo;&nbsp;";					
					$parse['pre_page']="&nbsp;&lsaquo;&nbsp;";
					$parse['prepage']=$page_pre;
				}else{
					$parse['first_page']='';					
					$parse['pre_page']='';
				}
				
				if($page<$totalPage){
					$parse['next_page']="&nbsp;&rsaquo;&nbsp;";
					$parse['nextpage']=$page_next;
					$parse['last_page']="&nbsp;&raquo;&nbsp;";
					$parse['lastpage']=$totalPage;
				}else{
					$parse['next_page']='';
					$parse['last_page']='';
				}
			}			
		}
	}		
	return parsetemplate(gettemplate("buy_table"), $parse);
}

function BuyAsuStatus(){
	global $db, $lang, $wg_village;
	$sql="SELECT * FROM wg_resource_orders WHERE village_id=$wg_village->id and type2=8";
	$db->setQuery($sql);
	$rsOrderList=null;
	$rsOrderList=$db->loadObjectList();
	if($rsOrderList){
		$row=gettemplate("buyAsu_row");
		foreach($rsOrderList as $rsOrder){
			$parse['offer_id']=$rsOrder->id;
			$parse['num1']=$rsOrder->num1;
			$parse['num2']=$rsOrder->num2;
			$parse['image_rs_1']="images/un/r/".$rsOrder->type1.".gif";
			$parse['image_rs_2']="images/un/r/".$rsOrder->type2.".gif";
			$parse['merchants']=$rsOrder->merchants;
			$rows.=parsetemplate($row, $parse);
		}
			$parse['rows']=$rows;
			return parsetemplate(gettemplate("buyAsu_table"), $parse);
	}
	return false;
}

function OfferAsuStatus(){
	global $db, $lang, $wg_village;
	$sql="SELECT * FROM wg_resource_orders WHERE village_id=$wg_village->id and type1=8";
	$db->setQuery($sql);
	$rsOrderList=null;
	$rsOrderList=$db->loadObjectList();
	if($rsOrderList){
		$row=gettemplate("offerAsu_row");
		foreach($rsOrderList as $rsOrder){
			$parse['offer_id']=$rsOrder->id;
			$parse['num1']=$rsOrder->num1;
			$parse['num2']=$rsOrder->num2;
			$parse['image_rs_1']="images/un/r/".$rsOrder->type1.".gif";
			$parse['image_rs_2']="images/un/r/".$rsOrder->type2.".gif";
			$parse['merchants']=$rsOrder->merchants;
			$rows.=parsetemplate($row, $parse);
		}
			$parse['rows']=$rows;
			return parsetemplate(gettemplate("offerAsu_table"), $parse);
	}
	return false;
}

/*	Xoa mot offer:
		- Xoa offer.
*/
function DeleteOffer($offer_id){
	global $db;
	$sql="DELETE FROM wg_resource_orders WHERE id=$offer_id";
	$db->setQuery($sql);
	return $db->query();
}

function InsertOder($village_id, $num1, $type1, $num2, $type2, $merchants, $time_transport=0){
	global $db;
	$num1 = $db->getEscaped($num1);
	$num2 = $db->getEscaped($num2);
	$type1 = $db->getEscaped($type1);
	$type2 = $db->getEscaped($type2);
	
	$sql="INSERT INTO wg_resource_orders (village_id, num1, type1, num2, type2, merchants, time_transport) VALUES ($village_id, $num1, $type1, $num2, $type2, $merchants, $time_transport)";
	$db->setQuery($sql);
	return $db->query();
}

/**
 * Kiem tra user co du 3 gold hay ko.
 */
function TradeCheckGold($user_id,$asu_npctrade)
{
	global $db,$user;
	$sql="SELECT gold FROM wg_plus WHERE user_id=".$user_id;
	$db->setQuery($sql);
	$gold=$db->loadResult();
	if($gold>=$asu_npctrade)
	{
		return true;
	}
	else
	{
		$asu_builling=get_gold_remote($user['username']);
		if(($gold+$asu_builling)>=$asu_npctrade)
		{	
			return true;
		}
	}
	return false;
}


/**
 * Tru gold cua mot user
 */
function TradeSubGold($user_id,$asu_npctrade)
{	
	global $db,$user;	
	$gold=$asu_npctrade;
	$sql="SELECT gold FROM wg_plus	WHERE user_id=".$user_id;
	$db->setQuery($sql);
	$currentValue=$db->loadResult();
	
	if($currentValue>=$gold)
	{
		$sql="UPDATE wg_plus SET gold = gold-".$gold." WHERE user_id=".$user_id;
		$db->setQuery($sql);
		$db->query();
		if($db->getAffectedRows()==0)
		{
			globalError2('function TradeSubGold:'.$sql);
		}
		return true;	
	}
	else
	{
		$asu_builling=get_gold_remote($user['username']);
		if(($asu_builling+$currentValue)>=$gold)	
		{
			$sql="UPDATE wg_plus SET gold=0 WHERE user_id=".$user_id;
			$db->setQuery($sql);
			$db->query();					
			withdraw_gold_remote($user['username'],$gold-$currentValue,8);			
		}
		return true;		
	}	
}

/**
 * @author Le Van Tu
 * @des thay doi so thuong nhan di giao dich
 * @param1 id cua lang
 * @param2 so thuong nhan
 */
function ChangeMerchantUnderaway($village_id, $num){
	global $db;
	$sql="UPDATE wg_villages SET merchant_underaway=merchant_underaway+($num) WHERE id=$village_id";		
	$db->setQuery($sql);
	return $db->query();
}

function GetMerchantCapacity($village_id, $nation_id){
	global $db;
	$sql="SELECT merchant_capacity FROM wg_nations WHERE id = '$nation_id'";
	$db->setQuery($sql);
	$nation = null;
	$db->loadObject($nation);
	if($nation){
		$capa=$nation->merchant_capacity;
		$tradeOfficeLevel=GetBuildingLevel($village_id, 20);
		if($tradeOfficeLevel>0){
			$capa+=round($capa*($tradeOfficeLevel*0.05), 0);
		}
		return $capa;
	}else{
		globalError2("Khong lay duoc thong tin cua bang nation GetMerchantCapacity($village_id, $nation_id)");
	}
}

function GetMerchantSpeed($village_id){
	global $db, $game_config;
	$sql="SELECT * FROM wg_villages WHERE id = $village_id";
	$db->setQuery($sql);
	$village=null;
	$db->loadObject($village);
	if($village){
		$sql="SELECT * FROM wg_nations WHERE id = '$village->nation_id'";
		$db->setQuery($sql);
		$nation = null;
		$db->loadObject($nation);
		if($nation){
			return $nation->merchant_speed*$game_config['k_speed'];
		}
	}
	return false;
}

function GetLevelMarketplace($village_id){
	global $db;
	$typeID=GetbuildingTypeID(BUILDING_TYPE_NAME_MARKETPLACE);
	if($typeID){
		$sql="SELECT level FROM wg_buildings WHERE vila_id='$village_id' AND type_id='$typeID'";
		$db->setQuery($sql);
		$building=null;
		$db->loadObject($building);
		if($building){
			return $building->level;
		}
	}
	return false;
}

function GetBuildingTypeID($typeName){
	global $db;
	$sql="SELECT id FROM wg_building_types WHERE name='$typeName'";
	$db->setQuery($sql);
	$buildingType=null;
	$db->loadObject($buildingType);
	if($buildingType){
		return $buildingType->id;
	}
	return false;
}

function InsertSendRSStatus($object_id, $village_id, $time_begin, $time_end, $cost_time, $type=6){
	global $db;
	$sql="INSERT INTO `wg_status` (`object_id`, `village_id`, `type`, `time_begin`, `time_end`, `cost_time`, `status`) VALUES ($object_id, $village_id, $type, '$time_begin', '$time_end', $cost_time, 0)";
	$db->setQuery($sql);
	return $db->query();
}

//chen mot rocord vao bang wg_resource_sends
function InsertSendRS($village_from, $village_to, $rs1, $rs2, $rs3, $rs4, $asu=0, $mc=0){
	global $db;
	$rs1=$db->getEscaped($rs1);
	$rs2=$db->getEscaped($rs2);
	$rs3=$db->getEscaped($rs3);
	$rs4=$db->getEscaped($rs4);
	$asu=$db->getEscaped($asu);
	$sql="INSERT INTO wg_resource_sends (village_id_from, village_id_to, rs1, rs2, rs3, rs4, status, asu, merchants) VALUES ($village_from, $village_to, $rs1, $rs2, $rs3, $rs4, 0, $asu, $mc)";
	$db->setQuery($sql);
	if($db->query()){
		return $db->insertid();
	}else{
		globalError2("Loi chen mot rocord vao bang wg_resource_sends".$sql);
	}
}

function AddRSSend($village_id, $r1, $r2, $r3, $r4){
	global $db;
	$sql="SELECT * FROM wg_villages WHERE id=$village_id";
	$db->setQuery($sql);
	$village=null;
	$db->loadObject($village);
	if($village){
		$sql="UPDATE wg_villages SET rs1=".($village->rs1 + $r1).", rs2=".($village->rs2 + $r2).", rs3=".($village->rs3 + $r3).", rs4=".($village->rs4 + $r4)." WHERE id=$village_id";
		$db->setQuery($sql);
		return $db->query();
	}
	return false;
}


function GetMerchantDuration($village_from_id, $village_to_id){
	global $db;
	$village_to_id = $db->getEscaped($village_to_id);
	$sql="SELECT x,y FROM wg_villages WHERE id=$village_from_id";
	$db->setQuery($sql);
	$villageFrom=null;
	$db->loadObject($villageFrom);
	if($villageFrom){
		$sql="SELECT x,y FROM wg_villages WHERE id=$village_to_id";
		$db->setQuery($sql);
		$villageTo=null;
		$db->loadObject($villageTo);
		if($villageTo){
			$speed=GetMerchantSpeed($village_from_id);	//field/hour.
			$s=S($villageFrom->x, $villageFrom->y, $villageTo->x, $villageTo->y);
			return ($s/$speed)*3600;	//thoi gian tinh bang giay
		}
	}
	return false;
}

//Tinh so thuong nhan co the dung:
function GetMerchantUnderaway($village_id){
	global $db;
	$result=0;
		
	//Lay so thuong nhan dang rao ban:
	$sql = "SELECT	wg_resource_orders.merchants FROM wg_resource_orders WHERE wg_resource_orders.village_id='$village_id'";
	$db->setQuery($sql);
	$orderList=$db->loadObjectList();
	if($orderList){
		foreach($orderList as $order){
			$result += $order->merchants;
		}
	}
	
	//Lay so thuong nhan dang di giao dich:
	$sql="SELECT
				wg_resource_sends.merchants
			FROM
				wg_resource_sends ,
				wg_status
			WHERE
				wg_resource_sends.id =  wg_status.object_id AND
				wg_status.`status` =  '0' AND
				wg_resource_sends.`status` =  '0' AND
				(wg_status.`type` =  '6' OR wg_status.`type` =  '22' OR wg_status.`type` =  '23') AND
				wg_resource_sends.village_id_from =  '$village_id'				
			GROUP BY
				wg_status.id";
	$db->setQuery($sql);
	$orderList=$db->loadObjectList();
	if($orderList){
		foreach($orderList as $order){
			$result += $order->merchants;
		}
	}	
	return $result;
}

/**
 * @author Le Van Tu
 * @todo tinh so thuong nhan co the dung
 */
function getMerchantAvailable($id, $lv){
	$un = GetMerchantUnderaway($id);
	return $lv>$un ? ($lv-$un) : 0;
}


function CheckActionBuy($building ,$offer_id, $num, $type, $merchants){
	global $wg_village, $user;
	
	$r1=$r2=$r3=$r4=$asu=0;
	switch($type){
		case 1:
			$r1=$num;
			break;
		case 2:
			$r2=$num;
			break;
		case 3:
			$r3=$num;
			break;
		case 4:
			$r4=$num;
			break;
		case 8://==ASU
			$asu=$num;
			break;
	}
	$objUserCenter=get_gold_remote($user['username']);

	if($wg_village->rs1>=$r1 && $wg_village->rs2>=$r2 && $wg_village->rs3>=$r3 && $wg_village->rs4>=$r4 && $objUserCenter >=$asu){
		//du RS.
		$merchantAvailable=getMerchantAvailable($wg_village->id, $building->level);
		if($merchants<=$merchantAvailable){
			//du thuong nhan.
			return 1;
		}else{
			//khong du thuong nhan.
			return -1;
		}
	}else{
		if($type==8){//==ASU
			//Khong du ASU nap:
			return -3;
		}else{
		//Khong du RS.
		return -2;
		}
	}
	return false;
}

//kiem tra ten lang co ton tai hay khong:
function CheckVillageName($village_name){
	global $db;
	$village_name = $db->getEscaped($village_name);
	$sql="SELECT * FROM wg_villages WHERE name='$village_name'";
	$db->setQuery($sql);
	$village=null;
	$db->loadObject($village);
	if($village){
		return $village;
	}
	return false;
}



function CheckVillageLocation($x, $y){
	global $db;
	$x=$db->getEscaped($x);
	$y=$db->getEscaped($y);
	$sql="SELECT * FROM wg_villages WHERE kind_id<7 AND x=$x And y=$y";
	$db->setQuery($sql);
	$village=null;
	$db->loadObject($village);
	return $village;
}


//Kiem tra co du thuong nhan de van chuyen hay khong
//neu du tra ve so thuong nhan can thiet
//neu khong tra ve false
function CheckMerchant($building, $rs){
	global $wg_village;
	
	$merchantAvailable= getMerchantAvailable($wg_village->id, $building->level);
	$merchantCapacity	= GetMerchantCapacity($wg_village->id, $wg_village->nation_id);
	$merchantRequire	= variant_idiv($rs, $merchantCapacity);
	if($merchantRequire < ($rs/$merchantCapacity)){
		$merchantRequire += 1;
	}
	if($merchantRequire <= $merchantAvailable){
		return $merchantRequire;
	}
	return false;
}

/**
 * @author Le Van Tu
 * @des Tinh so thuong nhan can thiet de van chuyen RS.
 */
function GetMerchantTransport($village_id, $rs){
	$village=getVillage($village_id, "nation_id");
	$merchantCapacity=GetMerchantCapacity($village_id, $village->nation_id);
	$merchantRequire=variant_idiv($rs, $merchantCapacity);
	if($merchantRequire < ($rs/$merchantCapacity)){
		$merchantRequire +=1;
	}
	return $merchantRequire;
}


//Sap xep mot mang theo thu tu tang dan.
function quickSort($A, $lower, $upper){
        $x = strtotime($A[intval(($lower + $upper) / 2)]['duration']);
        $i = $lower;
        $j = $upper;
        do{
                while(strtotime($A[$i]['duration']) < $x)
                        $i ++;
                while (strtotime($A[$j]['duration']) > $x)
                        $j --;
                if ($i <= $j){
                        swap(&$A[$i], &$A[$j]);
                        $i ++;
                        $j --;
                }
        }while($i <= $j);
        if ($j > $lower)
                quickSort(&$A, $lower, $j);
        if ($i < $upper)
                quickSort(&$A, $i, $upper);
}
 
//Swap two integers
function swap($a, $b){
    $temp = $a;
    $a = $b;
    $b = $temp;
}

/**
* @Author: ManhHX
* @Des: check max send resource per day
* @param: $vId village id, $postRs1 lumber resource, $postRs2 clay resource,
*		  $postRs3 iron resource, $postRs4 crop resource
* @return: boolean
*/ 
function checkMaxSendRsPerDay($vId, $vIdTo, $postRs1, $postRs2, $postRs3, $postRs4){
	include_once('function_resource.php');			
	global $db, $user, $wg_village, $wg_buildings;
	
	$uId = $user["id"];
	
	$sql="SELECT * FROM wg_villages WHERE id=$vIdTo AND user_id = $uId";
	$db->setQuery($sql);
	$vObject=null;
	$db->loadObject($vObject);
	if(!$vObject){ //2 thanh cung 1 account cho send source khong gioi han
		return true;
	}
	
	$sql="SELECT * FROM wg_villages WHERE id = $vId";
	$db->setQuery($sql);
	$vObject=null;
	$db->loadObject($vObject);
	
	//workers ang keep
	$numKeepWorkers = $vObject->troop_keep + $vObject->workers;
	
	//get production speed a day for each kind
	$lumberSpeedPerHour = round(GetSpeedIncreaseLumber($wg_buildings) * $vObject->krs1);
	$claySpeedPerHour = round(GetSpeedIncreaseClay($wg_buildings) * $vObject->krs2);
	$ironSpeedPerHour = round(GetSpeedIncreaseIron($wg_buildings) * $vObject->krs3);
	$cropSpeedPerHour = round(GetSpeedIncreaseCrop($wg_buildings) * $vObject->krs4);
	$cropSpeedPerHour = $cropSpeedPerHour - $numKeepWorkers;	
	
	$currDate = date("Y-m-d");						
	$sql2="SELECT DISTINCT(object_id) FROM wg_status WHERE village_id=$vId AND type=23 AND time_end >= $currDate";			
	$db->setQuery($sql2);	
	$stsObject = $db->loadObjectList();	
				
	if($stsObject[0]->object_id){ //has resource sent on current date
		$arrResource = array();
		//sum resource has sent for kinds
		foreach($stsObject as $k => $v){
			$sql3="SELECT rs1, rs2, rs3, rs4  FROM wg_resource_sends WHERE id=$v->object_id AND status=1";			
			$db->setQuery($sql3);	
			$rsSendObject = $db->loadObjectList();	
			$arrResource[0] = $arrResource[0] + $rsSendObject[0]->rs1;
			$arrResource[1] = $arrResource[1] + $rsSendObject[0]->rs2;
			$arrResource[2] = $arrResource[2] + $rsSendObject[0]->rs3;
			$arrResource[3] = $arrResource[3] + $rsSendObject[0]->rs4;
		}
	}
	//add resource post to resource just getting
	$currLumberSent = $arrResource[0] + $postRs1;
	$currClaySent = $arrResource[1] + $postRs2;
	$currIronSent = $arrResource[2] + $postRs3;
	$currCropSent = $arrResource[3] + $postRs4;
	
	//execute checking	
	if( ($lumberSpeedPerHour < $currLumberSent) || ($claySpeedPerHour <  $currClaySent)
	  || ($ironSpeedPerHour <  $currIronSent) || ($cropSpeedPerHour <  $currCropSent) ){
		return false;
	}	
	return true;
}


/**
 * @author Le Van Tu
 * @des cap  nhat so thuong nhan trong lang khi thuong nhan di giao dich ve
 * @param object status
 */
function executeTradeComeback($status){
	global $db;
	$sql="SELECT * FROM wg_resource_sends WHERE id=$status->object_id AND wg_resource_sends.status=0";
	$db->setQuery($sql);
	$db->loadObject($rssend);
	if($rssend){
		
		setSendResourceStatus($rssend->id);
		//tinh so thuong nhan di van chuyen:
		$sumMerchant = $rssend->merchants;
		
		if(!$sumMerchant){
			$sumMerchant = GetMerchantTransport($rssend->village_id_from, $rssend->rs1 + $rssend->rs2 + $rssend->rs3 + $rssend->rs4 + $rssend->asu);
		}
		
		//cap nha so thuong nhan cua lang
//		ChangeMerchantUnderaway($rssend->village_id_from, -$sumMerchant);
	}else{
		globalError2("Loi: xu ly status thuong nhan ve lang 2 lan");
	}
}

/**
 * @author Le Van Tu
 * @des status = 1 trong bang wg_resource_sends
 */
function setResourceSendStatus($id){
	global $db;
	$sql="UPDATE wg_resource_sends SET status=1 WHERE id = $id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2("Loi Khong cap nhat duoc status = 1 trong bang wg_resource_sends setResourceSendStatus($id)");
	}
}

/**
 * @author Le Van Tu
 * @todo xoa tat ca loi rao ban o cho cua mot thanh
 */
function deleteAllOffer($vl_id){
	global $db;
	$sql = "DELETE FROM wg_resource_orders WHERE village_id=$vl_id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
}

 
?>
