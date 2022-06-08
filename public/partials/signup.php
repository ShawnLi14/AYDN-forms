<form method="post" action="<?php echo esc_attr( admin_url( 'admin-post.php' ) ); ?>" id="signup-form" >
	<input type="hidden" name="action" value="process_signup_form" />

	<!-- information -->
	<div class="container">
		<div class="input-group mb-3">
			<span class="input-group-text" id="firstname">First Name</span>
			<input type="text" id="firstname" name="firstname" class="form-control" required>
			<span class="input-group-text" id="lastname">Last Name</span>
			<input type="text" id="lastname" name="lastname" class="form-control" required>
			<span class="input-group-text" id="name">Preferred Name (for public display)</span>
			<input type="text" id="name" name="name" class="form-control" required>
			<span class="input-group-text" id="birthdate">Date of Birth</span>
			<input type="text" id="birthdate" name="birthdate" class="form-control" required>
			<span class="input-group-text" id="email">Volunteer Email Address</span>
			<input type="email" id="email" name="email" class="form-control" required>
			<span class="input-group-text" id="aydn_number">AYDN #</span>
			<input type="text" id="aydn_number" name="aydn_number" class="form-control" required>
			<span class="input-group-text" id="parent_contact">Parent(s) Wechat or Email</span>
			<input type="text" id="parent_contact" name="parent_contact" class="form-control">
			<span class="input-group-text" id="resume">Resume (Summary/Name/Education/Skills & Experience/Awards)</span>
			<textarea id="resume" name="resume" rows="10" class="form-control"></textarea>												
		</div>
		<br /><br />
	    <div class="input-group">
	   		<span class="input-group-btn">
	        <button class="btn btn-default" type="submit">Submit</button>
	   		</span>
	   	</div>
    </div>

</form>