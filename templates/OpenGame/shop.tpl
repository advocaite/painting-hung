<div id="box1">
	<div id="box2">
		<table width="600" border="0">
			<tr>
				<td colspan="2">{current_gold}</td>
				<td colspan="2"><a href="http://id.xgo.vn/service.transfer.html" target="_blank">{Deposit Asu}</a></td>
			</tr>
			<tr>
				<td width="124">{Reward}</td>
				<td width="189">:&nbsp;<span style="color:#fbaf03"><b>{total_gold}&nbsp;{Gold}</b></span></td>
				<td width="79">{Deposit}</td>
				<td width="189">:&nbsp;<span style="color:#fbaf03"><b>{total_asu_bill}&nbsp;{Gold}</b></span></td>
			</tr> 
		</table><br />
        <fieldset>
        <legend><span {class_shops}><a href="shop.php">{Shop}</a></span> |
		<span {class_MyItem}><a href="shop.php?tab=1">{My_item}</a></span></legend>
        <script type="text/javascript">		
        function ChangeSelectIdShop(value)
		{
			for(var i=1;i<=4;i++)
			{
				document.getElementById('txtshop'+i).style.display="none";	
				document.getElementById('class_shop'+i).className="";				
			}
			document.getElementById('class_'+value).className="selected";		
			document.getElementById('txt'+value).style.display="block";
		}
        </script>
        <p>{msg}
        <fieldset>
        <legend><strong><span id="class_shop1" class="{class_shop1}"><a href="#" id="shop1" onclick="ChangeSelectIdShop(this.id);">{Item resource}</a></span> | <span id="class_shop2" class="{class_shop2}"><a href="#" id="shop2" onclick="ChangeSelectIdShop(this.id);">{Item attack defend}</a></span> | <span id="class_shop3" class="{class_shop3}"><a href="#" id="shop3" onclick="ChangeSelectIdShop(this.id);">{Item Building}</a></span> | <span id="class_shop4" class="{class_shop4}"><a href="#" id="shop4" onclick="ChangeSelectIdShop(this.id);">{Item Ctc}</a></span></strong></legend>
        <table cellspacing="1" cellpadding="2" id="txtshop1" {display1}>        
			<tr>
				<td width="50%"> <form action="shop.php?type=1&s=1" method="post">
                	<table class="tbg">
						<tr>
							<td colspan="2">{Des_lumber}</td>
						</tr>
                  <tr>
                    <td width="70"><img src="images/asu/{lumber_image}" width="70" /></td>
                    <td align="left">{Duration}: <b>[{lumber_duration}]</b> {Days}<br/>{Costs}: <b>[{lumber_asu}]</b> {Asu}<br />
                     {quantity}:<input name="input_lumber" type="text" size="2" value="1" maxlength="2" class="fm" onkeyup="CheckNumberInput(this);" /><br /><input type="submit" value="{Buy}" class="fm" {lumber_action}/></td>
                  </tr>
                </table>
                </form></td>				
				<td width="50%"><form action="shop.php?type=2&s=1" method="post">
                	<table class="tbg">
						<tr>
							<td colspan="2">{Des_iron}</td>
						</tr>
                    <tr>
							<td width="70"><img src="images/asu/{iron_image}" width="70"></td>
							<td align="left">{Duration}: <b>[{iron_duration}]</b> {Days}<br/>{Costs}: <b>[{iron_asu}]</b> {Asu}<br />
                     {quantity}:<input name="input_iron" type="text" size="2" value="1" maxlength="2" class="fm" onkeyup="CheckNumberInput(this);" /><br /><input type="submit" value="{Buy}" class="fm" {iron_action}/></td>
						</tr>
					</table>
                    </form></td>				
			</tr>
			<tr>
            	<td><form action="shop.php?type=3&s=1" method="post">
                	<table class="tbg">
						<tr>
							<td colspan="2">{Des_clay}</td>
						</tr>
						<tr>
							<td width="70"><img src="images/asu/{clay_image}" width="70"></td>
							<td align="left">{Duration}: <b>[{clay_duration}]</b> {Days}<br/>{Costs}: <b>[{clay_asu}]</b> {Asu}<br />
                     {quantity}:<input name="input_clay" type="text" size="2" value="1" maxlength="2" class="fm" onkeyup="CheckNumberInput(this);" /><br /><input type="submit" value="{Buy}" class="fm" {clay_action}/></td>
						</tr>
					</table>					          
			    </form></td>
				<td><form action="shop.php?type=4&s=1" method="post">
                	<table class="tbg">
						<tr>
							<td colspan="2">{Des_crop}</td>
						</tr>
						<tr>
							<td width="70"><img src="images/asu/{crop_image}" width="70"></td>
							<td align="left">{Duration}: <b>[{crop_duration}]</b> {Days}<br/>{Costs}: <b>[{crop_asu}]</b> {Asu}<br />
                     {quantity}:<input name="input_crop" type="text" size="2" value="1" maxlength="2" class="fm" onkeyup="CheckNumberInput(this);" /><br /><input type="submit" value="{Buy}" class="fm" {crop_action}/></td>
						</tr>
					</table>
                    </form></td>
                 </tr>
                 <tr>
                  <td><form action="shop.php?type=18&s=1" method="post">
                	<table class="tbg">
						<tr>
							<td colspan="2">{Des_all_resource}</td>
						</tr>
						<tr>
							<td width="70"><img src="images/asu/{all_resource_image}" width="70"></td>
							<td align="left">{Duration}: <b>[{all_resource_duration}]</b> {Days}<br/>{Costs}: <b>[{all_resource_asu}]</b> {Asu}<br />
                     {quantity}:<input name="input_all_resource" type="text" size="2" value="1" maxlength="2" class="fm" onkeyup="CheckNumberInput(this);" /><br /><input type="submit" value="{Buy}" class="fm" {all_resource_action}/></td>
						</tr>
					</table>
                    </form></td>
					<td><form action="shop.php?type=19&s=1" method="post">
                	<table class="tbg">
						<tr>
							<td colspan="2">{Des_random}</td>
						</tr>
						<tr>
							<td width="70"><img src="images/asu/{random_image}" width="70"></td>
							<td align="left">{Duration}: <b>[{mot_lan}]</b><br/>{Costs}: <b>[{random_asu}]</b> {Asu}<br />
                     {quantity}:<b>1</b><input name="input_random" type="hidden" value="1"/><br /><input type="submit" value="{Buy}" class="fm" {random_action}/></td>
						</tr>
					</table>
                    </form></td>
                  </tr>
          </table>
        <table cellspacing="1" cellpadding="2" id="txtshop2" {display2}>        
        <tr>
            <td width="50%"> <form action="shop.php?type=5&s=2" method="post">
            <table class="tbg" width="100%">
						<tr>
							<td colspan="2">{Des_attack}</td>
						</tr>
                    <tr>
                        <td width="70"><img src="images/asu/{attack_image}" width="70"></td>
                        <td align="left">{Duration}: <b>[{attack_duration}]</b> {Days}<br/>{Costs}: <b>{attack_asu}</b> {Asu}<br />{quantity}:<input name="input_attack" type="text" size="2" value="1" maxlength="2" class="fm" onkeyup="CheckNumberInput(this);" /><br /><input type="submit" value="{Buy}" class="fm" {attack_action}/></td>
                    </tr>
                </table>
            </form></td>				
            <td width="50%"><form action="shop.php?type=6&s=2" method="post">
                <table class="tbg">
					<tr>
							<td colspan="2">{Des_defence}</td>
						</tr>
                    <tr>
                        <td width="70"><img src="images/asu/{defence_image}" width="70"></td>
                        <td align="left">{Duration}: <b>[{defence_duration}]</b> {Days}<br />{Costs}: <b>{defence_asu}</b> {Asu}<br />{quantity}:<input name="input_defence" type="text" size="2" value="1" maxlength="2" class="fm" onkeyup="CheckNumberInput(this);" /><br /><input type="submit" value="{Buy}" class="fm" {defence_action}/></td>
                    </tr>
                </table>
                </form></td>
            </tr>
            <tr>
            <td width="50%"><form action="shop.php?type=14&s=2" method="post">
                <table class="tbg">
					<tr>
							<td colspan="2">{Des_dinh_chien}</td>
						</tr>
                    <tr>
                        <td width="70"><img src="images/asu/{dinh_chien_image}" width="70"></td>
                        <td align="left">{Duration}: <b>[{dinh_chien_duration}]</b> {Days}<br>{Costs}: <b>{dinh_chien_asu}</b> {Asu}<br />{quantity}:<input name="input_dinh_chien" type="text" size="2" value="1" maxlength="2" class="fm" onkeyup="CheckNumberInput(this);" /><br /><input type="submit" value="{Buy}" class="fm" {dinh_chien_action}/></td>
                    </tr>
                </table>					          
            </form></td>
        </tr>
        <!--<tr>
        	<td><form action="shop.php?type=10&s=2" method="post">
            <table class="tbg">						
                <tr>
                    <td width="70"><img src="images/asu/{sms_attack_image}" width="70"></td>
                    <td align="left">{Des_sms_attack}<br/>{Costs}: <b>{sms_attack_asu}</b> {Asu}<br />{quantity}:<input name="input_sms_attack" type="text" size="2" value="1" maxlength="2" class="fm" onkeyup="CheckNumberInput(this);" /><br /><input type="submit" value="{Buy}" class="fm"/></td>
                </tr>
				</table></form>
              </td>
           </tr>-->			
        </table>
        <table cellspacing="1" cellpadding="2" id="txtshop3" {display3}>        
        <tr>
            <td width="50%"><form action="shop.php?type=7&s=3" method="post">
            <table class="tbg">
				<tr>
					<td colspan="2">{Des_complete}</td>
				</tr>
            <tr>
				<td width="70"><img src="images/asu/{complete_image}" width="70"></td>
				<td align="left">{Duration}: <b>[{immediately}]</b><br>{Costs}: <b>{complete_asu}</b> {Asu}<br />{quantity}:<input name="input_complete" type="text" size="2" value="1" maxlength="2" class="fm" onkeyup="CheckNumberInput(this);" /><br /><input type="submit" value="{Buy}" class="fm" {complete_action}/></td>
						</tr>
					</table>
            </form></td>				
            <td width="50%"><form action="shop.php?type=17&s=3" method="post">
                <table class="tbg">
					<tr>
						<td colspan="2">{Des_map_large}</td>
					</tr>
                <tr>
					<td width="70"><img src="images/asu/{map_large_image}" width="70"></td>
					<td align="left">{Duration}: <b>[{map_large_duration}]</b> {Days}<br />{Costs}: <b>{map_large_asu}</b> {Asu}<br />{quantity}:<input name="input_map_large" type="text" size="2" value="1" maxlength="2" class="fm" onkeyup="CheckNumberInput(this);" /><br /><input type="submit" value="{Buy}" class="fm" {map_large_action}/></td>
					</tr>
				</table>					          
            </form></td>
        </tr>
        <tr>
			<td width="50%"><form action="shop.php?type=9&s=3" method="post">
                <table class="tbg">
					<tr>
						<td colspan="2">{Des_build}</td>
					</tr>
					<tr>
							<td width="70"><img src="images/asu/{build_image}" width="70"></td>
							<td align="left">{Duration}: <b>[{build_duration}]</b> {Days}<br />{Costs}: <b>{build_asu}</b> {Asu}<br />{quantity}:<input name="input_build" type="text" size="2" value="1" maxlength="2" class="fm" onkeyup="CheckNumberInput(this);" /><br /><input type="submit" value="{Buy}" class="fm" {build_action}/></td>
						</tr>
					</table>
                </form></td>
			<td width="50%">
				<form action="shop.php?type=20&s=3" method="post">
                	<table class="tbg">
						<tr>
							<td colspan="2">{Des_speedup_15}</td>
						</tr>
						<tr>
							<td width="70"><img src="images/asu/{speedup_15_image}" width="70"></td>
							<td align="left">{Duration}: <b>[{speedup_15_duration}]</b><br />{Costs}: <b>{speedup_15_asu}</b> {Asu}<br />{quantity}:<input name="input_speedup_15" type="text" size="2" value="1" maxlength="2" class="fm" onkeyup="CheckNumberInput(this);" /><br /><input type="submit" value="{Buy}" class="fm" {speedup_15_action}/></td>
						</tr>
					</table>
                </form>
			</td>
        </tr>
		<tr>
			<td width="50%">
				<form action="shop.php?type=21&s=3" method="post">
                	<table class="tbg">
						<tr>
							<td colspan="2">{Des_speedup_30}</td>
						</tr>
						<tr>
							<td width="70"><img src="images/asu/{speedup_30_image}" width="70"></td>
							<td align="left">{Duration}: <b>[{speedup_30_duration}]</b><br />{Costs}: <b>{speedup_30_asu}</b> {Asu}<br />{quantity}:<input name="input_speedup_30" type="text" size="2" value="1" maxlength="2" class="fm" onkeyup="CheckNumberInput(this);" /><br /><input type="submit" value="{Buy}" class="fm" {speedup_30_action}/></td>
						</tr>
					</table>
                </form>
			</td>
			<td width="50%">
				<form action="shop.php?type=22&s=3" method="post">
                	<table class="tbg">
						<tr>
							<td colspan="2">{Des_speedup_2h}</td>
						</tr>
						<tr>
							<td width="70"><img src="images/asu/{speedup_30_image}" width="70"></td>
							<td align="left">{Duration}: <b>[{speedup_2h_duration}]</b> <br />{Costs}: <b>{speedup_2h_asu}</b> {Asu}<br />{quantity}:<input name="input_speedup_2h" type="text" size="2" value="1" maxlength="2" class="fm" onkeyup="CheckNumberInput(this);" /><br /><input type="submit" value="{Buy}" class="fm" {speedup_2h_action}/></td>
						</tr>
					</table>
                </form>
			</td>
        </tr>		
        </table>
        <table cellspacing="1" cellpadding="2" id="txtshop4" {display4}>
        <tr>       				
            <td width="50%"><form action="shop.php?type=12&s=4" method="post">
                <table class="tbg">
					<tr>
						<td colspan="2">{Des_the_bai_2}</td>
					</tr>
						<tr>
							<td width="70"><img src="images/asu/{the_bai_2_image}" width="70"></td>
							<td align="left">{Duration}: <b>[{mot_lan}]</b><br />{Costs}: <b>{the_bai_2_asu}</b> {Asu}<br />{quantity}:<b>1</b><input name="input_the_bai_2" type="hidden" value="1"/><br /><input type="submit" value="{Buy}" class="fm" {the_bai_2_action}/></td>
						</tr>
					</table>
                </form></td>
            <td width="50%"><form action="shop.php?type=13&s=4" method="post">
                <table class="tbg">
					<tr>
						<td colspan="2">{Des_the_bai_3}</td>
					</tr>
                <tr>
						<td width="70"><img src="images/asu/{the_bai_3_image}" width="70"></td>
							<td align="left">{Duration}: <b>[{mot_lan}]</b><br />{Costs}: <b>{the_bai_3_asu}</b> {Asu}<br />{quantity}:<b>1</b><input name="input_the_bai_3" type="hidden" value="1"/><br /><input type="submit" value="{Buy}" class="fm" {the_bai_3_action}/></td>
						</tr>
					</table>					          
            </form></td>
        </tr>
		<tr>
			<td width="50%"><form action="shop.php?type=11&s=4" method="post">
             <table class="tbg">
			 	<tr>
						<td colspan="2">{Des_the_bai_1}</td>
					</tr>
                <tr>
                    <td width="70"><img src="images/asu/{the_bai_1_image}" width="70"></td>
                    <td align="left" width="70%">{Duration}: <b>[{mot_lan}]</b><br />{Costs}: <b>{the_bai_1_asu}</b> {Asu}<br />{quantity}:<b>1</b><input name="input_the_bai_1" type="hidden" value="1" class="fm"/><br /><input type="submit" value="{Buy}" class="fm" {the_bai_1_action}/></td>
                </tr>
            </table>    
            </form></td>
		</tr>
        </table>
        </fieldset>   
	</filedset>
</div>
</div>
</div>
