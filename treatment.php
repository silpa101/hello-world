<?php
session_start();
include 'functions/config.php';
include 'functions/log.php';
include 'functions/patient.php';
include 'functions/treatment.php';
include 'functions/herbs.php';
$patientprofileid ='';
$patientfname='';
$patientlname ='';
$patientdob ='';
$welcomeuser = '';
$patientuserid = '';
$caseid='';
$chiefcomplaint ='';
$treatmentdate = '';
$othercomplaint ='';
$effectslasttreatment = '';
$lasttreatmentdate = '';
$memberstatus ='';
$canvaspath = "";
$canvasdata = '';
$notes='';
$errcasemsg1= $errcasemsg2 = $errcasemsg3 = $errcasemsg4 = '';
$historyid = $appetite = $breath = $digestion = $hotcold = $stool = $allergies= $sweat = $urine = $exercise = $diet = $sleep = $thirst = $menses = $emotions = $medication = $trxsupple = $effects = '';
$tshape = $tcoating = $tcolor = $tunder = $pheart = $plung = $pliver = $pspleen  = $plkidney = $prkidney = $height = $weight = $resp = $heartrate = $bp = $oyy = $blood = $hg = $phlem = $tqi = $zangfu = $consti = $shem = '';
 $tplan = $tdosage  = '';
$sharecase = $errillness = $arrillness= $illnesslist = $errcomplaint = $errdate = $illness = '' ;
$tbpoints = $tbherbs = $tbformula ='';
$herblist = $pointlist = $formulalist = '';
$oldhistory = $tcmdiag = $biomed = $icd9 = $icd9values =  $caserefid = $casecount ='';
$compsugges = $pulsesugges = $suggestions= "<td>&nbsp</td><td>&nbsp;</td><td>&nbsp;</td>";
$arricd9  = array();
$diagres = false;
$observimagearr = array();
$diagimagearr = array();
if(isset($_SESSION['memberstatus']))
$memberstatus = $_SESSION['memberstatus'];
else
$memberstatus ='';

