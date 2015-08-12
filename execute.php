<?php
include('config.php');
include('function.php');

// Login To SIS
$logindata = GetDataFromURL($SIS_URL.'/WebRegist2005/Login.aspx',null);
$formdata = ExtractFormByID($logindata,'aspnetForm');
$formdata['ctl00$mainContent$Login1$UserName'] = $PSU_Passport_Username;
$formdata['ctl00$mainContent$Login1$Password'] = $PSU_Passport_Password;
$loginaction = GetDataFromURL($SIS_URL.'/WebRegist2005/Login.aspx',$formdata);

// Get Your Name if Login Successfull
$chklogin = str_get_html($loginaction);
$loginname_data = $chklogin->find('span[id=ctl00_ctl00_LoginView1_LoginName1]')[0]->innertext;
if (!empty($loginname_data))
{
	echo "[". getTimeNow() . "] User Information : " . $loginname_data . "\n";
	echo "------------------------------------------------------------------------\n";
	// Enter To Register Page
	while (true) 
	{
		$enterToReg = GetDataFromURL($SIS_URL.'/WebRegist2005/Enroll/Default.aspx',null);
		$enterToRegformdata = ExtractFormByID($enterToReg,'aspnetForm');
		$enterToRegformdata['ctl00$ctl00$mainContent$PageContent$DropDownList1'] = $SIS_Term;
		echo "[". getTimeNow() . "] Select Term to Register : ". $SIS_Term . "\n";
		$RegFormPage = GetDataFromURL($SIS_URL.'/WebRegist2005/Enroll/Default.aspx',$enterToRegformdata);
		$RegFormPage_Extract = str_get_html($RegFormPage);
		if ($RegFormPage_Extract->find('span[id=ctl00_ctl00_mainContent_PageContent_ucAttention1_lblAttention]')[0]->innertext)
		{
			// If have a error message loop to register unlimited
			echo "[". getTimeNow() . "] Register Error Message : " . $RegFormPage_Extract->find('span[id=ctl00_ctl00_mainContent_PageContent_ucAttention1_lblAttention]')[0]->innertext . "\n";
		}
		else
		{
			// Begin Register
			for ($i=0; $i < count($Subject_To_Register); $i++)
			{
				echo "-------------------------------- ". $Subject_To_Register[$i]['SubjectCode'] . " --------------------------------\n";
				echo "[". getTimeNow() . "] Register Information : Search and Select Subject ". $Subject_To_Register[$i]['SubjectCode'] . "\n";
				$RegisterFormData_Step1 = ExtractFormByID($RegFormPage,'aspnetForm');
				$RegisterToStep2 = GetDataFromURL($SIS_URL.'/WebRegist2005/Enroll/FindOpenSubject.aspx',$RegisterFormData_Step1);
				$RegisterFormData_Step2 = ExtractFormByID($RegisterToStep2,'aspnetForm');
				$RegisterFormData_Step2['ctl00$ctl00$mainContent$PageContent$UcFindSubject1$txtKeyWord'] = $Subject_To_Register[$i]['SubjectCode'];
				$RegisterToStep3 = GetDataFromURL($SIS_URL.'/WebRegist2005/Enroll/FindOpenSubject.aspx',$RegisterFormData_Step2);
				$RegisterFormData_Step3 = ExtractJavaScriptToGetURL($RegisterToStep3,'aspnetForm');
				$RegisterToStep4 = GetDataFromURL($SIS_URL.'/WebRegist2005/Enroll/'. $RegisterFormData_Step3['ctl00$ctl00$mainContent$PageContent$UcFindSubject1$GridView1$ctl02$Button1'], null);
				$RegisterFormData_Step4 = ExtractFormByID($RegisterToStep4,'aspnetForm');
				$RegisterFormData_Step4['ctl00$ctl00$mainContent$PageContent$gvPendingEnroll$ctl02$ddlSection'] = SelectSubjectSection($RegisterFormData_Step3['ctl00$ctl00$mainContent$PageContent$UcFindSubject1$GridView1$ctl02$Button1'],$Subject_To_Register[$i]['SubjectSec']);
				echo "[". getTimeNow() . "] Register Information : Select Section and Fetch Subject Status ". $Subject_To_Register[$i]['SubjectCode'] . "\n";
				$RegisterFormData_Step4['ctl00$ctl00$mainContent$PageContent$gvPendingEnroll$ctl02$ddlRegistType'] = $Subject_To_Register[$i]['SubjectCredit'];
				extractSecInfo($RegisterToStep4,$Subject_To_Register[$i]['SubjectSec']);
				unset($RegisterFormData_Step4['ctl00$ctl00$mainContent$PageContent$btnCancel']);
				$RegisterToStep5 = GetDataFromURL($SIS_URL.'/WebRegist2005/Enroll/'. $RegisterFormData_Step3['ctl00$ctl00$mainContent$PageContent$UcFindSubject1$GridView1$ctl02$Button1'], $RegisterFormData_Step4);
				$chkErrorBeforeReg = str_get_html($RegisterToStep5);
				$chkErrorBeforeSave = $chkErrorBeforeReg->find('span[id=ctl00_ctl00_mainContent_PageContent_UcAttention1_lblAttention]')[0]->innertext;
				if (!empty($chkErrorBeforeSave))
				{
					echo "[". getTimeNow() . "] [".$Subject_To_Register[$i]['SubjectCode']."] Register Error Message : (Before Confirm Register) ".explode('<br>', $chkErrorBeforeSave)[0]."\n";
				}
				else
				{
					echo "[". getTimeNow() . "] Register Information : Finally enroll to subject ". $Subject_To_Register[$i]['SubjectCode'] . "\n";
					$SemiFinalRegister = GetDataFromURL($SIS_URL.'/WebRegist2005/Enroll/EnrollDetail.aspx',null);
					$SemiFinalFormRegister = ExtractFormByID($SemiFinalRegister,'aspnetForm');
					$FinalRegister = GetDataFromURL($SIS_URL.'/WebRegist2005/Enroll/EnrollDetail.aspx',$SemiFinalFormRegister);
					echo "[". getTimeNow() . "] [".$Subject_To_Register[$i]['SubjectCode']."] Register Information : Complete!\n";
				}
			}
			break;	
		}
	}
}
else
{
	echo "[". getTimeNow() . "] Invalid PSU Passport Username or PSU Passport Password";
}
