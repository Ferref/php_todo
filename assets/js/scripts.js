document.getElementById('register-form').onsubmit = function() {
    console.log('Form action: ' + this.action);
    console.log('Form method: ' + this.method);
};
