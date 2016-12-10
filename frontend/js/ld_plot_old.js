$(document).ready(function(){
	
	
	/* Initialisiere die Tabs, in welche die Plots gezeichnet werden */
	$( "#plots-tabs" ).tabs().hide();
	
	// Buttons fuer hiRes und highcharts
	$("#hires_container").buttonset();
	$("#highcharts_container").buttonset();

	// Initialisiere Datensatz-Felder
	loadSelectDatasets();
	
	$("#helptext-close").click(function() { $("#helptext").hide(); });
			
	$("#submit-button").click(startJob); 
	
	// Sentinel SNP Autocomplete
	$.ajax({
		  url: "backend/snipaSnpBin.php",
		  type: "POST",
		  data: { task: "read" },
		  dataType: "json",
		  success: function(data) {	
			$("#sentinel").autocomplete({
			source: data,
			minLength: 0
			}).focus(function() { $(this).autocomplete("search",$("#sentinel").val()); });	
		  }
	  });
	  
	 // Event Trigger falls Enter-Key im Feld gedrückt wird zum starten des Jobs
	 $("#sentinel").keypress(function(event) { if (event.which == 13) { $("#submit-button").click(); } });
	
	
});

function startJob() 
	{
	// Get ID for temp directory
	$.ajax({
		dataType: "text",
		async: false,
		cache: false,
		url: "backend/snipaTempdir.php",
		success: 
		function(randid) {
			if (randid.length == 15) 
				{
					$("#progressbar").progressbar({	value: false });
					$(".progress-label").text("");
					$("#message").html("");
					$('#message').show();
					$('#progressbar').show();
					
					$("#progress-dialog").dialog({
						title: "Your job is being processed.",
						modal: true,
						dialogClass: "no-close",
						width: 500,
						resizable: false,
						draggable: false,
						buttons: {}
					});
				
					setTimeout("updateStatus("+randid+")",500);
				
				$.ajax(
					{
					  url : "backend/snipaLDPlots.php",
					  type: "POST",
					  data: {	id: randid, 
								genomerelease: $("select#dataset-genomerelease").val(), 
								referenceset: $("select#dataset-referenceset").val(), 
								population: $("select#dataset-population").val(), 
								annotation: $("select#dataset-annotation").val(),
								highcharts: $("input[name=highcharts]:checked").val(),
								assocs: $("textarea#assocs").val(), 
								sentinel: $("input#sentinel").val(),
								hires:$("input[name=hires]:checked").val(),
								plotwidth: $("#page").width()-20
							},
					  dataType:"json",
					  
					  beforeSend: 
						function() { 
							
						},
						
					  success: 
						function(phpresults) {
						  updateStatus(randid);
						 if (phpresults['ok'] !== "FAIL") { saveSelectDatasets(); showResults(randid); } else { $('#progress-dialog').dialog({ buttons: [ { text: "OK", click: function() { $(this).dialog("close"); } } ] });}
						 }
					});
				}
			}	
		});
	}
	

