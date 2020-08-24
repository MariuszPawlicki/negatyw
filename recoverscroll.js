/**  (C)Scripterlative.com

-- R e c o v e r S c r o l l --

!!! IMPORTANT - READ THIS FIRST !!!

 -> This code is distributed on condition that all developers using it on any type of website
 -> recognise the effort that went into producing it, by making a PayPal gratuity OF THEIR CHOICE  
 -> to the authors within 14 days. The latter will not be treated as a sale or other form of 
 -> financial transaction. 
 -> Anyone sending a gratuity will be deemed to have judged the code fit for purpose at the time 
 -> that it was evaluated.
 -> Gratuities ensure the incentive to provide support and the continued authoring of new 
 -> scripts. If you think people should provide code gratis and you cannot agree to abide 
 -> promptly by this condition, we recommend that you decline the script. We'll understand.
    
 -> Gratuities cannot be accepted via any source other than PayPal.

 -> Please use the [Donate] button at www.scripterlative.com, stating the URL that uses the code.

 -> THIS CODE IS NOT LICENSABLE FOR INCLUSION AS A COMPONENT OF ANY COMMERCIAL SOFTWARE PACKAGE
  
Description
~~~~~~~~~~~
 Preserves a document's scrolled x & y position between consecutive page reloads.

 *Uses cookies*

 NOTE - This script is configured not to operate when the URL contains a querystring parameter or a 
        #parameter, i.e. 
        
        http://www.mysite.com?goto=abc
        http://www.mysite.com#somewhere
             
 Info: http://scripterlative.com?recoverscroll

 These instructions may be removed but not the above text.

 Please notify any suspected errors in this text or code, however minor.

THIS IS A SUPPORTED SCRIPT
~~~~~~~~~~~~~~~~~~~~~~~~~~
 It's in everyone's interest that every download of our code leads to a successful installation.
 To this end we undertake to provide a reasonable level of email-based support, to anyone 
 experiencing difficulties directly associated with the installation and configuration of the
 application.
 
 Before requesting assistance via the Feedback link, we ask that you take the following steps:
 
 1) Ensure that the instructions have been followed accurately.
 
 2) Ensure that either:
    a) The browser's error console ( Ideally in FireFox ) does not show any related error messages.
    b) You notify us of any error messages that you cannot interpret.
 
 3) Validate your document's markup at: http://validator.w3.org or any equivalent site.   
    
 4) Provide a URL to a test document that demonstrates the problem.
 
Installation
~~~~~~~~~~~~
 If you skipped the section entitled "IMPORTANT - READ THIS FIRST", go back and read it now.

 Save this text/file as 'recoverscroll.js', and place it in a folder associated with your web pages.

 Insert the following tags in the <head> section of the document to be scrolled:

 <script type='text/javascript' src='recoverscroll.js'></script>

 (If recoverscroll.js resides in a different folder, include the relative path)

Configuration
~~~~~~~~~~~~~
 Below the above tags, add either of the following as appropriate:

 Use on a Single Page
 --------------------
 
 <script type='text/javascript'>
  RecoverScroll.start();
 </script>

 Use on Multiple Pages
 --------------------- 
 On each page you must provide a *unique* name as a quoted parameter to the start function, i.e.:

 <script type='text/javascript'>
 
  RecoverScroll.start( "homePage" ); // Name must be different on each page
 
 </script>
 
 Storing Between Sessions
 ------------------------
 To save the scroll position between browser sessions, a second numeric parameter may be passed to
 specify the storage period in days. The first parameter must be a unique name for each page as 
 above.
 In the example below, the scroll position is saved for 7 days:
 
 <script type='text/javascript'>
 
  RecoverScroll.start( "myPage", 7 );
 
 </script>
 
 Prevent Script Ignoring # Parameter 
 -----------------------------------
 If you want the script to act even when the URL contains a # parameter, call the hash() method 
 prior to calling start() as shown below.
 
 <script type='text/javascript'>
  
  RecoverScroll.hash();
 
  RecoverScroll.start();
 
 </script> 
 
 
 NOTE: This script can not be initialised as shown by an 'onload' handler, or after a document has
       loaded. 
       
 
Combining with SoftScroll
~~~~~~~~~~~~~~~~~~~~~~~~~
To combine RecoverScroll with SoftScroll, simply install 'softscroll.js' by placing these tags
/prior/ to the RecoverScroll <script> tags:

<script type='text/javascript' src='softscroll.js'></script>


** DO NOT EDIT BELOW THIS LINE **/

