<?php

############################################ COUNCILLOR FUNCTIONS ############################################


$common_included_flag = true;


$mysqlconnected = false;

include 'secure.php';


  $councillorRoles['chair'] = "Chair";
  $councillorRoles['vicechair'] = 'Vice Chair';
  $councillorRoles['secretary'] = 'Secretary';
  $councillorRoles['treasurer'] = 'Treasurer';
  $councillorRoles['events'] = 'Events';
  $councillorRoles['welfare'] = 'Welfare, Equality and Diversity';
  $councillorRoles['nus'] = 'NUS';
  $councillorRoles['charities'] = 'Charities';
  $councillorRoles['comms'] = 'Head of Communications';
  $councillorRoles['comms2'] = 'Communications';
  $councillorRoles['commsgraph'] = 'Comms: Graphics';
  $councillorRoles['commsmedia'] = 'Comms: Media';
  $councillorRoles['commsphot'] = 'Comms: Photography';
  $councillorRoles['commsweb'] = 'Comms: Web Design';
  $councillorRoles['environment'] = 'Environment';
  $councillorRoles['societies'] = 'Societies';

function getRoleFromShorthand($role) {
  global $councillorRoles;
  return $councillorRoles[$role];
}


#getCouncillorRow: returns the raw result from the MySQL database of the councillor's info (used in functions below)

function getCouncillorRow($role) {
  global $mysqlconnected;
  if(!$mysqlconnected) mysqlConnect();

  $councillorQuery = mysql_query("SELECT * FROM councillors WHERE role = '$role' AND active = true");

  if(mysql_num_rows($councillorQuery) == 1) {

    return mysql_fetch_assoc($councillorQuery);

  }
  else return false;

}



#getCouncillorName($role): returns the name of the councillor with that role

function getCouncillorName($role) {

  $councillorRow = getCouncillorRow($role);

  if($councillorRow !== false) {

    return $councillorRow['name'];

  }
  else return '-An error has occured-';

}




#getCouncillorFriendlyName($role): returns the 'friendly' name of the councillor with that role; for casual use in text
function getCouncillorFriendlyName($role, $possesiveForm=false) {

  $councillorRow = getCouncillorRow($role);

  if($councillorRow !== false) {
    if($possesiveForm) {
      if(substr($councillorRow['friendlyname'], -1) == 's') {
        return $councillorRow['friendlyname'] . '\''; // grammar please
      }
      else {
        return $councillorRow['friendlyname'] . '\'s';
      }
    }
    else {
      return $councillorRow['friendlyname'];
    }

  }
  else return '-An error has occured-';

}


#getCouncillorName($role): returns the subject list of the councillor with that role

function getCouncillorTutor($role) {

  $councillorRow = getCouncillorRow($role);

  if($councillorRow !== false) {

    return $councillorRow['tutor'];

  }
  else return '-An error has occured-';

}



#getCouncillorName($role): returns the subject list of the councillor with that role

function getCouncillorSubjects($role) {

  $councillorRow = getCouncillorRow($role);

  if($councillorRow !== false) {

    return $councillorRow['subjects'];

  }
  else return '-An error has occured-';

}


#getCouncillorName($role): returns the subject list of the councillor with that role

function getCouncillorEmail($role) {

  $councillorRow = getCouncillorRow($role);

  if($councillorRow !== false) {

    return $councillorRow['email'];

  }
  else return '-An error has occured-';

}



#getCouncillorName($role): returns the biography of the councillor with that role

function getCouncillorBiography($role) {

  $councillorRow = getCouncillorRow($role);

  if($councillorRow !== false) {

  	if($role == 'commsweb') return $councillorRow['biography']; // let the web dev have a more interesting bio
    else return htmlentities($councillorRow['biography'], ENT_COMPAT | ENT_HTML5, 'UTF-8', false);

  }
  else return '-An error has occured-';

}



#getCouncillorImagename($role): returns the image name of the councillor with that role

function getCouncillorImagename($role) {

  $councillorRow = getCouncillorRow($role);

  if($councillorRow !== false) {

    if(file_exists('/home/a6325779/public_html/old/img/profiles/thumbs/'.$councillorRow['imagename'].'.jpg'))
      return '/img/profiles/thumbs/'.$councillorRow['imagename'].'.jpg';
    else
      return '/img/profiles/thumbs/noimg.png';

  }
  else return '/img/profiles/thumbs/noimg.png';

}



############################################ END COUNCILLOR ############################################


?>