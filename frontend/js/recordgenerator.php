<?php
require_once( "/home/metabolomics/snipa/web/backend/snipaMaprsid.php" );
require_once( "/home/metabolomics/snipa/web/backend/snipaMapGenes.php" );
require_once( "/home/metabolomics/snipa/web/backend/snipaTabix.php" );
require_once( "/home/metabolomics/snipa/web/backend/snipaConfig.php" );



$loginputfile = fopen("/home/metabolomics/snipa/web/frontend/js/result.log","r");
$res= fgets($loginputfile);
print($res."\n");
$rec = explode( "\t", rtrim( $res ) );
$Genomerelease = $rec[0];
$Referenceset  = $rec[1];
$Population    = $rec[2];
$Annotation    = $rec[3];
$flag          = $rec[4];
$JobId         = $rec[5];
$min           = $rec[6];
$max           = $rec[7];
//$senitalsnp    = $rec[8];
$forward       = $argv[1];

$start="";
if($forward=="0")
{
    $start = $min-50;
}   else {
    $start = $max;
}
fclose($loginputfile);

print($Genomerelease."\t".$Referenceset."\t".$Population."\t".$Annotation."\t".$flag."\t".$JobId."\t".$min."\t".$max."\n");


//$Genomerelease = $argv[1];
//$Referenceset  = $argv[2];
//$Population    = $argv[3];
//$Annotation    = $argv[4];
//$flag          = $argv[5];
//$JobId         = $argv[6];

$SnpsInputType = "snps";
$Rsquare       = 0.1;


$allok = TRUE;

$JobId = preg_replace( '/[^0-9]/', '', $JobId );
if ( strlen( $JobId ) != 15 ) {
    $allok = FALSE;
}

if ( $allok ) {
    $tmpdatadir = "tmpdata";
    $serverdir  = "/home/metabolomics/snipa/web/";
    $JobDir     = $serverdir . "/" . $tmpdatadir . "/" . $JobId;
    if ( !file_exists( $JobDir ) ) {
        $allok = FALSE;
    }
}
$ldflname = "ldplot_snps_in_ld_to_sentinel.txt";

function sortArrayByArray( $array, $orderArray )
{
    $ordered = array();
    foreach ( $orderArray as $key ) {
        if ( array_key_exists( $key, $array ) ) {
            $ordered[$key] = $array[$key];
            unset( $array[$key] );
        }
    }
    return $ordered + $array;
}

