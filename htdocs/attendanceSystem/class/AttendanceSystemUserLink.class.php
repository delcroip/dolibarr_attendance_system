<?php
/* Copyright (C) 2007-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2014	   Juanjo Menent		<jmenent@2byte.es>
 * Copyright (C) 2018	   Patrick DELCROIX     <pmpdelcroix@gmail.com>
 * Copyright (C) ---Put here your own copyright and developer email---
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *  \file       dev/attendancesystemuserlinks/attendancesystemuserlink.class.php
 *  \ingroup    attendanceSystem othermodule1 othermodule2
 *  \brief      This file is an example for a CRUD class file (Create/Read/Update/Delete)
 *				Initialy built by build_class_from_table on 2020-03-28 19:01
 */

// Put here all includes required by your class file
require_once DOL_DOCUMENT_ROOT.'/core/class/commonobject.class.php';
//require_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';
//require_once DOL_DOCUMENT_ROOT.'/product/class/product.class.php';
//require_once(DOL_DOCUMENT_ROOT.'/projet/class/project.class.php');
require_once 'core/lib/generic.lib.php';
$attendancesystemuserlinkStatusPictoArray = array(0 => 'statut7',1 => 'statut3',2 => 'statut8',3 => 'statut4');
$attendancesystemuserlinkStatusArray = array(0 => 'Draft',1 => 'Validated',2 => 'Cancelled',3 => 'Payed');
/**
 *	Put here description of your class
 */
class AttendanceSystemUserLink extends CommonObject
{
    /**
     * @var string ID to identify managed object
     */				//!< To return several error codes (or messages)
    public $element = 'attendancesystemuserlink';			//!< Id that identify managed objects
    /**
     * @var string Name of table without prefix where object is stored
     */    
    public $table_element = 'attendance_system_user_link';		//!< Name of table without prefix where object is stored

    public $id;
    // BEGIN OF automatic var creation
    
	public $attendance_system;
	public $attendance_system_user;
	public $date_modification = '';
	public $user_modification;

    
    // END OF automatic var creation


    /**
     *  Constructor
     *
     *  @param	DoliDb		$db      Database handler
     */
    function __construct($db)
    {
        $this->db = $db;
        return 1;
    }


    /**
     *  Create object into database
     *
     *  @param	User	$user        User that creates
     *  @param  int		$notrigger   0=launch triggers after, 1=disable triggers
     *  @return int      		   	 <0 if KO, Id of created object if OK
     */
    function create($user, $notrigger = 0)
    {
    	global $conf, $langs;
		$error = 0;

		// Clean parameters
        $this->cleanParam();
        //if($this->fetchDuplicate()>0)  return 1;
		// Check parameters
		// Put here code to add control on parameters values

        // Insert request
        $sql = "INSERT INTO ".MAIN_DB_PREFIX.$this->table_element."(";
        
		$sql .= 'fk_attendance_system,';
		$sql .= 'fk_attendance_system_user';
        $sql .= ") SELECT ";
		$sql .= ' '.(empty($this->attendance_system)?'NULL':"'".$this->attendance_system."'").',';
		$sql .= ' '.(empty($this->attendance_system_user)?'NULL':"'".$this->attendance_system_user."'").'';
        if($this->db->type!='mysql')$sql .= ' FROM DUAL ';
        $sql .= ' WHERE NOT EXISTS (SELECT rowid FROM '.MAIN_DB_PREFIX.$this->table_element.' WHERE';
        $sql .= ' fk_attendance_system '.(empty($this->attendance_system)?'is NULL':"= '".$this->attendance_system."'").' AND ';
		$sql .= ' fk_attendance_system_user '.(empty($this->attendance_system_user)?'is NULL':"= '".$this->attendance_system_user."'");
        $sql .= ")";

        $this->db->begin();

        dol_syslog(__METHOD__, LOG_DEBUG);
        $resql = $this->db->query($sql);
    	if (! $resql) { $error++; $this->errors[]="Error ".$this->db->lasterror(); }

        if (! $error)
        {
            $this->id = $this->db->last_insert_id(MAIN_DB_PREFIX.$this->table_element);

            if (! $notrigger)
            {
            // Uncomment this and change MYOBJECT to your own tag if you
            // want this action calls a trigger.

            //// Call triggers
            //$result = $this->call_trigger('MYOBJECT_CREATE', $user);
            //if ($result < 0) { $error++; //Do also what you must do to rollback action if trigger fail}
            //// End call triggers
            }
        }

        // Commit or rollback
        if ($error)
        {
            foreach($this->errors as $errmsg)
            {
                dol_syslog(__METHOD__." ".$errmsg, LOG_ERR);
                $this->error .= ($this->error?', '.$errmsg:$errmsg);
            }
            $this->db->rollback();
            return -1*$error;
        }
        else
        {
            $this->db->commit();
            return $this->id;
        }
    }


