<div id="column-content">
    <div id="content">{info_}
        <form method="post" action="{link}">
            <input type="hidden" name="id_update" value="{id}">
            <table align="center" width="80%" style="border:solid 1px #CCCCCC; margin-top:5px">
                <tbody>
                    <tr>
                        <th colspan="3">{title_views}</th>
                    </tr>
                    <tr>
                        <th width="23%" align="right">{title_name}</th>
                        <td width="26%" colspan="2"><input id="valu_name" name="valu_name" value="{valu_name}" type="text" readonly="1" class="fm"></td>
                        <th width="28%">{title_attack}</th>
                        <td width="23%"><input id="valu_attack" name="valu_attack" value="{valu_attack}" type="text" class="fm"></td>
                    </tr>                   
                    <tr>
                        <th align="right">{title_melee_defense}</th>
                        <td colspan="2"><input id="valu_melee_defense" name="valu_melee_defense" class="fm" value="{valu_melee_defense}" type="text"></td>
                        <th>{title_ranger_defense}</th>
                        <td><input id="valu_ranger_defense" name="valu_ranger_defense"  class="fm" value="{valu_ranger_defense}" type="text"></td>
                    </tr>                   
                    <tr>
                        <th align="right">{title_magic_defense}</th>
                        <td colspan="2"><input id="valu_magic_defense" class="fm" name="valu_magic_defense" value="{valu_magic_defense}" type="text"></td>
                        <th>{title_hitpoint}</th>
                        <td><input id="valu_hitpoint" name="valu_hitpoint" class="fm" value="{valu_hitpoint}" type="text"></td>
                    </tr>                    
                    <tr>
                        <th align="right">&nbsp;</th>
                        <td colspan="2">&nbsp;</td>
                        <th>{title_speed}</th>
                        <td><input id="valu_speed" name="valu_speed" value="{valu_speed}" type="text" class="fm"></td>
                    </tr>                   
                    <tr>
                        <th align="right">{title_carry}</th>
                        <td colspan="2"><input id="valu_carry" name="valu_carry" value="{valu_carry}" type="text" class="fm"></td>
                       <!-- <th>{title_nation_id}</th>
                        <td><input id="valu_nation_id" name="valu_nation_id" value="{valu_nation_id}" type="text" class="fm"></td>-->
                    </tr>
                   
                    <tr>
                        <th align="right">{title_rs1}</th>
                        <td colspan="2"><input id="valu_rs1" name="valu_rs1" value="{valu_rs1}" type="text" class="fm"></td>
                        <th>Clay</th>
                        <td><input id="valu_rs2" name="valu_rs2" value="{valu_rs2}" type="text" class="fm"></td>
                    </tr>                    
                    <tr>
                        <th align="right">{title_rs3}</th>
                        <td colspan="2"><input id="valu_rs3" name="valu_rs3" value="{valu_rs3}" class="fm" type="text"></td>
                        <th>{title_rs4}</th>
                        <td><input id="valu_rs4" name="valu_rs4" value="{valu_rs4}" type="text" class="fm"></td>
                    </tr>                    
                    <tr>
                        <th align="right">{title_time_train}</th>
                        <td colspan="2"><input id="valu_time_train" name="valu_time_train" value="{valu_time_train}" type="text" class="fm"></td>
                        <th>{title_keep_hour}</th>
                        <td><input id="valu_keep_hour" name="valu_keep_hour" value="{valu_keep_hour}" type="text" class="fm"></td>
                    </tr>                    
                    <tr>
                        <th align="right">{title_requirement}</th>
                        <td><input id="valu_requirement" name="valu_requirement" value="{valu_requirement}" type="text" class="fm" readonly="1"></td>
                        <td><!--<input id="update_check" name="update_check" type="submit" value="Check" class="fm">--></td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <th align="right" >{title_nation_id}</th>
                        <td colspan="2">{show_combo_nation}</td>
                        <th>{title_type}</th>
                        <td>{show_combo_type}</td>
                    </tr>                   
                    
                    <tr>
                        <th align="right">{title_icon}</th>
                        <td colspan="4"><img src="../{valu_icon}" /></td>
                    </tr>
                    <tr>
                        <th align="right">{title_building_type}</th>
                        <td colspan="2">{show_combo_building_type}</td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="3" align="center"><input type="submit" name="update" value="Update" class="fm"></td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>
