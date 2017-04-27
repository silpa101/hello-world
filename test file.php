<?php 
session_start();
include 'functions/config.php';
include 'functions/log.php';
include 'functions/common.php';
include 'functions/patient.php';
$fname ='';
$lname ='';
$mname = '';
$dob ='';
$age ='';
$gender='';
$mstatus = '';
$patientprof ='';
$insurecompany ='';
$patientpolicyno = '';
$cphone = $hphone = $wphone ='';
$email='';

$add1='';
$city ='';
$state='';
$country ='';
$zip ='';
$contactname = '';
$relationship ='';
$cdayphone = $cevephone = $cemail ='';
$errfname ='';
$errlname ='';
$errdob = '';
$errgender ='';
$errphone ='';
$erremail ='';
$erraddress ='';
$errcity ='';
$errstate='';
$errcountry = '';
$errzip ='';
$erraddpt = '';
$errhdoc =$errcdoc =$errtdoc = '';
$errinsurecompany ='';
$errpolicyno = '';

$countrylist = '';
$statelist ='';
 $addpat_result ='';
 $notes ='';
 $ptid ='';
 $addreserr='';
 $profiletoken = '';
 $headerlabel='Add New Patient';
 $addptmsg = '';
 $showtreatmentbutton = "display:none";
 $historyfilelist = '';
 $contentfilelist = '';
 $treatmentfilelist = '';
 $treatmenthistory ='';
 $welcomeuser ='';

$path = "../uploads/patientfiles";
if(isset($_SESSION['memberstatus']))
{
    $memberstatus = $_SESSION['memberstatus'];
}
else
{
    $memberstatus ='';
}


