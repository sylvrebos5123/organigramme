<!--
function checkBrowser()
{
 //  --- Text étudde
  // alert(navigator.userAgent);
  // alert(navigator.appVersion);

 this.ver=(navigator.userAgent.indexOf('Firefox')>-1)?'Firefox':navigator.appVersion;
 this.SeaMonkey=(navigator.userAgent.indexOf('SeaMonkey')>-1)?1:0;
 this.firefox=(navigator.userAgent.indexOf("Firefox")>-1)?1:0;
 this.Opera=(navigator.userAgent.indexOf('Opera')>-1)?1:0;
 this.Netscape=(navigator.userAgent.indexOf('Netscape/')>-1)?1:0;
 this.dom=document.getElementById?1:0;
 this.ie9=(this.ver.indexOf("MSIE 9")>-1)?1:0;
 this.ie8=(this.ver.indexOf("MSIE 8")>-1)?1:0;
 this.ie7=(this.ver.indexOf("MSIE 7")>-1)?1:0;
 this.ie6=(this.ver.indexOf("MSIE 6")>-1 && this.dom)?1:0;
 this.ie55=((this.ver.indexOf("MSIE 5.5")>-1 || this.ie6) && this.dom)?1:0;
 this.ie5=((this.ver.indexOf("MSIE 5")>-1 || this.ie5 || this.ie6) && this.dom)?1:0;
 this.ie4=(document.all && !this.dom)?1:0;
 this.ns5=(this.dom && parseInt(this.ver) >= 5) ?1:0;
 this.ns4=(document.layers && !this.dom)?1:0;
 this.ie4plus=(this.ie9 || this.ie8 || this.ie7 || this.ie6 || this.ie5 || this.ie4);
 this.ie5plus=(this.ie9 || this.ie8 || this.ie7 || this.ie6 || this.ie5)
 this.ie=(this.ie8 || this.ie7 || this.ie6 || this.ie5 || this.ie55 || this.ie4);
 this.bw=(this.ie4plus || this.ns4 || this.ns5);
 this.Mozilla=(navigator.userAgent.indexOf('Mozilla')<0 ||this.SeaMonkey || this.firefox || this.Opera || this.Netscape || this.ie4plus || this.ie55)?0:1;
 return this;
}

function getObjectById(ID)
{
 // var obj;
 if (bw.dom)
  return document.getElementById(ID);
 else
  if (bw.ie4)
   return document.all(ID);
  else
   if (bw.ns4)
    return eval('document.' + ID);
}

function getObjectByIdParent(ID)
{
 // var obj;
 if (bw.dom)
  return parent.document.getElementById(ID);
 else
  if (bw.ie4)
   return parent.document.all(ID);
  else
   if (bw.ns4)
    return eval('parent.document.' + ID);
}

function fChangeClass(ps_id, ps_class)
{
 var obj = getObjectById(ps_id);
 obj.className=ps_class;
}

function checkHeight(ps_id,pn_min_height,ps__def_height)
{
 var d = getObjectById(ps_id);
 if (d.offsetHeight < pn_min_height) d.style.height=ps__def_height+'px';
}

function CorrectWidth(ps_id,ps_width,pb_explorateur)
{
 if (pb_explorateur)
 {
  var d = getObjectById(ps_id);
  d.style.width=ps_width;
 }
}

function fRedirection(ps_page)
{
 document.location=ps_page;
}

function fSelectReset(po_select, ps_value)
{
 var i=0;
 var j=0;
 while (i < po_select.options.length)
 {
  if (po_select.options[i].value==ps_value)
  {
   j=i;
   i=po_select.options.length;
   break;
  }
  else
  {
   i++
  }
 }

 po_select.options[j].selected=true;
 return true;
}

function fShowUpload(ps_div_show, ps_div_hidden)
{
 var d = getObjectById(ps_div_hidden);
 d.style.visibility='hidden';
 d.style.display='none';

 d = getObjectById(ps_div_show);
 d.style.visibility='visible';
 d.style.display='inline';
 return true;
}

function fAutoMiddle(pn_size, pid)
{
 var vn_size = document.body.clientWidth;
 var vn_width =  (vn_size > pn_size ? Math.round((vn_size - pn_size) / 2) : pn_size); 
 
 var obj = getObjectById(pid);
 obj.style.left=vn_width+'px';
}

function register_position()
{
 mouse_x = 0;
 mouse_y = 0;
 document.onmousemove = mouse_position;
}

function mouse_position(evt)
{
 if (!evt) evt = window.event;
 mouse_x = evt.clientX;
 mouse_y = evt.clientY;
}

function fFormParam(ps_form)
{
 var vs_param = '';
 var vs_name = '';
 var vs_value = '';
 if ((ps_form != '') && (ps_form != null))
 {
  for (var i=0; i < document.forms[ps_form].elements.length; i++)
  {
   vs_name = document.forms[ps_form].elements[i].name;
   if ((vs_name != '') && (vs_name != null))
   {
    if (vs_param != '') vs_param += '&';
    vs_param += vs_name + '=';
    vs_param += encodeURIComponent(document.forms[ps_form].elements[i].value);
   }
  }
 }
 return(vs_param);
}

function CreateHtmlElement(id)
{
 var elem = document.createElement('div');
 elem.id = id;
 document.body.appendChild(elem);
}

function DeleteHtmlElement(id)
{
 var old = document.getElementById(id);
 old.innerHTML=null;
 document.body.removeChild(old);
}

function DeleteHtmlContent(id)
{
 var old = document.getElementById(id);
 old.innerHTML='';
}

function fCloseDialog(id)
{
 var d = getObjectById(id);
 d.innerHTML='';
 d.style.visibility='hidden';
 d.style.width = '0px';
 d.style.height = '0px';
}

function fOpenPdf(ps_pdf)
{
 var oShell = new ActiveXObject("Shell.Application" );
 var commandtoRun = "AcroRd32.exe";
 var commandParms = ps_pdf;
 oShell.ShellExecute(commandtoRun, commandParms, "", "open", "1" )
}
//-->