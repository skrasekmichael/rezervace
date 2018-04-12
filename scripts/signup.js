function check_year()
{
    let year = $("#signup input[name='year']")[0];
    if (year.value != new Date().getFullYear())
    {
        year.focus();
        year.value = "";
        year.setCustomValidity("Nesprávně zadaný rok. ");
        return;
    } else {  year.setCustomValidity(""); }
}

function checking_email(email)
{
    if (!check_email(email.value))
    {
        email.setCustomValidity("Email nemá správný formát. ");
    } else {  email.setCustomValidity(""); }
}

function checking_password(password)
{
    if (password.value.length < 4)
    {
        password.setCustomValidity("Heslo musí obsahovat alespoň 4 znaky. ");
        $("#signup input[name='password_check']")[0].setCustomValidity("Heslo se neshoduje. ");
    } else { password.setCustomValidity(""); }
}

function checking_password_check(password_check)
{
    let password = $("#signup input[name='password']")[0];
    if (password.value != password_check.value)
    {
        password_check.setCustomValidity("Heslo se neshoduje.");
    } else {  password_check.setCustomValidity(""); }
}