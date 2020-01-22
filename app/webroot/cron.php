<?php

set_time_limit ( 5*60 );
ini_set('display_errors',1);
error_reporting(-1);

/**
 * IMPORTANT
 * use cron.php?use_stamnr_for_student_email=true&use_teacher_email_as_base=true
 * to build the student email adresses based on the teacher email adress and the stamnr
 * if not, you need to have an extra csv file with stamnr,emailaddress
 */


/**
 * Use the DS to separate the directories in other defines
 */
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

/**
 * These defines should only be edited if you have CakePHP installed in
 * a directory layout other than the way it is distributed.
 * When using custom settings be sure to use the DS and do not add a trailing DS.
 */

/**
 * The full path to the directory which holds "app", WITHOUT a trailing DS.
 *
 */
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__FILE__))));
}

/**
 * The actual directory name for the "app".
 *
 */
if (!defined('APP_DIR')) {
    define('APP_DIR', basename(dirname(dirname(__FILE__))));
}

// Predefine global variables
$csv 		= array();
$errors 	= array();
$email 		= 'support@test-correct.nl';
$log 		= array('[' . date('m/d/Y h:i:s a', time()) . ']');
$dir        = ROOT . DS . APP_DIR . DS . 'tmp' . DS .'csv-uploads' . DS;
//$dir   		= __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'csv'.DIRECTORY_SEPARATOR;
// $dir 	= __DIR__.DIRECTORY_SEPARATOR . 'csv' . DIRECTORY_SEPARATOR; // only for local testing....
$scandir 	= array_values(array_diff(scandir($dir),array('..','.')));

// Data manipulation variables
$studentsPerClass = array();
$teachersPerClass = array();
$allClasses = array();
$classesToIgnore = array();

if(!$scandir || !is_array($scandir) || count($scandir) > 2 || pathinfo($scandir[0], PATHINFO_EXTENSION) != 'csv' || (count($scandir) == 2 && pathinfo($scandir[1], PATHINFO_EXTENSION) != 'csv')){
    echo "no valid files found to import";exit;
}

$data = getDataFromFiles($scandir, $dir);

//// READ THE CSV AND PUT INTO READABLE ARRAY FOR USAGE;
//if (($handle = fopen($dir . $scandir[0], "r")) !== FALSE) {
//    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
//        $csv[] = $data;
//    }
//    fclose($handle);
//}

if(count($data->people) < 1 || (count($scandir) == 2 && count($data->emails) < 1 )){// emails is one less as it contains no header row
    echo "no valid data found to import";exit;
}

// SET UP A CONNECTION TO THE MYSQL SERVER BECAUSE WE DO A LOT OF QUERIES;
if($_SERVER['HTTP_HOST'] == 'testportal.test-correct.test'){
    $mysql = new MySQLi('localhost', 'homestead', 'secret', 'tccore_test');
}else {
    $mysql = new MySQLi('10.233.253.123', 'tccore_live_user', 'PjA9tdotAfDumA2h', 'tccore_live');
}
// $mysql = new MySQLi('localhost','root','','tccore_dev');

