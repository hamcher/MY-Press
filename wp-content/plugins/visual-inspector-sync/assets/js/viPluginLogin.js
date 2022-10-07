var errorText = jQuery("#viErrorText");
function LoginInit() {
    jQuery("#viEmail").on("keyup", function (e) {
        var emailId = this.value;
        if (emailId.length > 0 && !validateEmail(emailId)) {
            this.style.color = "red";
        } else {
            this.style.color = "black";
        }
    });

    jQuery("#viSignup").on("click", function (e) {
        var emailId = document.getElementById('viEmail');
        var password = document.getElementById('viPassword');
        var validEmail = true;
        var validPassword = true;

        //if email id is not null
        if (emailId !== null) {
            var userEmail = emailId.value.trim();
            var userPwd = password.value;
            var emailErrorMessage = document.getElementById("vi-email-error-message");
            var passwordErrorMessage = document.getElementById("vi-pwd-error-message");

            //if email id not valid show email error message
            if (userEmail.length == 0 && !validateEmail(userEmail)) {
                errorMessageFadeIn(emailErrorMessage);
                validEmail = false;
                return;
            } else {
                errorMessageFadeOut(emailErrorMessage);
            }

            //if password is provided, show password error message
            if (userPwd.length == 0) {
                errorMessageFadeIn(passwordErrorMessage);
                validEmail = false;
                return;
            } else {
                errorMessageFadeOut(passwordErrorMessage);
            }
            
            jQuery('#viSignup').attr("disabled", true);
            jQuery('#viSignup').text("Processing...");

            //if both email and password provided are valid
            if (validEmail && validPassword) {
                jQuery.ajax({
                    url: viHost + '/protected/actions/user.php?action=login-register',
                    xhrFields: {withCredentials: true}, //to send cookies in case of inline edit
                    data: {
                        loginId: userEmail,
                        password: userPwd,
                        is_vi: 1
                    },
                    type: 'POST',
                    cache: false,
                    dataType: 'html',
                    success: function (data) {
                        var loginjson = eval('(' + data + ')');
                        if (loginjson["result"] === "success" && loginjson["id"] > 0) {
                            jQuery('#viSignup').attr("disabled", false);
                            jQuery('#viSignup').text("Login");
                            ProjectsInit();
                        }
                    },
                    error: function (data) {
                        errorText.text("Something went wrong. Please check your internet connection.");
                        jQuery('#viSignup').attr("disabled", false);
                        jQuery('#viSignup').text("Login");  
                    },
                    fail: function (data) {
                        errorText.text("Something went wrong. Please check your internet connection.");
                        jQuery('#viSignup').attr("disabled", false);
                        jQuery('#viSignup').text("Login");
                    }
                });
            }
        }
    });

    jQuery("#viSocialSignin").on("click", "#cfFbLogin", function () {
        
        jQuery.ajax({
            url: 'https://www.canvasflip.com/protected/app/svcs/facebook/fbLogin.php',
            xhrFields: {withCredentials: true}, //to send cookies in case of inline edit
            type: 'POST',
            cache: false,
            success: function (data) {
                if (data.result == "success") {
                    var win = window.open(data.url, "facebook login", 'width=800, height=600');

                    // This event hander will listen for messages from the child
                    window.addEventListener('message', function (e) {
                        var data = e.data;
                        win.close();
                        jQuery("#cfFbLogin").button("loading");
                        var socialData = {
                            name: data.name,
                            email: data.email,
                            profilePic: "http://graph.facebook.com/" + data.id + "/picture?type=normal",
                            provider: "facebook",
                            oauthId: data.id
                        }
                        socialSignup(socialData);
                    }, false);
                }
            },
            error: function (data) {
                //..  
            },
            fail: function (data) {
                //..
            }
        });
    });

    jQuery("#viSocialSignin").on("click", "#cfGoogleLogin", function () {
        jQuery.ajax({
            url: 'https://www.canvasflip.com/protected/app/svcs/google/googleLogin.php',
            xhrFields: {withCredentials: true}, //to send cookies in case of inline edit
            type: 'POST',
            cache: false,
            success: function (data) {
                if (data.result == "success") {
                    var win = window.open(data.url, "google login", 'width=800, height=600');
                    

                    // This event hander will listen for messages from the child
                    window.addEventListener('message', function (e) {
                        var data = e.data;
                        win.close();
                        jQuery("#cfGoogleLogin").button("loading");
                        var socialData = {
                            name: data.name,
                            email: data.email,
                            profilePic: data.picture,
                            provider: "google",
                            oauthId: data.id
                        }
                        socialSignup(socialData);
                    }, false);
                }
            },
            error: function (data) {
                //..  
            },
            fail: function (data) {
                //..
            }
        });
    });
}

function socialSignup(data) {
    jQuery.ajax({
        type: 'POST',
        url: viHost + '/protected/actions/user.php?action=social-login-register',
        data: {
            name: data.name,
            email: data.email,
            profilePic: data.profilePic,
            provider: data.provider,
            oauthId: data.oauthId,
            is_vi: 1
        },
        success: function (data) {
            if (data.result == "success") {
                jQuery(".loginBtn").button("reset");
                ProjectsInit();
            } else {
                //user already logged in with different email
                jQuery(".loginBtn").button("reset");
                errorText.addClass("active");
                errorText.html("Email ID/Password is incorrect. <a href='https://www.canvasflip.com/index.php#/forgotpwd'> Recover password?</a>");
            }
        },
        fail: function (data) {
            jQuery(".loginBtn").button("reset");
            errorText.addClass("active");
            errorText.text("Something went wrong. Please check your internet connection.");

        },
        error: function (data) {
            jQuery(".loginBtn").button("reset");
            errorText.addClass("active");
            errorText.text("Something went wrong. Please check your internet connection.");
        }
    });
}

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function errorMessageFadeIn(element) {
    element.style.height = "20px";
}

function errorMessageFadeOut(element) {
    element.style.height = "0px";
}