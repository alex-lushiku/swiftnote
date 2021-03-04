var color_text;
var color_button_light;
var color_button_dark;
var color_background;
var color_hover;
var color_topbar;
var color_textfield;
var color_modal;
var color_border;
var color_btn_default;
var color_btn_primary;
var color_textfield_text;

var brightness;
var font_size = 32;
var saved = true;
var mouseObj;

var themeg;

function getContent() {
	return textfield.document.body.innerHTML;
}

var increment;

// Login / Register form popup
function popupForm() {
	var login = '<form id="form_log" action="php/login.php" method="POST"><label style="color:' + color_text + '">Login</label> <div class="form-group"> <input type="email" placeholder="Email address" class="form-control" name="log_email" id="email" style="color:' + color_textfield_text + ';border: 1px solid ' + color_border + '; background:' + color_textfield + '" required> </div> <div class="form-group"> <input type="password" placeholder="Password" class="form-control" name="log_password" style="color:' + color_textfield_text + ';border: 1px solid ' + color_border + '; background:' + color_textfield + '" required> </div> <div class="form-group"> <button type="submit" class="btn ' + color_btn_primary + ' btn-block form-control" name="login" style="border:0">Login</button></div> </form>';
	var register = '<form id="form_reg" action="php/register-email.php" method="POST"><label style="color:' + color_text + '">Register</label> <div class="form-group"> <input type="email" placeholder="Email address" class="form-control" name="reg_email" id="reg_email" style="color:' + color_textfield_text + ';border: 1px solid ' + color_border + '; background:' + color_textfield + '" required> </div> <div class="form-group"> <input type="password" placeholder="Password" class="form-control" name="reg_password" style="color:' + color_textfield_text + ';border: 1px solid ' + color_border + '; background:' + color_textfield + '" required> </div> <div class="form-group"> <input type="password" placeholder="Confirm Password" class="form-control" name="reg_confirm_password" style="color:' + color_textfield_text + ';border: 1px solid ' + color_border + '; background:' + color_textfield + '" required> </div> <div class="form-group"> <button type="submit" class="btn ' + color_btn_primary + ' btn-block form-control" name="register" style="border:0">Register</button> </div> </form>';
	var forgot_pass = '<form action="php/forgot-password.php" method="POST"><label style="color:' + color_text + '">Forgot password?</label><div class="form-group"><input type="email" placeholder="Email address" class="form-control" name="forgot_email" id="forgot_email" style="color:' + color_textfield_text + ';border: 1px solid ' + color_border + ';background:' + color_textfield + '" required></div><div class="form-group"><button type="submit" class="btn ' + color_btn_primary + ' btn-block form-control" name="send" style="border:0">Send link</button> </div></form>';

	//var social = '<div style="position:relative;left:7px;border:0" class="btn-group btn-group-justified"><a href="' + fb_login_url + '" class="btn ' + color_btn_primary + '">Facebook</a><a href="" class="btn ' + color_btn_primary + '">Google+</a></div>';
	var login_facebook = '<form><div class="form-group"><a href="' + fb_login_url + '"<button type="submit" style="border:0" class="btn ' + color_btn_primary + ' btn-block">Facebook</button></a></div></form>';
	//var login_google = '<form action="" method="POST"><div class="form-group"><button type="submit" style="border:0" class="btn ' + color_btn_primary + ' btn-block">Google+</button></div></form>';

	bootbox.dialog({
		title: "Login or register a new account", 
		message: login + login_facebook + "<br>" + register + "<br>" + forgot_pass, 
		backdrop: true, 
		onEscape: true
	}).find('.modal-content').css({'background-color': color_modal, 'color' : color_text});
}

// Log out
function logout () {
	document.location = 'php/logout.php';
}

// Confirm page close
window.onbeforeunload = function(evnt) {
	if (!saved && window.location.href.indexOf("index") > -1) {
		var msg = "You haven\'nt saved your note. Are you sure you want to leave?";
		
		evnt = evnt || window.event;

		if (evnt)
			evnt.returnValue = msg;

		return msg;
	}
}

function showMyNotes() {
	textfield.document.body.innerHTML = document.cookie;
}

function download() {
	var cont = textfield.document.body.innerText;

	if (getContent() != "") {
		var blob = new Blob([cont], {type: "text/plain;charset=utf-8"});
		saveAs(blob, "Note");
	} else {
		bootbox.alert({
			message: "Empty note",
			className: "bb-alternate-modal"
		});
	}
}

function retrieveNote() {
	var info = $("#hid").data("info");
	textfield.document.body.innerHTML = info; 
}

var idsc;
var id_count;

/*var js = textfield.document.createElement("script");
js.type = "text/javascript";
js.src = "script.js";
textfield.document.body.appendChild(js);*/

