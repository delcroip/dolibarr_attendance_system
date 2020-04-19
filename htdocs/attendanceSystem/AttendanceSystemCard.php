<?php
/*
 * Copyright (C) 2007-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2018           Patrick DELCROIX     <pmpdelcroix@gmail.com>
 * * Copyright (C) ---Put here your own copyright and developer email---
 *
 * This program is free software;you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation;either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY;without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
/**
 *        \file       dev/AttendanceSystems/AttendanceSystemPage.php
 *                \ingroup    attendanceSystem othermodule1 othermodule2
 *                \brief      This file is an example of a php page
 *                                        Initialy built by build_class_from_table on 2019-01-30 16:24
 */
		//if(! defined('NOREQUIREUSER'))  define('NOREQUIREUSER', '1');
		//if(! defined('NOREQUIREDB'))    define('NOREQUIREDB', '1');
		//if(! defined('NOREQUIRESOC'))   define('NOREQUIRESOC', '1');
		//if(! defined('NOREQUIRETRAN'))  define('NOREQUIRETRAN', '1');
		//if(! defined('NOCSRFCHECK'))    define('NOCSRFCHECK', '1');                        // Do not check anti CSRF attack test
		//if(! defined('NOSTYLECHECK'))   define('NOSTYLECHECK', '1');                        // Do not check style html tag into posted data
		//if(! defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL', '1');                // Do not check anti POST attack test
		//if(! defined('NOREQUIREMENU'))  define('NOREQUIREMENU', '1');                        // If there is no need to load and show top and left menu
		//if(! defined('NOREQUIREHTML'))  define('NOREQUIREHTML', '1');                        // If we don't need to load the html.form.class.php
		//if(! defined('NOREQUIREAJAX'))  define('NOREQUIREAJAX', '1');
		//if(! defined("NOLOGIN"))        define("NOLOGIN", '1');                                // If this page is public (can be called outside logged session)
		// Change this following line to use the correct relative path (../, ../../, etc)
include 'core/lib/includeMain.lib.php';
		// Change this following line to use the correct relative path from htdocs
		//include_once(DOL_DOCUMENT_ROOT.'/core/class/formcompany.class.php');
		//require_once 'lib/attendanceSystem.lib.php';
require_once 'class/AttendanceSystem.class.php';
require_once 'class/AttendanceSystemUser.class.php';
require_once 'class/AttendanceSystemUserLink.class.php';
require_once 'core/lib/generic.lib.php';
require_once 'core/lib/AttendanceSystem.lib.php';
include_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
		//document handling
include_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';
		//include_once DOL_DOCUMENT_ROOT.'/core/lib/images.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formother.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formprojet.class.php';
require_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';
require_once DOL_DOCUMENT_ROOT.'/projet/class/project.class.php';
require_once DOL_DOCUMENT_ROOT.'/projet/class/task.class.php';
require_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';
$PHP_SELF = $_SERVER['PHP_SELF'];
		// Load traductions files requiredby by page
		//$langs->load("companies");
$langs->load("AttendanceSystem@attendanceSystem");
		// Get parameter
$id = GETPOST('id', 'int');
$ref = GETPOST('ref', 'alpha');
$action = GETPOST('action', 'alpha');
$backtopage = GETPOST('backtopage');
$cancel = GETPOST('cancel');
$confirm = GETPOST('confirm');
$tms = GETPOST('tms', 'alpha');
		//// Get parameters
