$(document).ready(function(){
   $("#foo11").carouFredSel({
         auto	: true,
         infinite: false,
         circular    : true,
         direction   : 'up',
         items       : 1,
         scroll     : {				
                 duration: 1500 
             },
         prev : {	
         button	: "#foo11_prev",
         key		: "left"
     },
     next : { 
         button	: "#foo11_next",
         key		: "right"
     },
     pagination	: "#foo11_pag"
  });
   $("#foo221").carouFredSel({
      auto	: true,
      infinite: false,
      circular    : true,
      direction   : 'left',
      
      scroll     : {				
              duration: 1000 
          },
      prev : {	
      button	: "#foo221_prev",
      key		: "left"
      },
      next : { 
          button	: "#foo221_next",
          key		: "right"
      },
      pagination	: "#foo221_pag"
   });	
	$("#foo222").carouFredSel({
      auto	: false,
      infinite: false,
      circular    : true,
      direction   : 'left',
      
      scroll     : {				
              duration: 1000 
          },
      prev : {	
      button	: "#foo222_prev",
      key		: "left"
      },
      next : { 
          button	: "#foo222_next",
          key		: "right"
      },
      pagination	: "#foo222_pag"
	});
    $("#foo223").carouFredSel({
      auto	: false,
      infinite: false,
      circular    : true,
      direction   : 'left',
      
      scroll     : {				
              duration: 1000 
          },
      prev : {	
      button	: "#foo223_prev",
      key		: "left"
      },
      next : { 
          button	: "#foo223_next",
          key		: "right"
      },
      pagination	: "#foo223_pag"
	});	
	$('#s2').cycle({ 
		fx:     'fade', 
		speed:  'fast', 
		timeout: 6000, 
		next:   '#next2', 
		prev:   '#prev2' 
	});
    $('#home-document-folder').cycle({ 
		fx:     'fade', 
		speed:  3000, 
		timeout: 10000,
		next:   '#home-next', 
		prev:   '#home-prev' 
	});
	$('#document-folder2').cycle({ 
		fx:     'fade', 
		speed:  1000, 
		timeout: 6000,
		next:   '#next2', 
		prev:   '#prev2' 
	});
	$('#document-folder3').cycle({ 
		fx:     'fade', 
		speed:  1000, 
		timeout: 6000,
		next:   '#next3', 
		prev:   '#prev3' 
	});
	$('#document-folder4').cycle({ 
		fx:     'fade', 
		speed:  1000, 
		timeout: 6000,
		next:   '#next4', 
		prev:   '#prev4' 
	});
	$('#document-folder5').cycle({ 
		fx:     'fade', 
		speed:  1000, 
		timeout: 6000,
		next:   '#next5', 
		prev:   '#prev5' 
	});
   $('#cynewbox ul').cycle({
       fx: 'scrollVert',
       prev:   '#prev1', 
       next:   '#next1', 
   });
   $('#style1 ul').cycle({
       fx: 'fade',
       prev:   '#prev1', 
       next:   '#next1',
        timeout:       4900,
   });
   $('#style2 ul').cycle({
       fx: 'scrollUp',
       prev:   '#prev1', 
       next:   '#next1',
       timeout:       4500,
   });
   $('#style3 ul').cycle({
       fx: 'scrollUp',
       prev:   '#prev1', 
       next:   '#next1',
       timeout:       3800,
   });
    $('#style4 ul').cycle({
       fx: 'fade',
       prev:   '#prev1', 
       next:   '#next1',
        timeout:       6200,
   });
   var menungang = $('#menungang').superfish({
       //add options here if required
       minWidth:    10,   // minimum width of sub-menus in em units 
       maxWidth:    150,   // maximum width of sub-menus in em units 
   });
   // Tabs
   $('#tabs').tabs();
   $('#tabs_right').tabs();
   // Accordion
   $("#accordion").accordion({ header: "h3" });	
   
   $("a[rel=picture_group]").fancybox({
           'transitionIn'		: 'none',
           'transitionOut'		: 'none',
           'titlePosition' 	: 'over',
           'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
               return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
           }
       });
}); 
 