if(isset($_SESSION['userid']) & $_SESSION['usertype'] == 1 )
{    
    
    $obj_config =  new server_config();
    $obj_patient = new patient();
    $obj_log = new Log();
    $welcomeuser = $_SESSION['fstname'].' '.$_SESSION['lstname'];
   
    if($_SERVER['REQUEST_METHOD'] == "GET" &  filter_has_var(INPUT_GET , 'pid'))
    {  
        $ptid = filter_input(INPUT_GET,'pid',FILTER_VALIDATE_INT);
        $patient = $obj_patient->getpatientprofilesummary($ptid);
        $canvaspath = CANVASPATH ;
        if($patient != '')
        {
           
            $patientprofileid = $patient['profile_id'];
            $patientfname = $patient['firstname'];
            $patientlname = $patient['lastname'];
            if($patient['appointmentdate'] != null & $patient['appointmentdate'] != '')
            {
                 $dt = new DateTime($patient['appointmentdate']);
                  $lasttreatmentdate = $dt->format("m/d/Y");
            }
            else
            {    $lasttreatmentdate = '';  }
           
            if(!isset($_SESSION['sharecase']))
       {
           $_SESSION['sharecase'] =   $obj_case->getpractitionersharecase();
       }
       else if($_SESSION['sharecase'] == '')
       {
           $_SESSION['sharecase'] =   $obj_case->getpractitionersharecase();
       }
         if($_SESSION['sharecase'] == 1)
         {
             $sharecase = 'checked';
         }
         
        }
        else
        {
            header("Location:patientdetails.php?ptid=".$ptid);
        }
      
   
     
    }
    else if($_SERVER['REQUEST_METHOD'] == "POST" )
    {
        try
        {
            if(isset($_POST['patientid']) & $_POST['patientid'] != '')
            {
            $ptid = $_POST['patientid'];
            $patientprofileid = $_POST['txtpatientprofileid'];       
            $patientfname = $_POST['txtfname'];
            $patientlname=$_POST['txtlname'];
            $lasttreatmentdate = $_POST['txtldate'];
            $treatmentdate = $_POST['txtdate'];
            $chiefcomplaint = $_POST['txtchiefcomplaint'];  
            $othercomplaint = $_POST['txtothercomplaint'];
            $effectslasttreatment = $_POST['txtillness'];
            $notes = $_POST['canvasnotes'];
            if(isset($_POST['chksharemycase']) & $_POST['chksharemycase'] == "1")
            {
                $sharecase  = 'checked';
                $share = 1;
            }
            else
            {
                 $sharecase  = '';
                $share = 0;
            }
            
            $observimagearr= $_POST['observimages'];
            $diagimagearr = $_POST['diagimages'];
            
            if(trim($_POST['canvasimage']) != '')
             $canvasdata = $_POST['canvasimage'];
            
            $appetite = $_POST['txtappetite'];
            $breath = $_POST['txtbreathing'];
            $digestion = $_POST['txtdigestion'];
            $hotcold = $_POST['txthotcold'];
            $stool = $_POST['txtstool'];
            $allergies = $_POST['txtallergies'];
            $sweat= $_POST['txtsweat'];
            $urine = $_POST['txturine'];
            $exercise = $_POST['txtexercise'];
            $diet = $_POST['txtdiet'];
            $thirst = $_POST['txtthirst'];
            $sleep = $_POST['txtsleep'];
            $menses = $_POST['txtmenses'];
            $emotions = $_POST['txtemotions'];
            $medication = $_POST['txtmedtaken'];
             $trxsupple = $_POST['txtrxsuppl'];
             $effects = $_POST['txtmedeffects'];
             $tshape = $_POST['txtshape'];
             $tcolor = $_POST['txtbodycolor'];
             $tcoating = $_POST['txtcoating'];
             $tunder = $_POST['txtunderside'];
             $pliver = $_POST['txtliver'];
             $pheart = $_POST['txtheart'];
             $plkidney = $_POST['txtlkidney'];
             $plung = $_POST['txtlung'];
             
             $pspleen = $_POST['txtspleen'];
             $prkidney = $_POST['txtrkidney'];
             $height = $_POST['txtheight'];
             $weight = $_POST['txtweight'];
             $resp = $_POST['txtresp'];
             $bp = $_POST['txtbp'];
             $heartrate = $_POST['txtheartrate'];
             $consti= $_POST['txtconst'];
             $shem = $_POST['txtshem'];
             $tqi = $_POST['txtqi'];
             $phlem = $_POST['txtphlem'];
             $blood = $_POST['txtblood'];
             $hg = $_POST['txthg'];
             $zangfu = $_POST['txtzangfu'];

            $tcmdiag = $_POST['txttcmd'];
            $biomed = $_POST['txtbiomed'];
            $tplan = $_POST['txtplan'];
            $tdosage = $_POST['txtdosage'];
                if(isset($_POST['hdherb']))
               {
                 $herblist = $_POST['hdherb'];
               }
         
              if(isset($_POST['hdpoints']))
              {
                  $pointlist = $_POST['hdpoints'];
              }
               if(isset($_POST['hdformula']))
               {
                   $formulalist = $_POST['hdformula'];
               }
               
             
             
            if(trim($chiefcomplaint)!= '' & trim($treatmentdate)!= '' )
            {
                $tempdiagarr = $diagimagearr;
                $tempobservarr = $observimagearr;
                for($i=count($diagimagearr) -1;$i<4; $i++)
                {
                    
                         $tempdiagarr[$i] = '';
                    
                   
                         $tempobservarr[$i] = '';
                  
                }
                                
                $obj_case = new treatment();
                if( !isset($_POST['txtcaseid']) || $_POST['txtcaseid'] == '')
                {
                    $caseid =  $obj_case->definecase($ptid,$_SESSION['userid'], $chiefcomplaint,$treatmentdate,$lasttreatmentdate,$effectslasttreatment,$othercomplaint, $share);

                    if($caseid != 0)
                    {
                        $result1 = $result2 = $result3 = false;
                        
                        $canvaspath = getcanvasimage($ptid ,$caseid);
                        
                        $result1 = $obj_case->savediagnosisdata($caseid,$canvaspath,$tempdiagarr);
                        if($canvaspath =='')
                        {
                            $canvaspath = $canvasdata;
                            $errcasemsg1 = "Error saving diagnosis image";
                        }
                        if($result1)
                           $result2 =  $obj_case->saveobservation($caseid,$tempobservarr);
                        
                        if($result1 & $result2)
                          $result3 = $obj_case->savetreatment($caseid);
                        
                        if(!$result1 || !$result2 || !$result3)
                        {
                             $obj_case->deletetreatemnt($caseid);
                             
                            $errcasemsg1 = "Error saving data. Try again";
                        }
                         else {
                             
                            
                             $errcasemsg1 = "Treatment data saved";
                         }
                    }
                    else
                    {
                        $errcasemsg1 = "Error saving data.Try again";
                    }
                }
                else
                {
                    
                        $caseid = $_POST['txtcaseid'];
                    
                    
                        $canvaspath = getcanvasimage($ptid ,$caseid);
                        
                        $result1  = $obj_case->updatedefinecase($ptid,$_SESSION['userid'], $chiefcomplaint,$treatmentdate,$lasttreatmentdate,$effectslasttreatment,$othercomplaint, $share , $caseid);
                        $result2 = $obj_case->savediagnosisdata($caseid,$canvaspath,$tempdiagarr);
                        $result3 =  $obj_case->saveobservation($caseid,$tempobservarr);
                        $result4 = $obj_case->savetreatment($caseid);
                    
                    if(!$result1 || !$result2 || !$result3 || !$result4 )
                    {
                        $errcasemsg1 = "Error saving data.Try again";
                    }
                        
                }
                
                
                $suggarray = getherbsuggestions( $chiefcomplaint,$pheart,$pliver,$plkidney,$plung,$pspleen,$prkidney,$tshape,$tcolor,$tcoating,$tunder);
                $compsugges = $suggarray[0];
                $pulsesugges = $suggarray[1];
                $suggestions = $suggarray[2];
            }
            else
            {
               
                $errcasemsg1 = "Chief complaint and Treatment date  is required";
            }
            
       
         $tbherbs = constructhtmltable($herblist , 1);        
         $tbformula = constructhtmltable($formulalist , 2);
         $tbpoints= constructhtmltable($pointlist , 3);
           
            }
            else
            {
                 header("Location:mypatients.php?e=treatment");
        
            }
                
        }
        catch (Exception $e)
        {
            $obj_log->LogText("Exception in createtreatment .".$e->getMessage(), "Exception");
            $errcasemsg1 = "Error saving treatemnt data. Try later";
            
        }
            
    }
    else {
        
         header("Location:mypatients.php?e=treatment");
        
    }
        
       if($canvaspath == '') 
           $canvaspath =  "../assets/img/humanfigure.jpg";
         
    
}
else
{
    header("Location:../logout.php");
}

function getherbsuggestions( $complaint,$pheart,$pliver,$plkidney,$plung,$pspleen,$prkidney,$tshape,$tcolor,$tcoating,$tunder)
{
    
        $pulsecondition = '';
$tonguecondition ='';
$htoungecondition = '';
if($pheart !='')
    $pulsecondition.= " or pluse like '%".$pheart."%' ";

if($pliver != '')
    $pulsecondition.= " or pluse like '%".$pliver."%' ";
if($plkidney !='')
    $pulsecondition.= " or pluse like '%".$plkidney."%' ";
if($plung !='')
    $pulsecondition.= " or pluse like '%".$plung."%' ";

if($pspleen!='')
    $pulsecondition.= " or pluse like '%".$pspleen."%' ";

if($prkidney !='')
    $pulsecondition.= " or pluse like '%".$prkidney."%' ";

if($pulsecondition != '')
    $pulsecondition = substr ($pulsecondition, 3);

if($tshape != '')
{
    $tonguecondition.= " or tounge like '%".$tshape."%' ";
    $htoungecondition.= " or thermalproperties_tastes like '%".$tshape."%' ";
}
if($tcolor != '')
{
    $tonguecondition.= " or tounge like '%".$tcolor."%' ";
     $htoungecondition.= " or thermalproperties_tastes like '%".$tcolor."%' ";
}
if($tcoating != '')
{
    $tonguecondition.= " or tounge like '%".$tcoating."%' ";
     $htoungecondition.= " or thermalproperties_tastes like '%".$tcoating."%' ";
}
if($tunder != '')
{
    $tonguecondition.= " or tounge like '%".$tunder."%' ";
     $htoungecondition.= "or thermalproperties_tastes like '%".$tunder."%' ";
}
         $obj_suggest = new herbs();

        
          $sugg1  =  $obj_suggest->getsuggestionbycomplaint($complaint);
            
           $sugg2=  $obj_suggest->getsuggestionbypulse($pulsecondition, $tonguecondition , $htoungecondition);
            
          $sugg3 = $obj_suggest->getsuggestionbycomplaintpulse($pulsecondition, $tonguecondition, $complaint , $htoungecondition);
         
return array($sugg1,$sugg2,$sugg3);
}


 function constructhtmltable($list , $type)
{
   $htmltable = '';

   $arrlist = explode("@", $list);
   for($i =0 ; $i< count($arrlist); $i++)
   {
       $itemlist = explode("$", $arrlist[$i]);
       if(count($itemlist) > 2)
       {
          $htmltable.="<tr><td>".$itemlist[0].'</td><td>'.$itemlist[1].'</td><td><button  type="button"  onclick="deleteitem('.$type.',\''.$itemlist[2].'\')" class="btnbg" >Delete</button>';
       }
   }


   return $htmltable;
}


