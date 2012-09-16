<div id="column-content">
    <div id="content">
        <form action="">
            <table align="center" width="60%" border="1">
                <tbody>
                    <tr>
                        <th align="center" colspan="11">{title_building_type}</th>
                    </tr>
                    <tr>
                        <th align="center"><font color="red">{title_check_box}</font></th>
                        <th align="center">{title_name}</th>
                        <th align="center">{title_building_level}</th>
                    </tr>
                {views_list}
                <tr>
                    <td align="center" colspan="10"><input id="ok_check_update" name="ok_check_update" type="submit" value="{ok}">
                    </td>
                </tr>
                </tbody>
                
            </table>
            <div style="display: none;">
                <input type="hidden" id="id_update_valu" name="id_update_valu" value="{id_update_valu}">
                <table>
                    <tbody>
                        <tr>
                            <th colspan="2">{title_views}</th>
                        </tr>
                        <tr>
                            <th align="right">{title_name}</th>
                            <td><input id="valu_name" name="valu_name" value="{valu_name}" type="text"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th align="right">{title_attack}</th>
                            <td><input id="valu_attack" name="valu_attack" value="{valu_attack}" type="text"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th align="right">{title_melee_defense}</th>
                            <td><input id="valu_melee_defense" name="valu_melee_defense" value="{valu_melee_defense}" type="text"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th align="right">{title_ranger_defense}</th>
                            <td><input id="valu_ranger_defense" name="valu_ranger_defense" value="{valu_ranger_defense}" type="text"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th align="right">{title_magic_defense}</th>
                            <td><input id="valu_magic_defense" name="valu_magic_defense" value="{valu_magic_defense}" type="text"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th align="right">{title_hitpoint}</th>
                            <td><input id="valu_hitpoint" name="valu_hitpoint" value="{valu_hitpoint}" type="text"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th align="right">{title_mana}</th>
                            <td><input id="valu_mana" name="valu_mana" value="{valu_mana}" type="text"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th align="right">{title_speed}</th>
                            <td><input id="valu_speed" name="valu_speed" value="{valu_speed}" type="text"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th align="right">{title_carry}</th>
                            <td><input id="valu_carry" name="valu_carry" value="{valu_carry}" type="text"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th align="right" >{title_nation_id}</th>
                            <td><input id="valu_nation_id" name="valu_nation_id" value="{valu_nation_id}" type="text"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th align="right">{title_rs1}</th>
                            <td><input id="valu_rs1" name="valu_rs1" value="{valu_rs1}" type="text"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th align="right">{title_rs2}</th>
                            <td><input id="valu_rs2" name="valu_rs2" value="{valu_rs2}" type="text"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th align="right">{title_rs3}</th>
                            <td><input id="valu_rs3" name="valu_rs3" value="{valu_rs3}" type="text"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th align="right">{title_rs4}</th>
                            <td><input id="valu_rs4" name="valu_rs4" value="{valu_rs4}" type="text"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th align="right">{title_keep_hour}</th>
                            <td><input id="valu_keep_hour" name="valu_keep_hour" value="{valu_keep_hour}" type="text"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th align="right">{title_requirement}</th>
                            <td><input id="valu_requirement" name="valu_requirement" value="{valu_requirement}" type="text"></td>
                            <td><input id="save_check" name="save_check" type="submit" value="Check"></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center"><input type="submit" id="update_data" name="update_data" value="{update}"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
