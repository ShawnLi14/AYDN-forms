<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/ShawnLi14/AYDN-forms
 * @since      1.0.0
 *
 * @package    Aydn_Forms
 * @subpackage Aydn_Forms/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="wrap" class="aydn">
	<h1>AYDN Admin Console</h1>
		<?php
			// get current page uri		  		
			$uri = $_SERVER['REQUEST_URI'];
			$homeURL = substr($uri, 0, strpos($uri, "vid")-1);

			echo "<form method=\"post\" action=\"$uri\" id=\"aydn-admin\">";

			// get database handler
		 	global $wpdb;
		  	$charset_collate = $wpdb->get_charset_collate();
		  	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		 	$volunteers_tablename = $wpdb->prefix."aydn_volunteers";
		 	$courses_tablename = $wpdb->prefix."aydn_courses";
		 	$hours_tablename = $wpdb->prefix."aydn_hours";

			// edit hours
			if(isset($_GET['hid'])){
				// get volunteer id
				$vid = $_GET['vid'];
				// get hour id
				$hid = $_GET['hid'];
				$hours_updated = false;
                
				// update hour info
				if(isset($_POST['update_hours'])){
					$updatedData = array(
						'event_name' => $_POST['h_event_name'],
						'event_type' => $_POST['h_event_type'],
						'event_date' => $_POST['h_event_date'],
						'start_time' => $_POST['h_start_time'],
						'end_time' => $_POST['h_end_time'],
						'event_description' => $_POST['h_event_description'],
						'hours' => $_POST['h_hours'],
						'extra_hours' => $_POST['h_extra_hours'],
						'total_hours' => $_POST['h_total_hours'],
						'others' => $_POST['h_others'],
						'status' => $_POST['h_status']
					);
					$wpdb->update($hours_tablename, $updatedData, array('id'=>$hid));

					//backup email
					$subject = "Volunteer Hours Updated.";
					$body = "Here are updated volunteer hours details. Volunteer information: https://www.aydnetwork.org/wp-admin/admin.php?page=aydn-forms-admin&vid=$vid<br />
					<strong>Event name</strong>: ". $_POST['h_event_name']."<br />
					<strong>Event type</strong>: ". $_POST['h_event_type']."<br />
					<strong>Event date</strong>: ". $_POST['h_event_date']."<br />
					<strong>Start time</strong>: ". $_POST['h_start_time']."<br />
					<strong>End time</strong>: ". $_POST['h_end_time']."<br />
					<strong>Event description</strong>: ". $_POST['h_event_description']."<br />
					<strong>Hours</strong>: ". $_POST['h_hours']."<br />
					<strong>Extra hours</strong>: ". $_POST['h_extra_hours']."<br />
					<strong>Total hours</strong>: ". $_POST['h_total_hours']."<br />
					<strong>Others</strong>: ". $_POST['h_others']."<br />
					<strong>status</strong>: ". $_POST['h_status'];
					$headers = array('Content-Type: text/html; charset=UTF-8');
					
					wp_mail(get_option('aydn_backup_email'), $subject, $body, $headers);
					$hours_updated = true;					

				}
				//display hours eidting form
				// pull hours info
				$sql = "SELECT * from $hours_tablename where id='%d'";
				$volunteer_results = $wpdb->get_results($wpdb->prepare($sql, $hid));
				$hours = $volunteer_results[0];
				$breadcrumb = "<a href=\"$homeURL\">Admin Home</a> > <a href=\"$homeURL&vid=$vid\">Volunteer Details</a> > Edit Hours";
				echo "$breadcrumb<br><br>";
				echo "<h2>Edit Hours</h2>";
				// form to edit hours
				echo '<div class="hours_row">
					<div class="row">';
				echo "<div class=\"col-6\">";
				echo "<span class='title'>Event Name:</span><input type='text' name='h_event_name' value='$hours->event_name' /><br />";
				echo "<span class='title'>Event Type:</span><input type='text' name='h_event_type' value='$hours->event_type' /><br />";
				echo "<span class='title'>Event Date:</span><input type='text' name='h_event_date' value='$hours->event_date' /><br />";
				echo "<span class='title'>Start Time:</span><input type='text' name='h_start_time' value='$hours->start_time' /><br />";
				echo "<span class='title'>End Time:</span><input type='text' name='h_end_time' value='$hours->end_time' /><br />";
				echo "<span class='title'>Course Description:</span><textarea name='h_event_description'>$hours->event_description</textarea>";
				echo "</div>";
				echo "<div class=\"col-6\">";
				echo "<span class='title'>Hours:</span><input type='text' name='h_hours' value='$hours->hours' /><br />";
				echo "<span class='title'>Extra Hours:</span><input type='text' name='h_extra_hours' value='$hours->extra_hours' /><br />";
				echo "<span class='title'>Total Hours:</span><input type='text' name='h_total_hours' value='$hours->total_hours' /><br />";
				echo "<span class='title'>Status:</span><input type='text' name='h_status' value='$hours->status' /><br />";
				echo "<span class='title'>Extra Notes:</span><textarea name='h_others'>$hours->others</textarea>";
				echo "</div>";
				echo "</div>";
				if($hours_updated) echo "Changes saved. If everything looks good, click on Back to Volunteer Details button below to go back to the volunteer information page.";
				echo "<br><br>
				<input class='ui-button ui-widget ui-corner-all' name='update_hours' type='submit' value='Save Hours Changes'
				onclick=\"return confirm('Do you want to save changes?')\" style='background-color: darkred;color:white;'>
				<a class='ui-button ui-widget ui-corner-all' href='".$homeURL.'&vid='.$vid."' style='background-color: aqua;'>Back to Volunteer Details</a><br /><br />";				
			}

			// view details for volunteer with vid
			elseif(isset($_GET['vid'])){

				//get volunteer id
				$vid = $_GET['vid'];
				//get editing status. editing is true only when url has &editing=1
				$editing = false;
				if(isset($_POST['editing'])) $editing = true;				

				//update volunteer
				if(isset($_POST['update_volunteer'])){
					$updatedData = array(
						'name' => $_POST['edit_name'],
						'firstname' => $_POST['edit_firstname'],
						'lastname' => $_POST['edit_lastname'],
						'birthdate' => $_POST['edit_birthdate'],
						'email' => $_POST['edit_email'],
						'aydn_number' => $_POST['edit_aydn_number'],
						'resume' => $_POST['edit_resume'],
						'parent_contact' => $_POST['edit_parent_contact'],
						'phone' => $_POST['edit_phone'],
						'parent_phone' => $_POST['edit_parent_phone'],
						'status' => $_POST['edit_status']
					);
					$wpdb->update($volunteers_tablename, $updatedData, array('id'=>$vid));

					//backup email
					$subject = "Volunteer Information Updated.";
					$body = "Here are updated volunteer information:<br />
					<strong>name</strong>: ". $_POST['edit_name']."<br />
					<strong>firstname</strong>: ". $_POST['edit_firstname']."<br />
					<strong>lastname</strong>: ". $_POST['edit_lastname']."<br />
					<strong>birthdate</strong>: ". $_POST['edit_birthdate']."<br />
					<strong>email</strong>: ". $_POST['edit_email']."<br />
					<strong>aydn_number</strong>: ". $_POST['edit_aydn_number']."<br />
					<strong>resume</strong>: ". $_POST['edit_resume']."<br />
					<strong>parent_contact</strong>: ". $_POST['edit_parent_contact']."<br />
					<strong>phone</strong>: ". $_POST['edit_phone']."<br />
					<strong>parent_phone</strong>: ". $_POST['edit_parent_phone']."<br />
					<strong>status</strong>: ". $_POST['edit_status'];
					$headers = array('Content-Type: text/html; charset=UTF-8');
					
					wp_mail(get_option('aydn_backup_email'), $subject, $body, $headers);
				}

				// pull volunteer info
				$sql = "SELECT * from $volunteers_tablename where id='%d'";
				$volunteer_results = $wpdb->get_results($wpdb->prepare($sql, $vid));
				$volunteer = $volunteer_results[0];

				//approve volunteer
				if(isset($_POST['approve_volunteer'])){
					$wpdb->update($volunteers_tablename, array('status'=>'Approved'), array('id'=>$vid));
					$user_id = wp_insert_user( array(
					  'user_login' => $volunteer->email,
					  'user_pass' => NULL,
					  'user_email' => $volunteer->email,
					  'first_name' => $volunteer->firstname,
					  'last_name' => $volunteer->lastname,
					  'display_name' => $volunteer->name,
					  'role' => 'subscriber'
					));
					wp_new_user_notification( $user_id, null, 'both' );

					//backup email
					$subject = "Volunteer Approved.";
					$body = "
					<strong>User Email: </strong> $volunteer->email<br />
					<strong>User First Name: </strong> $volunteer->firstname<br />
					<strong>User Last Name: </strong> $volunteer->lastname<br />
					<strong>User Display Name: </strong> $volunteer->name<br />
					";
					$headers = array('Content-Type: text/html; charset=UTF-8');
					
					wp_mail(get_option('aydn_backup_email'), $subject, $body, $headers);
				}

				//disapprove volunteer
				if(isset($_POST['disapprove_volunteer'])){
					$user = get_user_by('login', $volunteer->email);
					if($user) wp_delete_user($user->ID);
					$wpdb->update($volunteers_tablename, array('status'=>'Rejected'), array('id'=>$vid));

					//backup email
					$subject = "Volunteer Denied.";
					$body = "
					<strong>User Email: </strong> $volunteer->email<br />
					<strong>User First Name: </strong> $volunteer->firstname<br />
					<strong>User Last Name: </strong> $volunteer->lastname<br />
					<strong>User Display Name: </strong> $volunteer->name<br />
					";
					$headers = array('Content-Type: text/html; charset=UTF-8');
					
					wp_mail(get_option('aydn_backup_email'), $subject, $body, $headers);
				}

				//delete volunteer
				if(isset($_POST['delete_volunteer'])){
					$user = get_user_by('login', $volunteer->email);
					if($user) wp_delete_user($user->ID);

					$wpdb->delete( $courses_tablename, array( 'volunteer_id' => $vid ) );
					$wpdb->delete( $hours_tablename, array( 'volunteer_id' => $vid ) );
					$wpdb->delete( $volunteers_tablename, array( 'id' => $vid ) );
					

					//backup email
					$subject = "Volunteer Deleted.";
					$body = "
					<strong>User Email: </strong> $volunteer->email<br />
					<strong>User First Name: </strong> $volunteer->firstname<br />
					<strong>User Last Name: </strong> $volunteer->lastname<br />
					<strong>User Display Name: </strong> $volunteer->name<br />
					";
					$headers = array('Content-Type: text/html; charset=UTF-8');
					
					wp_mail(get_option('aydn_backup_email'), $subject, $body, $headers);

					echo "<script>window.location.replace(\"https://".$_SERVER['HTTP_HOST'].$homeURL."\");</script>";
				}

				//approve course
				if(isset($_POST['approve_course'])){
					$cid = substr($_POST['approve_course'], 16);
					$wpdb->update($courses_tablename, array('status'=>'Approved'), array('id'=>$cid));

					//get course entry info
					$sql = "SELECT * from $courses_tablename where id='%d'";
					$courses_entry = $wpdb->get_results($wpdb->prepare($sql, $cid))[0];
					
					//send backup email
					$subject = "Course Approved.";
					$body = "
					<strong>Course Title: </strong> $courses_entry->title<br />
					<strong>Introduction: </strong> $courses_entry->introduction<br />
					<strong>Course Syllabus: </strong> $courses_entry->syllabus<br />
					<strong>Start Date: </strong> $courses_entry->start_date<br />
					<strong>Start Time: </strong> $courses_entry->start_time<br />
					<strong>Course Length: </strong> $courses_entry->length<br />
					<strong>Time Zone: </strong> $courses_entry->time_zone<br />
					<strong>Course Duration: </strong> $courses_entry->duration<br />
					<strong>Course Capacity: </strong> $courses_entry->capacity<br />
					<strong>Photo Consent: </strong> $courses_entry->photo_consent<br />
					<strong>Extra Information: </strong> $courses_entry->note<br />
					";
					$headers = array('Content-Type: text/html; charset=UTF-8');
					
					wp_mail(get_option('aydn_backup_email'), $subject, nl2br($body), $headers);
				}

				//disapprove course
				if(isset($_POST['disapprove_course'])){
					$cid = substr($_POST['disapprove_course'], 19);
					$wpdb->update($courses_tablename, array('status'=>'Rejected', 'deny_reason'=>$_POST['courses_deny_reason'.$cid]), array('id'=>$cid));

					//get course entry info
					$sql = "SELECT * from $courses_tablename where id='%d'";
					$courses_entry = $wpdb->get_results($wpdb->prepare($sql, $cid))[0];
					
					//send denial email
					$subject = "Your AYDN course proposal was denied.";
					$body = "The course you proposed has been denied. See details below.<br /><br />
					<strong>Course Title: </strong> $courses_entry->title<br />
					<strong>Introduction: </strong> $courses_entry->introduction<br />
					<strong>Course Syllabus: </strong> $courses_entry->syllabus<br />
					<strong>Start Date: </strong> $courses_entry->start_date<br />
					<strong>Start Time: </strong> $courses_entry->start_time<br />
					<strong>Course Length: </strong> $courses_entry->length<br />
					<strong>Time Zone: </strong> $courses_entry->time_zone<br />
					<strong>Course Duration: </strong> $courses_entry->duration<br />
					<strong>Course Capacity: </strong> $courses_entry->capacity<br />
					<strong>Photo Consent: </strong> $courses_entry->photo_consent<br />
					<strong>Extra Information: </strong> $courses_entry->note<br />
					<strong>Reason for Denial: </strong> $courses_entry->deny_reason<br />
					";
					$headers = array('Content-Type: text/html; charset=UTF-8','Bcc: '.$volunteer->email);
					
					wp_mail(get_option('aydn_backup_email'), $subject, nl2br($body), $headers);
				}		
				//approve hours
				if(isset($_POST['approve_hours'])){
					$hid = substr($_POST['approve_hours'], 15);
					$wpdb->update($hours_tablename, array('status'=>'Approved'), array('id'=>$hid));

					//get hour entry info
					$sql = "SELECT * from $hours_tablename where id='%d'";
					$hours_entry = $wpdb->get_results($wpdb->prepare($sql, $hid))[0];
					
					//send backup email
					$subject = "Hours Approved.";
					$body = "
					<strong>Event Name: </strong> $hours_entry->event_name<br />
					<strong>Event Type: </strong> $hours_entry->event_type<br />
					<strong>Event Description: </strong> $hours_entry->event_description<br />
					<strong>Event Date: </strong> $hours_entry->event_date<br />
					<strong>Start Time: </strong> $hours_entry->start_time<br />
					<strong>End Time: </strong> $hours_entry->end_time<br />
					<strong>Hours: </strong> $hours_entry->hours<br />
					<strong>Extra Hours: </strong> $hours_entry->extra_hours<br />
					<strong>Total Hours: </strong> $hours_entry->total_hours<br />
					<strong>Others: </strong> $hours_entry->others<br />
					";
					$headers = array('Content-Type: text/html; charset=UTF-8');
					
					wp_mail(get_option('aydn_backup_email'), $subject, nl2br($body), $headers);
				}

				//disapprove hours
				if(isset($_POST['disapprove_hours'])){
					$hid = substr($_POST['disapprove_hours'], 18);
					$wpdb->update($hours_tablename, array('status'=>'Rejected', 'deny_reason'=>$_POST['hours_deny_reason'.$hid]), array('id'=>$hid));
					
					//get hour entry info
					$sql = "SELECT * from $hours_tablename where id='%d'";
					$hours_entry = $wpdb->get_results($wpdb->prepare($sql, $hid))[0];
					
					//send denial email
					$subject = "Your AYDN hours submission was denied.";
					$body = "The hours you submitted have been denied. See details below.<br /><br />
					<strong>Event Name: </strong> $hours_entry->event_name<br />
					<strong>Event Type: </strong> $hours_entry->event_type<br />
					<strong>Event Description: </strong> $hours_entry->event_description<br />
					<strong>Event Date: </strong> $hours_entry->event_date<br />
					<strong>Start Time: </strong> $hours_entry->start_time<br />
					<strong>End Time: </strong> $hours_entry->end_time<br />
					<strong>Hours: </strong> $hours_entry->hours<br />
					<strong>Extra Hours: </strong> $hours_entry->extra_hours<br />
					<strong>Total Hours: </strong> $hours_entry->total_hours<br />
					<strong>Others: </strong> $hours_entry->others<br />
					<strong>Reason for Denial: </strong> $hours_entry->deny_reason<br />
					";
					$headers = array('Content-Type: text/html; charset=UTF-8','Bcc: '.get_option('aydn_backup_email'));
					
					wp_mail($volunteer->email, $subject, nl2br($body), $headers);
				}				

				//pull course info
				$sql = "SELECT * from $courses_tablename where volunteer_id='%d' order by status, title";
				$courses_results = $wpdb->get_results($wpdb->prepare($sql, $vid));

				//pull hours info
				$sql = "SELECT * from $hours_tablename where volunteer_id='%d' order by status, event_date";
				$hours_results = $wpdb->get_results($wpdb->prepare($sql, $vid));

				//calculate total hours
				$total_hours_submitted = 0;
				$total_hours_approved = 0;
				$min_date = null;
				$max_date = null;
				if(count($hours_results) > 0){
					$min_date = $hours_results[0]->event_date;
					$max_date = $min_date;
					foreach($hours_results as $entry){
						$total_hours_submitted += $entry->total_hours;
						if($entry->status == "Approved") $total_hours_approved += $entry->total_hours;
						if(strcmp($entry->event_date, $max_date) > 0) $max_date = $entry->event_date;
						if(strcmp($entry->event_date, $min_date) < 0) $min_date = $entry->event_date;
					}
				}

				//breadcrumb
				$breadcrumb = "<a href=\"$homeURL\">Admin Home</a> > Volunteer Details - $volunteer->name";

				// display volunteer personal information
				echo "$breadcrumb<br><br><h2>Volunteer Details</h2>";
				echo '<input class="ui-button ui-widget ui-corner-all" name="approve_volunteer" type="submit" value="Approve Volunteer" onclick="return confirm('."'Do you want to approve $volunteer->firstname $volunteer->lastname as an official AYDN volunteer? This will create an Wordpress account for them.'".')" style="background-color: aquamarine;">
				<input class="ui-button ui-widget ui-corner-all" name="disapprove_volunteer" type="submit" value="Disapprove Volunteer" onclick="return confirm('."'Do you want to DENY $volunteer->firstname $volunteer->lastname from being an official AYDN volunteer?'".')" style="background-color: darkred;color:white;">
				<input class="ui-button ui-widget ui-corner-all" name="editing" type="submit" value="Edit Volunteer" style="background-color: yellow;float: right;"> 
				<input class="ui-button ui-widget ui-corner-all" name="delete_volunteer" type="submit" value="Delete Volunteer" onclick="return confirm('."'Do you want to DELETE $volunteer->firstname $volunteer->lastname from the AYDN database? This will remove all records of their submitted courses and hours.'".')" style="background-color: darkred;color:white;float: right;">&nbsp;&nbsp;<br /><br />';
				echo "<div class=\"row\">";
				echo "<div class=\"col-6\">";
				echo "<span class=\"title\">First Name:</span>";
				if($editing) echo "<input type='text' name='edit_firstname' value='$volunteer->firstname' />";
				else echo $volunteer->firstname;
				echo "<br /><span class=\"title\">Last Name:</span>";
				if($editing) echo "<input type='text' name='edit_lastname' value='$volunteer->lastname' />";
				else echo $volunteer->lastname;
				echo "<br /><span class=\"title\">Display Name:</span>";
				if($editing) echo "<input type='text' name='edit_name' value='$volunteer->name' />";
				else echo $volunteer->name;
				echo "<br /><span class=\"title\">AYDN #:</span>";
				if($editing) echo "<input type='text' name='edit_aydn_number' value='$volunteer->aydn_number' />";
				else echo $volunteer->aydn_number;
				echo "<br /><span class=\"title\">Email:</span>";
				if($editing) echo "<input type='text' name='edit_email' value='$volunteer->email' />";
				else echo $volunteer->email;
				echo "<br /><span class=\"title\">Total Approved Hours:</span>$total_hours_approved<br>";				
				echo "</div>";
				echo "<div class=\"col-6\">";				
				echo "<span class=\"title\">Status:</span>";
				if($editing) echo "<input type='text' name='edit_status' value='$volunteer->status' />";
				else echo $volunteer->status;
				echo "<br /><span class=\"title\">Birth Date:</span>";
				if($editing) echo "<input type='text' name='edit_birthdate' value='$volunteer->birthdate' />";
				else echo $volunteer->birthdate;
				echo "<br /><span class=\"title\">Parent Contact:</span>";
				if($editing) echo "<input type='text' name='edit_parent_contact' value='$volunteer->parent_contact' />";
				else echo $volunteer->parent_contact;				
				echo "<br /><span class=\"title\">Volunteer Phone:</span>";
				if($editing) echo "<input type='text' name='edit_phone' value='$volunteer->phone' />";
				else echo $volunteer->phone;
				echo "<br /><span class=\"title\">Parent Phone:</span>";
				if($editing) echo "<input type='text' name='edit_parent_phone' value='$volunteer->parent_phone' />";
				else echo $volunteer->parent_phone;				
				echo "<br /></div></div>";
				echo "<div class=\"row\" id=\"resume\">";
				echo "<h3>Resume:</h3><div>";
				if($editing) echo "<textarea name='edit_resume' style='width:100%;'>".nl2br($volunteer->resume)."</textarea>";
				else echo nl2br($volunteer->resume);
				echo "</div></div>";
				if($editing) echo "<br /><input class='button button-primary' type='submit' name='update_volunteer' value='Save Volunteer Changes' onclick=\"return confirm('Do you want to save the changes you made on $volunteer->firstname $volunteer->lastname? ')\" /><br /><br />";
				
				// display courses by this volunteer
				echo "<h2>$volunteer->name's Courses</h2>";
				echo '<div id="accourses">';
				foreach ($courses_results as $course) {			
					$status_color = ($course->status == "Approved") ? "lightgreen" : (($course->status == "New") ? "gold" : "#999");
					echo "<h3>Course Title: $course->title | Status: <span style=\"background-color: $status_color; padding: 4px\">$course->status</span></h3><div>";
					echo '<div class="row">';
					echo "<div class=\"col-6\">";
						echo "<span class=\"title\">Duration:</span>$course->duration<br>";
						echo "<span class=\"title\">Photo Consent:</span>$course->photo_consent<br>";
						echo "<span class=\"title\">Capacity:</span>$course->capacity<br>";
						echo "<span class=\"title\">Status:</span>$course->status<br>";
					echo "</div>";
					echo "<div class=\"col-6\">";
						echo "<span class=\"title\">Start Date:</span>$course->start_date<br>";
						echo "<span class=\"title\">Start Time:</span>$course->start_time<br>";
						echo "<span class=\"title\">Length:</span>$course->length<br>";
						echo "<span class=\"title\">Time Zone:</span>$course->time_zone<br>";
					echo "</div>";
					echo "</div>";
					echo "
					<br><br>
					<div class=\"row\">
						<span class=\"title\">Course Description:</span><br>$course->introduction
					</div>
					<div class=\"row\">
						<span class=\"title\">Course Syllabus:</span><br>$course->syllabus
					</div>
					<div class=\"row\">
						<span class=\"title\">Extra Notes:</span><br>$course->note
					</div>";
					echo '<input class="ui-button ui-widget ui-corner-all" name="approve_course" type="submit" value="Approve Course #'.$course->id.'" onclick="return confirm('."'Do you want to approve course $course->title as an official AYDN course?'".')" style="background-color: aquamarine;"> <input class="ui-button ui-widget ui-corner-all" name="disapprove_course" type="submit" value="Disapprove Course #'.$course->id.'" onclick="return confirm('."'Do you want to DENY $course->title from being an official AYDN course?'".')" style="background-color: darkred;color:white;"><br /><br />';
					echo"<textarea style=\"width:100%;\" name=\"courses_deny_reason$course->id\" placeholder=\"Reason For Denial\">$course->deny_reason</textarea>
					</div>
					";

				}
				echo "</div>";

				// display hours by this volunteer
				echo "<h2>$volunteer->name's Hours</h2>";
				echo '<div class="card" style="max-width:100%;">
				Start Date
				<input type="date" style="width: 200px;" id="hours_search_start_date" value="'.$min_date.'" />
				End Date
				<input type="date" style="width: 200px;" id="hours_search_end_date"  value="'.$max_date.'"/>
				<button type="button" id="hours_search" class="ui-button ui-widget ui-corner-all">Filter By Date Range</button><br /><br />
				<strong>Total hours within date range:</strong> <span id="hours_submitted">'.$total_hours_submitted.'</span> 
				hours submitted; <span id="hours_approved">'.$total_hours_approved.'</span> hours approved
				</div>
				';
				echo '<div id="achours">';
				foreach ($hours_results as $entry) {	
					$status_color = ($entry->status == "Approved") ? "lightgreen" : (($entry->status == "New") ? "gold" : "#999");
					echo "<h3><span>$entry->event_date</span>  -  $entry->event_name, $entry->total_hours hr(s) | <span style=\"background-color: $status_color; padding: 4px\">$entry->status</span></h3><div>";
					echo '<div class="hours_row">
						<div class="row">';
					echo "<div class=\"col-6\">";
						echo "<span class=\"title\">Start Time:</span>$entry->start_time<br>";
						echo "<span class=\"title\">Hours:</span>$entry->hours<br>";
						echo "<span class=\"title\">Extra Hours:</span>$entry->extra_hours<br>";
						echo "<span class=\"title\">Event Date:</span><span class=\"event_date\">$entry->event_date</span><br>";
					echo "</div>";
					echo "<div class=\"col-6\">";
						echo "<span class=\"title\">Event Type:</span>$entry->event_type<br>";
						echo "<span class=\"title\">End Time:</span>$entry->end_time<br>";
						echo "<span class=\"title\">Total Hours:</span><span class=\"total_hours\">$entry->total_hours</span><br>";
						echo "<span class=\"title\">Status:</span><span class=\"hours_status\">$entry->status</span><br>";
					echo "</div>";
					echo "</div>";
					echo "
					<br><br>
					<div class=\"row\">
						<span class=\"title\">Course Description:</span><br>$entry->event_description
					</div>
					<div class=\"row\">
						<span class=\"title\">Extra Notes:</span><br>$entry->others
					</div>";
					echo '<input class="ui-button ui-widget ui-corner-all" name="approve_hours" type="submit" value="Approve Hours #'.$entry->id.
					'" onclick="return confirm('."'Do you want to approve this submission?'".')" style="background-color: aquamarine;"> 
					<input class="ui-button ui-widget ui-corner-all" name="disapprove_hours" type="submit" value="Disapprove Hours #'.$entry->id.
					'" onclick="return confirm('."'Do you want to DENY this submission?'".')" style="background-color: darkred;color:white;">
					<a class="ui-button ui-widget ui-corner-all" href="'.$homeURL.'&vid='.$vid.'&hid='.$entry->id.'" style="background-color: aqua;">Edit Hours</a><br /><br />';
					echo "<textarea style=\"width:100%;\" name=\"hours_deny_reason$entry->id\" placeholder=\"Reason For Denial\">$entry->deny_reason</textarea>";
					echo "</div>
					</div>
					";

				}
				echo "</div>";
			}

			else{
				$sort = 'aydn_number';
				if(isset($_GET['sort'])) $sort = $_GET['sort'];
				// pull volunteer list
				$sql = "SELECT *, (select sum(total_hours) from $hours_tablename where volunteer_id=v.id and status='Approved') as hours, 
				(select count(status) from $hours_tablename where volunteer_id=v.id and status='New') as hours_action_count, 
				(select count(status) from $courses_tablename where volunteer_id=v.id and status='New') as courses_action_count
				from $volunteers_tablename v";
				
				//filter hours by dates or last name
				$start = "2022-01-01"; 
				$end = "2030-01-01"; 
				$lname = ""; 
				$reload = false;
				if(isset($_POST['volunteer_start_date']) && strlen($_POST['volunteer_start_date']) > 0) {
					$start = $_POST['volunteer_start_date'];
					$reload = true;
				}
				if(isset($_POST['volunteer_end_date']) && strlen($_POST['volunteer_end_date']) > 0) {
					$end = $_POST['volunteer_end_date'];
					$reload = true;
				}
				if(isset($_POST['volunteer_lname']) && strlen($_POST['volunteer_lname']) > 0) {
					$lname = $_POST['volunteer_lname'];
					$reload = true;
				}
				if($reload){
					$sql = "SELECT *, (select sum(total_hours) from $hours_tablename where volunteer_id=v.id and status='Approved' 
					and (event_date between '$start' and '$end')) as hours,
					(select count(status) from $hours_tablename where volunteer_id=v.id and status='New') as hours_action_count, 
					(select count(status) from $courses_tablename where volunteer_id=v.id and status='New') as courses_action_count";	
					$sql .= " from $volunteers_tablename v";
					if(strlen($lname) > 0) $sql .= " where lastname = '$lname'";
				}
				if($sort) $sql .= ' order by '.$sort;
				$results = $wpdb->get_results($sql);
				echo '<div class="row">';
				echo '<label for="volunteer_start_date" class="form-label">Start Date</label>';
				echo '<input class="form-contol" value="'.$start.'" type="date" name="volunteer_start_date" style="margin: 10px 50px 10px 10px;">';
				echo '<label for="volunteer_end_date" class="form-label">End Date</label>';
				echo '<input type="date" value="'.$end.'" name="volunteer_end_date" style="margin: 10px 20px 10px 10px;">';
				echo '<label for="volunteer_lname" class="form-label">Last Name</label>';
				echo '<input type="text" value="'.$lname.'" name="volunteer_lname" style="margin: 10px 20px 10px 10px; width: 100px;">';
				echo '
				  	Action Required?
				  <input class="form-check-input" type="checkbox" value="on" name="filterByActionRequired" style="margin-right: 20px;" '; if(isset($_POST['filterByActionRequired'])){echo 'checked';} echo '>
				  	New Volunteers?
				  <input class="form-check-input" type="checkbox" value="on" name="filterByNewVolunteers" style="margin-right: 20px;" '; if(isset($_POST['filterByNewVolunteers'])){echo 'checked';} echo '>
				  ';
				  echo '<button class="button button-primary" name="volunteer_filter" form="aydn-admin" style="vertical-align: middle;">Filter</button>';
				echo '</div>';
				echo '<table class="table"><tr style="background-color:cornsilk;">
				<th></th>
			  	<th>First Name</th>
			  	<th>Last Name</th>
				<th>Date of Birth</th>
			  	<th>AYDN #</th>
			  	<th>Email</th>
				<th>Approved Hours</th>
			  	<th>Status</th>
			  	<th></th>
			  	</tr>';
			  	$bgcolor = '#fff';
			  	for($i = 0; $i < count($results); $i++){
			  		if($i % 2 == 0) $bgcolor = '#eee';
			  		else $bgcolor = '#fff';
					$actionRequired = $results[$i]->hours_action_count != 0 || $results[$i]->courses_action_count != 0 || $results[$i]->status == 'New';
					if(!isset($_POST['filterByActionRequired']) || (isset($_POST['filterByActionRequired']) && $actionRequired)){
						if(!isset($_POST['filterByNewVolunteers']) || (isset($_POST['filterByNewVolunteers']) && $results[$i]->status == 'New')){
							echo "<tr style=\"background-color:$bgcolor;\">";
							echo $actionRequired ? "<td style=\"color: red; font-weight: bold;\">!</td>" : "<td></td>";
							echo "<td>".$results[$i]->firstname."</td>";
							echo "<td>".$results[$i]->lastname."</td>";	
							echo "<td>".$results[$i]->birthdate."</td>";	
							echo "<td>".$results[$i]->aydn_number."</td>";	  
							echo "<td>".$results[$i]->email."</td>";
							echo "<td>".$results[$i]->hours."</td>";
							echo "<td>".$results[$i]->status."</td>";	
							echo '<td><a href="'.$uri.'&vid='.$results[$i]->id.'">View Details</a></td>';		
							echo "</tr>";
						}
					}
			  	}
			  	echo "</table>";

				// form to update settings
				if(isset($_POST['update_backup_email'])){
					$backup_email = $_POST['backup_email'];
					update_option('aydn_backup_email', $backup_email);
				}
			}
			?>
			<h3>AYDN System Settings</h3>
			<strong>Backup Email Address:</strong>
			<input type="email" name="backup_email" value="<?php echo get_option('aydn_backup_email'); ?>" />
			<input type="submit" name="update_backup_email" id="submit" class="button button-primary" value="Save Changes" />
	    <?php 
			// print sql for debug
			echo "<div style='visibility:hidden;'>$sql</div>";
		?>
		</form>

</div>