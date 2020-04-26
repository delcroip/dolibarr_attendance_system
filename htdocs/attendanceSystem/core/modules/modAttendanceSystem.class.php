<?php
/* Copyright (C) 2003      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis.houssin@capnetworks.com>
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
 *        \defgroup   attendanceSystem     Module attendanceSystem
 *  \brief      Example of a module descriptor.
 *                                Such a file must be copied into htdocs/attendanceSystem/core/modules directory.
 *  \file       htdocs/attendanceSystem/core/modules/modTimesheet.class.php
 *  \ingroup    attendanceSystem
 *  \brief      Description and activation file for module attendanceSystem
 */
include_once DOL_DOCUMENT_ROOT .'/core/modules/DolibarrModules.class.php';
/**
 *  Description and activation class for module attendanceSystem
 */
class modAttendanceSystem extends DolibarrModules
{
        /**
         *   Constructor. Define names, constants, directories, boxes, permissions
         *
         *   @param      DoliDB                $db      Database handler
         */
        public function __construct($db)
        {
        global $langs, $conf;
        $this->db = $db;
                // Id for module(must be unique).
                // Use here a free id(See in Home -> System information -> Dolibarr for list of used modules id).
                $this->numero = 861012;
                // Key text used to identify module(for permissions, menus, etc...)
                $this->rights_class = 'attendanceSystem';
                // Family can be 'crm', 'financial', 'hr', 'projects', 'products', 'ecm', 'technic', 'other'
                // It is used to group modules in module setup page
                $this->family = "hr";
                // Module label(no space allowed), used if translation string 'ModuleXXXName' not found(where XXX is value of numeric property 'numero' of module)
                $this->name = preg_replace('/^mod/i', '', get_class($this));
                // Module description, used if translation string 'ModuleXXXDesc' not found(where XXX is value of numeric property 'numero' of module)
                $this->description = "attendanceSystemDesc";
                // Possible values for version are: 'development', 'experimental', 'dolibarr' or version
                $this->version = '0.1';
                // Key used in llx_const table to save module status enabled/disabled(where attendanceSystem is value of property name of module in uppercase)
                $this->const_name = 'MAIN_MODULE_'.strtoupper($this->name);
                // Where to store the module in setup page(0=common, 1=interface, 2=others, 3=very specific)
                $this->special = 0;
                // Name of image file used for this module.
                // If file is in theme/yourtheme/img directory under name object_pictovalue.png, use this->picto='pictovalue'
                // If file is in module/img directory under name object_pictovalue.png, use this->picto='pictovalue@module'
                $this->picto='attendanceSystem@attendanceSystem';
                // Defined all module parts(triggers, login, substitutions, menus, css, etc...)
                // for default path (eg: /attendanceSystem/core/xxxxx) (0=disable, 1=enable)
                // for specific path of parts(eg: /attendanceSystem/core/modules/barcode)
                // for specific css file(eg: /attendanceSystem/css/attendanceSystem.css.php)
                //$this->module_parts = array('triggers' => 0,
                //                            'css' => array('/attendanceSystem/core/css/attendanceSystem.css'));
                ////$this->module_parts = array(
                //                         'triggers' => 0,        // Set this to 1 if module has its own trigger directory(core/triggers)
                //                                                        'login' => 0,        // Set this to 1 if module has its own login method directory(core/login)
                //                                                        'substitutions' => 0,        // Set this to 1 if module has its own substitution function file(core/substitutions)
                //                                                        'menus' => 0,        // Set this to 1 if module has its own menus handler directory(core/menus)
                //                                                        'theme' => 0,        // Set this to 1 if module has its own theme directory(theme)
                //                         'tpl' => 0,        // Set this to 1 if module overwrite template dir(core/tpl)
                //                                                        'barcode' => 0,        // Set this to 1 if module has its own barcode directory(core/modules/barcode)
                //                                                        'models' => 0,        // Set this to 1 if module has its own models directory(core/modules/xxx)
                //                                                        'css' => array('/attendanceSystem/css/attendanceSystem.css.php'),        // Set this to relative path of css file if module has its own css file
                //                                                        'js' => array('/attendanceSystem/js/attendanceSystem.js'), // Set this to relative path of js file if module must load a js on all pages
                //                                                        'hooks' => array('hookcontext1', 'hookcontext2')        // Set here all hooks context managed by module
                //                                                        'dir' => array('output' => 'othermodulename'), // To force the default directories names
                //                                                        'workflow' => array('WORKFLOW_MODULE1_YOURACTIONTYPE_MODULE2' => array('enabled' => '! empty($conf->module1->enabled) && ! empty($conf->module2->enabled)', 'picto' => 'yourpicto@attendanceSystem')) // Set here all workflow context managed by module
                //                      );
                //$this->module_parts = array();
                //$this->module_parts = array('css' => array('/attendanceSystem/css/attendanceSystem.css'));
                // Data directories to create when module is enabled.
                // Example: this->dirs = array("/attendanceSystem/temp");
                $this->dirs = array("/attendanceSystem", "/attendanceSystem/users");
                // Config pages. Put here list of php page, stored into attendanceSystem/admin directory, to use to setup module.
                $this->config_page_url = array("setup.php@attendanceSystem");
                // Dependencies
                $this->hidden = false;                        // A condition to hide module
                //$this->depends = array('modProjet','modHr');                // List of modules id that must be enabled if this module is enabled
                $this->requiredby = array();        // List of modules id to disable if this one is disabled
                $this->conflictwith = array();        // List of modules id this module is in conflict with
                $this->phpmin = array(5, 0);                                        // Minimum version of PHP required by module
                $this->need_dolibarr_version = array(9, 0);        // Minimum version of Dolibarr required by module
                $this->langfiles = array("attendanceSystem@attendanceSystem");
                // Constants
                // List of particular constants to add when module is enabled(key, 'chaine', value, desc, visible, 'current' or 'allentities', deleteonunactive)
                // Example: $this->const=array(0 => array('attendanceSystem_MYNEWCONST1', 'chaine', 'myvalue', 'This is a constant to add', 1),
                //                             1 => array('attendanceSystem_MYNEWCONST2', 'chaine', 'myvalue', 'This is another constant to add', 0, 'current', 1)
                //);
                $r = 0;
                $this->const = array();
                $this->const[$r] = array("ATTENDANCE_VERSION", "chaine", $this->version, "save the attendanceSystem verison");// hours or days
                $r++;
//                $this->const[$r] = array("ATTENDANCE_DAY_DURATION", "int", 8, "number of hour per day(used for the layout per day)");
//                $r++;
                $this->const[$r] = array("ATTENDANCE_DAY_MAX_DURATION", "int", 30, "maximum time for a day of 1 person 30 means that a day of work can finish at 6 the next day ");
                $r++;               
                $this->const[$r] = array("ATTENDANCE_MIN_OVERDAY_BREAK", "int", 12, "if two event have a bigger time distance then they wonn't be considred together");
                $r++;
                $this->const[$r] = array("ATTENDANCE_EVENT_MAX_DURATION", "int", 8, "max event duration");// hours or days
                $r++;
                $this->const[$r] = array("ATTENDANCE_EVENT_DEFAULT_DURATION", "int", 2, "default event duration");// hours or days
                $r++;
                $this->const[$r] = array("ATTENDANCE_EVENT_MIN_DURATION", "int", 15, "minimum time (second) per event (to manage double entries)");// hours or days
                $r++;
                $this->const[$r] = array("ATTENDANCE_ROUND", "int", "3", "round timespend display in day");// hours or days
                $r++;
                $this->const[$r] = array("ATTENDANCE_CLEAR_EVENT", "int", "1", "clear the attendance after fetching them");// hours or days
                $r++;
                
                                 //$this->const[2] = array("CONST3", "chaine", "valeur3", "Libelle3");
                // Array to add new pages in new tabs
                // Example: $this->tabs = array('objecttype:+tabname1:Title1:mylangfile@attendanceSystem:$user->rights->attendanceSystem->read:/attendanceSystem/mynewtab1.php?id=__ID__',        // To add a new tab identified by code tabname1
        //                              'objecttype:+tabname2:Title2:mylangfile@attendanceSystem:$user->rights->othermodule->read:/attendanceSystem/mynewtab2.php?id=__ID__',        // To add another new tab identified by code tabname2
        //                              'objecttype:-tabname':NU:conditiontoremove);// To remove an existing tab identified by code tabname
                // where objecttype can be
                // 'thirdparty'       to add a tab in third party view
                // 'intervention'     to add a tab in intervention view
                // 'order_supplier'   to add a tab in supplier order view
                // 'invoice_supplier' to add a tab in supplier invoice view
                // 'invoice'          to add a tab in customer invoice view
                // 'order'            to add a tab in customer order view
                // 'product'          to add a tab in product view
                // 'stock'            to add a tab in stock view
                // 'propal'           to add a tab in propal view
                // 'member'           to add a tab in fundation member view
                // 'contract'         to add a tab in contract view
                // 'user'             to add a tab in user view
                // 'group'            to add a tab in group view
                // 'contact'          to add a tab in contact view
                // 'payment'                  to add a tab in payment view
                // 'payment_supplier' to add a tab in supplier payment view
                // 'categories_x'          to add a tab in category view(replace 'x' by type of category(0=product, 1=supplier, 2=customer, 3=member)
                // 'opensurveypoll'          to add a tab in opensurvey poll view
        $this->tabs = array();
		// Example:
		// $this->tabs[] = array('data' => 'objecttype:+tabname1:Title1:mylangfile@project_cost:$user->rights->project_cost->read:/project_cost/mynewtab1.php?id=__ID__');  					// To add a new tab identified by code tabname1
        //$this->tabs[] = array('data' => 'project:+invoice:projectInvoice:attendanceSystem@attendanceSystem:$user->rights->facture->creer:/attendanceSystem/TimesheetProjectInvoice.php?projectid=__ID__');  					// To add a new tab identified by code tabname1
        //$this->tabs[] = array('data' => 'project:+report:projectReport:attendanceSystem@attendanceSystem:true:/attendanceSystem/TimesheetReportProject.php?projectSelected=__ID__');  					// To add a new tab identified by code tabname1
        // Dictionaries
        if(! isset($conf->attendanceSystem->enabled)) {
            $conf->attendanceSystem=new stdClass();
            $conf->attendanceSystem->enabled=0;
        }
                $this->dictionaries=array();
        /* Example:
        if(! isset($conf->attendanceSystem->enabled)) $conf->attendanceSystem->enabled=0;        // This is to avoid warnings
        $this->dictionaries=array(
            'langs' => 'mylangfile@attendanceSystem',
            'tabname' => array(MAIN_DB_PREFIX."table1", MAIN_DB_PREFIX."table2", MAIN_DB_PREFIX."table3"),                // List of tables we want to see into dictonnary editor
            'tablib' => array("Table1", "Table2", "Table3"),                                                                                                        // Label of tables
            'tabsql' => array('SELECT f.rowid as rowid, f.code, f.label, f.active FROM '.MAIN_DB_PREFIX.'table1 as f', 'SELECT f.rowid as rowid, f.code, f.label, f.active FROM '.MAIN_DB_PREFIX.'table2 as f', 'SELECT f.rowid as rowid, f.code, f.label, f.active FROM '.MAIN_DB_PREFIX.'table3 as f'),        // Request to select fields
            'tabsqlsort' => array("label ASC", "label ASC", "label ASC"),                                                                                                                                                                        // Sort order
            'tabfield' => array("code, label", "code, label", "code, label"),                                                                                                                                                                        // List of fields(result of select to show dictionary)
            'tabfieldvalue' => array("code, label", "code, label", "code, label"),                                                                                                                                                                // List of fields(list of fields to edit a record)
            'tabfieldinsert' => array("code, label", "code, label", "code, label"),                                                                                                                                                        // List of fields(list of fields for insert)
            'tabrowid' => array("rowid", "rowid", "rowid"),                                                                                                                                                                                                        // Name of columns with primary key(try to always name it 'rowid')
            'tabcond' => array($conf->attendanceSystem->enabled, $conf->attendanceSystem->enabled, $conf->attendanceSystem->enabled)                                                                                                // Condition to show each dictionary
      );
        */
        // Boxes
                // Add here list of php file(s) stored in core/boxes that contains class to show a box.
        //$this->boxes = array(
        //    0 => array(
        ///'file' => 'box_approval.php@attendanceSystem',
        //'note' => 'attendanceSystemApproval',
        //'enabledbydefaulton' => 'Home'));                        // List of boxes
                // Example:
                //$this->boxes=array(array(0 => array('file' => 'myboxa.php', 'note' => '', 'enabledbydefaulton' => 'Home'), 1 => array('file' => 'myboxb.php', 'note' => ''), 2 => array('file' => 'myboxc.php', 'note' => '')););
                // Permissions
                $this->rights = array();                // Permission array used by this module
                $r = 0;
                 $this->rights[$r][0] = 86101206;                                // Permission id(must not be already used)
                 $this->rights[$r][1] = 'AttendanceAdmin';        // Permission label
                 $this->rights[$r][3] = 0;                                        // Permission by default for new user(0/1)
                 $this->rights[$r][4] = 'System';                                // In php code, permission will be checked by test if($user->rights->permkey->level1->level2)
                 $this->rights[$r][5] = 'admin';                                // In php code, permission will be checked by test if($user->rights->permkey->level1->level2)
                 $r++;
// Example:
                // $this->rights[$r][0] = 2000;                                // Permission id(must not be already used)
                // $this->rights[$r][1] = 'Permision label';        // Permission label
                // $this->rights[$r][3] = 1;                                        // Permission by default for new user(0/1)
                // $this->rights[$r][4] = 'level1';                                // In php code, permission will be checked by test if($user->rights->permkey->level1->level2)
                // $this->rights[$r][5] = 'level2';                                // In php code, permission will be checked by test if($user->rights->permkey->level1->level2)
                // $r++;
                // Main menu entries
                $this->menu = array();                        // List of menus to add
                $r = 0;
                // Add here entries to declare new menus
                //
                // Example to declare a new Top Menu entry and its Left menu entry:
                
                $this->menu[$r]=array('fk_menu' => 'fk_mainmenu=hrm',                    // Use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx, fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
                        'type' => 'left',                                        // This is a Left menu entry
                        'titre' => 'AttendanceSystem',
                        'mainmenu' => 'hrm',
                        'leftmenu' => 'attendancesystem',
                        'url' => '/attendanceSystem/AttendanceSystemList.php',
                        'langs' => 'attendanceSystem@attendanceSystem',                // Lang file to use(without .lang) by module. File must be in langs/code_CODE/ directory.
                        'position' => 400,
                        'enabled' => 1, // Define condition to show or hide menu entry. Use '$conf->attendanceSystem->enabled' if entry must be visible if module is enabled. Use '$leftmenu == \'system\'' to show if leftmenu system is selected.
                        'perms' => '$user->rights->attendanceSystem->System->admin',                                     // Use 'perms' => '$user->rights->attendanceSystem->level1->level2' if you want your menu with a permission rules
                        'target' => '',
                        'user' => 2);
                $r++;
                $this->menu[$r]=array('fk_menu' => 'fk_mainmenu=hrm,fk_leftmenu=attendancesystem',                    // Use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx, fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
                        'type' => 'left',                                        // This is a Left menu entry
                        'titre' => 'AttendanceSystemList',
                        'mainmenu' => 'hrm',
                        'leftmenu' => 'AttendanceSystem',
                        'url' => '/attendanceSystem/AttendanceSystemList.php?action=list',
                        'langs' => 'attendanceSystem@attendanceSystem',                // Lang file to use(without .lang) by module. File must be in langs/code_CODE/ directory.
                        'position' => 450,
                        'enabled' => 1, // Define condition to show or hide menu entry. Use '$conf->attendanceSystem->enabled' if entry must be visible if module is enabled. Use '$leftmenu == \'system\'' to show if leftmenu system is selected.
                        'perms' => '$user->rights->attendanceSystem->System->admin',                                        // Use 'perms' => '$user->rights->attendanceSystem->level1->level2' if you want your menu with a permission rules
                        'target' => '',
                        'user' => 2);
                $r++;
                $this->menu[$r]=array('fk_menu' => 'fk_mainmenu=hrm,fk_leftmenu=attendancesystem',                    // Use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx, fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
                        'type' => 'left',                                        // This is a Left menu entry
                        'titre' => 'AttendanceSystemUser',
                        'mainmenu' => 'hrm',
                        'leftmenu' => 'AttendanceSystem',
                        'url' => '/attendanceSystem/AttendanceSystemUserList.php?action=list',
                        'langs' => 'attendanceSystem@attendanceSystem',                // Lang file to use(without .lang) by module. File must be in langs/code_CODE/ directory.
                        'position' => 410,
                        'enabled' => 1, // Define condition to show or hide menu entry. Use '$conf->attendanceSystem->enabled' if entry must be visible if module is enabled. Use '$leftmenu == \'system\'' to show if leftmenu system is selected.
                        'perms' => '$user->rights->attendanceSystem->System->admin',                                     // Use 'perms' => '$user->rights->attendanceSystem->level1->level2' if you want your menu with a permission rules
                        'target' => '',
                        'user' => 2);
                $r++;
                $this->menu[$r]=array('fk_menu' => 'fk_mainmenu=hrm,fk_leftmenu=attendancesystem',                    // Use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx, fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
                        'type' => 'left',                                        // This is a Left menu entry
                        'titre' => 'AttendanceSystemUserLink',
                        'mainmenu' => 'hrm',
                        'leftmenu' => 'AttendanceSystem',
                        'url' => '/attendanceSystem/AttendanceSystemUserLinkList.php?action=list',
                        'langs' => 'attendanceSystem@attendanceSystem',                // Lang file to use(without .lang) by module. File must be in langs/code_CODE/ directory.
                        'position' => 420,
                        'enabled' => 1, // Define condition to show or hide menu entry. Use '$conf->attendanceSystem->enabled' if entry must be visible if module is enabled. Use '$leftmenu == \'system\'' to show if leftmenu system is selected.
                        'perms' => '$user->rights->attendanceSystem->System->admin ',                                    // Use 'perms' => '$user->rights->attendanceSystem->level1->level2' if you want your menu with a permission rules
                        'target' => '',
                        'user' => 2);
                $r++;
                $this->menu[$r]=array('fk_menu' => 'fk_mainmenu=hrm,fk_leftmenu=attendancesystem',                    // Use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx, fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
                        'type' => 'left',                                        // This is a Left menu entry
                        'titre' => 'AttendanceSystemEvent',
                        'mainmenu' => 'hrm',
                        'leftmenu' => 'AttendanceSystem',
                        'url' => '/attendanceSystem/AttendanceSystemEventList.php?action=list',
                        'langs' => 'attendanceSystem@attendanceSystem',                // Lang file to use(without .lang) by module. File must be in langs/code_CODE/ directory.
                        'position' => 430,
                        'enabled' => 1, // Define condition to show or hide menu entry. Use '$conf->attendanceSystem->enabled' if entry must be visible if module is enabled. Use '$leftmenu == \'system\'' to show if leftmenu system is selected.
                        'perms' => '$user->rights->attendanceSystem->System->admin',                                    // Use 'perms' => '$user->rights->attendanceSystem->level1->level2' if you want your menu with a permission rules
                        'target' => '',
                        'user' => 2);
                $r++;
// impoort
/*                $r++;
                $this->import_code[$r]=$this->rights_class.'_'.$r;
                $this->import_label[$r]="ImportDataset_Kimai";        // Translation key
                $this->import_icon[$r]='project';
                $this->import_entities_array[$r]=array('pt.fk_user' => 'user');                // We define here only fields that use another icon that the one defined into import_icon
                $this->import_tables_array[$r]=array('ptt' => MAIN_DB_PREFIX.'project_task_time');
                $this->import_fields_array[$r]=array('ptt.fk_task' => "ThirdPartyName*", 'ptt.fk_user' => "User*");
                $this->import_convertvalue_array[$r]=array(
                                'ptt.fk_task' => array('rule' => 'fetchidfromref', 'classfile' => '/attendanceSystem/class/attendanceSystem.class.php', 'class' => 'Timesheet', 'method' => 'fetch', 'element' => 'ThirdParty'),
                                'sr.fk_user' => array('rule' => 'fetchidfromref', 'classfile' => '/user/class/user.class.php', 'class' => 'User', 'method' => 'fetch', 'element' => 'User')
              );
                $this->import_examplevalues_array[$r]=array('sr.fk_soc' => "MyBigCompany", 'sr.fk_user' => "login");*/
                // Exports
                //$r = 1;
                // Example:
                // $this->export_code[$r]=$this->rights_class.'_'.$r;
                // $this->export_label[$r]='CustomersInvoicesAndInvoiceLines';        // Translation key(used only if key ExportDataset_xxx_z not found)
        }
        /**
         *                Function called when module is enabled.
         *                The init function add constants, boxes, permissions and menus(defined in constructor) into Dolibarr database.
         *                It also creates data directories
         *
         *      @param      string        $options    Options when enabling module('', 'noboxes')
         *      @return     int              1 if OK, 0 if KO
         */
        public function init($options = '')
        {
            global $db, $conf;
            $result = $this->_load_tables('/attendanceSystem/sql/');
            dolibarr_set_const($db, "ATTENDANCE_VERSION", $this->version, 'chaine', 0, '', $conf->entity);
            include_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
            $sql = array();
            //FIXME add default task to project and third partie
            //$extrafields = new ExtraFields($this->db);
            // add the "Default server" select list to the user
            //$extrafields->addExtraField('fk_service', "DefaultService", 'sellist', 1, '', 'user', 0, 0, '', array('options' => array("product:ref|label:rowid::tosell='1' AND fk_product_type='1'" => 'N')), 1, 1, 3, 0, '', 0, 'attendanceSystem@pattendanceSystem', '$conf->attendanceSystem->enabled');
            // add the "Default server" select list to the task
            //$extrafields->addExtraField('fk_service', "DefaultService", 'sellist', 1, '', 'projet_task', 0, 0, '', array('options' => array("product:ref|label:rowid::tosell='1' AND fk_product_type='1'" => 'N')), 1, 1, 3, 0, '', 0, 'attendanceSystem@pattendanceSystem', '$conf->attendanceSystem->enabled');
            // allow ext id of 32 char
           // $extrafields->addExtraField('external_id', "ExternalId", 'varchar', 100, 32, 'user', 1, 0, '', '', 1, '$user->rights->attendanceSystem->AttendanceAdmin', 3, 'specify the id of the external system', '', 0, 'attendanceSystem@pattendanceSystem', '$conf->global->ATTENDANCE_EXT_SYSTEM');
            // add the "invoicable" bool to the task
            //$extrafields->addExtraField('invoiceable', "Invoiceable", 'boolean', 1, '', 'projet_task', 0, 0, '', '', 1, 1, 1, 0, '', 0, 'attendanceSystem@pattendanceSystem', '$conf->attendanceSystem->enabled');
            return $this->_init($sql, $options);
        }
        /**
         *                Function called when module is disabled.
         *      Remove from database constants, boxes and permissions from Dolibarr database.
         *                Data directories are not deleted
         *
     *      @param      string        $options    Options when enabling module('', 'noboxes')
         *      @return     int              1 if OK, 0 if KO
         */
        public function remove($options = '')
        {
                $sql = array();
                return $this->_remove($sql, $options);
        }
}
