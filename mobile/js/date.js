function ShowDate()
{
			var mydate=new Date()
			var year=mydate.getYear()
			if (year < 1000) year+=1900
			var day=mydate.getDay()
			var month=mydate.getMonth()
			var daym=mydate.getDate()
			if (daym<10) daym="0"+daym
			var dayarray=new Array("Chủ nhật","Thứ 2","Thứ 3","Thứ 4","Thứ 5","Thứ 6","Thứ 7")
			var montharray=new Array("1","2","3","4","5","6","7","8","9","10","11","12")
			document.getElementById("date").innerHTML=""+dayarray[day]+", ngày "+daym+"-"+montharray[month]+"-"+year+"";
}