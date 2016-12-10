$(document).ready(function(){

	// Blendet Hilfetext aus
	$("#helptext-close").click(function() { $("#helptext").hide(); });
	
	// Initialisiere Datensatz-Felder und Gennamen-Autocomplete
	loadSelectDatasets("genesautocomplete");
	
	// Update der annorelease Variable wenn anderes Release gewählt 
	$("select#dataset-genomerelease").change(function() {
		iniGenesAutocomplete();
	}); 
	$("select#dataset-annotation").change(function() {
		iniGenesAutocomplete();
	});
	
	// Buttons fuer input type (snps, gene, region)
	$("#selection_snps_container").buttonset();
	$("#snps_variants").hide(); $("#snps_ld").show(); $("#snps_ld_r2").show(); $("#snps_gene").hide(); $("#snps_region").hide();
	
	// Buttons fuer Functionale Annotation
	$("#incl_funcann_container").buttonset();
	
	// Event handler wenn input type geaendert wird
	$("input[name='selection_snps']").change(function(){
		if (this.id == "selection_snps_variants") { $("#snps_variants").show(); $("#snps_ld").hide(); $("#snps_ld_r2").hide(); $("#snps_gene").hide(); $("#snps_region").hide(); }
		if (this.id == "selection_snps_ld") {  $("#snps_variants").hide(); $("#snps_ld").show(); $("#snps_ld_r2").show(); $("#snps_gene").hide(); $("#snps_region").hide(); }
		if (this.id == "selection_snps_gene") { $("#snps_variants").hide(); $("#snps_ld").hide(); $("#snps_ld_r2").hide(); $("#snps_gene").show(); $("#snps_region").hide(); }
		if (this.id == "selection_snps_region") { $("#snps_variants").hide(); $("#snps_ld").hide(); $("#snps_ld_r2").hide(); $("#snps_gene").hide(); $("#snps_region").show(); }
	});
	
	// Slider fuer LD Threshold
	$(function() {
		var ld_threshold_select = $("#ld_threshold");
		var ld_threshold_slider_labels = new Array("","0.1","0.2","0.3","0.4","0.5","0.6","0.7","0.8","0.9","1.0")
		var ld_threshold_slider = $("<div id='ld_threshold_slider_label_container' style='margin-bottom: 5px;'><div id='ld_threshold_slider_label'>0.8</div></div><div id='ld_threshold_slider'></div>").insertAfter(ld_threshold_select);
		$("#ld_threshold_slider_label").css("position","relative");
		$("#ld_threshold_slider_label").css("width","20px");
		$("#ld_threshold_slider_label").css("margin-left",-($("#ld_threshold_slider_label_container").width()/7.5));
		$("#ld_threshold_slider_label").css("left", (8/9)*100+"%"); 
		$("#ld_threshold_slider").slider({
			min: 1,
			max: 10,
			step: 1, 
			value: ld_threshold_select[0].selectedIndex + 1,
			slide: function(event,ui) {
				ld_threshold_select[0].selectedIndex = ui.value -1;
				$("#ld_threshold_slider_label").text(ld_threshold_slider_labels[ui.value]);
				$("#ld_threshold_slider_label").css("left", ((ui.value)/(9))*100+"%");
				$("#ld_threshold_slider_label").css("margin-left",-($("#ld_threshold_slider_label_container").width()/7.5));
				}
			});
		$("#ld_threshold").hide();
		}
	);
	
	// Intialisiere Tabs
	$( "#tabs" ).tabs().hide();
	
	// SNP Autocomplete
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
	
	// Button für SNP pastebin
	if ($("#snpbinbox").is(":visible")) {
		$("#snps_variants_input").after("<button id='snps_variants_from_pastebin'>Paste from SNiPA's clipboard</button>");
		$("#snps_variants_from_pastebin").button().click(function() {
			$.ajax({
				  url: "backend/snipaSnpBin.php",
				  type: "POST",
				  data: { task: "read" },
				  dataType: "json",
				  success: function(data) {	
					$.each(data, function(i,v) {
						$("#snps_variants_input").val($("#snps_variants_input").val()+v.value+'\n');
					});
				  }
			  });
			}
		);
	}
		
	// Status-Variable für default-action bei click auf SNP
	snpclickdefault = 0;
	
	// Starte Anfrage wenn Submit button angeklickt
	$("#submit-button").click(startJob);
	
	// Event Trigger falls Enter-Key im Feld gedrückt wird zum starten des Jobs
	$("#ld_sentinel").keypress(function(event) { if (event.which == 13) { $("#submit-button").click(); } });
	$("#gene_symbol").keypress(function(event) { if (event.which == 13) { $("#submit-button").click(); } });
	$("#snps_region_begin").keypress(function(event) { if (event.which == 13) { $("#submit-button").click(); } });
	$("#snps_region_end").keypress(function(event) { if (event.which == 13) { $("#submit-button").click(); } });
	
});