function showResults(randid) {
	$('#progress-dialog').dialog("close");
	$('#message').hide();
	$('#progressbar').hide();
	$('#raplot-form-container').hide();
	$('#plots-tabs-static').html('<img id="plot-static-image" src="tmpdata/'+randid+'/ldplot_plot_static.png" />');
        //$('#plots-tabs-heatmap').html("<p>Coming Soon</p>");
        $('#plots-tabs-heatmap').load("tmpdata/heatmap2.html");

        
	$.ajax({url: 'tmpdata/'+randid+'/report.txt', async: false, dataType: "json"})
		.done(function(report) { 
			var reporthtml = "";
			reporthtml += "<p>Genetic variants were annotated using the &quot;" + report['userinput']['genomerelease'] + "/" + report['userinput']['referenceset'] +  "&quot; reference set (&quot;" + report['userinput']['population'] +  "&quot; population). Functional SNP annotations are based on &quot;" + report['userinput']['annotation'] +  "&quot;.</p>";
			reporthtml += "<p>The sentinel SNP " + report['userinput']['sentinelname'] + " is located on chromosome " + report['snppos']['sentinelchr'] + " at position " + report['snppos']['sentinelpos'] + ".</p>";
			if (report['jobinfo']['staticplotpdf'] != "") {
				reporthtml += "<p>You can download high-resolution versions of the static plot in <a href='"+report['jobinfo']['staticplotpdf']+"' style='color: rgb(228,0,58);' target='_blank'>PDF</a> and <a href='"+report['jobinfo']['staticplotpng']+"' style='color: rgb(228,0,58);' target='_blank'>PNG (1920x1080px)</a> format. Note that there might be a problem with the SNP symbols when using browser-integrated PDF-viewers such as the Firefox-embedded &quot;pdf.js&quot;. In this case use an external PDF viewer instead.</p>";
			}
			
			$('#plots-tabs-report').html(reporthtml);
                        //$('#plots-tabs-heatmap').html(reporthtml);
		});
	
	
	if ($("input[name=highcharts]:checked").val() == 1) {
		// initialisiere Status-Variable für default-action bei click auf SNP
		if (! $("#plots-tabs-dynamic").length) {
			snpclickdefault = 0;
		}
		
		if (! $("#plots-tabs-snpinfo").length) { 
			$('#plots-tabs-header').prepend('<li class="plots-tabs-tab"><a href="#plots-tabs-snpinfo">Variant annotations</a></li>'); 
		}
		if (! $("#plots-tabs-dynamic").length) { 
			$('#plots-tabs-header').prepend('<li class="plots-tabs-tab"><a href="#plots-tabs-dynamic">Interactive Plot</a></li>'); 
		}
		
		var tmpsnpinfo = '<div id="plots-tabs-snpinfo" class="plots-tabs-body-content">';
		tmpsnpinfo += '<span id="nosnpinfo">Click on a SNP in the interactive plot. Detailed annotations for this SNP will be displayed in this section.</span>'; 
		tmpsnpinfo += '<div id="plots-snpinfo-accordion"></div>'; 
		tmpsnpinfo += '</div>'; 
		if (! $("#plots-tabs-snpinfo").length) { 
			$('#plots-tabs-body').prepend(tmpsnpinfo);
			$("#plots-snpinfo-accordion").accordion({collapsible: true, heightStyle: "content", event: "click"});
		}

		
		var tmpdynplot = '<div id="plots-tabs-dynamic" class="plots-tabs-body-content" style="overflow: auto;">';
		tmpdynplot += '<div id="snipa-raplot-dynamic" style="width: 100%; height: 600px; overflow: auto;" ></div>';
		tmpdynplot += '<div id="snipa-raplot-dynamic-snpdetails-container" class= "plot-snpdetails"><div id="snipa-raplot-dynamic-snpdetails-menu"></div></div>';
		tmpdynplot += '</div>';
		if (! $("#plots-tabs-dynamic").length) {
			$('#plots-tabs-body').prepend(tmpdynplot);
		}
		
	
		$( "#plots-tabs" ).tabs("refresh");
		$( "#plots-tabs" ).tabs({active: 0});
				
		// Hole Plotdaten
		$.ajax(
			{
			  url : 'tmpdata/'+randid+'/ldplot_plot_dynamic.js',
			  type: "GET",
			  dataType:"script",
			  cache: false,
			  async: false,
			  success: 
				function() {
				   $('#plots-tabs').show();
				   Highcharts.setOptions({"chart": {"style": { "fontFamily": "Ubuntu", "fontSize": "10pt"}}});
				   // Dateinnamen zum Export festlegen
				   chartoptions.exporting.filename = "snipa_ldplot_"+$("input#sentinel").val()+"_"+$("select#dataset-genomerelease").val()+"_"+$("select#dataset-referenceset").val()+"_"+$("select#dataset-population").val()+"_"+$("select#dataset-annotation").val();
				   chart = new Highcharts.Chart(chartoptions);
				   chart = new Highcharts.Chart(chartoptions);
				   chart.renderer.image('frontend/img/raplot_dynamic_ldlegend.png',59,10,100,76).add();
			   }
			});
		
	}
							
	$('#plots-tabs').show();

}