foreach($data->people as $rowNr => $recordInCsv) {
	if($rowNr === 0) { // We are currently in the header row;
		$mapping = $recordInCsv; // store current row in seperate variable before working;
		continue; // Skip current itteration because we don't need to do anything with header row;
	}


	if($recordInCsv[0] === NULL) break; // Empty rows in CSV

	try {
		// Try to create a mapping for CSV so we know in which column each piece of
		// Information is found, and throw an error when we cannot find a certain row;
		$school_location_name 	= $recordInCsv[array_search('Schoolnaam', $mapping)];
		$external_main_code 	= $recordInCsv[array_search('Brincode', $mapping)];
		$external_sub_code 		= $recordInCsv[array_search('Locatiecode', $mapping)];
		$study_direction 		= $recordInCsv[array_search('Studierichting', $mapping)];
		$study_year_layer 		= $recordInCsv[array_search('lesJaarlaag', $mapping)];
		$study_year 			= $recordInCsv[array_search('Schooljaar', $mapping)];
		$student_external_code 	= $recordInCsv[array_search('leeStamNummer', $mapping)];
		$student_name_first 	= $recordInCsv[array_search('leeVoornaam', $mapping)];
		$student_name_suffix 	= $recordInCsv[array_search('leeTussenvoegsels', $mapping)];
		$student_name_last 		= $recordInCsv[array_search('leeAchternaam', $mapping)];
		$class_name 			= $recordInCsv[array_search('lesNaam', $mapping)];
		$subject_abbreviation 	= $recordInCsv[array_search('vakNaam', $mapping)];
		$teacher_external_code 	= $recordInCsv[array_search('docStamNummer', $mapping)];
		$teacher_name_first 	= $recordInCsv[array_search('docVoornaam', $mapping)];
		$teacher_name_suffix 	= $recordInCsv[array_search('docTussenvoegsels', $mapping)];
		$teacher_name_last 		= $recordInCsv[array_search('docAchternaam', $mapping)];
		$teacher_is_mentor 		= $recordInCsv[array_search('IsMentor', $mapping)];
        $teacher_email	        = $recordInCsv[array_search('gbrEmailAdres', $mapping)];

        $student_email = '';

        // To use this
        if($_GET['use_stamnr_for_student_email'] && $_GET['use_teacher_email_as_base']){
            if(substr_count($teacher_email,'@') > 0) {
                $domain = explode('@', $teacher_email)[1];
                $student_email = sprintf('%s@%s',$student_external_code,$domain);
            }
            else{
                throw new Exception(sprintf('email address could not be build for student %s %s %s with stamnummer %s', $student_name_first, $student_name_suffix, $student_name_last, $student_external_code));
            }

        }
		else if(!isset($data->emails) || !array_key_exists($student_external_code, $data->emails)){
		    throw new Exception(sprintf('email address not found for user %s %s %s with stamnummer %s', $student_name_first, $student_name_suffix, $student_name_last, $student_external_code));
        }
//        if(!array_key_exists($teacher_external_code, $data->emails)){
//            throw new Exception(sprintf('email address not found for teacher %s %s %s with stamnummer %s', $teacher_name_first, $teacher_name_suffix, $teacher_name_last, $teacher_external_code));
//        }
        if($student_email == '') {
            $student_email = $data->emails[$student_external_code];
        }
//		$teacher_email          = $data->emails[$teacher_external_code];
	} catch (Exception $e) {
		die(var_dump($e->getMessage()));
	}

	// Get all General IDS we need for matching, and updating/inserting specific users/classes into the database;
	$school_location_id = checkForSchoolLocationId($external_sub_code, $external_main_code, $mysql);
	$education_level_id = checkForStudyDirectionId($study_direction, $mysql);
	$school_class_id = checkSchoolClassId( $class_name, $school_location_id,  $study_year, $study_year_layer, $education_level_id, $mysql );
	$school_year_id = checkSchoolYearId($school_location_id, $study_year, $mysql);
	$subject_id = checkForSubjectId($subject_abbreviation,$school_location_id, $mysql);
	$teacher_id = checkUserPerLocation($teacher_external_code, $school_location_id, $mysql);
	$student_id = checkUserPerLocation($student_external_code, $school_location_id, $mysql);

	$allClasses[$school_location_id]['school_class_id'][] = $school_class_id;
	$allClasses[$school_location_id]['school_year_id'] = $school_year_id;

	$isMentor = false;
	if($teacher_id && $teacher_id != NULL){
		$isMentor = checkIfIsAlreadyMentor($teacher_id, $school_class_id, $mysql);
	}

	// General checks for data being found or not
	if($school_location_id < 1)
		$errors[] = " [FATAL] Cannot find school location by brin/location code : " . $external_main_code . '/' . $external_sub_code . PHP_EOL;
	if($education_level_id < 1)
		$errors[] = " [FATAL] Cannot find education level in database for : " . $study_direction . PHP_EOL;
	if($school_year_id < 1) 
		$errors[] = " [FATAL] Cannot find school year id : please fix;";
	if($subject_id < 1) 
		$errors[] = " [FATAL] Cannot find subject : please fix; ".$subject_abbreviation;

	foreach($errors as $error) checkLogForFatal($error, $email);

	if($school_class_id < 1) {
		// class does not exists, please create;
		$insertSchoolClassQuery = " INSERT INTO `school_classes`
		(`school_location_id`,`education_level_id`,`school_year_id`,`name`,`education_level_year`,`is_main_school_class`,`do_not_overwrite_from_interface`, `created_at`, `updated_at`)
		VALUES
		(?,?,?,?,?,?,0, NOW(), NOW());
		";
		$stmt = $mysql->prepare($insertSchoolClassQuery);
		$stmt->bind_param('iiisii',$school_location_id, $education_level_id, $school_year_id, $class_name, $study_year_layer,$teacher_is_mentor);
		$stmt->execute();
		$school_class_id = $stmt->insert_id;
		$stmt->close();
		$log[] = "[INFO] Added new class : $class_name";

		$classesToIgnore[] = $school_class_id;
	}

	if($student_id > 0 ) {
		// student exist we can do an update function later;
		if(!checkStudentPerClass($student_id, $school_class_id, $mysql)){
			$stmt = $mysql->prepare("INSERT INTO `students` (`user_id`,`class_id`,`created_at`, `updated_at`) VALUES(?,?, NOW(), NOW());");
			$stmt->bind_param('ii',$student_id, $school_class_id);
			$stmt->execute();
			$stmt->close();

			$log[] = "[INFO] Added student in student table with id: " . $student_id;
		}

		if(!checkUserHasBeenUpdated($student_external_code,  $school_location_id, $student_name_first, $student_name_suffix, $student_name_last, $mysql)){
			// we need to update the user
			$stmt = $mysql->prepare('UPDATE `users` SET `name_first` = ?, `name_suffix` = ?, `name` = ? WHERE `external_id` = ? AND `school_location_id` = ?');
			$stmt->bind_param('sssii',$student_name_first, $student_name_suffix, $student_name_last, $student_external_code,  $school_location_id);
			$stmt->execute();
			$stmt->close();
			$log[] = "[INFO] Updated student information with external id: " . $student_external_code;
            $log[] = "[INFO] Student username: " . $student_email;
		}

	} else {
		// Student does not exists, let's create it;
		$api_key = substr( hash('ripemd320', uniqid().time('now')), rand(0,38), 41);
//		$username = str_replace(' ','',strtolower($student_name_first[0].'.'.$student_name_last.'@'.$school_location_id.'.com'));
		$stmt = $mysql->prepare("INSERT INTO `users` (`external_id`,`name_first`,`name_suffix`,`name`,`username`,`school_location_id`,`api_key`, `created_at`,`updated_at`) VALUES (?,?,?,?,?,?,?, NOW(), NOW());");
		$stmt->bind_param('issssis',$student_external_code, $student_name_first, $student_name_suffix, $student_name_last, $student_email, $school_location_id,$api_key);

		$stmt->execute();
		$student_id = $stmt->insert_id;

		if($stmt->error) {
			$log[] = "[ERROR] Mysql error : " . $stmt->error;
		}

		$stmt->close();

		$stmt = $mysql->prepare('INSERT INTO `user_roles` (`user_id`,`role_id`,`created_at`,`updated_at`) VALUES(?, 3, NOW(), NOW());');
		$stmt->bind_param('i',$student_id);
		$stmt->execute();

		if($stmt->error) {
			$log[] = "[ERROR] Mysql error : " . $stmt->error;
		}
		$stmt->close();

		$log[] = "[INFO] Added new student with user id: " . $student_id;

		$stmt = $mysql->prepare("INSERT INTO `students` (`user_id`,`class_id`, `created_at`, `updated_at`) VALUES(?,?, NOW(), NOW());");
		$stmt->bind_param('ii',$student_id, $school_class_id);
		$stmt->execute();
		$stmt->close();

		$log[] = "[INFO] Added student in student table with id: " . $student_id;
		$log[] = "[INFO] Student external id: " . $student_external_code;
        $log[] = "[INFO] Student username: " . $student_email;
	}

	// Add all students to general array
	$studentsPerClass[$school_class_id][] = $student_id;

	if($teacher_id > 0 ) {
		// teacher exist we can do an update function later;
		$teacher_table_id = checkTeachersPerClass($teacher_id, $school_class_id, $subject_id, $mysql);
		if($teacher_table_id < 1){
			$stmt = $mysql->prepare("INSERT INTO `teachers` (`user_id`,`class_id`, `subject_id`, `created_at`, `updated_at`) VALUES(?,?,?, NOW(), NOW());");
			$stmt->bind_param('iii',$teacher_id, $school_class_id, $subject_id);
			$stmt->execute();
			$stmt->close();

			$log[] = "[INFO] Added teacher in teachers table with id: " . $teacher_id;
		}


		if(!checkUserHasBeenUpdated($teacher_external_code,  $school_location_id, $teacher_name_first, $teacher_name_suffix, $teacher_name_last, $mysql)){
			// we need to update the user
			
			$stmt = $mysql->prepare('UPDATE `users` SET `name_first` = ?, `name_suffix` = ?, `name` = ? WHERE `external_id` = ? AND `school_location_id` = ?');
			$stmt->bind_param('sssii',$teacher_name_first, $teacher_name_suffix, $teacher_name_last, $teacher_external_code,  $school_location_id);
			$stmt->execute();
			$stmt->close();
			$log[] = "[INFO] Updated teacher information with external id: " . $teacher_external_code;
		}
	}else {
		// teacher does not exists, let's create it;
		$api_key = substr( hash('ripemd320', uniqid().time('now')), rand(0,38), 41);
//		if(isset($data->emails[$teacher_external_code])){
//		    $username = $data->emails[$teacher_external_code];
//        }else {
//            $username = str_replace(' ', '', strtolower($teacher_name_first[0] . '.' . $teacher_name_last . '@' . $school_location_id . '.com'));
//        }
		$stmt = $mysql->prepare("INSERT INTO `users` (`external_id`,`name_first`,`name_suffix`,`name`,`username`,`school_location_id`,`api_key`, `created_at`,`updated_at`) VALUES (?,?,?,?,?,?,?, NOW(), NOW());");
		$stmt->bind_param('issssis',$teacher_external_code, $teacher_name_first, $teacher_name_suffix, $teacher_name_last, $teacher_email, $school_location_id, $api_key);

		$stmt->execute();
		$teacher_id = $stmt->insert_id;
		$stmt->close();

		$stmt = $mysql->prepare('INSERT INTO `user_roles` (`user_id`,`role_id`,`created_at`,`updated_at`) VALUES(?, 1, NOW(), NOW());');
		$stmt->bind_param('i',$teacher_id);
		$stmt->execute();
		$stmt->close();

		if($teacher_is_mentor == '1'){
			$stmt = $mysql->prepare('INSERT INTO `user_roles` (`user_id`,`role_id`,`created_at`,`updated_at`) VALUES(?, 1, NOW(), NOW());');
			$stmt->bind_param('i',$teacher_id);
			$stmt->execute();
			$stmt->close();
		}

		$stmt = $mysql->prepare("INSERT INTO `teachers` (`user_id`,`class_id`, `subject_id`,`created_at`, `updated_at`) VALUES(?,?,?, NOW(), NOW());");
		$stmt->bind_param('iii',$teacher_id, $school_class_id, $subject_id);
		$stmt->execute();
		$teacher_table_id = $stmt->insert_id;
		$stmt->close();

		$log[] = "[INFO] Added teacher in teachers table with id: " . $teacher_id;
	}

	if($teacher_is_mentor == "1" && !$isMentor){
		$stmt = $mysql->prepare('INSERT INTO `mentors` (`school_class_id`, `user_id`,`created_at`,`updated_at`) VALUES (?,?,NOW(),NOW());');
		$stmt->bind_param('ii',$school_class_id, $teacher_id);
		$stmt->execute();
		$log[] = "[NOTICE] Added teacher : " . $teacher_id . " as mentor for class with id " . $school_class_id;
		$stmt->close();
	}elseif($teacher_is_mentor == "0" && $isMentor) {
		$stmt = $mysql->prepare("UPDATE `mentors` SET `deleted_at` = NOW(), `updated_at` = NOW() WHERE `school_class_id` = ?  AND `user_id` = ?");
		$stmt->bind_param('ii',$school_class_id, $teacher_id);
		$stmt->execute();
		$log[] = "[NOTICE] Deleted teacher : " . $teacher_id . " as mentor for class with id " . $school_class_id;
		$stmt->close();
	}

	$teachersPerClass[$teacher_id][] = array('subject_id' => $subject_id, 'class_id' => $school_class_id);
}

