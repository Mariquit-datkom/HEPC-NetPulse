document.getElementById('regAccBtn').addEventListener('click', function() {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    
    if (!username || !password) {
        alert("Please fill all fields with necessary input first.");
        return;
    }

    if (confirm("Are you sure you want to register the account for " + username + "?")) {
        // Create a hidden input to tell PHP we want to register
        const form = this.closest('form');
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'action';
        hiddenInput.value = 'register';
        
        form.appendChild(hiddenInput);
        form.submit();
    }
});