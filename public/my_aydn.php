<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="my_aydn" >
	<input type="hidden" name="action" value="process_my_aydn" />
	<!-- information -->
	<div class="container-fluid">
		<?php
		$current_user = wp_get_current_user();
		$email = (string) $current_user->user_email;

		global $wpdb;
	  	$charset_collate = $wpdb->get_charset_collate();
	  	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	 	$volunteers_tablename = $wpdb->prefix."aydn_volunteers";
	 	$courses_tablename = $wpdb->prefix."aydn_courses";
	 	$hours_tablename = $wpdb->prefix."aydn_hours";
	 	$sql = "select h.* from $hours_tablename h join $volunteers_tablename v on h.volunteer_id = v.id
	 	where v.email = '%s' order by h.event_date";
	 	$hours_results = $wpdb->get_results($wpdb->prepare($sql, $email));
	 	$sql = "select c.* from $courses_tablename c join $volunteers_tablename v on c.volunteer_id = v.id
	 	where v.email = '%s'";
	 	$courses_results = $wpdb->get_results($wpdb->prepare($sql, $email));
	 	$sql = "select * from $volunteers_tablename where email = '%s'";
	 	$volunteer_results = $wpdb->get_results($wpdb->prepare($sql, $email));
	 	if(count($volunteer_results) == 0) {
	 		exit;
	 	}
	 	$volunteer = $volunteer_results[0];
	 	$total_submitted = 0;
	 	$total_approved = 0;

	 	//hours form
	 	if(isset($_GET['entry']) && $_GET['entry'] == 'hours'){
	 		// process hours submission
	 		if(isset($_POST['hours_submit'])){
	 			$hours_entry = array(
	 				'event_type' => $_POST['event_type'],
					'event_name' => $_POST['event_name'],
	 				'event_date' => $_POST['event_date'],
	 				'event_description' => $_POST['event_description'],
	 				'start_time' => date('Y-m-d H:i:s',strtotime($_POST['event_date'] . " " . $_POST['start_time'])),
	 				'end_time' => date('Y-m-d H:i:s',strtotime($_POST['event_date'] . " " . $_POST['end_time'])),
	 				'hours' => $_POST['hours'],
	 				'extra_hours' => $_POST['extra_hours'],
	 				'total_hours' => $_POST['total_hours'],
	 				'others' => $_POST['others'],
	 				'volunteer_id' => $volunteer->id
	 			);
	 			$success = $wpdb->insert($hours_tablename, $hours_entry);
	 			if($success){
	 				echo "Successfully submitted";
	 				$url = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);
	  				print("<br><a href='$url'>Return to Dashboard</a>");
	 			}
	 		}
	 		// display hours form
	 		else require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/hours.php';
	 	}
	 	else if(isset($_GET['entry']) && $_GET['entry'] == 'courses'){
				if(isset($_POST['courses_submit'])){
	 			$courses_entry = array(
	 				'title' => $_POST['title'],
	 				'introduction' => $_POST['introduction'],
	 				'syllabus' => $_POST['syllabus'],
	 				'start_date' => $_POST['start_date'],
	 				'start_time' => $_POST['start_time'],
	 				'length' => $_POST['length'],
	 				'time_zone' => $_POST['time_zone'],
	 				'capacity' => $_POST['capacity'],
	 				'duration' => $_POST['duration'],
	 				'photo_consent' => $_POST['photo_consent'],
	 				'note' => $_POST['note'],
	 				'volunteer_id' => $volunteer->id
	 			);
	 			$success = $wpdb->insert($courses_tablename, $courses_entry);
	 			if($success){
	 				echo "Successfully submitted";
	 				$url = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);
	  				print("<br><a href='$url'>Return to Dashboard</a>");
	 			}
	 		}
	 		else require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/courses.php';
	 	}
	 	else{
			// find out the min and max date for hours submissions
			$min_date = null;
			$max_date = null;
			if(count($hours_results) > 0){
				$min_date = $hours_results[0]->event_date;
				$max_date = $min_date;
				foreach ($hours_results as $entry) {
					if(strcmp($entry->event_date, $max_date) > 0) $max_date = $entry->event_date;
					if(strcmp($entry->event_date, $min_date) < 0) $min_date = $entry->event_date;
				}				
			}

			?>
			<h4>My Profile</h4>
			<div class="row">
				<div class="col-6">
					<?php echo "
					<strong>Display Name: </strong>$current_user->display_name<br />
					<strong>Login: </strong>$current_user->user_login<br />
					<strong>AYDN Number: </strong>$volunteer->aydn_number<br />";
					?>
				</div>
				<div class="col-6">
					<?php echo "
					<strong>Email: </strong>$current_user->user_email<br />
					<strong>Join Date: </strong>$current_user->user_registered<br />";
					?>
				</div>
		   	</div><br><hr />
		   	<h4>My Hours</h4>
		   	<div class="row">
		   		<button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#hoursSubmissionHistory" aria-expanded="false" aria-controls="hoursSubmissionHistory">
				    View Hours Submission History
				</button>
		   		<div class="collapse" id="hoursSubmissionHistory">
				  	<div><br /><br />
						Start Date
						<input type="date" style="width: 200px;" id="hours_search_start_date" value="<?php echo $min_date; ?>" />
						End Date
						<input type="date" style="width: 200px;" id="hours_search_end_date"  value="<?php echo $max_date; ?>"/>
						<div class="d-grid gap-2 col-6 mx-auto">
							<button type="button" id="hours_search" class="btn btn-primary">Filter By Date Range</button><br /><br />
						</div>
				   		<?php
				   			echo '
					 			<table class="table table-striped table-bordered" id="hours_table">
					 		';
					 		echo '<tr>
					 			<th>Event</th>
					 			<th>Event Date</th>
					 			<th>Hours</th>
					 			<th>Extra Hours</th>
								<th>Total Hours</th>
					 			<th>Status</th>
					 		</tr>';
							$i = 0;
				   			foreach ($hours_results as $entry) {
						 		$total_submitted += $entry->total_hours;
						 		if($entry->status == "Approved"){
						 			$total_approved += $entry->total_hours;
						 		}
						 		$status_color = ($entry->status == "Approved") ? "LawnGreen" : (($entry->status == "New") ? "LightBlue" : "LightPink");
						 		$format = 'Y-m-d H:i:s';
						 		$start_time = DateTime::createFromFormat($format, $entry->start_time);
						 		$end_time = DateTime::createFromFormat($format, $entry->end_time);
						 		echo "
						 			<tr id=\"hours_row_$i\" class=\"hours_row\">
						 			<td>$entry->event_type<br />$entry->event_name</td>
						 			<td><span class=\"event_date\">".substr($entry->event_date, 0, 10)."</span><br>".$start_time->format('H:i A')." - ".$end_time->format('H:i A')."</td>
						 			<td>$entry->hours</td>
						 			<td>$entry->extra_hours</td>
									<td class=\"total_hours\">$entry->total_hours</td>
						 			<td class=\"hours_status\" style=\"background-color: $status_color\">$entry->status</td>
						 			</tr>
						 		";
								$i++;
						 	}
						 	echo '</table>'
				   		?>
		   			</div>
				</div>
		   	</div><br /><br />
		   	<div class="row">
				<div class="col-sm-6">
					<?php echo "
					<strong>Total Hours Submitted: </strong><span id=\"hours_submitted\">$total_submitted</span><br />"
					?>
				</div>
				<div class="col-sm-6">
					<?php echo "
					<strong>Total Hours Approved: </strong><span id=\"hours_approved\">$total_approved</span><br />"
					?>
				</div>
		   	</div><br /><br />
			<a href="<?php echo $_SERVER['REQUEST_URI']."?entry=hours"; ?>" class="btn btn-primary" role="button">Submit Hours</a><br /><br /><hr />
			<h4>My Courses</h4>
		   	<div>

		   		<div class="accordion" id="coursesAccordion">
			   		<?php 
			   			$i = 0;
			   			foreach ($courses_results as $entry) {
			   				echo '
			   					<div class="accordion-item">
							    <h2 class="accordion-header" id="heading'.$i.'">
							      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse'.$i.'" aria-expanded="true" aria-controls="collapse'.$i.'">
							        '.$entry->title . " - " . $entry->status .'
							      </button>
							    </h2>
							    <div id="collapse'.$i.'" class="accordion-collapse collapse" aria-labelledby="heading'.$i.'" data-bs-parent="#coursesAccordion">
							      <div class="accordion-body">
							      	<h4>Introduction</h4>
							        <p>'.$entry->introduction.'</p><br>
							        <h4>Syllabus</h4>
							        <p>'.$entry->syllabus.'</p><br>';
							        echo "<div class=\"col-6\">";
							        	echo "<span class=\"title\">Duration:</span>$entry->duration<br>";
							        	echo "<span class=\"title\">Photo Consent:</span>$entry->photo_consent<br>";
							        	echo "<span class=\"title\">Capacity:</span>$entry->capacity<br>";
							        	echo "<span class=\"title\">Status:</span>$entry->status<br>";
							        echo "</div>";
							        echo "<div class=\"col-6\">";
							        	echo "<span class=\"title\">Start Date:</span>$entry->start_date<br>";
							        	echo "<span class=\"title\">Start Time:</span>$entry->start_time<br>";
							        	echo "<span class=\"title\">Length:</span>$entry->length<br>";
							        	echo "<span class=\"title\">Time Zone:</span>$entry->time_zone<br>";
							        echo '</div>
							      </div>
							    </div>
							  </div>
			   				';
			   				$i++;
			   			}
			   		?>
		   		</div>
		   	</div>
		   	<br /><br />
			<a href="<?php echo $_SERVER['REQUEST_URI']."?entry=courses"; ?>" class="btn btn-primary" role="button">Submit New Course Proposal</a><br /><br />
		<?php } ?>
    </div>

</form>