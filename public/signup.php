<?php
	if(isset($_POST['submit'])){
	 	global $wpdb;
		 $charset_collate = $wpdb->get_charset_collate();
		 require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$volunteers_tablename = $wpdb->prefix."aydn_volunteers";
		$courses_tablename = $wpdb->prefix."aydn_courses";
		$sql = "SELECT email from $volunteers_tablename where email='%s'";  
		 $emailRows = $wpdb->get_results($wpdb->prepare($sql, $_POST["v_email"]));
	   $status = "New";
	   if(substr($_POST['aydn_number'], 1, 1) != 0 || strlen($_POST['aydn_number']) != 5){
		   $status = "Invalid";
		   print("You have entered an invalid AYDN number. Please go back and try again.");
	   }
	   elseif(count($emailRows) == 0){
			 $volunteers_data = array(
			   'name' => $_POST['v_name'],
			   'firstname' => $_POST['v_firstname'],
			   'lastname' => $_POST['v_lastname'],
			   'birthdate' => $_POST['birthdate'],
			   'email' => $_POST['v_email'],
			   'aydn_number' => $_POST['aydn_number'],
			   'resume' => $_POST['resume'],
			   'parent_contact' => $_POST['parent_contact'],
			   'status' => $status
		   );

		   $volunteers_success=$wpdb->insert( $volunteers_tablename, $volunteers_data);
		   $lastid = $wpdb->insert_id;
		   $courses_data = array(
			   'title' => $_POST['c_title'],
			   'introduction' => $_POST['introduction'],
			   'syllabus' => $_POST['syllabus'],
			   'start_date' => $_POST['start_date'],
			   'start_time' => $_POST['start_time'],
			   'length' => $_POST['length'],
			   'time_zone' => $_POST['time_zone'],
			   'duration' => $_POST['duration'],
			   'capacity' => $_POST['capacity'],
			   'photo_consent' => $_POST['photo_consent'],
			   'note' => $_POST['note'],
			   'volunteer_id' => $lastid
		   );

		   $courses_success=$wpdb->insert( $courses_tablename, $courses_data);
		   if($volunteers_success && $courses_success){
			   echo 'data has been saved' ; 
		   }
		 }
		 else{
			 print("Email already exists!<br>");
			 $url = $_SERVER['HTTP_REFERER'];
			 print("<a href='$url'>If you are an existing AYDN volunteer, please log in. Otherwise click Here to Return to Form</a>");
		 }
	   return;
	}
?>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="signup-form" >

	<!-- information -->
	<div class="container">
		<div class="row" id="adyn_volunteers">
			<label class="form-label" for="v_firstname">First Name</label>
			<input type="text" id="v_firstname" name="v_firstname" class="form-control preferredName" required>
			<label class="form-label" for="v_lastname">Last Name</label>
			<input type="text" id="v_lastname" name="v_lastname" class="form-control preferredName" required>
			<label class="form-label" for="v_name">Preferred Name (for public display)</label>
			<input type="text" id="v_name" name="v_name" class="form-control" required>
			<label class="form-label" for="birthdate">Date of Birth</label>
			<input type="date" id="birthdate" name="birthdate" class="form-control" required>
			<label class="form-label" for="v_email">Volunteer Email Address</label>
			<input type="email" id="v_email" name="v_email" class="form-control" required>
			<label class="form-label" for="aydn_number">AYDN #</label>
			<input type="text" id="aydn_number" name="aydn_number" class="form-control" required>
			<label class="form-label" for="parent_contact">Parent(s) Wechat or Email</label>
			<input type="text" id="parent_contact" name="parent_contact" class="form-control">
			<label class="form-label" for="resume">Resume (Summary/Name/Education/Skills & Experience/Awards)</label>
			<textarea id="resume" name="resume" rows="10" class="form-control"></textarea>											
		</div>
		<br /><br />
		<div class="row" id="adyn_courses">
			<label class="form-label" for="c_title">Course Title</label>
			<input type="text" id="c_title" name="c_title" class="form-control" required>
			<label class="form-label" for="introduction">Course Description</label>
			<input type="text" id="introduction" name="introduction" class="form-control" required>
			<label class="form-label" for="syllabus">Syllabus</label>
			<textarea id="syllabus" name="syllabus" rows="10" class="form-control"></textarea>

			<label class="form-label" for="start_date">First Class Date</label>
			<input type="text" id="start_date" name="start_date" class="form-control date" required>
			<label class="form-label" for="length">Length of Course</label>
			<input type="text" list="courseLengthList" id="length" name="length" class="form-control" required>
			<datalist id="courseLengthList">
				<option value="8 Weeks"></option>
				<option value="10 Weeks"></option>
				<option value="12 Weeks"></option>
			</datalist>
			<label class="form-label" for="start_time">Start Time</label>
			<input type="text" id="start_time" name="start_time" class="form-control time"required>
			<label class="form-label" for="time_zone">Time Zone</label>
			<input type="text" list="timeZoneList" id="time_zone" name="time_zone" class="form-control"required>
			<datalist id="timeZoneList">
				<option value="EDT" selected>Eastern Daylight Time</option>
				<option value="CDT">Central Daylight Time</option>
				<option value="MDT">Mountain Daylight Time</option>
				<option value="PDT">Pacific Daylight Time</option>
			</datalist>
			<label class="form-label" for="duration">Class Duration</label>
			<input type="text" list="durationList" id="duration" name="duration" class="form-control">
			<datalist id="durationList">
				<option value="1 Hour"></option>
				<option value="1.5 Hours"></option>
				<option value="2 Hours"></option>
			</datalist>
			<label class="form-label" for="capacity">Max # of Students</label>
			<input type="text" id="capacity" name="capacity" class="form-control">
			Photo and Video: I give permission for my and/or my child's (if student is under 18) photograph and video to be used by Asian Youh Development Network on its website or at its facility for any Asian Youth Development Network related publicity, including print and broadcast media (If "NO" can NOT participate.)
			<div class="form-check form-check-inline">
				<input type="radio" id="photo_consent_yes" name="photo_consent" checked="checked" class="form-check-input" value="YES">
				<label class="form-check-label" for="photo_consent_yes">
				   YES


				   
				</label>
			<div class="form-check form-check-inline">
			</div>
				<input type="radio" id="photo_consent_no" name="photo_consent" class="form-check-input" value="NO">
				<label class="form-check-label" for="photo_consent_no">
			    	NO
			  	</label>
			</div>
			<label class="form-label" for="note">Note</label>
			<textarea id="note" name="note" rows="10" class="form-control"></textarea>												
		</div>
		<br />
	    <input class="btn btn-primary" type="submit" name="submit" value="Submit" />
    </div>

</form>