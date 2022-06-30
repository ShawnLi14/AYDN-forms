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

			echo "<form method=\"post\" action=\"$uri\" id=\"aydn-admin\">";

			// get database handler
		 	global $wpdb;
		  	$charset_collate = $wpdb->get_charset_collate();
		  	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		 	$volunteers_tablename = $wpdb->prefix."aydn_volunteers";
		 	$courses_tablename = $wpdb->prefix."aydn_courses";
		 	$hours_tablename = $wpdb->prefix."aydn_hours";

			// view details for volunteer with vid
			if(isset($_GET['vid'])){

				//get volunteer id
				$vid = $_GET['vid'];
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
				}

				//disapprove volunteer
				if(isset($_POST['disapprove_volunteer'])){
					$user = get_user_by('email', $volunteer->email);
					if($user) wp_delete_user($user->ID);
					$wpdb->update($volunteers_tablename, array('status'=>'Rejected'), array('id'=>$vid));
				}

				//approve course
				if(isset($_POST['approve_course'])){
					$cid = substr($_POST['approve_course'], 16);
					$wpdb->update($courses_tablename, array('status'=>'Approved'), array('id'=>$cid));
				}

				//disapprove course
				if(isset($_POST['disapprove_course'])){
					$cid = substr($_POST['disapprove_course'], 19);
					$wpdb->update($courses_tablename, array('status'=>'Rejected', 'deny_reason'=>$_POST['courses_deny_reason'.$cid]), array('id'=>$cid));
				}		
				//approve hours
				if(isset($_POST['approve_hours'])){
					$hid = substr($_POST['approve_hours'], 15);
					$wpdb->update($hours_tablename, array('status'=>'Approved'), array('id'=>$hid));
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
					$headers = array('Content-Type: text/html; charset=UTF-8');
					
					wp_mail($volunteer->email, $subject, $body, $headers);
				}				

				//pull course info
				$sql = "SELECT * from $courses_tablename where volunteer_id='%d' order by title";
				$courses_results = $wpdb->get_results($wpdb->prepare($sql, $vid));

				//pull hours info
				$sql = "SELECT * from $hours_tablename where volunteer_id='%d' order by event_date";
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
				$homeURL = substr($uri, 0, strpos($uri, "vid")-1);
				$breadcrumb = "<a href=\"$homeURL\">Admin Home</a> > Volunteer Details - $volunteer->name";

				// display volunteer personal information
				echo "$breadcrumb<br><br>";
				echo '<input class="ui-button ui-widget ui-corner-all" name="approve_volunteer" type="submit" value="Approve Volunteer" onclick="return confirm('."'Do you want to approve $volunteer->firstname $volunteer->lastname as an official AYDN volunteer? This will create an Wordpress account for them.'".')" style="background-color: aquamarine;"> <input class="ui-button ui-widget ui-corner-all" name="disapprove_volunteer" type="submit" value="Disapprove Volunteer" onclick="return confirm('."'Do you want to DENY $volunteer->firstname $volunteer->lastname from being an official AYDN volunteer?'".')" style="background-color: darkred;color:white;"><br /><br />';
				echo "<div class=\"row\">";
				echo "<div class=\"col-6\">";
				echo "<span class=\"title\">First Name:</span>$volunteer->firstname<br>";
				echo "<span class=\"title\">Last Name:</span>$volunteer->lastname<br>";
				echo "<span class=\"title\">Display Name:</span>$volunteer->name<br>";
				echo "<span class=\"title\">AYDN #:</span>$volunteer->aydn_number<br>";
				echo "<span class=\"title\">Total Approved Hours:</span>$total_hours_approved<br>";
				echo "</div>";
				echo "<div class=\"col-6\">";
				echo "<span class=\"title\">Email:</span>$volunteer->email<br>";
				echo "<span class=\"title\">Status:</span>$volunteer->status<br>";
				echo "<span class=\"title\">Birth Date:</span>$volunteer->birthdate<br>";
				echo "<span class=\"title\">Parent Contact:</span>$volunteer->parent_contact<br>";				
				echo "</div></div>";
				echo "<div class=\"row\" id=\"resume\">";
				echo "<h3>Resume:</h3><div>";
				echo nl2br($volunteer->resume);
				echo "</div></div>";
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
					echo "<h3><span>$entry->event_date</span>  -  $entry->event_type | status: <span style=\"background-color: $status_color; padding: 4px\">$entry->status</span></h3><div>";
					echo '<div class="hours_row">
						<div class="row">';
					echo "<div class=\"col-6\">";
						echo "<span class=\"title\">Start Time:</span>$entry->start_time<br>";
						echo "<span class=\"title\">Hours:</span>$entry->hours<br>";
						echo "<span class=\"title\">Extra Hours:</span>$entry->extra_hours<br>";
						echo "<span class=\"title\">Event Date:</span><span class=\"event_date\">$entry->event_date</span><br>";
					echo "</div>";
					echo "<div class=\"col-6\">";
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
					echo '<input class="ui-button ui-widget ui-corner-all" name="approve_hours" type="submit" value="Approve Hours #'.$entry->id.'" onclick="return confirm('."'Do you want to approve this submission?'".')" style="background-color: aquamarine;"> <input class="ui-button ui-widget ui-corner-all" name="disapprove_hours" type="submit" value="Disapprove Hours #'.$entry->id.'" onclick="return confirm('."'Do you want to DENY this submission?'".')" style="background-color: darkred;color:white;"><br /><br />';
					echo "<textarea style=\"width:100%;\" name=\"hours_deny_reason$entry->id\" placeholder=\"Reason For Denial\">$entry->deny_reason</textarea>";
					echo "</div>
					</div>
					";

				}
				echo "</div>";
			}

			else{
				// pull volunteer list
				$sql = "SELECT *, (select sum(total_hours) from $hours_tablename where volunteer_id=v.id and status='Approved') as hours, 
				(select count(status) from $hours_tablename where volunteer_id=v.id and status='New') as hours_action_count, 
				(select count(status) from $courses_tablename where volunteer_id=v.id and status='New') as courses_action_count
				from $volunteers_tablename v";
				
				//filter hours by dates
				$start = ""; $end = "";
				if(isset($_POST['volunteer_start_date']) && strlen($_POST['volunteer_start_date']) > 0 && isset($_POST['volunteer_end_date']) && strlen($_POST['volunteer_end_date']) > 0){
					$start = $_POST['volunteer_start_date'];
					$end = $_POST['volunteer_end_date'];
					$sql = "SELECT *, (select sum(total_hours) from $hours_tablename where volunteer_id=v.id and status='Approved' and (event_date between '$start' and '$end')) as hours,
					(select count(status) from $hours_tablename where volunteer_id=v.id and status='New') as hours_action_count, 
					(select count(status) from $courses_tablename where volunteer_id=v.id and status='New') as courses_action_count
					from $volunteers_tablename v";
				}
				$results = $wpdb->get_results($sql);
				echo '<div class="row">';
				echo '<label for="volunteer_start_date" class="form-label">Start Date</label>';
				echo '<input class="form-contol" value="'.$start.'" type="date" name="volunteer_start_date" style="margin: 10px 50px 10px 10px;">';
				echo '<label for="volunteer_end_date" class="form-label">End Date</label>';
				echo '<input type="date" value="'.$end.'" name="volunteer_end_date" style="margin: 10px 20px 10px 10px;">';
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
					$actionRequired = $results[$i]->hours_action_count != 0 || $results[$i]->courses_action_count != 0;
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
		    }
			// form to update settings
			if(isset($_POST['update_backup_email'])){
				$backup_email = $_POST['backup_email'];
				update_option('aydn_backup_email', $backup_email);
			}
		?>
		<h3>AYDN System Settings</h3>
		<strong>Backup Email Address:</strong>
		<input type="email" name="backup_email" value="<?php echo get_option('aydn_backup_email'); ?>" />
		<input type="submit" name="update_backup_email" id="submit" class="button button-primary" value="Save Changes" />
	</form>

</div>