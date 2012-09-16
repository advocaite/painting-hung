<div id="column-content">
    <div id="content">
        <form action="">
            <table border="1" width="80%" align="center">
                <tbody>
                    <tr>
                        <th align="center" colspan="4">{name_tech_views}</th>
                    </tr>
                    <tr>
                        <th align="center"><font color="red">{select_tech_check}</font></th>
                        <th align="center">{name_tech_building}</th>
                        <th align="center">{name_tech_level}</th>
                        <th align="center">{name_tech_res}</th>
                    </tr>
                {list_select}
                <tr>
                    <td align="center" colspan="4"><input id="select_tech" name="select_tech" type="submit" value="{select_tech_valu}">
                    </td>
                </tr>
                </tbody>
                
            </table>
            <div style="display:none">
                <table>
                    <tbody>
                        <tr>
                            <th align="center" colspan="2">{name_tech_save}</th>
                        </tr>
                        <tr>
                            <td align="right">{name_tech_building}</td>
                            <td align="left"> {name_tech_building_valu} </td>
                        </tr>
                        <tr>
                            <td align="right">{name_tech_level}</td>
                            <td align="left"><input id="name_tech_level" name="name_tech_level" value="{name_tech_level_valu}">
                            </td>
                        </tr>
                        <tr>
                            <td align="right">{name_tech_res}</td>
                            <td align="left"><input id="name_tech_res" name="name_tech_res" value="{name_tech_res_valu}">
                                <input id="name_tech_res_check" name="name_tech_res_check" value="{name_tech_res_check_valu}" type="submit">
                            </td>
                        </tr>
                        <tr>
                            <td align="right">{name_tech_rs1}</td>
                            <td align="left"><input id="name_tech_rs1" name="name_tech_rs1" value="{name_tech_rs1_valu}">
                            </td>
                        </tr>
                        <tr>
                            <td align="right">{name_tech_rs2}</td>
                            <td align="left"><input id="name_tech_rs2" name="name_tech_rs2" value="{name_tech_rs2_valu}">
                            </td>
                        </tr>
                        <tr>
                            <td align="right">{name_tech_rs3}</td>
                            <td align="left"><input id="name_tech_rs3" name="name_tech_rs3" value="{name_tech_rs3_valu}">
                            </td>
                        </tr>
                        <tr>
                            <td align="right">{name_tech_rs4}</td>
                            <td align="left"><input id="name_tech_rs4" name="name_tech_rs4" value="{name_tech_rs4_valu}">
                            </td>
                        </tr>
                        <tr>
                            <td align="right">{name_tech_rs5}</td>
                            <td align="left"><input id="name_tech_rs5" name="name_tech_rs5" value="{name_tech_rs5_valu}">
                            </td>
                        </tr>
                        <tr>
                            <td align="right">{name_tech_product_hour}</td>
                            <td align="left"><input id="name_tech_product_hour" name="name_tech_product_hour" value="{name_tech_product_hour_valu}">
                            </td>
                        </tr>
                        <tr>
                            <td align="right">{name_tech_type}</td>
                            <td align="left"><input id="name_tech_type" name="name_tech_type" value="{name_tech_type_valu}">
                            </td>
                        </tr>
                        <tr>
                            <td align="right">{name_tech_des}</td>
                            <td align="left"><textarea id="name_tech_des" name="name_tech_des" cols="10" rows="5">{name_tech_des_valu}</textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
