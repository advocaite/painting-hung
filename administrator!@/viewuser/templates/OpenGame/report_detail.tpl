<div id="box1"><div id="box2"><h1>{Reports}</h1>
<p class="title_menu">
	<span {class}><a href="report.php" >{All}</a></span> |	
	<span {class1}><a href="report.php?tab={REPORT_ATTACK}">{Attacks}</a></span> |
	<span {class2}><a href="report.php?tab={REPORT_DEFEND}">{Reinforcement}</a></span> |
    <span {class3}><a href="report.php?tab={REPORT_TRADE}">{Trade}</a></span>
</p>
<p>
<table cellspacing="1" cellpadding="2" class="tbg">
    <tr class="rbg">
    <td class="s7">{title}</td><td class="s7">{title_de}</td>
    </tr>   
    <tr>   
     <td class="s7">{time}</td>
    <td class="s7">{time_de}</td>
    </tr>
    
    <tr><td colspan="4"></td></tr>
    <tr>
    <td colspan="4" valign="top">{_report_detail}</td>
    </tr>   
	
	<tr><td colspan="4">	
	<form method="post" action="report_detail.php?id={preId}{typeTab}" style="display:inline">
	<!--<input type="submit" name="Preview" value="Về trước" {enablePreview} />-->
	<input type="image" name="Preview" src="{btnPrevious}" {enablePreview} />
	</form>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<form method="post" action="report_detail.php?id={reportId}{typeTab}" style="display:inline">
	<input type="hidden" name="Del" value="1" />
	<input type="image" name="imgDel" src="{btnDelete}" />
	</form>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<form method="post" action="report_detail.php?id={nextId}{typeTab}" style="display:inline">
	<!--<input type="submit" name="Next" value="Tiếp theo" {enableNext} />-->
	<input type="image" name="Next" src="{btnNext}" {enableNext} />
	</form>
	
	</td></tr>
</table>
</p>
</div>
</div>
</div>