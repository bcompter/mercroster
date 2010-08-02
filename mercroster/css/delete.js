//<script type="text/javascript">
//  <!--
function confirmSubmit(x) {
	var agree = confirm(x + ". Are you sure?");
	if (agree)
		return true;
	else
		return false;
}

function validate_required(field, alerttxt) {
	with (field) {
		if (value == null || value == "") {
			alert(alerttxt);
			return false;
		} else {
			return true;
		}
	}
}

function validate_form(thisform) {
	with (thisform) {
		if (validate_required(username, "Username must be filled out!") == false) {
			username.focus();
			return false;
		}
		if (validate_required(name, "Site name must be filled out!") == false) {
			name.focus();
			return false;
		}
		if (validate_required(firstname, "First name must be filled out!") == false) {
			firstname.focus();
			return false;
		}
		if (validate_required(lastname, "Last name must be filled out!") == false) {
			lastname.focus();
			return false;
		}
	}
}
// -->
// </script>