function getcanvasimage($ptid,$caseid)
{
           $tempfilename = '';
           
               //check if destination folder exists
                $destpath = PATIENTFILEPATH.$ptid."/treatment/";
                if(!file_exists($destpath))
                {

                    mkdir($destpath,0777,true);

                }
           
               
            if(isset($_POST['canvasimage']) & $_POST['canvasimage'] != '')
            { 
              
                if($_POST['canvaspath'] == '' || $_POST['canvaspath'] == CANVASPATH )
               {
                    $dt = new DateTime(); 
                    $tempfilename =$destpath.$caseid."_".$dt->getTimestamp()."canvas.jpg";
               }
               else
               {
                   $tempfilename = $_POST['canvaspath'];
               }
                
               if(file_exists($tempfilename))                   
                   unlink($tempfilename);
               
                //Save image to folder                   
                $img = str_replace('data:image/png;base64,', '',$_POST['canvasimage']);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                
                $success = file_put_contents( $tempfilename, $data);
                if(!$success)
                {
                    $tempfilename = '';
                }
               
             }
             else
             {
                 if($_POST['canvaspath'] != '' || $_POST['canvaspath'] != CANVASPATH )
               {
                     $tempfilename = $_POST['canvaspath'];
               }
             }
             
             
             return $tempfilename ;
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->  
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->  
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->  
<head>
    <title>Barefoot Taoists | Welcome...</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
    <!-- CSS Global Compulsory-->
    <link rel="stylesheet" href="../assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/headers/header1.css">
    <link rel="stylesheet" href="../assets/plugins/bootstrap/css/bootstrap-responsive.min.css">
    <link rel="stylesheet" href="../assets/css/jquery-ui.css" >
    <link rel="stylesheet" href="../assets/css/responsive.css">
      
    <!-- CSS Implementing Plugins -->    
    <link rel="stylesheet" href="../assets/plugins/font-awesome/css/font-awesome.css">
    <link rel="icon" type="image/png" href="../assets/img/favicon.ico">
    <style>
        .modal {
    display:    none;
    position:   fixed;
    z-index:    1000;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url('../assets/img/loading.gif') 
                50% 50% 
                no-repeat;
}
    </style>
    </head>
    <body>
        <?php include 'headeracupuncturist.html' ?>
<!--=== End Header ===-->    

<div class="breadcrumbs margin-bottom-40">
    <div class="container">
        <h1 class="pull-left" style="font-weight:bold;font-family: Arial,Helvetica,sans-serif;
                                color: #8DB600;"><i class="icon-file-text"></i>&nbsp;New Treatment</h1>

    </div><!--/container-->
</div><!--/breadcrumbs-->
      <!--=== Content Part ===-->
<div class="container">
<form method="POST" class="form-horizontal">
    <div style="height:10px;"></div>
<!--Search Block-->
<!-- <div class="row" style="z-index:1000"  >            -->
     <div class="tab-v1">
              
                <ul class="nav nav-tabs">
                    <li class="active" id="define"><a href="#complaintdata" data-toggle="tab">Chief Complaint & Diagnosis Data</a></li>                    
                    <li id="observationt" class=""><a href="#observation" data-toggle="tab">Observation & Treatment Suggestions</a></li>
                      <li id="treatmentt" class=""><a href="#treatment" data-toggle="tab">Assessment & Treatment</a></li>
                    
                </ul>     
          </div> 
  <div class="tab-content">  
             <input type="hidden"  value="<?php echo $caseid ?>"  name="txtcaseid"  id="caseid" />
             <input type="hidden" name="canvaspath" id="canvaspath" value="<?php if($canvaspath != CANVASPATH ) echo $canvaspath; ?>"  />
             <input type="hidden"    name="canvasimage"  id="canvasimage" />
             <input type="hidden"  value="<?php echo $memberstatus ?>"  name="memberstatus"  id="memberstatus" />
             <input type="hidden"  value="<?php echo $ptid ?>"  name="patientid"  id="patientid" />
              <input type="hidden"    name="diagimages[]"  id="diagimage" />
              <input type="hidden"    name="observimages[]"  id="observimage" />
             
      <!-- Define Case -->
   <div class="tab-pane active" id="complaintdata">          
      <div class="panel-body">   
            
            <div class="margin-bottom-20"></div> 
              <div class="col-md-14" > 
                <div class="form-group">
                     <label  class="col-lg-2 control-label">Patient ID</label>
                  <div class="col-lg-3">
                      <input type="text" class="form-control" id="txtpatientprofileid"  name ="txtpatientprofileid" value="<?php echo $patientprofileid;?>"  readonly="true" >
                   
                   </div>
                    <label  class="col-lg-2 control-label"> Last Treatment Date (mm/dd/yyyy)</label>
                  <div class="col-lg-3">
                      <input type="text" class="form-control" id="txtldate"  name ="txtldate"    value="<?php echo $lasttreatmentdate;?>" >
                      <label id="errldate" style="color:red"></label>
                   </div>
              </div>   
                
             <div class="form-group">
                     <label  class="col-lg-2 control-label">First Name </label>
                  <div class="col-lg-3">
                      <input type="text" class="form-control" id="txtfname"  name ="txtfname" value="<?php echo $patientfname;?>"  readonly="true" >
                   
                   </div>
                   
                     <label  class="col-lg-2 control-label">Last Name</label>
                  <div class="col-lg-3">
                      <input type="text" class="form-control" id="txtlname"  name ="txtlname" value="<?php echo $patientlname;?>"  readonly="true" >
              </div>   
                </div>
                       
                  <div class="form-group">
                      <label  class="col-lg-2 control-label"> Treatment Date<span class="color-red">*</span> (mm/dd/yyyy)</label>
                  <div class="col-lg-3">
                      <input type="text" class="form-control" id="txtdate"  name ="txtdate"  onblur="return checkDate();" value="<?php echo $treatmentdate;?>" >
                      <label id="errdate" style="color:red"></label>
                   </div>
                      
                  </div>
                    <div class="form-group">
                     <label  class="col-lg-2 control-label">Chief Complaint<span class="color-red">*</span></label>
                  <div class="col-lg-6">
                      <textarea class="form-control" id="complaint"  name ="txtchiefcomplaint" rows="3"  autocomplete="off" onblur="updatesuggestions();"   ><?php echo $chiefcomplaint; ?></textarea>
                    
                       <label id="errcomplaint" style="color:red"></label>
              </div> 
                    
                   
                    
              </div> 
                  
                
            <div class="form-group">
                     <label  class="col-lg-2 control-label">Effect after last treatment</label>
                  <div class="col-lg-4" style="min-width:150px;">
 
                      <textarea rows="3" id="txtillness" name="txtillness"  autocomplete="off"  class="form-control"><?php echo $effectslasttreatment; ?></textarea>
 
  </div>
                        
                      <label id="errillness" style="color:red"></label>
                     
                   </div>
                       <label  class="col-lg-2 control-label">Other Complaints</label>
                  <div class="col-lg-3">
                      <textarea class="form-control" id="othercomplaint"  rows="3" name ="txtothercomplaint"  autocomplete="off" ><?php echo $othercomplaint; ?></textarea>
                    
                   </div>
              </div> 
<!--          <div class="form-group">
                     <label  class="col-lg-4 control-label">Number of treatment</label>
               <div class="col-lg-6">
                     <input type="text" class="form-control" id="txttreatmentno"  name ="txttreatmentno" value="" readonly >
                   </div>
              </div>          -->
            </div>
           
                   
                     <div class="margin-bottom-50"></div> 
                     <div class="col-md-14">
                            <div class="col-lg-8" style="max-width:600px">   <h4  class="pull-left color-green"><strong>Diagnosis Data </strong></h4>
                           
       <button id="clearBtn" class="btnbg" type="button" style="float:right;margin-top:5px">Clear Markings</button>   
                </div>  
                          <div class=" row col-md-14" style="clear:both">
          
            <div class="col-md-7">
                <canvas id="myCanvas" width="550px" height="550px" style="border:1px solid black;border-radius:25px">
  <p>This is fallback content 
  for users of assistive technologies 
  or of browsers that don't have 
  full support for the Canvas API.</p>
</canvas>
             
            </div>
                <div class="col-md-5">
                       <label class="col-lg-2 control-label" >Notes </label>
                <div class="col-lg-10" style="clear: both">
                <textarea class=" form-control" id="canvasnotes" name="canvasnotes" rows="10" ><?php echo $notes ; ?></textarea>
                </div>
                
                    <div class="col-md-12" style="margin-top:10px;clear:both">
                          <div class="col-md-12" ><img src="../assets/img/webcam.png"/> <input type="button" onclick="javascript:void(start_diagnosissnapshot())" class="btnbg" value="Turn on WebCam"></div>
                          <div class="col-md-12" id="divcamera" style="display:none;width:350px; height:260px;clear:both">
                           <div id="my_camera" style="width:350px; height:260px;border:1px solid black;border-radius:25px;padding:10px" > 
                             <?php echo $webcampicture ?>
                           </div>
                              <input type="button"  onclick="capturediagnosispicture()" class="btnbg" value="Take snapshot" >
                          </div>
                         
                        </div>
                        <div class="col-md-12" style="clear:both;margin:10px 0;">
                             <div id="my_result" style="border:0px solid black;border-radius:25px;margin-top:20px" ></div>
                          </div>
                </div>
            </div>
                    <div class=" row col-md-12" style="clear:both;margin-top:10px">
                       
                             
                 <div class="form-group" style="clear:both;">
                     <label  class="col-lg-2 control-label">Appetite</label>
                  <div class="col-lg-4">
                     <textarea class="form-control" id="appetite"  name ="txtappetite" rows="3"  ><?php echo $appetite ; ?></textarea>
                   </div>
                     <label  class="col-lg-2 control-label">Breathing</label>
                  <div class="col-lg-4">
                     <textarea  class="form-control" id="breath"  name ="txtbreathing" rows="3" ><?php echo $breath ; ?></textarea>
                   </div>
              </div> 
                 <div class="form-group">
                     <label  class="col-lg-2 control-label">Digestion</label>
                  <div class="col-lg-4">
                     <textarea  class="form-control" id="digest"  name ="txtdigestion" rows="3" ><?php echo $digestion ; ?></textarea>
                   </div>
                     <label  class="col-lg-2 control-label">Hot/Cold</label>
                  <div class="col-lg-4">
                     <textarea  class="form-control" id="hotcold"  name ="txthotcold" rows="3" ><?php echo $hotcold ; ?></textarea>
                   </div>
              </div> 
                 <div class="form-group">
                     <label  class="col-lg-2 control-label">Stool</label>
                  <div class="col-lg-4">
                     <textarea  class="form-control" id="stool"  name ="txtstool" rows="3" ><?php echo $stool ; ?></textarea>
                   </div>
                     <label  class="col-lg-2 control-label">Allergies</label>
                  <div class="col-lg-4">
                     <textarea  class="form-control" id="allergy"  name ="txtallergies" rows="3" ><?php echo $allergies ; ?></textarea>
                   </div>
              </div> 
                 <div class="form-group">
                     <label  class="col-lg-2 control-label">Sweating</label>
                  <div class="col-lg-4">
                     <textarea  class="form-control" id="sweat"  name ="txtsweat" rows="3" ><?php echo $sweat ; ?></textarea>
                   </div>
                     <label  class="col-lg-2 control-label">Exercise</label>
                  <div class="col-lg-4">
                     <textarea  class="form-control" id="exercise"  name ="txtexercise" rows="3" ><?php echo $exercise ; ?></textarea>
                   </div>
              </div>                     
                 <div class="form-group">
                     <label  class="col-lg-2 control-label">Urination</label>
                  <div class="col-lg-4">
                     <textarea  class="form-control" id="urine"  name ="txturine" rows="3" ><?php echo $urine ; ?></textarea>
                   </div>
                     <label  class="col-lg-2 control-label">Diet</label>
                  <div class="col-lg-4">
                     <textarea  class="form-control" id="diet"  name ="txtdiet" rows="3" ><?php echo $diet ; ?></textarea>
                   </div>
              </div> 
                 <div class="form-group">
                     <label  class="col-lg-2 control-label">Thirst</label>
                  <div class="col-lg-4">
                     <textarea  class="form-control" id="thirst"  name ="txtthirst" rows="3" ><?php echo $thirst ; ?></textarea>
                   </div>
                     <label  class="col-lg-2 control-label">Sleep</label>
                  <div class="col-lg-4">
                     <textarea  class="form-control" id="sleep"  name ="txtsleep" rows="3" ><?php echo $sleep ; ?></textarea>
                   </div>
              </div>
                 <div class="form-group">
                     <label  class="col-lg-2 control-label">Emotions</label>
                  <div class="col-lg-4">
                     <textarea  class="form-control" id="emotion"  name ="txtemotions" rows="3" ><?php echo $emotions ; ?></textarea>
                   </div>
                     <label  class="col-lg-2 control-label">Menses</label>
                  <div class="col-lg-4">
                     <textarea  class="form-control" id="menses"  name ="txtmenses" rows="3" ><?php echo $menses ; ?></textarea>
                   </div>
              </div> 
                 <div class="form-group">
                     <label  class="col-lg-2 control-label">Medication Taken</label>
                  <div class="col-lg-4">
                     <textarea  class="form-control" id="medtaken"  name ="txtmedtaken" rows="4" ><?php echo $medication ; ?></textarea>
                   </div>
                     <label  class="col-lg-2 control-label">RX-Supplement</label>
                  <div class="col-lg-4">
                     <textarea  class="form-control" id="trxsupp"  name ="txtrxsuppl" rows="4" ><?php echo $trxsupple ; ?></textarea>
                   </div>
              </div> 
                 <div class="form-group">
                     <label  class="col-lg-2 control-label">Other</label>
                  <div class="col-lg-4">
                     <textarea  class="form-control" id="medef"  name ="txtmedeffects" rows="4" ><?php echo $effects ; ?></textarea>
                   </div>
            </div>
           
                        
                  
                      
                    </div>
                  
                     </div>
                      <div class="margin-bottom-50"></div> 
                      
                    <div class="margin-bottom-50"></div> 
                    <div class="col-md-6" >
             <div class="form-group  text-right">  
                 <label  style="color:red" id="errcomplaintsave" ><?php echo $errcasemsg1 ?></label>
             <input class="btnbg" type="submit" name="savecase1"  id ="savecase" onclick="return validatedefinecase();" value="Save" /> 
            <input class="btnbg" type="button" id="next1" name="savecase3" value="Next" onclick="validatedefinecasenext();" />            
        </div>
       <div class="margin-bottom-30"></div> 
                    </div>
  
      </div>
  
     <!-- End Define case -->
     
     

     <!-- Observation -->
     <div class="tab-pane " id="observation"> 
         <div calss="col-md-14" >
         <div class="col-md-8">
         <div  id=divtongue"">
              <div class="margin-bottom-20"></div> 
          <div class="col-lg-3 ">   <h4  class="pull-left color-green"><strong>Tongue</strong></h4> </div>
       <div class="margin-bottom-10"></div> 
         <div class="form-group">
                     <label  class="col-lg-2 control-label">Shape</label>
                  <div class="col-lg-3">
                     <textarea  class="form-control" id="txtshape"  name ="txtshape" rows="3"   onchange="updatesuggestions();" ><?php echo $tshape; ?></textarea>
                   </div>
                     <label  class="col-lg-2 control-label">Coating</label>
                  <div class="col-lg-3">
                     <textarea  class="form-control" id="txtcoating"  name ="txtcoating" rows="3"  onchange="updatesuggestions();"><?php echo $tcoating; ?></textarea>
                   </div>
              </div>
         <div class="form-group">
                     <label  class="col-lg-2 control-label">Body Color</label>
                  <div class="col-lg-3">
                     <textarea  class="form-control" id="txtbodycolor"  name ="txtbodycolor" rows="3" onchange="updatesuggestions();"><?php echo $tcolor; ?></textarea>
                   </div>
                     <label  class="col-lg-2 control-label">Underside</label>
                  <div class="col-lg-3">
                     <textarea  class="form-control" id="txtunderside"  name ="txtunderside" rows="3" onchange="updatesuggestions();"><?php echo $tunder; ?></textarea>
                   </div>
              </div>
     </div>
         
               <div id="divpulse">
              <div class="margin-bottom-20"></div> 
          <div class="col-lg-3 ">   <h4  class="pull-left color-green"><strong>Pulse</strong></h4> </div>
       <div class="margin-bottom-10"></div> 
         <div class="form-group">
                     <label  class="col-lg-2 control-label">Heart</label>
                  <div class="col-lg-3">
                     <textarea  class="form-control" id="txtheart"  name ="txtheart" rows="3" onchange="updatesuggestions();"><?php echo $pheart; ?></textarea>
                   </div>
                     <label  class="col-lg-2 control-label">Lung</label>
                  <div class="col-lg-3">
                     <textarea  class="form-control" id="txtlung"  name ="txtlung" rows="3" onchange="updatesuggestions();"><?php echo $plung; ?></textarea>
                   </div>
              </div>
         <div class="form-group">
                     <label  class="col-lg-2 control-label">Liver</label>
                  <div class="col-lg-3">
                     <textarea  class="form-control" id="txtliver"  name ="txtliver" rows="3" onchange="updatesuggestions();"><?php echo $pliver; ?></textarea>
                   </div>
                     <label  class="col-lg-2 control-label">Spleen</label>
                  <div class="col-lg-3">
                     <textarea  class="form-control" id="txtspleen"  name ="txtspleen" rows="3" onchange="updatesuggestions();"><?php echo $pspleen; ?></textarea>
                   </div>
              </div>
       <div class="form-group">
                     <label  class="col-lg-2 control-label">Kidney Yin</label>
                  <div class="col-lg-3">
                     <textarea  class="form-control" id="txtlkidney"  name ="txtlkidney" rows="3" ><?php echo $plkidney ?></textarea>
                   </div>
                     <label  class="col-lg-2 control-label">Kidney Yang</label>
                  <div class="col-lg-3">
                     <textarea  class="form-control" id="txtrkidney"  name ="txtrkidney" rows="3" ><?php echo $prkidney ?></textarea>
                   </div>
              </div>
     </div>
         </div>
         <div class="col-md-4">
             <div ><img src="../assets/img/webcam.png"/> <input type="button" onclick="start_treatmentsnapshot()" class="btnbg" value="Turn on webcam"></div>
                          <div style="border:1px solid black;border-radius:25px;display:none" id="divcamera1">
                           <div id="my_camera1" style="width:100%; height:150px;" >                            
                           </div>
                            <br/> <input type="button"  onclick="capturetreatmentpicture()" class="btnbg" value="Take snapshot" >
                          </div>
                          <div style="border:0px solid black;border-radius:25px;margin-top:20px">
                             <div id="my_result1"  ></div>
                          </div>
         </div>
         </div>        <div id="divgeneralexam" >
              <div class="margin-bottom-20"></div> 
          <div class="col-lg-3 ">   <h4  class="pull-left color-green"><strong>General Examination</strong></h4> </div>
       <div class="margin-bottom-10"></div> 
       <div class="panel panel-default col-lg-5" style="margin-right:10px;min-height:258px;padding-top:30px" >
         <div class="form-group">
                     <label  class="col-lg-2 control-label">Height</label>
                  <div class="col-lg-4">
                     <input type="text"  class="form-control" id="txtheight"  name ="txtheight"  value=" <?php echo $height ?>"  ></input>
                   </div>
                     <label  class="col-lg-2 control-label">Weight</label>
                  <div class="col-lg-4">
                      <input type="text"   class="form-control" id="txtweight"  name ="txtweight"  value=" <?php echo $weight ?>" ></input>
                   </div>
              </div>
         <div class="form-group">
                     <label  class="col-lg-2 control-label">Respiration</label>
                  <div class="col-lg-4">
                      <input type="text"  class="form-control" id="txtresp"  name ="txtresp" value="<?php echo $resp ?>" ></input>
                   </div>
                     <label  class="col-lg-2 control-label">Blood Pressure</label>
                  <div class="col-lg-4">
                     <input type="text" class="form-control" id="txtbp"  name ="txtbp" value="<?php echo $bp?>" ></input>
                   </div>
              </div>
       <div class="form-group">
                     <label  class="col-lg-2 control-label">Heart Rate</label>
                  <div class="col-lg-4">
                      <input type="text"  class="form-control" id="txtheartrate"  name ="txtheartrate" value="<?php echo $heartrate?>"  ></input>
                   </div>
                     
              </div>
     </div>
       <div class="panel panel-default col-lg-6" style="padding-top:10px"  >
         <div class="form-group">
                     <label  class="col-lg-4 control-label">General Constitution</label>
                  <div class="col-lg-7">
                     <textarea  rows="2"   class="form-control" id="txtconst"  name ="txtconst"  ><?php echo $consti ?></textarea>
                   </div>
                     
              </div>
         <div class="form-group">
                     <label  class="col-lg-4 control-label">Shen</label>
                  <div class="col-lg-7">
                     <textarea  rows="2"   class="form-control" id="txtshem"  name ="txtshem"  ><?php echo $shem ?></textarea>
                   </div>
                     
              </div>
          
           <div class="form-group">
                     <label  class="col-lg-4 control-label">Qi</label>
                  <div class="col-lg-3">
                     <input type="text" class="form-control" id="txtoi"  name ="txtqi"  value="<?php echo $tqi ?>">  </input>
                   </div>
                     <label  class="col-lg-2 control-label">Blood</label>
                  <div class="col-lg-3">
                     <input type="text" class="form-control" id="txtblood"  name ="txtblood"  value=" <?php echo $blood ?>" > </input>
                   </div>
              </div>
           <div class="form-group" style="display:none">
                     <label  class="col-lg-4 control-label">Phlegm</label>
                  <div class="col-lg-3">
                     <input type="text" class="form-control" id="txtphlem"  name ="txtphlem" value=" <?php echo $phlem ?>" > </input>
                   </div>
                     <label  class="col-lg-2 control-label">HG</label>
                  <div class="col-lg-3">
                     <input type="text" class="form-control" id="txthg"  name ="txthg"  value="<?php echo $hg ?>">  </input>
                   </div>
              </div>
       <div class="form-group">
                     <label  class="col-lg-4 control-label">Specific Zangfu Conditions</label>
                  <div class="col-lg-7">
                     <textarea  rows="2"   class="form-control" id="txtzangfu"  name ="txtzangfu"  >  <?php echo $zangfu ?></textarea>
                   </div>
                     
              </div>
       </div>
     </div>
         <div id="divsuggestion" >
             <div class="margin-bottom-20"></div> 
          <div class="col-lg-14 ">   <h4  class="pull-left color-green"><strong>Herb/Points/Herb Formula Suggestions</strong></h4> </div>
       <div class="margin-bottom-10"></div> 
         <div class=" panel-default col-lg-14" style="margin-right:10px" >
            <table class="table panel panel-green" id="suggesttable" >
                 <tr class="success table-striped table-hover" style="width:15%;"><td>&nbsp;</td><td style="width:28%;"><strong>Herbs</strong></td>
                 <td style="width:28%;"><strong>Herb Formula</strong></td> <td><strong>Points</strong></td></tr>
                 <tr id="trcompsugg"><td><strong>Match with chief complaint only</strong></td><?php echo $compsugges ?></tr>
                 <tr id="trpulsesugg"><td><strong>Match with pulse and tongue readings</strong></td><?php echo $pulsesugges ?></tr>
                  <tr id="trsuggest"><td><strong>Match with chief complaint, pulse and tongue readings</strong></td><?php echo  $suggestions ?></tr>
                    </table>
         </div>

         
         <div class="form-group  text-right">  
             <label  style="color:red" id="errobservation" ></label>
            <button class="btnbg" type="submit" name="saveobserv" onclick="return validatedefinecase();">Save</button> 
            <input class="btnbg" type="button" id="saveobserv3" value="Next" onclick="validatedefinecasenext2()"/>            
        </div>
       <div class="margin-bottom-30"></div> 
    </div>
     </div>
     <!-- End Observation -->
     
     <!-- Treatment -->
     <div class="tab-pane e" id="treatment">  
        
         <input type="hidden" id="hdherb" name="hdherb" value="" />
          <input type="hidden" id="hdformula"  name="hdformula" value="" />
           <input type="hidden" id="hdpoints" name="hdpoints"  value="" />
           
           
               <div class="margin-bottom-10"></div> 
               <div class="form-group" style="clear:both">
                     <label  class="col-lg-2 control-label">TCM Diagnosis</label>
                  <div class="col-lg-4">
                     <textarea class="form-control" id="txttcm"  name ="txttcmd" rows="3" >  <?php echo $tcmdiag ?></textarea>
                   </div>
                
              </div> 
               <div class="form-group" >
                     <label  class="col-lg-2 control-label">Biomedical Diagnosis</label>
                  <div class="col-lg-4">
                     <textarea class="form-control" id="txtbio"  name ="txtbiomed" rows="3" >  <?php echo $biomed ?></textarea>
                   </div>
                
              </div> 
                <div class="form-group" >
                     <label  class="col-lg-2 control-label">ICD Diagnosis Code & Description</label>
                  <div class="col-lg-4">
                      <textarea  id="txticd9" name="txticd9" class="form-control" rows="3"  >  <?php echo $icd9 ?></textarea>
                   
                       <!--<select multiple="multiple" id="txticd9" name="txticd9[]" style="width:100%">echo $icd9 </select>-->
<!--                      <input type="text" id="zatxticd9" name="txticd9" class="form-control" onblur="showicd9codes()" /></br>
                      <select id="lsticd9" name="lsticd9[]" multiple size="5" style="width:100%">echo $icd9 </select>-->
<!--                     <textarea class="form-control" id="txticd"  name ="txticd9" rows="3" ></textarea>-->
                   </div>
                
              </div> 
               <div  id="divadd">
                   <div class="col-lg-3 ">   <h5  class="pull-left color-green"><strong>Add Points/Herbs/Herb Formula</strong></h5> </div>
                   <div class="form-group" style="clear:both">
                       <label  class="col-lg-2 control-label">Type</label>
                       <div class="col-lg-4">
                           <select  class="form-control" id="dlltype" name="dlltype"  onchange="cleartext()"  >
                         <option value ="-1"> --Select--</option>
                         <option value="1">Herbs</option>
                         <option value="2">Herb Formula</option>
                         <option value="3">Points</option>
                     </select>
                   </div>
                   </div>
                    <div class="form-group" style="clear:both">
                       <label  class="col-lg-2 control-label">Name</label>
                       <div class="col-lg-4">
                        <input id="txtherbname"  name="txtherbname" type="text" class="form-control"  autocomplete="off"  onkeyup="treatmentsuggest(this.value);" onblur="closesuggestionbox(3);"></input>
                        <div class="suggestionsBox" id="treatsuggestions" style="display: none;"> <div class="suggestionList" id="treatsuggestionsList"> &nbsp; </div>
  </div>
                   </div>
                   </div>
                   <div class="form-group" style="clear:both;">
                       <div class="col-lg-14" style="padding-left:45%">
                       <button type="button" class="btnbg"  name="addherb"  onclick="addtoherblist()" >Add</button>
                         <button type="button" class="btnbg"  name="clearherb"  onclick="clearaddherb()" >Clear</button>
                         <div class="modal" id="model" ></div>
                       </div>
                   </div>
                   </div>
              
               <div id="herbsdiv">
                   <div class="margin-bottom-20"></div>
                    <div class="col-lg-3 ">   <h5  class="pull-left color-green"><strong>Herbs</strong></h5> </div>
                <div class="form-group"   style="width:90%;min-width:200px;float:right">
                     <table class="table panel panel-green" id="herbtable" >
                 <tr class="success table-striped table-hover" style="width:auto;"><td style="width:20%"><strong>Name</strong></td>
                     <td><strong>Contradictions</strong></td><td></td></tr>
                   <?php echo $tbherbs ?>
                    </table>
                     
                </div>
               </div>
               <div id="formuladiv">
                   <div class="margin-bottom-20"></div>
                    <div class="col-lg-3 ">   <h5  class="pull-left color-green"><strong>Herb Formula</strong></h5> </div>
                <div class="form-group"  style="width:90%;min-width:200px;float:right">
                     <table class="table panel panel-green" id="formulatable" >
                 <tr class="success table-striped table-hover" style="width:auto;"><td style="width:20%"><strong>Name</strong></td>
                     <td><strong>Contradictions</strong></td><td></td></tr>
                      <?php echo $tbformula ?>
                    </table>
                     
                </div>
               </div>
               
               <div id="points">
                    <div class="col-lg-3 ">   <h5  class="pull-left color-green"><strong>Points</strong></h5> </div>
                <div class="form-group"  style="width:90%;min-width:200px;float:right">
                     <table class="table panel panel-green" id="pointtable" >
                 <tr class="success table-striped table-hover" style="width:auto;"><td style="width:20%"><strong>Name</strong></td>
                     <td><strong>Contradictions</strong></td><td>&nbsp;</td></tr>
                 <?php echo $tbpoints ?>
                    </table>
                     
                </div>
               </div>
               
               <div>
                   <div class="margin-bottom-20"></div>
              <div class="form-group">
                     <label  class="col-lg-2 control-label">Treatment Plan</label>
                  <div class="col-lg-8">
                     <textarea  class="form-control" id="txtplan"  name ="txtplan" rows="6" ><?php echo $tplan ?></textarea>
                   </div>
                     
              </div>
              <div class="form-group">
                     <label  class="col-lg-2 control-label">Formula/Herbal Dosage</label>
                  <div class="col-lg-8">
                     <textarea  class="form-control" id="txdosage"  name ="txtdosage" rows="6" ><?php echo $tdosage ?></textarea>
                   </div>
                     
              </div>
               <div class="form-group">
                   <label  class="col-lg-10 control-label">
         
                         <input type="checkbox" name="chksharemycase" value="1" style="margin-right:15px" <?php echo $sharecase ?> />Please share all of my treatment and diagnosis data with my peers as a way of learning from one another. Patients personal information such as name, date of birth, contact info will NOT be shared</label>  
                 
                     
              </div>
               </div>
         <div class="form-group  text-right">   
             <label  style="color:red" id="errtreatment"></label>
            <button class="btnbg" type="submit" name="savetreat"  onclick="return validatedefinecase();">Save</button> 
                
        </div>
       <div class="margin-bottom-30"></div> 
        
         
     </div>
     <!-- End Treatment -->
</div> 
 
</form> 
</div>
      <!--=== Footer ===-->
<!-- Add Footer -->
<?php  include 'footer.html' ?>
<!--=== End Footer ===-->


<!-- JS Global Compulsory -->           
<script type="text/javascript" src="../assets/plugins/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="../assets/plugins/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="../assets/plugins/jquery-1.10.2.min.js"></script>
<script type="text/javascript"  src="../assets/plugins/jquery-ui.min.js"></script>
<script type="text/javascript" src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script> 
<script type="text/javascript" src="../assets/plugins/back-to-top.js"></script>
<script type="text/javascript" src="js/treatment.js"></script>

<script type="text/javascript"  src="../assets/plugins/jcanvas/jcanvas.min.js"></script>
<script src="../assets/plugins/webcam/webcam.js"></script>
<script>
 $( "#txtdate" ).datepicker({ dateFormat: 'mm/dd/yy',onSelect:function(dateText,inst){ checkDate(); } });
 $( "#txtldate" ).datepicker({ dateFormat: 'mm/dd/yy',onSelect:function(dateText,inst){ checklasttreatemntDate(); } });
var myCanvas = $('#myCanvas');
 var ischanged = false;
myCanvas.drawImage({
  source: "<?php if($canvaspath == '') {echo $canvasdata; } else { echo $canvaspath ;} ?>",
  x: 10, y: 25,
  fromCenter: false
  
});

var offset = myCanvas.offset();
  //Btn to clear all canvas surface
  var clearBtn = $('#clearBtn');
  //Numeric value of line width
  var lineWidthVal = 2;
  //Current line color
  var lineColor = '#333';
  var isMouseDown = false;
 
  var pos = {
    x: 0,
    y: 0
  };
  var lastPos = {
    x: 0,
    y: 0
  };

function paintLine(x1, y1, x2, y2, paintWidth, paintColor) {
    myCanvas.drawLine({
      strokeStyle: paintColor,
      strokeWidth: paintWidth,
      rounded: true,
      strokeJoin: 'round',
      strokeCap: 'round',
      x1: x1,
      y1: y1,
      x2: x2,
      y2: y2
    });
  }
  //On mousedown the painting functionality kicks in
  myCanvas.on('mousedown', function(e) {
    isMouseDown = true;
  });

  //On mouseup the painting functionality stops
  myCanvas.on('mouseup', function() {
    isMouseDown = false;
    return;
  });

  //On mousemove store the mouse coordinates and 
  //use jCanvas drawLine() method
  myCanvas.on('mousemove', function(e) {

    lastPos.x = pos.x;
    lastPos.y = pos.y;
    pos.x = e.pageX - offset.left;
    pos.y = e.pageY - offset.top;
   
    if (isMouseDown) {

      paintLine(lastPos.x, lastPos.y, pos.x, pos.y, lineWidthVal, lineColor);
       ischanged = true;
    }
  });

  //Clears all canvas surface
  clearBtn.on('click', function() {
    myCanvas.clearCanvas();
    ischanged = true;
    myCanvas.drawImage({
  source: "../assets/img/humanfigure.jpg",
  x: 10, y: 25,
  fromCenter: false
  
});
  });

function getcanvasimage()
{
    if(ischanged)
    {
        document.getElementById('canvasimage').value = myCanvas[0].toDataURL('image/png');

    }
}
</script>


    

    <script type="text/javascript" >
     
     Webcam.set({
        width: 300,
        height: 240,
        dest_width: 240,
        dest_height: 240,
        image_format: 'jpeg',
        jpeg_quality: 90,
        force_flash: false
});
       picturepath1 = <?php echo json_encode($diagimagearr) ?>;
       picturepath2 = <?php echo json_encode($observimagearr) ?>;
        function start_diagnosissnapshot() {
            
             document.getElementById('divcamera').style.display = "inline-block";
              Webcam.attach( '#my_camera' );
            }
            
           function capturediagnosispicture(){
            Webcam.snap( function(data_uri) {
                if(picturepath1.length < 4)
                {
                document.getElementById('my_result').innerHTML+= '<img src="'+data_uri+'" style="width:150px;height:150px"/>';
                picturepath1.push( data_uri)
                }
                else
                {
                    alert("Only 4 images are allowed.");
                }
            } );
        }
        
        function start_treatmentsnapshot() {
             Webcam.attach( '#my_camera1' );
             document.getElementById('divcamera1').style.display = "inline-block";
            }
            
           function capturetreatmentpicture(){
            Webcam.snap( function(data_uri) {
                 if(picturepath2.length < 4)
                 {
                document.getElementById('my_result1').innerHTML+= '<img src="'+data_uri+'" style="width:150px;height:150px"/>';
                picturepath2.push(data_uri);
            }
            else
                {
                    alert("Only 4 images are allowed.");
                }
            
            } );
        }
     function getwebcamimages()   
     {
         if( picturepath1 !== null & picturepath1.length > 0)
         {
             document.getElementById('diagimage').value = picturepath1;
         }
          if(  picturepath2 !== null & picturepath2.length > 0)
          {
              document.getElementById('observimage').value = picturepath2;
          }
     }
    </script>

    
</script>
    
    </body>
</html>

