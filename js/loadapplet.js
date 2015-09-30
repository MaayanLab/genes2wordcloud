function display_applet(name,value) { 
    xmlhttp=GetXmlHttpObject();
    if (xmlhttp==null) {
        alert ("Your browser does not support AJAX!");
        return;
    } 
    var url="change_settings.php";
    params=Create_param(name,value);
    xmlhttp.open("POST",url,true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.setRequestHeader("Content-length", params.length);
    xmlhttp.setRequestHeader("Connection", "close");
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 || xmlhttp.readyState=="complete") {
        	//alert(xmlhttp.responseText);
        	//alert(document.getElementById('applet').innerHTML);
            document.getElementById('applet').innerHTML=xmlhttp.responseText;
        }
    }
    xmlhttp.send(params);
}

function GetXmlHttpObject() {
    var xmlhttp=null;
    try {
        // Firefox, Opera 8.0+, Safari
        xmlhttp=new XMLHttpRequest();
    }
    catch (e) {
        // Internet Explorer
        try {
            xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (e) {
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
    }
    return xmlhttp;
} 

function Create_param(name,value){
	return name+"="+value;
}

function get_info(str) { 
	
    xmlhttp=GetXmlHttpObject();
    if (xmlhttp==null) {
        alert ("Your browser does not support AJAX!");
        return;
    } 
    var url="get_info.php";
    params="str="+str;
	//params = "word=disease&weight=0.587";
    xmlhttp.open("POST",url,true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.setRequestHeader("Content-length", params.length);
    xmlhttp.setRequestHeader("Connection", "close");
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 || xmlhttp.readyState=="complete") {
        	//alert(xmlhttp.responseText);
			document.getElementById('appletData').innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.send(params);
}
