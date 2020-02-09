<script type="text/javascript">
var XMLHttpRequestObject=false; //creer var
if(window.XMLHttpRequest){
  XMLHttpRequestObject=new XMLHttpRequest();
}else if(window.ActiveXObject){
  XMLHttpRequestObject=new ActiveXObject("Microsoft.XMLHTTP");
}

                //the functionS
                function getSearchInvestors(){
                  if(XMLHttpRequestObject){
                    XMLHttpRequestObject.open("POST","class/ajaxControler.php");
                    XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
                    XMLHttpRequestObject.onreadystatechange=function(){

                    if(XMLHttpRequestObject.readyState==4 && XMLHttpRequestObject.status==200){
                      var datar=XMLHttpRequestObject.responseText;
                      var divsee=document.getElementById("display");/// la ou ca va afficher
                      divsee.innerHTML=datar;
                    }
                }
                    //les variables a etre envoyer et utiliser
                    var data=document.getElementById("keyword").value;
                   // var data=report+'|'+stat+'|'+dat1+'|'+dat2+'|'; //pour concatener plusieures variables
                    XMLHttpRequestObject.send("search_investor=" + data); // Send variables
                  }
                  return false;
                }
</script>