function updateStatus(id){
  $.ajax({ dataType: "json",
           url: 'tmpdata/'+id+'/status.txt', 
		   cache: false, 
		   error: function(a,textstatus,c) { if (textstatus == "parsererror") { setTimeout("updateStatus("+id+")", 150);} },
		   success: function(data){
			   pbvalue = 0;

			   if(data){
					var total = data['totalstepnum'];
					var current = data['stepnum'];
					var message = data['message'];
					var ok = data['ok'];
					var errmessage = data['errmessage'];
					var pbvalue = Math.floor((current / total) * 100);
					if (pbvalue>0){
						$("#progressbar").progressbar({
							value: pbvalue
						});
						$(".progress-label").text(pbvalue+" %");
					}
					$("#message").html(message+"..");
				}
				if ((pbvalue < 100) && (ok !== "FAIL")) {
				   setTimeout("updateStatus("+id+")", 500);
				}
				if (ok == "FAIL") {
					$("#message").html(message+".. <span style='font-weight: bold; color: rgb(180,0,0);'>FAILED</span>.<br />"+errmessage);
				}
			}
	});
}


jQuery.extend(
   {
	  getAnnotations: function(url) 
	  {
		  var result = null;
		  $.ajax(
		  {
			url: url,
			type: 'get',
			dataType: 'text',
			async: false,
			cache: false,
			timeout: 3000,
			success: function(data) { result = data; },
			error: function(x,t,m) { if (t==='timeout') { result = 'Annotations could not be downloaded<br />due to a connection timeout.<br />Please try again later.' } else { result = 'Annotations could not be downloaded<br />since the server seems to be offline.<br />Please try again later.'  } }
		  });
	  return result;
	  }
   }
);


// Statischer Plot: lade passende Groesse nach, wenn Fensterbreite sich ändert
$( window ).resize(function() {
	var plotwidths = [ 570, 600, 630, 660, 690, 720, 750, 780, 810, 840, 870, 900 ];
	
	if ($("#plot-static-image").length) {
		var windowwidth = $("#plots-tabs-body").width();
		var closest = null;
		
		$.each(plotwidths, function() {
			if ( closest == null || 
			     ((Math.abs(this - windowwidth) < Math.abs(closest - windowwidth)) && (this <= windowwidth ))
				) 
			{ 
				closest = this; 
			} 
		});
		
		imgpath = $("#plot-static-image").attr("src");
		imgpath = imgpath.split('/');
		imgpath.pop();
		imgpath = imgpath.join('/');
		
		$("#plot-static-image").attr("src",imgpath + "/ldplot_plot_static_"+ closest + ".png");		
	}
});


function addToSnpAnnotationsTab(SnpInfoTabId,snpname,snppos,snpchr,sentinelpos,genomerelease,referenceset,population,annotation) {
	$.ajax(
			{
			  url: "backend/snipaRAPlotsAnnotations.php",
			  type: "POST",
			  data: {	snpname: snpname,
						snppos: snppos,
						snpchr: snpchr,
						sentinelpos: sentinelpos,
						genomerelease: genomerelease, 
						referenceset: referenceset, 
						population: population, 
						annotation: annotation
				},
			  dataType: "text",
			  success: function(anno) { 
					$("#nosnpinfo").hide();
					$("#plots-snpinfo-accordion").show();
					var annopanel = "<h3>"+snpname;
					annopanel += "<span onclick=\"$(this).parent('h3').next('div').remove(); $(this).parent('h3').hide().remove(); $('#plots-snpinfo-accordion').accordion('refresh'); if ($('#plots-snpinfo-accordion h3').length < 1) { $('#nosnpinfo').show(); dynplottab = getIndexForId('#plots-tabs-header','#plots-tabs-dynamic'); $('#plots-tabs').tabs({active: dynplottab}); } \" class=\"pinkspan\">delete</span>";
					annopanel += "<span onclick=\"printCard('"+genomerelease+"', '"+referenceset+"', '"+population+"', '"+annotation+"', '"+snpname+"');\" class=\"pinkspan\">save as PDF</span>";
					annopanel += "<span onclick=\"addToSnpBin('"+snpname+"','"+snppos+"','"+snpchr+"','"+genomerelease+"','"+referenceset+"','"+population+"','"+annotation+"'); \" class=\"pinkspan\">add to clipboard</span>";
					annopanel += "</h3>";
					annopanel += "<div>" + anno + "</div>";
					$("#plots-snpinfo-accordion").prepend(annopanel);
					if (snpclickdefault != 1) {
						var tabIndex = getIndexForId("#plots-tabs-header",SnpInfoTabId);
						$("#plots-tabs").tabs({active: tabIndex});
					}
					$("#plots-snpinfo-accordion").accordion("refresh");
					$("#plots-snpinfo-accordion").accordion("option","active",0);
					$("#plots-snpinfo-accordion h3 span").click(function() {return false;} ); // Verhindert das ein- und ausklappen bei den löschen und snpbin buttons
					
					// Tooltips für CADD scores etc
					$( ".whatsthis" ).tooltip({
							content:function(){return $(this).attr('title').replace(/\[/g,'<').replace(/\]/g,'>')},
							my: "left top+25", 
							at: "left bottom", 
							collision: "flipfit",
							show: false,
							hide: false,
							track: true
						});
				}
			}
		);
}