    /**
     *  Load object in memory from the database
     *
     *  @param	int		$id    	Id object
     *  @param	string	$ref	Ref
     *  @return int          	<0 if KO, >0 if OK
     */
    function fetch($id, $ref = '')
    {
    	global $langs;
        $sql = "SELECT";
        $sql .= " t.rowid,";
        
		$sql .= ' t.fk_attendance_system,';
		$sql .= ' t.fk_attendance_system_user,';
		$sql .= ' t.date_modification,';
		$sql .= ' t.fk_user_modification';

        
        $sql .= " FROM ".MAIN_DB_PREFIX.$this->table_element." as t";
        if ($ref) $sql .= " WHERE t.ref = '".$ref."'";
        else $sql .= " WHERE t.rowid = ".$id;
    	dol_syslog(__METHOD__, LOG_DEBUG);
        $resql = $this->db->query($sql);
        if ($resql)
        {
            if ($this->db->num_rows($resql))
            {
                $obj = $this->db->fetch_object($resql);
                $this->id = $obj->rowid;
                
		$this->attendance_system = $obj->fk_attendance_system;
		$this->attendance_system_user = $obj->fk_attendance_system_user;
		$this->date_modification = $this->db->jdate($obj->date_modification);
		$this->user_modification = $obj->fk_user_modification;

                
            }
            $this->db->free($resql);

            return 1;
        }
        else
        {
      	    $this->error="Error ".$this->db->lasterror();
            return -1;
        }
    }

        /**
     *  Look for duplicate
     *  @return int          	<0 if KO, >0 if OK
     */

