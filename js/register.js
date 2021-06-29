let username = document.getElementById("username");
let email = document.getElementById("email");
let password = document.getElementById("password");
let cpassword = document.getElementById("cpassword");
let message = document.getElementById("message");

function validateRegistrationForm() {
  if (
    username.value == "" ||
    email.value == "" ||
    password.value.trim() == "" ||
    cpassword.value.trim() == ""
  ) {
    message.innerText = "All fields required !";
    return false;
  } else {
    if (/\s/.test(username.value)) {
      message.innerText = "Invalid username !";
      return false;
    } else {
      if (/\s/.test(email.value)) {
        message.innerText = "Invalid email !";
        return false;
      } else {
        if (password.value != cpassword.value) {
          message.innerText = "Both passowrd are not same !";
          return false;
        } else {
            message.innerText = "";
          return true;
        }
      }
    }
  }
}
