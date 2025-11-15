<!-- <!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<title>Login Page</title>
</head>
<body>
	<div class="container">
		<form>
			<label for="username">Username:</label>
			<input type="text" name="uname" placeholder="Enter your username">
			<label for="password">Password</label>
			<input type="password" name="psw" placeholder="Enter your password">
			<button class="btn btn-success">Submit</button>
		</form>
	</div>
</body>
</html> -->


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Login Page</title>
    <style>
        .container {
            max-width: 400px;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4" style="background-color: #fffbfb;"><b>Login</b></h2>
        <form id="loginForm" novalidate>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="uname" placeholder="Enter your username" required autocomplete="username">
                <div class="invalid-feedback">
                    Please enter your username.
                </div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="psw" placeholder="Enter your password" required autocomplete="current-password">
                <div class="invalid-feedback">
                    Please enter your password.
                </div>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </form>
    </div>

    <script>
    
        (function () {
            'use strict';

            var forms = document.querySelectorAll('.needs-validation');

            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }

                        form.classList.add('was-validated');
                    }, false);
                });
        })();

        document.getElementById('loginForm').addEventListener('submit', function (event) {
            event.preventDefault();

            var form = event.target;
            if (form.checkValidity()) {
                // Process form data here
                confirm('Are you sure you want to login');
            } else {
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Jd+b1d+lP2QQ1Tc3CXFP8XdKvcM6bZ6S7JAEdfYw7KJjFQ" crossorigin="anonymous"></script>
</body>
</html>
