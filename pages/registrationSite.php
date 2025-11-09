      <h1>Registrace</h1>
        <form id="registerForm" class="row g-3" novalidate>
            <div class="col-md-6">
                <label for="username" class="form-label">Uživatelské jméno</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <div class="col-md-6">
                <label for="fullname" class="form-label">Celé jméno</label>
                <input type="text" class="form-control" id="fullname" name="fullname" required>
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="col-md-6">
                <label for="password" class="form-label">Heslo</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="col-md-6">
                <label for="confirmPassword" class="form-label">Zopakujte heslo</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                <div id="passwordHelp" class="form-text"></div>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-success">Registrovat se</button>
            </div>
        </form>

        <script>
const minPasswdLen = 5; // minimální délka hesla

const password = document.getElementById('password');
const confirm = document.getElementById('confirmPassword');
const message = document.getElementById('passwordHelp');
const form = document.getElementById('registerForm');

function checkPasswords() {
    let errors = [];

    // kontrola délky
    if (password.value.length < minPasswdLen || confirm.value.length < minPasswdLen) {
        errors.push(`❗ Minimální délka hesla je ${minPasswdLen} znaků`);
    }

    // kontrola shody
    if (password.value !== confirm.value) {
        errors.push("❌ Hesla se neshodují");
    }

    // nastavení zprávy
    if (errors.length === 0) {
        message.textContent = "✅ Hesla se shodují";
        message.classList.remove('text-danger');
        message.classList.add('text-success');
    } else {
        message.innerHTML = errors.join("<br>");
        message.classList.remove('text-success');
        message.classList.add('text-danger');
    }
}

// reaguje při psaní
password.addEventListener('input', checkPasswords);
confirm.addEventListener('input', checkPasswords);

// kontrola při odeslání
form.addEventListener('submit', function(e) {
    if (password.value.length < minPasswdLen || password.value !== confirm.value) {
        e.preventDefault();
        confirm.focus();
    }
});
</script>


