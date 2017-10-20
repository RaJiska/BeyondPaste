function paste_formIsValid()
{
	var paste_content_length = document.getElementById("paste_contentid").value.length;
	var error_str = null;

	if (paste_content_length <= 0)
		error_str = "Paste content musn't be empty";
	else if (paste_content_length > 65000)
		error_str = "Paste too large, 65000 bytes at most";

	if (error_str != null)
	{
		document.getElementById("paste_content_formid").className += " has-danger";
		var error_label = document.getElementById("paste_content_errorid");
		error_label.innerHTML = error_str;
		error_label.removeAttribute("hidden");
		return false;
	}
	return true;
}

function paste_publishDisplayAlert(alert_type, error_str)
{
	var alert = document.getElementById("paste_alertid");
	var error_prefix;

	alert.className += " " + alert_type;
	alert.innerHTML = "<span class=\"lead\">Ouch !</span><br>An error occured while publishing your paste:<br>&emsp;<strong>" + error_str + "</strong>";
	alert.removeAttribute("hidden");
}