let size = 18;
let charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&_~|}{[]?-=";
let generate = (length = size) => Array.from({ length }, () => charset[Math.floor(Math.random() * charset.length)]).join('');
let inputPasswords = ['new_passwd', 'confirm_passwd'];
let createNewPass = generate(16)
export function generatePassword() {
   inputPasswords.map( inp => $(`#${inp}`).attr({ type: 'text' }).val(createNewPass));
}