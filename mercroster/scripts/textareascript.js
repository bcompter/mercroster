function storeCaret(text) {
	if (text.createTextRange) {
		text.caretPos = document.selection.createRange().duplicate();
	}
}

// Surrounds part of the text with given tags
function surroundText(pretag, posttag, textarea) {
	// IE Style solution
	if (typeof (textarea.caretPos) != "undefined" && textarea.createTextRange) {
		var caretPos = textarea.caretPos, temp_length = caretPos.text.length;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? pretag
				+ caretPos.text + posttag + ' '
				: pretag + caretPos.text + posttag;

		if (temp_length == 0) {
			caretPos.moveStart("character", -posttag.length);
			aretPos.moveEnd("character", -posttag.length);
			caretPos.select();
		} else {
			textarea.focus(caretPos);
		}
	}
	// Mozilla type solution
	else if (typeof (textarea.selectionStart) != "undefined") {
		var begin = textarea.value.substr(0, textarea.selectionStart);
		var selection = textarea.value.substr(textarea.selectionStart,
				textarea.selectionEnd - textarea.selectionStart);
		var end = textarea.value.substr(textarea.selectionEnd);
		var newCursorPos = textarea.selectionStart;
		var scrollPos = textarea.scrollTop;
		textarea.value = begin + pretag + selection + posttag + end;
		if (textarea.setSelectionRange) {
			if (selection.length == 0) {
				textarea.setSelectionRange(newCursorPos + pretag.length,
						newCursorPos + pretag.length);
			} else {
				textarea.setSelectionRange(newCursorPos, newCursorPos
						+ pretag.length + selection.length + posttag.length);
			}
			textarea.focus();
		}
		textarea.scrollTop = scrollPos;
	}
	// If everything fails just put it last
	else {
		textarea.value += pretag + posttag;
		textarea.focus(textarea.value.length - 1);
	}
}

// Replaces the currently selected text with the passed text.
function replaceText(text, textarea) {
	// IE Style solution
	if (typeof (textarea.caretPos) != "undefined" && textarea.createTextRange) {
		var caretPos = textarea.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' '
				: text;
		caretPos.select();
	}
	// Mozilla type solution
	else if (typeof (textarea.selectionStart) != "undefined") {
		var begin = textarea.value.substr(0, textarea.selectionStart);
		var end = textarea.value.substr(textarea.selectionEnd);
		var scrollPos = textarea.scrollTop;
		textarea.value = begin + text + end;
		if (textarea.setSelectionRange) {
			textarea.focus();
			textarea.setSelectionRange(begin.length + text.length, begin.length
					+ text.length);
		}
		textarea.scrollTop = scrollPos;
	}
	// If everything fails just put it last
	else {
		textarea.value += text;
		textarea.focus(textarea.value.length - 1);
	}
}