<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>
<body class="container mt-5">

<h2>Register</h2>

<label>Name</label>
<input type="text" id="name" class="form-control mb-2" placeholder="Enter name">

<label>Email</label>
<input type="email" id="email" class="form-control mb-2" placeholder="Enter email">

<label>Password</label>
<input type="password" id="password" class="form-control mb-2" placeholder="Enter password">

<button onclick="register()" class="btn btn-success">Register</button>

<p class="mt-3">
    Already have an account? 
    <a href="/smartTaskManagement/public/login">Login here</a>
</p>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>



<script>
    function register() {
        let name = $('#name').val().trim();
        let email = $('#email').val().trim();
        let password = $('#password').val().trim();

        // Validation
        if (!name) {
            toastr.error("Name is required");
            $('#name').focus();
            return;
        }
        if (!email) {
            toastr.error("Email is required");
            $('#email').focus();
            return;
        }
        // Email format check
        let emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
        if (!email.match(emailPattern)) {
            toastr.error("Enter valid email");
            $('#email').focus();
            return;
        }
        if (!password) {
            toastr.error("Password is required");
            $('#password').focus();
            return;
        }
        if (password.length < 6) {
            toastr.error("Password must be at least 6 characters");
            $('#password').focus();
            return;
        }

        // API call
        $.ajax({
            url: "http://localhost/smartTaskManagement/public/register",
            type: "POST",
            data: {
                name: name,
                email: email,
                password: password
            },
            success: function(res) {
                if (res.error) {
                    toastr.error(res.error);
                    return;
                }

                toastr.success("Registered successfully");

                setTimeout(() => {
                    window.location.href = "/smartTaskManagement/public/login";
                }, 1000);
            },
            error: function() {
                toastr.error("Something went wrong");
            }
        });
    }
</script>

</body>
</html>