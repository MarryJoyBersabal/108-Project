
// Button should be disabled if "Terms and Conditions" is not checked.

document.getElementById('checkcon').addEventListener('change', function() {
    var submitButton = document.getElementById('submitform');
    submitButton.disabled = !this.checked;
});

//8 characters long
document.getElementById('password').addEventListener('input', function() {

var origPass = document.getElementById('password').value;
var checkPass = document.getElementById('checkpass');
var messageElement = document.getElementById('passmessage');


if (origPass.length < 8 && origPass.length > 0) {
this.style.border = '1px solid red';
messageElement.innerHTML = 'Password must be 8 characters or more';
checkPass.value = '';
checkPass.disabled = true;
} else {
this.style.border = '1px solid green';
messageElement.innerHTML = '';
checkPass.disabled = false;
}
});


//password do not match
document.getElementById('checkpass').addEventListener('input', function() {

var origPass = document.getElementById('password').value;
var checkPass = this.value;
var messageElement = document.getElementById('passmessage');


if (origPass !== checkPass) {
this.style.border = '1px solid red';
messageElement.innerHTML = 'Password does not match';
messageElement.style.color = 'red';

} else {
this.style.border = '1px solid green';
messageElement.innerHTML = '';
messageElement.style.color = 'green';
}
});