    /**
     *  Update object into database
     *
     *  @param	User	$user        User that modifies
     *  @param  int		$notrigger	 0=launch triggers after, 1=disable triggers
     *  @return int     		   	 <0 if KO, >0 if OK
     */
    function update($user, $notrigger = 0)
    {
	$error = 0;
        // Clean parameters
        $this->cleanParam(true);
        // Check parameters
        // Put here code to add a control on parameters values
        // Update request
        $sql = "UPDATE ".MAIN_DB_PREFIX.$this->table_element." SET";
        $sql .= $this->setSQLfields($user);
        $sql .= " WHERE rowid=".$this->id;
		$this->db->begin();
		dol_syslog(__METHOD__, LOG_DEBUG);
        $resql = $this->db->query($sql);
    	if (! $resql) { $error++; $this->errors[]="Error ".$this->db->lasterror(); }
            if (! $error)
            {
                if (! $notrigger)
                {
            // Uncomment this and change MYOBJECT to your own tag if you
            // want this action calls a trigger.

            //// Call triggers
            //$result = $this->call_trigger('MYOBJECT_MODIFY', $user);
            //if ($result < 0) { $error++; //Do also what you must do to rollback action if trigger fail}
            //// End call triggers
                 }
            }

        // Commit or rollback
            if ($error)
            {
                foreach($this->errors as $errmsg)
                {
                    dol_syslog(__METHOD__." ".$errmsg, LOG_ERR);
                    $this->error .= ($this->error?', '.$errmsg:$errmsg);
                }
                $this->db->rollback();
                return -1*$error;
            }
            else
            {
                $this->db->commit();
                return 1;
            }
    }

 
     /**
     *	Return clickable name (with picto eventually)
     *
     *	@param		int			$withpicto		0=_No picto, 1=Includes the picto in the linkn, 2=Picto only
     *	@param		int			$id                     Object ID
     *	@param		string			$ref                    Object ref
     *	@return		string						String with URL
     */
    function getNomUrl($withpicto, $id = 0, $ref = '')
    {
	global $conf, $langs;


        if (! empty($conf->dol_no_mouse_hover)) $notooltip = 1;   // Force disable tooltips
    	$result = '';
        if(empty($ref) && $id == 0){
            if(isset($this->id))  {
                $id = $this->id;
            }else if (isset($this->rowid)){
                $id = $this->rowid;
            }if(isset($this->ref)){
                $ref = $this->ref;
            }
        }
        $linkclose = '';
        $label = '';
        //field to show beside the icon
        $label .= 'field1: '.$this->field1;

        //info card more info could be display
        $card = '<u>' . $langs->trans("AttendanceSystemUserLink") . '</u>';
        $card .= '<br>';
        if($ref){
            $card .= $langs->trans("Ref").': '.$ref;
        }else if($id){
            $card .= $langs->trans("#").': '.$id;
        }

        if (empty($notooltip))
        {
            if (! empty($conf->global->MAIN_OPTIMIZEFORTEXTBROWSER))
            {
                $label = $langs->trans("AttendanceSystemUserLink");
                $linkclose .= ' alt = "'.dol_escape_htmltag($label, 1).'"';
            }
            $linkclose .= ' title = "'.dol_escape_htmltag($card, 1).'"';
            $linkclose .= ' class = "classfortooltip'.($morecss?' '.$morecss:'').'"';
        }else $linkclose = ($morecss?' class = "'.$morecss.'"':'');
        
        if($id){
            $lien = '<a href = "'.dol_buildpath('/attendanceSystem/AttendanceSystemUserLinkCard.php',1).'?id='.$id.'&action=view"'.$linkclose.'>';
        }else if (!empty($ref)){
            $lien = '<a href = "'.dol_buildpath('/attendanceSystem/AttendanceSystemUserLinkCard.php',1).'?ref='.$ref.'&action=view"'.$linkclose.'>';
        }else{
            $lien = "";
        }
        $lienfin = empty($lien)?'':'</a>';

    	$picto = 'generic';
 
        
        
        
    	if ($withpicto == 1){ 
            $result .= ($lien.img_object($label, $picto).$htmlcontent.$lienfin);
        }else if ($withpicto == 2) {
            $result .= $lien.img_object($label, $picto).$lienfin;
        }else{  
            $result .= $lien.$label.$lienfin;
        }
    	return $result;
    }  
     /**
	 *  Retourne select libelle du status (actif, inactif)
	 *
	 *  @param	object 		$form          form object that should be created	
      *  *  @return	string 			       html code to select status
	 */
	function selectLibStatut($form, $htmlname = 'Status')
	{
            global $attendancesystemuserlinkStatusPictoArray, $attendancesystemuserlinkStatusArray;
            return $form->selectarray($htmlname, $attendancesystemuserlinkStatusArray, $this->status);
	}   
    /**
	 *  Retourne le libelle du status (actif, inactif)
	 *
	 *  @param	int		$mode          0 = libelle long, 1 = libelle court, 2 = Picto + Libelle court, 3 = Picto, 4 = Picto + Libelle long, 5 = Libelle court + Picto
	 *  @return	string 			       Label of status
	 */
	function getLibStatut($mode = 0)
	{
		return $this->LibStatut($this->status, $mode);
	}
	/**
	 *  Return the status
	 *
	 *  @param	int		$status        	Id status
	 *  @param  int		$mode          	0 = long label, 1 = short label, 2 = Picto + short label, 3 = Picto, 4 = Picto + long label, 5 = Short label + Picto, 6 = Long label + Picto
	 *  @return string 			       	Label of status
	 */
	static function LibStatut($status, $mode = 0)
	{
		global $langs, $attendancesystemuserlinkStatusPictoArray, $attendancesystemuserlinkStatusArray;
		if ($mode == 0)
		{
			$prefix = '';
			return $langs->trans($attendancesystemuserlinkStatusArray[$status]);
		}
		if ($mode == 1)
		{
			return $langs->trans($attendancesystemuserlinkStatusArray[$status]);
		}
		if ($mode == 2)
		{
			 return img_picto($attendancesystemuserlinkStatusArray[$status], $attendancesystemuserlinkStatusPictoArray[$status]).' '.$langs->trans($attendancesystemuserlinkStatusArray[$status]);
		}
		if ($mode == 3)
		{
			 return img_picto($attendancesystemuserlinkStatusArray[$status], $attendancesystemuserlinkStatusPictoArray[$status]);
		}
		if ($mode == 4)
		{
			 return img_picto($attendancesystemuserlinkStatusArray[$status], $attendancesystemuserlinkStatusPictoArray[$status]).' '.$langs->trans($attendancesystemuserlinkStatusArray[$status]);
		}
		if ($mode == 5)
		{
			 return $langs->trans($attendancesystemuserlinkStatusArray[$status]).' '.img_picto($attendancesystemuserlinkStatusArray[$status], $attendancesystemuserlinkStatusPictoArray[$status]);
		}
		if ($mode == 6)
		{
			 return $langs->trans($attendancesystemuserlinkStatusArray[$status]).' '.img_picto($attendancesystemuserlinkStatusArray[$status], $attendancesystemuserlinkStatusPictoArray[$status]);
		}
	}