function calculatepairwise( $SnpsSentinelsArray, $ldhashmap, $zoomlevel, $flag )
{
    $Genomerelease = $GLOBALS['Genomerelease'];
    $Referenceset  = $GLOBALS['Referenceset'];
    $Population    = $GLOBALS['Population'];
    $Annotation    = $GLOBALS['Annotation'];
    $Rsquare       = $GLOBALS['Rsquare'];
    $JobDir        = $GLOBALS['JobDir'];
    
    
    if ( $flag == "1" ) {
        $Population = "qtr";
    }
    
    $flname = $JobDir . "/LDHeatMapZoom_" . $flag . "_" . $zoomlevel . ".csv";
    
    if ( file_exists( $flname ) ) {
        unlink( $flname );
    }
    $dlfile = fopen( $flname, 'a' );
    
    foreach ( $SnpsSentinelsArray as $snp ) {
        $tmp         = snipaMapRsid( $Genomerelease, $Referenceset, $Population, $snp );
        $Sentinels[] = array(
             'RSID' => $snp,
            'CHR' => $tmp['CHR'],
            'POS' => $tmp['POS'] 
        );
        unset( $tmp );
    }
    $sentinelsTotal   = count( $Sentinels );
    $sentinelsCount   = 0;
    $helptext_header  = array(
         "QRSID" => "Query SNP rsID",
        "RSID" => "Proxy SNP rsID",
        "CHR" => "Chromosome",
        "POS1" => "Sentinel SNP Position",
        "POS2" => "Proxy SNP Position",
        "DIST" => "Distance",
        "R2" => "LD r^2",
        "D" => "LD D",
        "DPRIME" => "LD D'",
        "MAJOR" => "Proxy Allele A",
        "MINOR" => "Proxy Allele B",
        "MAF" => "Allele B Frequency",
        "CMMB" => "Recombination Rate (CM/Mb)",
        "CM" => "Genetic distance (CM)" 
    );
    $dlfileinclheader = TRUE;
    $ResultsCount     = 0;
    foreach ( $Sentinels as $sentinel ) {
        $sentinelsCount++;
        $tabixresults = array();
        if ( ( $sentinel['CHR'] != "" ) && ( $sentinel['POS'] != "" ) ) {
            $tabixresults = snipaGetProxies( $Genomerelease, $Referenceset, $Population, $sentinel['CHR'], $sentinel['POS'], "", "", $Rsquare );
        }
        $sentinelrsids = array_map( function( $element )
        {
            return $element['RSID'];
        }, $Sentinels );
        for ( $i = 0; $i < count( $tabixresults ); $i++ ) {
            if ( in_array( $tabixresults[$i]['RSID'], $sentinelrsids ) == FALSE ) {
                $tabixresults[$i] = NULL;
            }
        }
        $tabixresults              = array_values( array_filter( $tabixresults ) );
        $sentinel['Genomerelease'] = $Genomerelease;
        $sentinel['Referenceset']  = $Referenceset;
        $sentinel['Population']    = $Population;
        $sentinel['Annotation']    = $Annotation;
        $ResultsCount              = $ResultsCount + count( $tabixresults );
        $downloadresults           = $tabixresults;
        
        
        foreach ( $downloadresults as $line ) {
            
            $line = array(
                 'QRSID' => $sentinel['RSID'] 
            ) + $line;
            if ( in_array( $line['POS1'], $ldhashmap, true ) && in_array( $line['POS2'], $ldhashmap, true ) ) {
                $line = sortArrayByArray( $line, array_keys( $helptext_header ) );
                if ( $dlfileinclheader ) {
                    $dlfileinclheader = FALSE;
                    fputcsv( $dlfile, array_keys( $line ), "\t" );
                }
                array_walk( $line, function( &$el )
                {
                    $el = html_entity_decode( $el );
                } );
                fputcsv( $dlfile, $line, "\t" );
            }
            
        }
    }
    fclose( $dlfile );
    
    echo exec( 'Rscript /home/metabolomics/snipa/web/backend/generatematrix_pyramidlogic.R ' . $flname . ' ' . $Population . ' ' . $zoomlevel . " " . $flag );
}


echo exec( 'rm -r /home/metabolomics/snipa/web/frontend/js/tmp');
echo exec( 'mkdir /home/metabolomics/snipa/web/frontend/js/tmp');


$ldhashmap          = array();
$SnpsSentinelsArray = array();
$file               = fopen($JobDir, "/ldplot_snps_around_sentinel.txt", "r" );
while ( !feof( $file ) ) {
    $line = fgets( $file );
    if ( $line ) {
        $rec = explode( ";", rtrim( $line ) );
        array_push( $SnpsSentinelsArray, $rec[0] );
        array_push( $ldhashmap, $rec[1] );
    }
}
fclose( $file );


$senitalfinal =array_slice($SnpsSentinelsArray, $start, 50);
$coordinatefinal =array_slice($ldhashmap, $start, 50);


calculatepairwise( $senitalfinal, $coordinatefinal, "0", "0" );
calculatepairwise( $senitalfinal, $coordinatefinal, "0", "1" );

$logfile = fopen("/home/metabolomics/snipa/web/frontend/js/result.log", "w") or die("Unable to open file!");

fwrite($logfile, $Genomerelease."\t".$Referenceset."\t".$Population."\t".$Annotation."\t".$flag."\t".$JobId."\t".$start."\t".($start+50)."\n");
fclose($logfile);
?>
	