/*
$sortfield = GETPOST('sortfield', 'alpha');
$sortorder = GETPOST('sortorder', 'alpha')?GETPOST('sortorder', 'alpha'):'ASC';
$removefilter = isset($_POST["removefilter_x"]) || isset($_POST["removefilter"]);
		//$applyfilter = isset($_POST["search_x"]) ;//|| isset($_POST["search"]);
if(!$removefilter)                // Both test must be present to be compatible with all browsers {
        $ls_label = GETPOST('ls_label', 'alpha');
        $ls_ip = GETPOST('ls_ip', 'alpha');
        $ls_port = GETPOST('ls_port', 'int');
        $ls_note = GETPOST('ls_note', 'alpha');
        $ls_third_party = GETPOST('ls_third_party', 'int');
        $ls_task = GETPOST('ls_task', 'int');
        $ls_project = GETPOST('ls_project', 'int');
        $ls_serial_nb = GETPOST('ls_serial_nb', 'int');
        $ls_zone = GETPOST('ls_zone', 'int');
        $ls_passwd = GETPOST('ls_passwd', 'alpha');
        $ls_status = GETPOST('ls_status', 'int');
        $ls_mode = GETPOST('ls_mode', 'int');
}
*/
 // uncomment to avoid resubmision
		//if(isset($_SESSION['AttendanceSystem_class'][$tms]))
		//{
 //   $cancel = true;
 //  setEventMessages('Internal error, POST not exptected', null, 'errors');
		//}
		// Right Management
 /*
if($user->societe_id > 0 ||
       (!$user->rights->attendanceSystem->add && ($action == 'add' || $action = 'create')) ||
       (!$user->rights->attendanceSystem->view && ($action == 'list' || $action = 'view')) ||
       (!$user->rights->attendanceSystem->delete && ($action == 'confirm_delete')) ||
       (!$user->rights->attendanceSystem->edit && ($action == 'edit' || $action = 'update')))
{
        accessforbidden();
}
*/
		// create object and set id or ref if provided as parameter
$object = new AttendanceSystem($db);
if($id>0) {
    $object->id = $id;
    $object->fetch($id);
    $ref = dol_sanitizeFileName($object->ref);
}
if(!empty($ref)) {
    $object->ref = $ref;
    $object->id = $id;
    $object->fetch($id, $ref);
    $ref = dol_sanitizeFileName($object->ref);
}
/*******************************************************************
* ACTIONS
*
* Put here all code to do according to value of "action" parameter
********************************************************************/
		// Action to add record
$error = 0;
if(empty($action)){
    $action = 'view';
}
if($cancel) {
        AttendanceSystemReloadPage($backtopage, $id, $ref);
} elseif(($action == 'add') || ($action == 'update' && ($id>0 || !empty($ref)))) {
    //block resubmit
    if(empty($tms) || (!isset($_SESSION['AttendanceSystem'][$tms]))) {
            setEventMessage('WrongTimeStamp_requestNotExpected', 'errors');
            $action = ($action == 'add')?'create':'view';
    }
    //retrive the data
    $object->label = GETPOST('Label');
	$object->ip = GETPOST('Ip');
	$object->port = GETPOST('Port');
	$object->note = GETPOST('Note');
	$object->third_party = GETPOST('Thirdparty');
    $object->task = (GETPOST('Task') == '-1')?'':GETPOST('Task');
	$object->project = GETPOST('Project');
	$object->serial_nb = GETPOST('Serialnb');
	$object->zone = GETPOST('Zone');
	$object->passwd = GETPOST('Passwd');
	$object->status = GETPOST('Status');
	$object->mode = GETPOST('Mode');
		// test here if the post data is valide
 /*
 if($object->prop1 == 0 || $object->prop2 == 0) {
     if($id>0 || $ref!='')
        $action = 'create';
     else
        $action = 'edit';
 }
  */
} elseif($id == 0 && $ref == '' && $action!='create') {
    $action = 'create';
}
switch($action) {
    case 'update':
        $result = $object->update($user);
        if($result > 0) {
            // Creation OK
            unset($_SESSION['AttendanceSystem'][$tms]);
            setEventMessage('RecordUpdated', 'mesgs');
        } else {
                // Creation KO
            if(! empty($object->errors)) setEventMessages(null, $object->errors, 'errors');
            else setEventMessage('RecordNotUpdated', 'errors');
        }
        $action = 'view';
    case 'delete':
        if(isset($_GET['urlfile'])) $action = 'deletefile';
    case 'view':
    case 'viewinfo':
    case 'edit':
        // fetch the object data if possible
        if($id > 0 || !empty($ref)) {
            $result = $object->fetch($id, $ref);
            if($result < 0) {
                dol_print_error($db);
            } else {
            // fill the id & ref
                if(isset($object->id))$id = $object->id;
                if(isset($object->rowid))$id = $object->rowid;
                if(isset($object->ref))$ref = $object->ref;
            }
        } else {
            setEventMessage($langs->trans('noIdPresent').' id:'.$id, 'errors');
            $action = 'create';
        }
        break;
    case 'add':
        $result = $object->create($user);
        if($result > 0) {
            // Creation OK
        // remove the tms
           unset($_SESSION['AttendanceSystem'][$tms]);
           setEventMessage('RecordSucessfullyCreated', 'mesgs');
           AttendanceSystemReloadPage($backtopage, $result, '');
        } else {
            // Creation KO
            if(! empty($object->errors)) setEventMessages(null, $object->errors, 'errors');
            else  setEventMessage('RecordNotSucessfullyCreated', 'errors');
            $action = 'create';
        }
        break;
     case 'confirm_delete':
            $result = ($confirm == 'yes')?$object->delete($user):0;
            if($result > 0) {
                // Delete OK
                setEventMessage($langs->trans('RecordDeleted'), 'mesgs');
            } else {
                // Delete NOK
                if(! empty($object->errors)) setEventMessages(null, $object->errors, 'errors');
                else setEventMessage('RecordNotDeleted', 'errors');
            }
            AttendanceSystemReloadPage($backtopage, 0, '');
         break;
         case 'get_user':
            $result = $object->getUsers($user); //uid,string name,int role, string passwd
            //echo 'result:';
            //var_dump($result);
            if($result > 0) {
                // get user  OK
                $result2 = $object->loadAttendanceUserFromArray( $result);
                if($result2 > 0) {
                    setEventMessage($langs->trans('UserRetrieved'), 'mesgs');
                }else{
                    setEventMessage('UserNotSaved', 'errors');
                }
            } else {
                // get user NOK
                if(! empty($object->errors)) setEventMessages(null, $object->errors, 'errors');
                else setEventMessage('UserNotRetrieved', 'errors');
            }
            $action = "view"; 
         break;
         case 'get_time':
            $result = $object->importEvent($user);
            if($result > 0) {
                $result2 = loadAttendanceUserEventFromArray($object->ip, $object->third_party, $object->project, $object->task, $result);
                if($result2 > 0) {
                    setEventMessage($langs->trans('EventRetrieved'), 'mesgs');
                }else{
                    setEventMessage('EventNotSaved', 'errors');
                }

            } else {
                // get user NOK
                if(! empty($object->errors)) setEventMessages(null, $object->errors, 'errors');
                else setEventMessage('TimeNotRetrieved', 'errors');
            }
            $action = "view";            
         break;
         case 'test_connection':
            $result = $object->testConnection($user);
            if($result > 0) {
                // get user  OK
                setEventMessage($langs->trans('ConnectionOK'), 'mesgs');
            } else {
                // get user NOK
                if(! empty($object->errors)) setEventMessages(null, $object->errors, 'errors');
                else setEventMessage('ConnectionNOK', 'errors');
            }
            $action = "view"; 
         break;
}
		//Removing the tms array so the order can't be submitted two times
