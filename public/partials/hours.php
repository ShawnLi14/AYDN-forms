	<!-- information -->
	<div class="container">
		<h2>AYDN Volunteer Hours Entry Form</h2>
		<div class="row">
			<label class="form-label" for="event_type">Event</label>
			<select class="form-select heightFix" aria-label="Event" id="event_type" name="event_type">
			  <option selected>Select...</option>
			  <option value="zoom">Zoom Class Teaching</option>
			  <option value="writing">Writing Club</option>
			  <option value="reading">Reading Club</option>
			  <option value="outdoor">Outdoor Program</option>
			  <option value="cohosting">Co-hosting</option>
			  <option value="other">Other Event</option>			  
			</select>
			<label class="form-label" for="event_name">Event Name/Course Title</label>
			<input type="text" id="event_name" name="event_name" class="form-control" required>
			<label class="form-label" for="event_description">Event Description</label>
			<input type="text" id="event_description" name="event_description" class="form-control">
			<label class="form-label" for="event_date">Event Date</label>
			<input type="text" id="event_date" name="event_date" class="form-control date" required>
			<label class="form-label" for="start_time">Event Start Time</label>
			<input type="text" id="start_time" name="start_time" class="form-control time" required>
			<label class="form-label" for="end_time">Event End Time</label>
			<input type="text" id="end_time" name="end_time" class="form-control time" required>
			<label class="form-label" for="hours">Event Hour(s)</label>
			<input type="number" step="0.01" min="0" id="hours" name="hours" class="form-control hour" required>
			<label class="form-label" for="extra_hours">Extra Hour(s)</label>
			<input type="number" step="0.01" min="0" id="extra_hours" name="extra_hours" class="form-control hour">
			<label class="form-label" for="total_hours">Total Hour(s)</label>
			<input type="number" step="0.01" min="0" id="total_hours" name="total_hours" class="form-control">
			<label class="form-label" for="others">Others</label>			
			<textarea id="others" name="others" rows="10" class="form-control"></textarea>		
		</div>										

		<br />
        <button class="btn btn-primary" type="submit" name="hours_submit">Submit Hours</button>
    </div>