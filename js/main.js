// Bind buttons.
$(function() {
	$("#registration-submit").click(function(e){registerUser(e);});
	$("#login-submit").click(function(e){login(e);});
});

function registerUser(e){
	var regexEmail = /^([A-Za-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[A-Za-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?\.)+[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?)+$/;
	var $errors = 0;

	// Loop through form fields.
	$('.form-control').each(function(){
		var $this = $(this);
		var id = $this.attr('id');
		var value = $this.val();

		// Verify that required fields are filled in.
		if( value.length == 0 ){
			;
			// Format field names.
			var fieldID = id.replace('_', ' ');
			fieldID = fieldID.replace( /(^|\s)([a-z])/g, function(x){ return x.toUpperCase(); });
			if (fieldID == 'Email Field'){
				fieldID = 'Email Address';
			}
			var fieldName = 'Enter a valid ' + fieldID + '.';
			// Code to add notification text below corresponding field
			$this.next().text(fieldName);
			$this.next().removeClass("invisible");
			$errors++;
		}
		// Validate email field.
		else if (id == 'email_field'){
			// Invalid email address.
			if(!regexEmail.test(value)) {
				$this.next().text("Invalid email address.");
				$this.next().removeClass("invisible");
				$errors++;
			}
			else{
				// Remove warning for previous field submissions.
				$this.next().addClass("invisible");
			}
		}
		// If the field does not trigger any validation errors,
		// remove it's error text (if it exists).
		else {
			// Remove warning for previous field submissions.
			$this.next().addClass("invisible");   
		}  
	});

	// If there are any errors, prevent form submission.
	if( $errors > 0){
		e.preventDefault();
		return;
	}
	// If there are no errors, continue with adding user.   
	var fd = new FormData();   
		fd.append('email', document.getElementById("email").value);
		fd.append('username', document.getElementById("username").value);
		fd.append('password', document.getElementById("password").value);

	var results = $.ajax({
		type: "POST",
		dataType: "json",
		data: fd,
		async: false,
		processData: false,
		contentType: false,		
		url: "lib/addUser.php",
		beforeSend: function() {
			$(document.body).addClass('loading');
		},
		complete: function(data){
			$('#toast-text').html(data.responseText);
			$('#registration-toast').toast('show');
		},
	});
	e.preventDefault();
}

function login(e){
	// if (email and password exist and are valid) {
	var fd = new FormData();   
		fd.append('email', document.getElementById("email").value);
		fd.append('password', document.getElementById("password").value);

	var results = $.ajax({		
		type: "POST",
		dataType: "json",
		data: fd,
		async: false,
		processData: false,
		contentType: false,		
		url: "lib/login.php",
		beforeSend: function() {
			$(document.body).addClass('loading');
			$( "div" ).css( "opacity", "0.9" );
		},
		complete: function(data){
			var response = JSON.stringify(data.responseText);
			if (response == '"true"'){
				window.location.href = "/";
			} else {
				// else clear fields and provide incorrect password message
				$('.form-control').val('');
				alert('Invalid password!');
			}
		},
	});

	e.preventDefault();
};