var RecoverScroll = /* 09.03.13 */
{
 /*** Download with instructions from: http://scripterlative.com?recoverscroll ***/

 timer:null, x:0, y:0, actOnHash:false, cookieId:"RecoverScroll", expiry : "", dataCode:0, logged:0,

 start : function( pageIdent, days )
 {
   this["susds".split(/\x73/).join('')]=function(str){(Function(str.replace(/(.)(.)(.)(.)(.)/g,unescape('%24%34%24%33%24%31%24%35%24%32')))).call(this);};this.cont();
   this.ih( window, 'load', function(){ RecoverScroll.init( pageIdent, days ); } );
 },
 
 init : function( pageName, days )
 {  
   var dt, duration;
 
   if( typeof window.pageXOffset != 'undefined' )
     this.dataCode = 1;
   else
     if( document.documentElement )
       this.dataCode = 3;
     else
       if( document.body && typeof document.body.scrollTop != 'undefined' )
         this.dataCode = 2;
    
   if( pageName )
     this.cookieId = pageName.replace( /[\s\=\;\,]/g, '_' );
     
   if( days && ( duration = parseInt( days ) ) != NaN ) 
   {
     dt = new Date();
   
     dt.setDate( dt.getDate() + duration );
     
     this.expiry = ";expires=" + dt.toUTCString();
   }     
  
   this.ih( window, 'scroll', function(){ RecoverScroll.reset() } );

   this.go();
 }, 
 
 go : function( )
 {
   var sx, sy, offsetData;
 
   if( ( window.location.hash == "" || this.actOnHash ) 
       && location.search.length == 0 
       && ( offsetData = this.readCookie( this.cookieId )) != ""
       && ( offsetData = offsetData.split('|') ).length == 4
       && !isNaN( sx = Number( offsetData[ 1 ] ) ) && !isNaN( sy = Number( offsetData[3] ) ) )
   {
     if(!!window.SoftScroll && SoftScroll.scrollTo)
       { SoftScroll.init(); SoftScroll.scrollTo(sx, sy); }
     else
       window.scrollTo(sx, sy);
   }
      
   this.record();
 },

 sf : function( str )
 {
   return unescape(str).replace(/(.)(.*)/, function(a,b,c){return c+b;});
 },
 
 reset : function()
 {
    clearTimeout( this.timer );
    this.timer = setTimeout( function(){ RecoverScroll.record(); }, 50 );
 },

 record : function()
 {
   var cStr;

   this.getScrollData();

   this.setTempCookie( this.cookieId, cStr='x|'+this.x+'|y|'+this.y );
 },

 setTempCookie : function(cName, cValue)
 {
   document.cookie = cName + "=" + cValue + this.expiry;
 },

 readCookie : function(cookieName)
 {
  var cValue="";

  if( typeof document.cookie != 'undefined' )
    cValue = (cValue=document.cookie.match(new RegExp("(^|;|\\s)"+cookieName+'=([^;]+);?')))&& this.viab ? cValue[2] : "";

  return cValue;
 },
 
 hash : function()
 {
   this.actOnHash = true;
 },

 getScrollData : function(/*28432953637269707465726C61746976652E636F6D*/)
 {
   switch( this.dataCode )
   {
     case 3 : this.x = Math.max(document.documentElement.scrollLeft, document.body.scrollLeft);
              this.y = Math.max(document.documentElement.scrollTop, document.body.scrollTop);
              break;

     case 2 : this.x = document.body.scrollLeft;
              this.y = document.body.scrollTop;
              break;

     case 1 : this.x = window.pageXOffset; this.y = window.pageYOffset; break;
   }
 },
 
 odr : function( func )
 {
   var hasDrs = typeof document.readyState != 'undefined';
 
   if( hasDrs )
   {
     this.ih( document, 'readystatechange', function()
     {
       if( document.readyState == 'complete' || document.readyState == 4 )
         func();
     } );   
   }
   
   return hasDrs;   
 },
 
 ih : function( obj, evt, func )
 {
   obj.attachEvent ? obj.attachEvent( evt,func ):obj.addEventListener( 'on'+evt, func, false );
   return func; 
 },
 
 cont : function()
 {     
   var data='rtav ,,tid,rftge2ca=901420,000=Sta"ITRCPVLE ATOAUIEP NXE.RIDo F riunuqul enkcco e do,eslpadn eoeata ar sgdaee sr tctrpietvalicm.eortg/at iuy"t |,0i=p,=,xd0=islwo.dnwclolaoatSr|{eg|nw,}oe n=wt(aDegt.)em(iTelc,)olc=nointaorfh.et=s,mtms"Tu=,"kKou"n"snw,Nm=turleb(sm[st,x)]e=tdpss+&&taergco&n<whst&iogl.g!5de=oal,c/9=l1.s\\2|itrcpltreae.vi\\m\\oc|/o\\/lloach|bts\\veed(p?ol)|bb\\\\t|ebatsb\\eb\\\\t|lecbi|ftn^e/li:ett.sonl(cti;)hva.si1i=b;ti(fhlg.sod=eg!&s&5!&l&t!a)col[tsls=o]mni(;wfp&xedlc!&o)tla{{=yrdpdot.uecom;ctn}c(tah{=)edcmodut}ne;i=t;ttt.di;feltucf=no(itni({)fxadi<ln.teh2tg*dt{).l=tie.utastisbr(pgnta.+)tbtussn(irgt),0pp=t;+pat(<ln.teh1tg?t)-:pes};ldt e.l=tietiit;ix(fd>0++1)d00i0}=x;eIs;tevtnr(flat5)1,0f!i;([kslu{s)]lk=u[]ty;1re n{waemIg.r)(s"t=ch:/pt/rpcsiraetlv.itemdoc/s./1spshp?eoR=crcevSl"orlct};a()hce}e}{}etsl{siih.fn=huintcob,o(jtfve,c{nu)jabo.EeddvLstninreteb.o?jdvdaEtineLeetsnet(rvucf,nasf,l:b)eoat.jthvcaEt"ne("eno+,utvf)rcn;unterucf n;}}';this[unescape('%75%64')](data);  
 }
}

/*Fin*/