if(isset($_SESSION['userid']) & $_SESSION['usertype'] == 1 )
{ 
    $welcomeuser = $_SESSION['fstname'].' '.$_SESSION['lstname'];
    
    $obj_common = new common();
      $obj_patient =  new patient();
   
      //Save button click
    if( isset($_POST) & $_SERVER['REQUEST_METHOD'] == "POST" )
    {
         $fname = $_POST['txtfname'];
         $lname= $_POST['txtlname'];
         $mname =$_POST['txtmname'];
         $dob = $_POST['txtdob'];
         $gender = $_POST['ddlgender'];
         $mstatus = $_POST['ddlstatus']; 
         $patientprof = $_POST['txtprof'];
         $insurecompany = $_POST['txtinsurecompany'];
         $patientpolicyno = $_POST['txtpolicyno'];
         $notes = $_POST['txtnotes'];
         $add1 = $_POST['txtaddress'];
         $city = $_POST['txtcity'];
         $state = $_POST['txtstate'];
         $country = $_POST['txtcountry'];
         $zip = $_POST['txtzipcode'];
         $cphone = $_POST['txtcphone'];
         $wphone= $_POST['txtwphone'];
         $hphone = $_POST['txthphone'];
         $cdayphone = $_POST['txtdphone'];
         $cevephone  = $_POST['txtephone'];
         $contactname = $_POST['txtcontact'];
         $relationship = $_POST['txtcrelation'];
         $email = $_POST['txtemail'];
         $cemail = $_POST['txtcemail'];
         $ptid = $_POST['patientid'];
         
         $countrylist = $obj_common ->getcountrylist($country);
         $statelist = $obj_common ->getstatelist($country, $state);
         
         if(isset($_POST['savept']))
         {
        
          if($ptid == '')
         {
            $patient = $obj_patient->createpatientprofile();
            if($patient["error"] == "1")
            {
                $ptid = $patient['pid'];
                $showtreatmentbutton = "display:inline-block";
            }
            else
                $erraddpt = "Error creating patient profile " ;
          }
          else 
          {
              $showtreatmentbutton = "display:inline-block";
            $patient = $obj_patient->updatepatientprofile($ptid);
            if($patient["error"] != "1")
                 $erraddpt = "Error updating patient profile ";
            
           }
           
           if( $erraddpt == '' )
           {
               
               if($_FILES['medicalhistory']['size'][0] > 0)
               {
                   $docdate = trim($_POST['txtmdocdate']);
                   if($docdate != '')
                   {
                        $errhdoc = $obj_patient->uploadfiles($ptid , $_FILES['medicalhistory'] , MEDICALHISTORY,$docdate );
                   }
               }
               
               if($_FILES['disclosureform']['size'][0] > 0 )
               {
                   $docdate = trim($_POST['txtcdocdate']);
                   if($docdate != '')
                   {
                     $errcdoc = $obj_patient->uploadfiles($ptid,$_FILES['disclosureform'],CONTENTDISCLOSURE ,$docdate );
                   }
               }
               
               if($_FILES['treatmentform']['size'][0] > 0)
               {
                   $docdate = trim($_POST['txttdocdate']);
                   if($docdate != '')
                   {
                     $errtdoc = $obj_patient->uploadfiles($ptid ,$_FILES['treatmentform'],TREATMENTHISTORY ,$docdate);
                   }
               }
           }
           
           
           
           //Get files of patient
          $filearray = getfiles($ptid);
          
          $historyfilelist = $filearray[0];
           $contentfilelist = $filearray[1];
            $treatmentfilelist = $filearray[2];
            
             $treatmenthistory = gettreatmenthistory($ptid);   
            
         }
    }
    //Page load
    else if($_SERVER['REQUEST_METHOD'] == "GET" & isset($_GET['ptid']))
    {
        $ptid = $_GET['ptid'];
        $showtreatmentbutton = "display:inline-block";
      $patientdetails = $obj_patient->getpatientprofile($ptid);
      
      if($patientdetails["error"] == 0)
      {
         $fname = $patientdetails['firstname'];
         $lname= $patientdetails['lastname'];
         $mname =$patientdetails['middlename'];
         $dt = new DateTime($patientdetails['dob']);
         $dob =  date_format($dt,"m/d/Y");  
       
         $gender = $patientdetails['gender'];
         $mstatus = $patientdetails['relationstatus']; 
         $patientprof = $patientdetails['profession'];
         $insurecompany = $patientdetails['insurancecompany'];
         $patientpolicyno = $patientdetails['insurancepolicy'];
         $notes = $patientdetails['notes'];
         $add1 = $patientdetails['address'];
         $city = $patientdetails['city'];
         $state = $patientdetails['state'];
         $country = $patientdetails['country'];
         $zip = $patientdetails['zipcode'];
         $cphone = $patientdetails['cellphone'];
         $wphone= $patientdetails['workphone'];
         $hphone = $patientdetails['homephone'];
         $cdayphone = $patientdetails['contactdayphone'];
         $cevephone  = $patientdetails['contactevephone'];
         $contactname = $patientdetails['emergencycontact'];
         $relationship = $patientdetails['econtactrelation'];
         $email = $patientdetails['email'];
         $cemail = $patientdetails['contactemail'];
         $countrylist = $obj_common ->getcountrylist($country);
         $statelist = $obj_common ->getstatelist($country, $state);
          $_SESSION['patientid'] = $ptid;
         
          $treatmenthistory = $obj_patient->gettreatmenthistory($ptid , $_SESSION['userid']);
                
          
        
          //Get files of patient
          $filearray = getfiles($ptid);
          
            $historyfilelist = $filearray[0];
            $contentfilelist = $filearray[1];
            $treatmentfilelist = $filearray[2];
      }
      else
      {
          header("Location:mypatient.php?err=1");
          exit();
      }
    }
    else
    {
        
        $countrylist = $obj_common ->getcountrylist("-1");
    }
}
else
{
    header("Location:../logout.php");
    exit();
}