function showSaved() {
	var ids = $("#saved_id").data("info").split(",");
	var names = $("#saved_name").data("info").split(",");

	idsc = ids;

	if (ids != undefined && ids != null && ids.length != 1 && names.length != 1 && names[0] != "") {
		for (var i = 0;i < ids.length-1;i++) {
			id_count = i;

			url = textfield.document.createElement("a");

			url.href = "index.php?id=" + ids[i];
			url.target = "_parent";
			url.appendChild(document.createTextNode(names[i] + "\n"));
			$(url).css("text-decoration", "none");
			$(url).css("color", color_text);

			// Delete note cross
			del = textfield.document.createElement("img");
			if (themeg == 'light')
				del.setAttribute('src', 'img/cross_light.png');
			else if (themeg == 'dark')
				del.setAttribute('src', 'img/cross_dark.png');
			else
				del.setAttribute('src', 'img/cross_color.png');
			del.setAttribute('alt', 'Delete note');
			del.setAttribute('width', '10px');
			del.setAttribute('height', '10px');
			del.setAttribute('style', 'vertical-align:middle;margin-right:15px');
			del.setAttribute('onclick','parent.deleteNote(idsc[id_count]);');
			del.setAttribute('id', ids[i]);
			del.onclick = function () {
				$.post("php/delete.php", {id:this.id}, function (data) {
					window.location.reload(true);
				});
				//window.location.reload(true);
			};

			$(del).hover(function () {
				$(this).css("width", "13px");
				$(this).css("height", "13px");
			}, function () {
				$(this).css("width", "10px");
				$(this).css("height", "10px");
			});

			textfield.document.body.appendChild(del);
			textfield.document.body.appendChild(url);
		}
	} else {
		textfield.document.body.innerHTML = "You don't have any saved notes.";
	}
}

var cont = $(".textfield").contents();

$(document).ready(function(){
	$(".textfield").contents().keyup(function(evnt) {
		document.getElementById("btn_save").style.opacity = "1";

		if (getContent() != "")
			saved = false;
	});

	if (saved) {
		document.getElementById("btn_save").style.opacity = "0.3";
	}
});

function enableEdit(theme) {
	increment = 0;
	themeg = theme;

	// Themes
	if (theme === 'dark') {
		color_text = "#757575";
		color_button_light = "#303030";
		color_button_dark = "#646464";
		color_hover = "#535353";
		brightness = 125;
		color_background = "#151515";
		color_topbar = "#757575";
		color_textfield = "#545454";
		color_modal = "#202020";
		color_border = "#303030";
		color_textfield_text = "#999";

		color_btn_default = "btn-dark-default";
		color_btn_primary = "btn-dark-primary";
	} else if (theme === 'hacker') {
		/*color_text = "#00BB00";
		color_button_light = "#202020";
		color_button_dark = "#00BB00";
		color_hover = "#009900";
		brightness = 125;
		color_background = "#000000";*/
		color_text = "#885B35";
		color_button_light = "#835833";
		color_button_dark = "#48301C";
		color_hover = "#573A22";
		brightness = 80;
		color_background = "#FFF7CC";
		color_topbar = "#FFF7CC";
		color_textfield = "#FFFAE0";
		color_modal = "#FFF7CC";
		color_border = "FFFAE0";
		color_textfield_text = color_text;

		color_btn_default = "btn-color-default";
		color_btn_primary = "btn-color-primary";
	} else if (theme === 'light') {
		color_text = "#999";
		color_button_light = "#BBBBBB";
		color_button_dark = "#757575";
		color_hover = "#646464";
		brightness = 90;
		color_background = "#EEEEEE";
		color_topbar = "#EEEEEE";
		color_textfield = "#F4F4F4";
		color_modal = "#EEE";
		color_border = "#BBB";
		color_textfield_text = color_text;

		color_btn_default = "btn-light-default";
		color_btn_primary = "btn-light-primary";
	}

	// Background Color
	document.body.style.backgroundColor = color_background;
	document.getElementById("toolbar").backgroundColor = color_background;

	var tag = "<link href='https://fonts.googleapis.com/css?family=Open+Sans|Heebo|Lato' rel='stylesheet'><style>body{font-family:consolas, heebo;}</style>"
	$(".textfield").contents().find("head").append(tag);

	textfield.document.body.style.fontSize = font_size + "px";	
	textfield.document.body.style.color = color_text;
	textfield.document.body.style.padding = "1%";
	textfield.document.body.style.tabSize = "4";
	textfield.document.body.style.whiteSpace = "pre-wrap";
	textfield.document.body.style.wordWrap = "break-word";
	textfield.document.body.style.lineHeight = "1.4";
	textfield.focus();
	
	// Buttons
	document.getElementById("btn_bold").style.color = color_button_light;
	document.getElementById("btn_ita").style.color = color_button_light;
	document.getElementById("btn_und").style.color = color_button_light;
	document.getElementById("btn_plus").style.color = color_button_dark;
	document.getElementById("btn_minus").style.color = color_button_dark;

	document.getElementById("topbar").style.backgroundColor = color_button_light;
	document.getElementById("topbar-item").style.color = color_topbar;

	// Tooltip
	$(document).ready(function() {
    	$('[data-toggle="tooltip"]').tooltip(); 
	});
}