function showResults(randid) {
	
	// Lade Block-Annotation
	$.ajax({ type: "GET", url: 'tmpdata/'+randid+'/block_anno.html', async: false, dataType: "text", success: function(data){ $("#annotationcontainer").html(data);  } });

	if ($("input[name=incl_funcann]:checked").val() == 1) {
		$('#tabs-header').append('<li class="plots-tabs-tab"><a href="#tabs-snpinfo">SNP annotations</a></li>');
		$('#tabs-body').append('<div id="tabs-snpinfo" class="plots-tabs-body-content"><span id="nosnpinfo">There are no SNP annotations that could be displayed.</span><div id="snpinfo-accordion"></div></div>');
		$( "#tabs" ).tabs("refresh");
		$( "#tabs" ).tabs({active: 0});
		
		// Lade SNP-Annotation
		$.ajax({ type: "GET", url: 'tmpdata/'+randid+'/snp_anno.html', async: false, dataType: "text", 
				 success: function(data){ 
					$("#snpinfo-accordion").accordion({collapsible: true, heightStyle: "content", event: "click"});
					$("#nosnpinfo").hide();
					$("#snpinfo-accordion").show();
					$("#snpinfo-accordion").html(data);
					$("#snpinfo-accordion").accordion("refresh");
					$("#snpinfo-accordion").accordion("option","active",0);
					$("#snpinfo-accordion h3 span").click(function() {return false;} );
			} 
		});
	}
		
	/* ENDE Funktionen nach erfolgreichem Berechnen der Input-Daten */
	$('#progress-dialog').dialog("close");
	$('#message').hide();
	$('#progressbar').hide();
	$('#form-container').hide();
	
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
	
	$('#tabs').show();
}



function startJob(){
	// Get ID for temp directory
	$.ajax({
		dataType: "text",
		async: false,
		cache: false,
		url: "backend/snipaTempdir.php",
		success: 
		function(randid) {
			if (randid.length == 15) {
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
				  url : "backend/snipaBlockAnnotation.php",
				  type: "POST",
				  data: {	id: randid, 
							genomerelease: $("select#dataset-genomerelease").val(), 
							referenceset: $("select#dataset-referenceset").val(), 
							population: $("select#dataset-population").val(), 
							annotation: $("select#dataset-annotation").val(), 
							snps_input_type: $("input[name=selection_snps]:checked").val(), 
							snps_variants: $("textarea#snps_variants_input").val(),
							snps_ld_sentinel: $("input#ld_sentinel").val(),
							snps_gene: $("input#gene_symbol").val(), 
							snps_region_chr: $("select#snps_region_chr").val(),
							snps_region_begin: $("input#snps_region_begin").val(),
							snps_region_end: $("input#snps_region_end").val(),
							rsquare: $("select#ld_threshold").val(),
							incl_funcann: $("input[name=incl_funcann]:checked").val() 
						},
				  dataType:"json",
				  
				  beforeSend: function() {},
					
				  success: function(phpresults) {
								updateStatus(randid);
								if (phpresults['ok'] !== "FAIL") { saveSelectDatasets(); showResults(randid); } else { $('#progress-dialog').dialog({ buttons: [ { text: "OK", click: function() { $(this).dialog("close"); } } ] });}
							}
				});
			}
		}
	});
}


// Liefert zu einem Tabiid den entsprechenden Index
function getIndexForId(tabsDivId, searchedId)
{
    var index = -1;
    var i = 0, els = $(tabsDivId).find("a");
    var l = els.length, e;
    while ( i < l && index == -1 )
    {
        e = els[i];
        if (searchedId == $(e).attr('href') )
        { index = i; }
        i++;
    };
    return index;
}

// Aktualisiert den Fortschrittsbalken
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



