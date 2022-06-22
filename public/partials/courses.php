	<!-- information -->
	<div class="container">
		<h2>AYDN Volunteer Course Proposal Form</h2>
		<div class="row">
			<label class="form-label" for="title">Title</label>
			<input type="text" id="title" name="title" class="form-control" required>
			<label class="form-label" for="introduction">Introduction</label>			
			<textarea id="introduction" name="introduction" rows="10" class="form-control" required></textarea>
			<label class="form-label" for="syllabus">Syllabus</label>			
			<textarea id="syllabus" name="syllabus" rows="10" class="form-control" required></textarea>
			<label class="form-label" for="start_date">Start Date</label>
			<input type="text" id="start_date" name="start_date" class="form-control date" required>
			<label class="form-label" for="start_time">Start Time</label>
			<input type="text" id="start_time" name="start_time" class="form-control time" required>
			<label class="form-label" for="length">Length</label>
			<input type="text" id="length" name="length" class="form-control" required>
			<label class="form-label" for="time_zone">Time Zone</label>
			<input type="text" id="time_zone" name="time_zone" class="form-control">
			<label class="form-label" for="capacity">Capacity</label>
			<input type="text" id="capacity" name="capacity" class="form-control" required>
			<label class="form-label" for="duration">Duration</label>
			<input type="text" id="duration" name="duration" class="form-control" required>
			<label class="form-label" for="photo_consent">Photo and Video: I give permission for my and/or my child's (if student is under 18) photograph and video to be used by Asian Youh Development Network on its website or at its facility for any Asian Youth Development Network related publicity, including print and broadcast media (If "NO" can NOT participate.)</label>
			<div class="form-check form-check-inline">
				<input type="radio" id="photo_consent_yes" name="photo_consent" checked="checked" class="form-check-input">
				<label class="form-check-label" for="photo_consent_yes">
				   YES
				</label>
			<div class="form-check form-check-inline">
			</div>
				<input type="radio" id="photo_consent_no" name="photo_consent" class="form-check-input">
				<label class="form-check-label" for="photo_consent_no">
			    	NO
			  	</label>
			</div><br /><br />
			<label class="form-label" for="note">Additional Information</label>			
			<textarea id="note" name="note" rows="10" class="form-control"></textarea>		
		</div>										

		<br />
        <button class="btn btn-primary" type="submit" name="courses_submit">Submit Course Proposal</button>
    </div>