// We loop over all the students that are in the csv and compare which are overhead in each class
// and we delete them accordingly.
foreach ($studentsPerClass as $class_id => $students) {
	$deleteStudentQuery = "
		UPDATE `students` 
		SET `updated_at` = NOW(),
		`deleted_at` = NOW()
		WHERE NOT `user_id` IN(".implode(',',$students).")
			AND `class_id` = ?
			AND `deleted_at` IS NULL";
	$stmt = $mysql->prepare($deleteStudentQuery);
	$stmt->bind_param('i',$class_id);
	$stmt->execute();
	$log[] = "[INFO] Deleted " . $stmt->affected_rows . " students from class: " . $class_id;
	$stmt->close();
}

foreach ($teachersPerClass as $teacher_id => $class_subjects) {
	$deleteStudentQuery = "
		UPDATE `teachers` 
		SET `updated_at` = NOW(), 
		`deleted_at` = NOW()
		WHERE `user_id` = ?
			AND `class_id` != ?
			AND `subject_id` != ?
			AND `deleted_at` IS NULL";
	$stmt = $mysql->prepare($deleteStudentQuery);
	$stmt->bind_param('iii',$teacher_id, $class_subjects['class_id'], $class_subjects['subject_id']);
	$stmt->execute();
	$log[] = "[INFO] Deleted " . $stmt->affected_rows . " teachers from classes ";
	$stmt->close();
}