// Menue fuer SNP-Annotationen im dynamischen Plot
function showPlotAnnotationMenu(eventtype,snpname,snppos,snpchr,sentinelpos,genomerelease,referenceset,population,annotation) 
{ 
	if (snpclickdefault == 0) {
		$('#snipa-raplot-dynamic-snpdetails-container').show(); 
		$('#snipa-raplot-dynamic-snpdetails-container').dialog({
				title: 'SNP ' + snpname,
				modal: true,
				position: { my: 'left top', of: eventtype, offset: '10 10', collision: 'fit' },
				buttons: [ { text: 'Show annotation', 
							 click: function() { if ($('#snipa-raplot-dynamic-snpdetails-sameaction').is(':checked')) {
													snpclickdefault = 1;
												 }
												 $(this).dialog('destroy'); 
												 $('#snipa-raplot-dynamic-snpdetails-menu').html('');
												 addToSnpAnnotationsTab('#plots-tabs-snpinfo',snpname,snppos,snpchr,sentinelpos,genomerelease,referenceset,population,annotation);
											   }
						   } , 
						   { text: 'Copy to clipboard', 
							 click: function() { 
												 if ($('#snipa-raplot-dynamic-snpdetails-sameaction').is(':checked')) {
													snpclickdefault = 2;
												 }
												$('#snipa-raplot-dynamic-snpdetails-menu').html('');
												$(this).dialog('destroy'); 
												addToSnpBin(snpname,snppos,snpchr,genomerelease,referenceset,population,annotation);
												}
						   } ,
						   { text: 'Update plot using this SNP as sentinel', 
							 click: function() { 
												 if ($('#snipa-raplot-dynamic-snpdetails-sameaction').is(':checked')) {
													snpclickdefault = 3;
												 }
												$('#snipa-raplot-dynamic-snpdetails-menu').html('');
												$(this).dialog('destroy'); 
												$("input#sentinel").val(snpname); startJob();
												}
						   } 
						 ]
			});  
		$('#snipa-raplot-dynamic-snpdetails-menu').html('You can either get detailed annotations for this variant, copy it to SNiPA\'s clipboard, or redraw the LD plot using this variant as a sentinel.<br /><input type=\"checkbox\" id=\"snipa-raplot-dynamic-snpdetails-sameaction\" style=\"width: 10px;\" /> Use this as default action for this session.');  
		} 
		
	if (snpclickdefault == 1) { addToSnpAnnotationsTab('#plots-tabs-snpinfo',snpname,snppos,snpchr,sentinelpos,genomerelease,referenceset,population,annotation); }
	if (snpclickdefault == 2) { addToSnpBin(snpname,snppos,snpchr,genomerelease,referenceset,population,annotation); }
	if (snpclickdefault == 3) { $("input#sentinel").val(snpname); startJob(); }
} 
