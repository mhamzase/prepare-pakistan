let email = document.getElementById("email");
let password = document.getElementById("password");
let message = document.getElementById("message");

function validateLoginForm() {
  if (email.value == "" || password.value.trim() == "") {
    message.innerText = "All fields required !";
    return false;
  } else {
    if (/\s/.test(email.value)) {
      message.innerText = "Invalid email !";
      return false;
    } else {
      message.innerText = "";
      return true;
    }
  }
}
