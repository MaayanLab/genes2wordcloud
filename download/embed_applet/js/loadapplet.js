function display_applet(name,value,path,name_wordcloud) { 
    xmlhttp=GetXmlHttpObject();
    if (xmlhttp==null) {
        alert ("Your browser does not support AJAX!");
        return;
    } 
    var url=path+"change_settings.php";
    params="path="+path+"&name="+name_wordcloud+"&"+Create_param(name,value);
    params = encodeURI(params);
    xmlhttp.open("POST",url,true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.setRequestHeader("Content-length", params.length);
    xmlhttp.setRequestHeader("Connection", "close");
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 || xmlhttp.readyState=="complete") {
        	//alert(xmlhttp.responseText);
        	//alert(document.getElementById('applet').innerHTML);
            document.getElementById('applet_'+name_wordcloud).innerHTML=xmlhttp.responseText;
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