if(isset($_SESSION['AttendanceSystem'][$tms])) {
    unset($_SESSION['AttendanceSystem'][$tms]);
}
if(($action == 'create') || ($action == 'edit' && ($id>0 || !empty($ref)))) {
    $tms = getToken();
    $_SESSION['AttendanceSystem'][$tms] = array();
    $_SESSION['AttendanceSystem'][$tms]['action'] = $action;
}
/***************************************************
* VIEW
*
* Put here all code to build page
****************************************************/
llxHeader('', 'AttendanceSystem', '');
print "<div> <!-- module body-->";
$form = new Form($db);
$formother = new FormOther($db);
$formproject = new FormProjets($db);
$fuser = new User($db);
		// Put here content of your page
		// Example : Adding jquery code
/*print '<script type = "text/javascript" language = "javascript">
jQuery(document).ready(function()
{
        function init_myfunc()
        {
                jQuery("#myid").removeAttr(\'disabled\');
                jQuery("#myid").attr(\'disabled\', \'disabled\');
        }
        init_myfunc();
        jQuery("#mybutton").click(function()
{
                init_needroot();
        });
});
</script>';*/
$edit = $new = 0;
switch($action) {
    case 'create':
        $new = 1;
    case 'edit':
        $edit = 1;
    case 'delete';
        if($action == 'delete' && ($id>0 || $ref!="")) {
         $ret = $form->form_confirm($PHP_SELF.'?action=confirm_delete&id='.$id, $langs->trans('DeleteAttendanceSystem'), $langs->trans('ConfirmDelete'), 'confirm_delete', '', 0, 1);
         if($ret == 'html') print '<br />';
         //to have the object to be deleted in the background\
        }
    case 'view':
        // tabs
        if($edit == 0 && $new == 0) {
            //show tabs
            $head = AttendanceSystemPrepareHead($object);
            dol_fiche_head($head, 'card', $langs->trans('AttendanceSystem'), 0, 'attendanceSystem@attendanceSystem');
        } else{
            print_fiche_titre($langs->trans('AttendanceSystem'));
        }
        print '<br>';
        if($edit == 1) {
            if($new == 1) {
                print '<form method = "POST" action = "'.$PHP_SELF.'?action=add">';
            } else{
                print '<form method = "POST" action = "'.$PHP_SELF.'?action=update&id='.$id.'">';
            }
            print '<input type = "hidden" name = "tms" value = "'.$tms.'">';
            print '<input type = "hidden" name = "backtopage" value = "'.$backtopage.'">';
        } else {
            // show the nav bar
            $basedurl = dol_buildpath("/attendanceSystem/AttendanceSystemList.php", 1);
            $linkback = '<a href = "'.$basedurl.(! empty($socid)?'?socid='.$socid:'').'">'.$langs->trans("BackToList").'</a>';
            if(!isset($object->ref))//save ref if any
                $object->ref = $object->id;
            print $form->showrefnav($object, 'action = view&id', $linkback, 1, 'rowid', 'ref', '');
            //reloqd the ref
        }
        print '<table class = "border centpercent">'."\n";
		// show the field label
        print "<tr>\n";
        print '<td class = "fieldrequired">'.$langs->trans('Label').' </td><td>';
        if($edit == 1) {
                print '<input type = "text" value = "'.$object->label.'" name = "Label">';
        } else {
                print $object->label;
        }
        print "</td>";
        print "\n</tr>\n";
		// show the field ip
        print "<tr>\n";
        print '<td class = "fieldrequired">'.$langs->trans('Ip').' </td><td>';
        if($edit == 1) {
            print '<input type = "text" value = "'.$object->ip.'" name = "Ip">';
        } else {
                print $object->ip;
        }
        print "</td>";
        print "\n</tr>\n";
		// show the field port
        print "<tr>\n";
        print '<td>'.$langs->trans('Port').' </td><td>';
        if($edit == 1) {
            if($new == 1)
                    print '<input type = "text" value = "4370" name = "Port">';
            else
                    print '<input type = "text" value = "'.$object->port.'" name = "Port">';
        } else{
            print $object->port;
        }
        print "</td>";
        print "\n</tr>\n";
		// show the field note
        print "<tr>\n";
        print '<td>'.$langs->trans('Note').' </td><td>';
        if($edit == 1) {
            print '<input type = "text" value = "'.$object->note.'" name = "Note">';
        } else{
            print $object->note;
        }
        print "</td>";
        print "\n</tr>\n";
		// show the field third_party
        print "<tr>\n";
        print '<td>'.$langs->trans('Thirdparty').' </td><td>';
        if($edit == 1) {
            $selected = ($new)?-1:$object->third_party;
            $htmlname = 'Thirdparty';
            print $form->select_company($selected, $htmlname, '', 1);
        } else {
            if($object->third_party>0) {
                $StaticObject = New Societe($db);
                $StaticObject->fetch($object->third_party);
                print $StaticObject->getNomUrl(1);
            }else{
                print "<td></td>";
            }
        }
        print "</td>";
        print "\n</tr>\n";
		// show the field task
        print "<tr>\n";
        print '<td>'.$langs->trans('Task').' </td><td>';
        if($edit == 1) {
            $selected = $object->task;
            $htmlname = 'Task';
            $formproject->selectTasks(-1, $selected, $htmlname);
        } else{
            if($object->task>0) {
                $StaticObject = New Task($db);
                $StaticObject->fetch($object->task);
                print $StaticObject->getNomUrl(1);
            } else{
                print "<td></td>";
            }
        }
        print "</td>";
        print "\n</tr>\n";
		// show the field project
        print "<tr>\n";
        print '<td>'.$langs->trans('Project').' </td><td>';
        if($edit == 1) {
            $selected = $object->project;
            $htmlname = 'Project';
            $formproject->select_projects(-1, $selected, $htmlname);
        } else{
            if($object->project>0) {
                    $StaticObject = New Project($db);
                    $StaticObject->fetch($object->project);
                    print $StaticObject->getNomUrl(1);
            } else{
                print "<td></td>";
            }
        }
        print "</td>";
        print "\n</tr>\n";
        
        // show the field serial_nb
        
        print "<tr>\n";
        print '<td>'.$langs->trans('Serialnb').' </td><td>';
        if($edit == 1){
            print '<input type="text" value="'.$object->serial_nb.'" name="Serialnb">';
        }else{
            print $object->serial_nb;
        }
        print "</td>";
        print "\n</tr>\n";
        
        // show the field zone

        print "<tr>\n";
        print '<td class="fieldrequired">'.$langs->trans('Zone').' </td><td>';
        if($edit == 1){
            print '<input type="text" value="'.$object->zone.'" name="Zone">';
        }else{
            print $object->zone;
        }
        print "</td>";
        print "\n</tr>\n";
        
        // show the field passwd
        
        print "<tr>\n";
        print '<td>'.$langs->trans('Passwd').' </td><td>';
        if($edit == 1){
            print '<input type="text" value="'.$object->passwd.'" name="Passwd">';
        }else{
            print $object->passwd;
        }
        print "</td>";
        print "\n</tr>\n";
		// show the field status
        print "<tr>\n";
        print '<td>'.$langs->trans('Status').' </td><td>';
        if($edit == 1) {
            print $object->selectLibStatut($form);
        } else{
            print $object->libStatut($object->status);
        }
        print "</td>";
        print "\n</tr>\n";
        // show the field mode

	print "<tr>\n";
	print '<td>'.$langs->trans('Mode').' </td><td>';
	if($edit == 1){
		if ($new == 1)
			print '<input type="text" value="1" name="Mode">';
		else
			print '<input type="text" value="'.$object->mode.'" name="Mode">';
	}else{
		print $object->mode;
	}
	print "</td>";
	print "\n</tr>\n";
        print '</table>'."\n";
        print '<br>';
        print '<div class = "center">';
        if($edit == 1) {
            if($new == 1) {
                print '<input type = "submit" class = "butAction" name = "add" value = "'.$langs->trans('Add').'">';
            } else{
                print '<input type = "submit" name = "update" value = "'.$langs->trans('Update').'" class = "butAction">';
            }
            print ' &nbsp;<input type = "submit" class = "butActionDelete" name = "cancel" value = "'.$langs->trans('Cancel').'"></div>';
            print '</form>';
        } else {
            $parameters = array();
            $reshook = $hookmanager->executeHooks('addMoreActionsButtons', $parameters, $object, $action);// Note that $action and $object may have been modified by hook
            if($reshook < 0) setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');
            if(empty($reshook)) {
                print '<div class = "tabsAction">';
                print '<a href = "'.$PHP_SELF.'?id='.$id.'&action=test_connection" class = "butAction">'.$langs->trans('TestConnection').'</a>';
                print '<a href = "'.$PHP_SELF.'?id='.$id.'&action=get_user" class = "butAction">'.$langs->trans('GetUsers').'</a>';
                print '<a href = "'.$PHP_SELF.'?id='.$id.'&action=get_time" class = "butAction">'.$langs->trans('GetTime').'</a>';
                print '<a href = "'.$PHP_SELF.'?id='.$id.'&action=edit" class = "butAction">'.$langs->trans('Update').'</a>';
                print '<a class = "butActionDelete" href = "'.$PHP_SELF.'?id='.$id.'&action=delete">'.$langs->trans('Delete').'</a>';

                print '</div>';
            }
        }
        break;
    case 'viewinfo':
        print_fiche_titre($langs->trans('AttendanceSystem'));
        $head = AttendanceSystemPrepareHead($object);
        dol_fiche_head($head, 'info', $langs->trans("AttendanceSystem"), 0, 'attendanceSystem@attendanceSystem');
        print '<table width = "100%"><tr><td>';
        dol_print_object_info($object);
        print '</td></tr></table>';
        print '</div>';
        break;
    case 'delete':
        if(($id>0 || $ref!='')) {
            $ret = $form->form_confirm($PHP_SELF.'?action=confirm_delete&id='.$id, $langs->trans('DeleteAttendanceSystem'), $langs->trans('ConfirmDelete'), 'confirm_delete', '', 0, 1);
            if($ret == 'html') print '<br />';
            //to have the object to be deleted in the background
        }
}
dol_fiche_end();
		// End of page
llxFooter();
$db->close();
