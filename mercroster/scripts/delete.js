function confirmSubmit(x) {
	var agree = confirm(x + ". Are you sure?");
	if (agree)
		return true;
	else
		return false;
}