    /**
     *  Delete object in database
     *
    *	@param  User	$user        User that deletes
    *   @param  int		$notrigger	 0 = launch triggers after, 1 = disable triggers
     *  @return	int					 <0 if KO, >0 if OK
     */
    function delete($user, $notrigger = 0)
    {
        global $conf, $langs;
        $error = 0;
        $this->db->begin();
        if (! $error)
        {
            if (! $notrigger)
            {
        // Uncomment this and change MYOBJECT to your own tag if you
        // want this action calls a trigger.
        //// Call triggers
        //$result = $this->call_trigger('MYOBJECT_DELETE', $user);
        //if ($result < 0) { $error++; //Do also what you must do to rollback action if trigger fail}
        //// End call triggers
            }
        }
        if (! $error)
        {
        $sql = "DELETE FROM ".MAIN_DB_PREFIX.$this->table_element;
        $sql .= " WHERE rowid = ".$this->id;

        dol_syslog(__METHOD__, LOG_DEBUG);
        $resql = $this->db->query($sql);
        if (! $resql) { $error++; $this->errors[] = "Error ".$this->db->lasterror(); }
        else if ($this->db->affected_rows($resql) == 0){$error++;$this->errors[] = "Item no found in database"; }

        }

// Commit or rollback
        if ($error)
        {
            foreach($this->errors as $errmsg)
            {
                dol_syslog(__METHOD__." ".$errmsg, LOG_ERR);
                $this->error .= ($this->error?', '.$errmsg:$errmsg);
            }
            $this->db->rollback();
            return -1*$error;
        }
        else
        {
            $this->db->commit();
            return 1;
        }
    }



    /**
     *	Load an object from its id and create a new one in database
     *
     *	@param	int		$fromid     Id of object to clone
     * 	@return	int					New id of clone
     */
    function createFromClone($fromid)
    {
        global $user, $langs;
        $error = 0;
        $object = new AttendanceSystemUserLink($this->db);
        $this->db->begin();
        // Load source object
        $object->fetch($fromid);
        $object->id = 0;
        $object->statut = 0;
        // Clear fields
        // ...
        // Create clone
        $result = $object->create($user);

        // Other options
        if ($result < 0)
        {
            $this->error = $object->error;
            $error++;
        }
        if (! $error)
        {
        }
        // End
        if (! $error)
        {
            $this->db->commit();
            return $object->id;
        }
        else
        {
            $this->db->rollback();
            return -1;
        }
    }