$deleteStudentQ = "UPDATE `students` SET `deleted_at` = NOW(), `updated_at` = NOW() WHERE `class_id` = ?" ;
$deleteTeacherQ = "UPDATE `teachers` SET `deleted_at` = NOW(), `updated_at` = NOW() WHERE `class_id` = ?";
$deleteMentorQ = "UPDATE `mentors` SET `deleted_at` = NOW(), `updated_at` = NOW() WHERE `school_class_id` = ?";
$deleteClassQ = "UPDATE `school_classes` SET `deleted_at` = NOW(), `updated_at` = NOW() WHERE `id` = ?";

foreach($allClasses as $school_location_id => $data) {
	$stmt = $mysql->prepare("
		SELECT `id`
		FROM `school_classes` 
		WHERE `school_location_id` = ?
			AND `do_not_overwrite_from_interface` = 0
			AND `school_year_id` = ?
			AND NOT `id` IN (".implode(',', $data['school_class_id']).")
			AND `deleted_at` IS NULL;
		");
	$stmt->bind_param('ii',$school_location_id, $data['school_year_id']);
	$stmt->execute();
	$stmt->bind_result($id);

	$idsToDelete = array();
	while($row = $stmt->fetch()){
		if(array_search($id, $classesToIgnore)) continue;
		$idsToDelete[] = $id;
	}
	$stmt->close();

	foreach($idsToDelete as $id) {
		$stmt = $mysql->prepare($deleteStudentQ);
		$stmt->bind_param('i',$id);
		$stmt->execute();
		$log[] = "[NOTICE] deleted students from class : " . $id;
		$stmt->close();
		$stmt = $mysql->prepare($deleteTeacherQ);
		$stmt->bind_param('i',$id);
		$stmt->execute();
		$log[] = "[NOTICE] deleted teachers from class : " . $id;
		$stmt->close();
		$stmt = $mysql->prepare($deleteMentorQ);
		$stmt->bind_param('i',$id);
		$stmt->execute();
		$log[] = "[NOTICE] deleted mentors from class : " . $id;
		$stmt->close();
		$stmt = $mysql->prepare($deleteClassQ);
		$stmt->bind_param('i',$id);
		$stmt->execute();
		$log[] = "[NOTICE] deleted class : " . $id;
		$stmt->close();
	}
}

dd($log);

// Function to check if we have a fatal error in the log and cancel script in case
function checkLogForFatal($log, $email)
{
	if(strpos($log, 'FATAL')) {
	    $log .= PHP_EOL;
		$log .= '------------------------------------------------------------------------------------'.PHP_EOL;
		$log .= 'Aborted import with fatal errors, please fix errors and retry'.PHP_EOL;

		// sendLog($log, $email);
        dd($log);
		exit(1);
	}
}

// Final function to send the log results to the end user.
function sendLog($log, $email) 
{
	$subject = 'RTTI-CSV import log ' . date('m/d/Y h:i:s a', time());
	$message = wordwrap( implode(PHP_EOL.$log), 70, "\r\n");
	$headers = 'From: webmaster@example.com' . "\r\n" .
			   'Reply-To: webmaster@example.com' . "\r\n" .
			   'X-Mailer: PHP/' . phpversion();

	mail($email, $subject, $message, $headers);
}

/**
 * @return int $id the school loocation id, per external sub and main code; 
 */
function checkForSchoolLocationId($external_sub_code, $external_main_code, $mysql)
{
	$stmt = $mysql->prepare(
		"SELECT `id` FROM `school_locations` SL
			WHERE SL.`external_sub_code` = ? 
				AND SL.`external_main_code` = ?
				AND `deleted_at` IS NULL;"
	);

	$stmt->bind_param('ss',$external_sub_code, $external_main_code);
	$stmt->execute();
	$stmt->bind_result($id);
	$stmt->fetch();
	$stmt->close();

	return $id;
}

/**
 * @return int $id The school class Id as per many CSV data fetched from all other relevant data.
 */
function checkSchoolClassId($class_name, $school_location_id, $year, $education_level_year, $education_level_id, $mysql)
{
	$stmt = $mysql->prepare("
	SELECT SC.`id` 
	FROM `school_classes` SC
	WHERE SC.`name` = ?
		AND SC.`school_location_id` = ?
		AND SC.`school_year_id` = (
			SELECT `school_year_id` 
	        FROM `school_location_school_years` SLSY
	        LEFT JOIN `school_years` SY ON SY.`id` =  SLSY.`school_year_id`
	        WHERE SLSY.`school_location_id` = ?
				AND SY.`year` = ?
	            AND SLSY.`deleted_at` IS NULL
	            AND SY.`deleted_at` IS NULL
		)
	    AND `education_level_year` = ?
	    AND `education_level_id` = ?
	    AND SC.`deleted_at` IS NULL;
	");
	

	$stmt->bind_param('siisii',$class_name,$school_location_id, $school_location_id, $year, $education_level_year, $education_level_id);
	$stmt->execute();
	$stmt->bind_result($id);
	$stmt->fetch();
	$stmt->close();

	return $id;
}

/**
 * @return int $id the subject id per abbreviation and school location
 */
function checkForSubjectId($abbreviation,$school_location_id, $mysql)
{
	$stmt = $mysql->prepare("
		SELECT SUB.`id` 
		FROM `subjects` SUB
		INNER JOIN `sections` SEC
			ON SEC.`id` = SUB.`section_id`
		INNER JOIN `school_location_sections` SLS
			ON SLS.`section_id` = SEC.`id`
		WHERE `abbreviation` = ?
		AND SLS.`school_location_id` = ? 
		AND SUB.`deleted_at` IS NULL
		AND SEC.`deleted_at` IS NULL
		AND SLS.`deleted_at` IS NULL;
	");

	$stmt->bind_param('si',$abbreviation,$school_location_id);
	$stmt->execute();
	$stmt->bind_result($id);
	$stmt->fetch();
	$stmt->close();

	return $id;
}

/**
 * @return int $id the study direction id as per name EG: 'vwo'
 */
function checkForStudyDirectionId($name, $mysql)
{

    $name = translateStudyDirectionName($name);


	$stmt = $mysql->prepare("SELECT `id` FROM `education_levels` WHERE `name` = ?");
	
	$stmt->bind_param('s',$name);
	$stmt->execute();
	$stmt->bind_result($id);
	$stmt->fetch();
	$stmt->close();

	return $id;
}

function translateStudyDirectionName($name){
    $ar = [
        'b' => 'Vmbo bb',
        'b/k' => 'Vmbo kb',
        'k' => 'Vmbo kb',
        'k/t' => 'Mavo / Vmbo tl',
        't' => 'Mavo / Vmbo tl',
        'm/h' => 'Havo',
    ];
    return array_key_exists($name,$ar) ? $ar[$name] : $name;
}

/**
 * @return bool $resultCount == 1 if the user already exists in this location having external id
 */
function checkUserPerLocation($external_id, $school_location_id, $mysql)
{
	$stmt = $mysql->prepare("
		SELECT `id` 
		FROM `users` 
		WHERE `external_id` = ? 
			AND `school_location_id` = ?  
			AND `deleted_at` IS NULL;
	");
	$stmt->bind_param('si',$external_id, $school_location_id);
	$stmt->execute();
	$stmt->bind_result($id);
	$stmt->fetch();
	$stmt->close();

	return $id;
}


function checkStudentPerClass($user_id, $class_id, $mysql)
{
	$stmt = $mysql->prepare("SELECT `user_id` FROM `students` WHERE `user_id` = ? AND `class_id` = ? AND `deleted_at`  IS NULL");
	$stmt->bind_param('ii',$user_id, $class_id);
	$stmt->execute();
	$stmt->bind_result($id);
	$stmt->fetch();
	$stmt->close();

	return $id > 0;

}



function checkTeachersPerClass($user_id, $class_id, $subject_id, $mysql)
{
	$stmt = $mysql->prepare("SELECT `user_id` FROM `teachers` WHERE `user_id` = ? AND `class_id` = ? AND `subject_id` = ? AND `deleted_at`  IS NULL");
	$stmt->bind_param('iii',$user_id, $class_id,$subject_id);
	$stmt->execute();
	$stmt->bind_result($id);
	$stmt->fetch();
	$stmt->close();

	return $id;

}

/**
 * @return bool $resultCount == 1 if the user is a teacher and mentor return true;
 */
function checkIfIsAlreadyMentor($user_id, $school_class_id, $mysql)
{
	$stmt = $mysql->prepare("

		SELECT `user_id` 
		FROM `mentors`
		WHERE `user_id` = ? 
			AND `school_class_id` = ? 
			AND `deleted_at` IS NULL;
	");
	$stmt->bind_param('ii',$user_id, $school_class_id);
	$stmt->execute();
	$stmt->bind_result($id);
	$stmt->fetch();
	$stmt->close();

	return ($id != 0);
}


function checkSchoolYearId($school_location_id, $year, $mysql)
{
	$stmt = $mysql->prepare("
		SELECT `school_year_id` 
		FROM `school_location_school_years` SLSY
		LEFT JOIN `school_years` SY ON SY.`id` =  SLSY.`school_year_id`
		WHERE SLSY.`school_location_id` = ?
			AND SY.`year` = ?
			AND SLSY.`deleted_at` IS NULL
			AND SY.`deleted_at` IS NULL
	");
	$stmt->bind_param('is',$school_location_id, $year);
	$stmt->execute();
	$stmt->bind_result($id);
	$stmt->fetch();
	$stmt->close();
	return $id;
}

function checkUserHasBeenUpdated($external_id,  $school_location_id, $name_first, $name_suffix, $name_last, $mysql)
{
	$stmt = $mysql->prepare('SELECT `id` FROM `users` WHERE `external_id` = ? AND `school_location_id` = ?;');
	$stmt->bind_param('ii',$external_id,$school_location_id);
	$stmt->execute();
	$stmt->bind_result($id_through_external);
	$stmt->fetch();
	$stmt->close();

	$stmt = $mysql->prepare('SELECT `id` FROM `users` WHERE `external_id` = ? AND `school_location_id` = ? AND `name` = ? AND `name_suffix` = ? AND `name_first` = ?;');
	$stmt->bind_param('iisss', $external_id, $school_location_id, $name_last, $name_suffix, $name_first);
	$stmt->execute();
	$stmt->bind_result($id_through_data);
	$stmt->fetch();
	$stmt->close();

	return $id_through_data === $id_through_external;
}

function getDataFromFiles($files, $dir){
    $data = (object) [
        'emails' => [],
        'people' => [],
    ];
    foreach($files as $file){
        if($file == 'Testcorrect.csv'){
            // email list found
            // READ THE CSV AND PUT INTO READABLE ARRAY FOR USAGE;
            if (($handle = fopen($dir . $file, "r")) !== FALSE) {
                $headerDone = false;
                while (($row = fgetcsv($handle, 1000, ";")) !== FALSE) {
                    if(!$headerDone){
                        $headerDone = true;
                        continue;
                    }
                    if($row[0] === NULL) break; // Empty rows in CSV
                    $data->emails[$row[0]] = $row[1];
                }
                fclose($handle);
            }
        }
        else{
            // people list
            // READ THE CSV AND PUT INTO READABLE ARRAY FOR USAGE;
            if (($handle = fopen($dir . $file, "r")) !== FALSE) {
                while (($row = fgetcsv($handle, 1000, ";")) !== FALSE) {
                    $data->people[] = $row;
                }
                fclose($handle);
            }
        }
    }
    return $data;
}

// TEST FUNCTION TO DUMP DATA IN HTML PRE FORMAT FOR EASY DEBUGGING.
function dd($data)
{
	echo '<pre>'; var_dump($data); echo '</pre> <hr />';
}