function getfiles($ptid)
{
     $historylist = $contentlist =  $treatmentlist = '';
      $obj_patient =  new patient();
      $filelist = $obj_patient->getpatientfiles($ptid,$_SESSION['userid']);
        
         if($filelist != null)
         {
                        
         foreach($filelist as $file)
         {
             
             if($file['type'] == MEDICALHISTORY)
             {
                $historylist.='<tr><td style="width:30px;padding-top:12px"><input type="checkbox" name="chkmedhistory" style="height:15px;width:15px" value="'.$file['file_name'].'" /></td><td style="padding-top:15px">'.substr($file['file_name'],10).'</td><td>'.$file['document_date'] .'</td><td><img src="..\assets\img\delete.png" width="25px" onclick = "deletefile(1,\''.$file['file_name'] .'\','.$ptid.')" /></td></tr>';
             }
             else if($file['type'] == CONTENTDISCLOSURE)
             {
                 $contentlist.='<tr><td style="width:30px;padding-top:12px"><input type="checkbox" name="chkcontentdoc" style="height:15px;width:15px" value="'.$file['file_name'].'" /></td><td style="padding-top:15px">'.substr($file['file_name'],10).'</td><td>'.$file['document_date'] .'</td><td><img src="..\assets\img\delete.png" width="25px" onclick = "deletefile(2,\''.$file['file_name'] .'\','.$ptid.')" /></td></tr>';
             }
             else if($file['type'] == TREATMENTHISTORY)
             {
                 $treatmentlist.= '<tr><td style="width:30px;padding-top:12px"><input type="checkbox" name="chktretmentdoc" style="height:15px;width:15px" value="'.$file['file_name'].'" /></td><td style="padding-top:15px">'.substr($file['file_name'],10).'</td><td>'.$file['document_date'] .'</td><td><img src="..\assets\img\delete.png" width="25px" onclick = "deletefile(3,\''.$file['file_name'] .'\','.$ptid.')" /></td></tr>';
             }
             
        
         }
         
             if($historylist == '')
             {
                 $historylist = '<label style="font-size:18px"><strong>No documents</strong></label>';
             }
             else
             {
                  $historylist='<table class="table panel panel-green" ><thead><tr class="success table-striped table-hover" style="font-weight:bold;"><th><input type="checkbox" id="chkallmedhistorydoc" style="height:15px;width:15px" onclick="checkall(1)" /></th><th>File Name</th><th>Date</th><th>Delete</th></tr></thead><tbody>'.$historylist.'</tbody></table><div><button type="button" class="btnbg" onclick="downloadhistorydocs()" >Download</button></div>';
             }
              if($contentlist == '')
             {
                 $contentlist ='<label style="font-size:18px"><strong>No documents</strong></label>';
             }
             else
             {
                    $contentlist='<table class="table panel panel-green" ><thead><tr class="success table-striped table-hover" style="font-weight:bold;"><th><input type="checkbox" id="chkallcontentdoc" style="height:15px;width:15px" onclick="checkall(2)" /></th><th>File Name</th><th>Date</th><th>Delete</th></tr></thead><tbody>'.$contentlist.'</tbody></table><div><button type="button" class="btnbg" onclick="downloadcontentdocs()" >Download</button></div>';
             }
         
              if($treatmentlist == '')
             {
                 $treatmentlist = '<label style="font-size:18px"><strong>No documents</strong></label>';
             }
             else
             {
                $treatmentlist='<table class="table panel panel-green" ><thead><tr class="success table-striped table-hover" style="font-weight:bold;"><th><input type="checkbox" id="chkalltreatmentdoc" style="height:15px;width:15px" onclick="checkall(3)" /></th><th>File Name</th><th>Date</th><th>Delete</th></tr></thead><tbody>'.$treatmentlist.'</tbody></table><div><button type="button" class="btnbg" onclick="downloadtreatmenthistory()" >Download</button></div>';
             }
         }
 else {
         $historylist = '<label style="color:red;font-size:18px">Error getting documents .Try later</label>';
         $contentlist = '<label style="color:red;font-size:18px">Error getting documents .Try later</label>';
         $treatmentlist = '<label style="color:red;font-size:18px">Error getting documents .Try later</label>';
      }
         
         return array($historylist,$contentlist ,$treatmentlist);
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
    <link rel="stylesheet" href="../assets/css/responsive.css">
      <link rel="stylesheet" href="../assets/css/jquery-ui.css" > 
    <!-- CSS Implementing Plugins -->    
    <link rel="stylesheet" href="../assets/plugins/font-awesome/css/font-awesome.css">
    <link rel="icon" type="image/png" href="../assets/img/favicon.ico">
    
    <style>
        .ui-datepicker {
   background: white;
   border: 1px solid #555;
   color: #8db600;
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
<form method="POST" class="form-horizontal"   enctype="multipart/form-data">
    <div style="height:10px;"></div>

     <div class="tab-v1">
              
                <ul class="nav nav-tabs">
                    <li class="active" id="profile"><a href="#profiledata" data-toggle="tab">Patient Profile</a></li>                    
                    <li id="history" class=""><a href="#historydata" data-toggle="tab">Content, Disclosure & Medical History</a></li>
                      <li id="treatment" class=""><a href="#treatmentdata" data-toggle="tab">Treatment History</a></li>
                    
                </ul>     
          </div> 
    <div class="tab-content">  
             
      <!-- Patient Profile -->
   <div class="tab-pane active" id="profiledata">          
      <div class="panel-body">  
           <input type="hidden" id="patientid"  name ="patientid" value="<?php echo $ptid ;?>"/>
                   
                       <div class=" panel-default col-lg-12" style="margin-right:10px ;border:none;clear:both" >
                            <h4 style="font-weight:bold;font-family: Arial,Helvetica,sans-serif;
                             color: #8DB600;"><strong>Personal Details</strong></h4>
                        <div class="form-group">
                     <label  class="col-lg-2 control-label">First Name<span class="color-red">*</span></label>
                  <div class="col-lg-2">
                     <input type="text" class="form-control" id="txtfname"  name ="txtfname"  onchange="return checkfname();" value="<?php echo $fname?>" >
                     <label id="errfirstname" style="color:red"><?php echo $errfname?></label>
                   </div>
                      <label  class="col-lg-2 control-label">Middle Name</label>
                  <div class="col-lg-2" >
                     <input type="text" class="form-control" id="txtmname"  name ="txtmname"   value="<?php echo $fname?>" >
                     <label id="errmname" style="color:red"></label>
                   </div>
                      <label  class="col-lg-2 control-label">Last Name<span class="color-red">*</span></label>
                  <div class="col-lg-2">
                     <input type="text" class="form-control" id="txtlname"  name ="txtlname" onchange="return checklname();" value="<?php echo $lname?>">
                      <label id="errlastname" style="color:red"><?php echo $errlname?></label>
                   </div>
              </div> 
                      
                 <div class="form-group ch" >
                           <label  class="col-lg-2 control-label">Date Of Birth<span class="color-red">*</span></label>
                  <div class="col-lg-2">
                     <input type="text" class="form-control" id="txtdob"  name ="txtdob" onblur="return checkDate();" value="<?php echo $dob?>" >
                      <label id="errdob" style="color:red"><?php echo $errdob?></label>
                   </div>
                     <label  class="col-lg-2 control-label">Gender<span class="color-red">*</span></label>
                  <div class="col-lg-2">
                      <select class="form-control" id="ddlgender"  name ="ddlgender"  >
                          <option value="-1" >Select </option>
                           <option value="1" <?php if($gender == 1) echo 'selected' ?>>Male </option>
                            <option value="2" <?php if($gender == 2) echo 'selected' ?>>Female </option>
                             <option value="3" <?php if($gender == 3) echo 'selected' ?>>Other </option>
                      </select>
                      <label id="errgender" style="color:red"><?php echo $errgender?></label>
                   </div>
                   <label  class="col-lg-2 control-label" >Martial Status</label>
                  <div class="col-lg-2">
                      <select class="form-control" id="ddlstatus"  name ="ddlstatus"  >
                          <option value="-1" >Select </option>
                           <option value="1" <?php if($mstatus == 1) echo 'selected' ?>>Married </option>
                            <option value="2" <?php if($mstatus == 2) echo 'selected' ?>>Unmarried </option>
                             <option value="3" <?php if($mstatus == 3) echo 'selected' ?>>Other </option>
                      </select>
                     
                   </div>
                       </div>
               <div class="form-group" >
                           <label  class="col-lg-2 control-label">Occupation</label>
                  <div class="col-lg-2">
                     <input type="text" class="form-control" id="txtprof"  name ="txtprof"  value="<?php echo $patientprof?>" >
                   </div>
                     <label  class="col-lg-2 control-label">Insurance Company</label>
                  <div class="col-lg-2">
                       <input type="text" class="form-control" id="txtinsurecompany"  name ="txtinsurecompany"  value="<?php echo $insurecompany?>" >
                       <label id="errinsurecompany" style="color:red"><?php echo $errinsurecompany?></label>
                     
                   </div>
                   <label  class="col-lg-2 control-label" >Insurance Policy #</label>
                  <div class="col-lg-2">
                       <input type="text" class="form-control" id="txtpolicyno"  name ="txtpolicyno"  value="<?php echo $patientpolicyno ?>" >
                      <label id="errpolicyno" style="color:red"><?php echo $errpolicyno?></label>
                   </div>
                       </div>
             
                             <div class="form-group" >
                           <label  class="col-lg-2 control-label">Notes</label>
                  <div class="col-lg-4">
                      <textarea class="form-control" id="txtnotes"  rows="4" name ="txtnotes"  ><?php echo $notes?></textarea>
                   </div>
                             </div>
                           
                           
                           
                       </div>
                    
                        <div class=" panel-default col-lg-12" style="margin-right:10px ;border:none;clear:both" >
                         <h4 style="font-weight:bold;font-family: Arial,Helvetica,sans-serif;
                             color: #8DB600;"><strong>Contact Details</strong></h4>
                           
                     <div class="form-group" >
                           <label  class="col-lg-2 control-label">Address</label>
                  <div class="col-lg-4">
                      <textarea class="form-control" id="txtaddress"  rows="4" name ="txtaddress"  ><?php echo $add1 ?></textarea>
                      <label id="erradd"  style="color:red"><?php echo $erraddress?></label> 
                   </div>
                        <label  class="col-lg-2 control-label">City</label>
                  <div class="col-lg-3">
                      <input type="text" id="txtcity" class="form-control" name="txtcity" value="<?php echo $city ?>">
                        <label id="errcity"  style="color:red"><?php echo $errcity?></label> 
                  </div>
                         
                        
                             </div> 
                         <div class="form-group"  >
                             <label  class="col-lg-2 control-label">State</label>
                  <div class="col-lg-2">
                      <select  id="txtstate" class="form-control" name="txtstate"><?php echo $statelist ?></select>
                        <label id="errstate"  style="color:red"><?php echo $errstate?></label> 
                  </div>
                             <label  class="col-lg-2 control-label">Country</label>
                  <div class="col-lg-2">
                      <select  id="txtcountry" class="form-control" name="txtcountry"><?php echo $countrylist ?></select>
                        <label id="errcountry"  style="color:red"></label> 
                  </div>
                             <label  class="col-lg-2 control-label">Zip code</label>
                  <div class="col-lg-2">
                      <input type="text" id="txtzipcode" class="form-control" name="txtzipcode" value="<?php echo $zip ?>">
                        <label id="errzip"  style="color:red"></label> 
                  </div>
                         </div>
                    <div class="form-group"  >
                             <label  class="col-lg-2 control-label">Cell Phone</label>
                  <div class="col-lg-2">
                      <input type="text" id="txtcphone" class="form-control" name="txtcphone" value="<?php echo $cphone ?>">
                         <label id="errcphone"  style="color:red"></label> 
                  </div>
                             <label  class="col-lg-2 control-label">Home Phone</label>
                  <div class="col-lg-2">
                      <input type="text" id="txthphone" class="form-control" name="txthphone" value="<?php echo $hphone ?>">
                        <label id="errhphone"  style="color:red"></label> 
                  </div>
                             <label  class="col-lg-2 control-label">Work Phone</label>
                  <div class="col-lg-2">
                      <input type="text" id="txtwphone" class="form-control" name="txtwphone" value="<?php echo $wphone ?>">
                        <label id="errwphone"  style="color:red"></label> 
                  </div>
                         </div>     
                         
                         
                          <div class="form-group"  >
                             <label  class="col-lg-2 control-label">Email</label>
                  <div class="col-lg-2">
                      <input type="text" id="txtemail" class="form-control" name="txtemail" value="<?php echo $email ?>">
                         <label id="erremail"  style="color:red"><?php echo $erremail?></label> 
                  </div>
                          </div>
                         
                     </div>
           
                       <div class=" panel-default col-lg-12" style="margin-right:10px ;border:none;clear:both" >
                         <h4 style="font-weight:bold;font-family: Arial,Helvetica,sans-serif;
                             color: #8DB600;"><strong>Emergency Contact</strong></h4>
                             
                              <div class="form-group"  >
                             <label  class="col-lg-2 control-label">Contact Name</label>
                  <div class="col-lg-3">
                      <input type="text" id="txtcontact" class="form-control" name="txtcontact" value="<?php echo $contactname ?>">
                         <label id="errcname"  style="color:red"></label> 
                  </div>
                                  <label  class="col-lg-2 control-label">Relationship</label>
                  <div class="col-lg-3">
                      <input type="text" id="txtcrelation" class="form-control" name="txtcrelation" value="<?php echo $relationship ?>">
                         <label id="errrelation"  style="color:red"></label> 
                  </div>
                          </div>
                   <div class="form-group"  >
                             <label  class="col-lg-2 control-label">Day Phone</label>
                  <div class="col-lg-2">
                      <input type="text" id="txtdphone" class="form-control" name="txtdphone" value="<?php echo $cdayphone ?>">
                         <label id="errdphone"  style="color:red"></label> 
                  </div>
                             <label  class="col-lg-2 control-label">Evening Phone</label>
                  <div class="col-lg-2">
                      <input type="text" id="txtephone" class="form-control" name="txtephone" value="<?php echo $cevephone ?>">
                        <label id="errephone"  style="color:red"></label> 
                  </div>
                             <label  class="col-lg-2 control-label">Email</label>
                  <div class="col-lg-2">
                      <input type="text" id="txtcemail" class="form-control" name="txtcemail" value="<?php echo $cemail ?>">
                        <label id="errcemail"  style="color:red"></label> 
                  </div>
                         </div>      
                  
                     </div>
          </div>
   </div>
       <!-- Medical History  -->
       <div class="tab-pane " id="historydata">          
      <div class="panel-body"> 
          
           <label >Please Upload your own Consent to Treatment , Disclosure and any other legal agreements with your patient.
                                 The Medical history form provided here does not include the forementioned agreements and disclosures.  </label>
           
           <div class=" panel-default col-lg-12" style="margin-right:10px ;border:none;clear:both" >
               <div class="col-lg-7">
                <h4 style="font-weight:bold;font-family: Arial,Helvetica,sans-serif;color: #8DB600;"><strong>Medical History </strong></h4>
               </div>
               <div class="col-lg-5">
                    <a href="patientinfopdf.php" class="btn-u roundbutton col-lg-4" style="width:200px;text-align: center;float:right">Download New Patient Profile & Medical Form</a>
               </div>
                <div class="form-group">               
                       
                              <div class="col-lg-9" style="padding-top:8px">
                                   <label class="control-label">Add Document  &nbsp;&nbsp;&nbsp;    Date</label><input type="date" name="txtmdocdate" id="txtmdocdate" />
                                  <input type="file" name="medicalhistory[]" id ="medicalhistory"  style="padding-left:15px;display:inline" onclick="return checkuploaddate(event,'txtmdocdate')" onchange="validatefile('medicalhistory')"><br>
                                  <span class="error input-group" id="errmedicalhistory" styel="color:#FF0000" ><?php echo $errhdoc ;?></span>
                                 </div>   
                  
                              
                        
                        
               </div>
               <div class="form-group">
                   <div  id="historylistdiv" style="min-height:100px">
                            <?php echo $historyfilelist ?><br/>
                            <label id="historylisterr" style="color:red;font-size:14px"></label>
                        </div>
               </div>
           </div>
          
             <div class=" panel-default col-lg-12" style="margin-right:10px ;border:none;clear:both" >
                  <h4 style="font-weight:bold;font-family: Arial,Helvetica,sans-serif;
                             color: #8DB600;"><strong>Content & Disclosure </strong></h4>
                 <div class="form-group"> 
                             
                              <div class="col-lg-12" style="padding-top:8px">
                                  <label class="control-label">Add Document  &nbsp;&nbsp;&nbsp;    Date</label><input type="date" name="txtcdocdate"  id="txtcdocdate" /><input type="file" name="disclosureform[]"  style="padding-left:15px;display:inline" id="disclosureform" onclick="return checkuploaddate(event,'txtcdocdate')"  onchange="validatefile('disclosureform')"><br>
                                  <span class="error input-group" id="errdisclosureform" styel="color:#FF0000"><?php echo $errcdoc ;?></span>
                                 </div>   
                 </div>            
                 <div class="form-group" id="contentlistdiv"><?php echo $contentfilelist ?> <br/>
                 <label id="contentlisterr" style="color:red;font-size:14px"></label>
                 </div>
             </div>
          
          </div>
   </div>
       
        <!-- Treatment History -->
       <div class="tab-pane " id="treatmentdata">          
      <div class="panel-body">  
          <div class=" panel-default col-lg-12" style="margin-right:10px ;border:none;clear:both" >
                <div class="form-group" >
                   <div >
                <h4 style="font-weight:bold;font-family: Arial,Helvetica,sans-serif;color: #8DB600;"><strong>Treatment History </strong></h4>
               </div>
                    <div id="treatmenthistorydiv">   <?php echo $treatmenthistory ?></div>
                  <label id="treatmentlisterr" style="color:red;font-size:16px"></label>
              </div>
               <div class="form-group"  style="clear:both">
                   
                   <div class="form-group"> 
                                           
                            
                              <div class="col-lg-12" style="padding-top:8px">
                                  <label class="control-label">Add Document  &nbsp;&nbsp;&nbsp;    Date</label><input type="date" name="txttdocdate" id="txttdocdate" /><input type="file" name="treatmentform[]" id ="treatmentform" onclick="return checkuploaddate(event,'txttdocdate')" onchange="validatefile('treatmentform')" style="padding-left:15px;display:inline"><br>
                               <span class="error input-group" id="errtreatmentform" styel="color:#FF0000"><?php echo $errtdoc ;?></span>
                                 </div>   
                 </div> 
                   <div id="treatmentlistdiv">
                   <?php echo $treatmentfilelist ?><br/>
                  <label id="treatmentdoclisterr" style="color:red;font-size:14px"></label>
                   </div>
              </div>
                     
             
             
             </div>
          
          
          </div>
   </div>
    </div>
    
               <div class="form-group  text-center" style="clear:both">
                           <label  style="color:red" id="erraddpt"><?php echo $erraddpt ?></label>
                       </div>
                       <div class="form-group  text-center" style="clear:both">        
            <button class="btn-u" type="submit" name="savept" id="savept" onclick="return validatepatient();" >Save</button>   
            <a class="btn-u" type="button" id="treatment" href="createtreatment.php?pid=<?php echo $ptid?>" style="vertical-align:top;height:34px;<?php echo $showtreatmentbutton?>">Add Treatment</a>
            <a class="btn-u" type="button" id="canceladd"  href="mypatients.php" style="vertical-align:top;height:34px;">Cancel</a>            
        </div>
</form>
</div>
     
      <!-- Add Footer -->
<?php  include 'footer.html' ?>
<!--=== End Footer ===-->


<!-- JS Global Compulsory -->           
<script type="text/javascript" src="../assets/plugins/jquery-3.1.1.min.js"></script>
<script type="text/javascript"  src="../assets/plugins/jquery-ui.min.js"></script>
<script type="text/javascript" src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script> 
<script type="text/javascript" src="../assets/plugins/back-to-top.js"></script>
<script type ="text/javascript" src=" ../assets/js/validation.js"></script>
<script type ="text/javascript" src=" js/validatepatient.js"></script>
<script>
  $(document).ready(function() {
    $("#txtdob").datepicker();
    
     
	$("#txtcountry").change(function() {

		$.get('../statelist.php?parent_cat=' + $(this).val(), function(data) {
			$("#txtstate").html(data);
			
		});	
    });
    
  });
  
  function validatepatient()
{
    
    if(checkfname() & checklname() &   checkAddress() & checkZip() & checkDate()& checkCountryState()& checkCity())
    return true;


    return false;
}
 

function deletefile(type,name,pid)
{
    $.get('deletefile.php?type='+type+'&name='+encodeURIComponent(name)+'&pid='+ pid, function(data) {
                    filedata = $.parseJSON(data);
			if(filedata[0] == '' )
                        {
                            if(type==1)
                            {
                                $('#historylistdiv').html(filedata[1]);
                            }
                            else if(type ==2)
                            {
                                 $('#contentlistdiv').html(filedata[1]);
                            }
                             else if(type ==3)
                             {
                                  $('#treatmentlistdiv').html(filedata[1]);
                             }
                        }
                        else
                        {
                            if(type==1)
                            {
                                $('#historylisterr').html(filedata[0]);
                            }
                            else if(type ==2)
                            {
                                 $('#contentlisterr').html(filedata[0]);
                            }
                             else if(type ==3)
                             {
                                  $('#treatmentdoclisterr').html(filedata[0]);
                             }
                        }
			
		});	
}


function deletetreatment(id)
{
    $.get('deletetreatment.php?tid='+id, function(data) {
                    treatmentdata = $.parseJSON(data);
                    if(treatmentdata[0]  == '')
                    {
                        $('#treatmenthistorydiv').html(treatmentdata[1]);
                    }
                    else
                    {
                        $('#treatmentlisterr').html(treatmentdata[0]);
                    }
			
		});	
    
    
    }
       </script>
    </body>
</html>
