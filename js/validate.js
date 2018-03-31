// Rupesh Pancholi [rgp090020]
// CS 6314.002
// 2-15-2018
// Final Project

$(document).ready(function() {
	// Insert spans after form fields with 'info' css class and info messages
    $("#username").after("<span class='info' id='usernameSpan'> Username must contain only alphabetical or numeric characters </span>");
    $("#password").after("<span class='info' id='passwordSpan'> Password field should be at least 8 characters long </span>");
    $("#email").after("<span class='info' id='emailSpan'> Email should be a valid email address  </span>");
    
    // Hide all span messages initially
    $("span").hide();
    
    // Get all input fields
    $( "input" )
    
        // When user enters input field, get id of following span element
        // Make that span visible with initial message and 'info' class
        .focusin(function() {
            const spanID = $(this).next().attr('id');
            const inputID = $(this).attr('id');

            $('#' + spanID).removeClass();
            $('#' + spanID).addClass("info");
            $('#' + spanID).show();
            
            if( inputID === 'username' ) {
                $('#' + spanID).text("Username must contain only alphabetical or numeric characters");
            }
            if( inputID === 'password' ) {
                $('#' + spanID).text("Password field should be at least 8 characters long");
            }
            if( inputID === 'email' ) {
                $('#' + spanID).text("Email should be a valid email address");
            }
        })
        
        // When user leaves input field, check for input entry
        // If data input, submit for validation
        .focusout(function() {
            const spanID = $(this).next().attr('id');
            const inputID = $(this).attr('id');

            // If no entry in input field, hide span, and remove CSS classes ['ok' or 'error']
            if( !$(this).val() ){
                $('#' + spanID).hide();
                $('#' + spanID).removeClass();
            } 
            // Else submit input for validation based on id of input field
            // and apply respective CSS class and message
            else{
                if( inputID === 'username' ) {
                    setCSS(spanID, validateUsername());
                }
                else if( inputID === 'password' ) {
                    setCSS(spanID, validatePassword());
                }
                else if( inputID === 'email' ) {
                    setCSS(spanID, validateEmail());
                }
            }
        });
});

// Validate Username
function validateUsername(){
    // Regex checks for only Alphanumeric input
    const usernameRegex = /^[a-zA-Z0-9]+$/i;
    var username = $('#username').val();
    // Test input against regex
    return usernameRegex.test(username);
}

// Validate Password
function validatePassword(){
    var password = $('#password').val();
    // Password must be at least 8 characters long
    return (password.length >= 8);
}

// Validate Email address
function validateEmail(){
    // Regex checks for proper email format [user@example.com]
    const emailRegex = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
    var email = $('#email').val();
    // Test input against regex
    return(emailRegex.test(email));
}

// Apply appropriate CSS class and text for span
function setCSS(spanID, passedValidation){
    // If Valid, set span text to "ok", 
    // remove 'info' or 'error' css classes and set 'ok' css class
    if(passedValidation){
        $('#' + spanID).text("OK");
        $('#' + spanID).removeClass();
        $('#' + spanID).addClass("ok");
    } 
    // If Invalid, set span text to "error", 
    // remove 'info' or 'ok' css classes and set 'error' css class
    else {
        $('#' + spanID).text("Error");
        $('#' + spanID).removeClass();
        $('#' + spanID).addClass("error");
    }
}