function handlePaste() { 
	textfield.document.addEventListener("paste", function(evnt) {
		evnt.preventDefault();
		var text = evnt.clipboardData.getData("text/plain");

		if (getContent() != "" && getContent() != null && getContent() != undefined) {
			document.getElementById("btn_save").style.opacity = "1";
			saved = false;
		}

		textfield.document.execCommand("insertText", false, text);
	});
}

function handleDrop() {
	textfield.document.addEventListener("drop", function (evnt) {
		evnt.preventDefault();

		if (getContent() != "" && getContent() != null && getContent() != undefined) {
			document.getElementById("btn_save").style.opacity = "1";
			saved = false;
		}

		var text = evnt.dataTransfer.getData("text/plain");
        textfield.document.execCommand("insertText", false, text);
	});
}

var sources = ["theme_light2.png", "theme_dark.png", "theme_color.png"];
var sources2 = ["save.png", "save_dark.png", "save_color.png"];
var sources3 = ["cloud_light.png", "cloud_dark.png", "cloud_color.png"];
var sources4 = ["notepad_light.png", "notepad_dark.png", "notepad_color.png"];
var sources5 = ["cross_light.png", "cross_dark.png", "cross_color.png"];
var themes = ["light", "dark", "hacker"];
var iterator;

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function createCookie() {
	if (getCookie("theme") == "")
		iterator = 0;
	else
		iterator = getCookie("theme");

	document.getElementById('btn_theme').src = "img/" + sources[iterator];
	document.getElementById('btn_save').src = "img/" + sources2[iterator];
	document.getElementById('btn_down').src = "img/" + sources3[iterator];
	document.getElementById('btn_notes').src = "img/" + sources4[iterator];
	enableEdit(themes[iterator]);
}

function changeTheme () {
	createCookie();

	if (iterator < sources.length-1) {
		iterator++;
	} else {
		iterator = 0;
	}
	
	document.cookie = "theme=" + iterator + ";expires=Date.getTime() + 60 * 60 * 24 * 365 * 10;";
	document.getElementById('btn_theme').src = "img/" + sources[iterator];
	document.getElementById('btn_save').src = "img/" + sources2[iterator];
	document.getElementById('btn_down').src = "img/" + sources3[iterator];
	document.getElementById('btn_notes').src = "img/" + sources4[iterator];
	enableEdit(themes[iterator]);

	if (window.location.href.indexOf("mynotes") > -1) {
		textfield.document.body.innerHTML = "";
		showSaved();
	}

	onMouseEnter('btn_theme');
}

function bold() {
	textfield.document.execCommand("bold", false, null);
}

function italic() {
	textfield.document.execCommand("italic", false, null);
}

function underline() {
	textfield.document.execCommand("underline", false, null);
}

function changeFontSize (amount) {
	font_size += amount;
	textfield.document.body.style.fontSize = font_size + "px";
}

function onMouseEnter(id) {
	mouseObj = id;
	
	if (mouseObj != 'btn_theme' && mouseObj != 'btn_save' && mouseObj != 'btn_down' && mouseObj != 'btn_notes') {
		document.getElementById(mouseObj).style.color = color_hover;
	} else {
		document.getElementById(mouseObj).style.filter = "brightness(" + brightness + "%)";
	}
}

function onMouseLeave(id) {
	mouseObj = null;
}

setInterval(function() {
	var isBold = textfield.document.queryCommandState("Bold");
	var isItalic = textfield.document.queryCommandState("Italic");
	var isUnderlined = textfield.document.queryCommandState("Underline");
	
	if (isBold == true && mouseObj == null) {
		document.getElementById("btn_bold").style.color = color_button_dark;
	} else if (isBold == false && mouseObj == null) {
		document.getElementById("btn_bold").style.color = color_button_light;
	}
	
	if (isItalic == true && mouseObj == null) {
		document.getElementById("btn_ita").style.color = color_button_dark;
	} else if (isItalic == false && mouseObj == null) {
		document.getElementById("btn_ita").style.color = color_button_light;
	}
	
	if (isUnderlined == true && mouseObj == null) {
		document.getElementById("btn_und").style.color = color_button_dark;
	} else if (isUnderlined == false && mouseObj == null) {
		document.getElementById("btn_und").style.color = color_button_light;
	}
	
	if (mouseObj != 'btn_plus') {
		document.getElementById("btn_plus").style.color = color_button_dark;
	}
	
	if (mouseObj != 'btn_minus') {
		document.getElementById("btn_minus").style.color = color_button_dark;
	}
	
	if (mouseObj != 'btn_theme') {
		document.getElementById("btn_theme").style.filter = "brightness(100%)";
	}

	if (mouseObj != 'btn_save') {
		document.getElementById("btn_save").style.filter = "brightness(100%)";
	}

	if (mouseObj != 'btn_down') {
		document.getElementById("btn_down").style.filter = "brightness(100%)";
	}

	if (mouseObj != 'btn_notes') {
		document.getElementById("btn_notes").style.filter = "brightness(100%)";
	}
},10);