    /**
     *	Initialise object with example values
     *	Id must be 0 if object instance is a specimen
     *
     *	@return	void
     */
    function initAsSpecimen($attendance_system = '', $attendance_system_user = '')
    {
        $this->id = 0;
        
	$this->attendance_system = $attendance_system;
	$this->attendance_system_user = $attendance_system_user;


        
    }
    /**
     *	will clean the parameters
     *	
     *
     *	@return	void
     */       
    function cleanParam(){
        
			if (!empty($this->attendance_system)) $this->attendance_system = trim($this->attendance_system);
			if (!empty($this->attendance_system_user)) $this->attendance_system_user = trim($this->attendance_system_user);
			if (!empty($this->date_modification)) $this->date_modification = trim($this->date_modification);
			if (!empty($this->user_modification)) $this->user_modification = trim($this->user_modification);

        
    }
     /**
     *	will create the sql part to update the parameters
     *	
     *
     *	@return	void
     */    
    function setSQLfields($user){
        $sql = '';
        
		$sql .= ' fk_attendance_system = '.(empty($this->attendance_system) != 0 ? 'null':"'".$this->attendance_system."'").',';
		$sql .= ' fk_attendance_system_user = '.(empty($this->attendance_system_user) != 0 ? 'null':"'".$this->attendance_system_user."'").',';
		$sql .= ' date_modification = NOW() ,';
		$sql .= ' fk_user_modification = "'.$user->id.'"';

        
        return $sql;
    }
    /*
    * function to save a attendancesystemuserlink as a string
    * @param    int     $mode   0 => serialize, 1 => json_encode, 2 => json_encode PRETTY PRINT 
    * @return   string       serialized object
    */
    public function serialize($mode = 0){
		$ret = '';
		$array = array();
		
		$array['attendance_system'] = $this->attendance_system;
		$array['attendance_system_user'] = $this->attendance_system_user;
		$array['date_modification'] = $this->date_modification;
		$array['user_modification'] = $this->user_modification;

		
		$array['processedTime'] = mktime();
        switch($mode)
        {
            default:
            case 0:
                $ret = serialize($array);
                break;
            case 1:
                $ret = json_encode($array);
                break;
            case 2:
                $ret = json_encode($array, JSON_PRETTY_PRINT);
                break;
        }
         return $ret;
    }
     /* function to load a attendancesystemuserlink as a string
     * @param   string    $str   serialized object
     * @param    int     $mode   0 => serialize, 1 => json_encode, 2 => json_encode PRETTY PRINT
     * @return  int              OK
     */    
       public function unserialize($str, $mode = 0){
       $ret = '';
       $array = array();
        switch($mode)
        {
            default:
            case 0:
                $array = unserialize($str);
                break;
            case 1:
            case 2:
                $array = json_decode($str);
                break;
        }
        // automatic unserialisation based on match between property name and key value
        foreach ($array as $key => $value) {
            if(property_exists($this,$key))$this->{$key} = $value;
        }
    }

        /**
     *  Function to generate a sellist
     *  @param string $htmlname name of the sellist input
     *  @param int $selected rowid to be preselected
     *  @return string HTML select list
     */
    
    Public function sellist($htmlname = '', $selected = ''){    
        $sql = array('table' => $this->table_element , 'keyfield' => 't.rowid', 'fields' => $this->getLabel('sql'), 'join' =>  $this->getLabel('join'), 'where' => '', 'tail' => '');
        $html = array('name' => (($htmlname == '')?'AttendanceSystemUserLink':$htmlname), 'class' => '', 'otherparam' => '', 'ajaxNbChar' => '', 'separator' => '-');
        $addChoices = null;
		return select_sellist($sql, $html, $selected, $addChoices );
    }

    /**     * function to define display of the object
     * @param string $type type of return text or sql
     * @return string Label
     */
    public function getLabel($type = 'text'){
        $ret = '';
        switch ($type){
            case 'sql':
                $ret = "t.fk_attendance_system as AttendanceSystem, t.fk_attendance_system_user as AttendanceSystemUser";
            break;
            case 'join':
                $ret = "";
            break;                
            case 'text':
            default:
                $ret = $this->attendance_system.' - '.$this->attendance_system_user;
            break;
        } 
        return $ret;
    }

}
