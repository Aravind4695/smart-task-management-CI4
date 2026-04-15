<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>
<body class="container mt-5">

<h2>Login</h2>

<input type="email" id="email" class="form-control mb-2" placeholder="Email">
<input type="password" id="password" class="form-control mb-2" placeholder="Password">
<button onclick="login()" class="btn btn-primary">Login</button>

<p class="mt-3">
    Don't have an account? 
    <a href="/smartTaskManagement/public/register">Register</a>
</p>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<script>
function login() {
    $.ajax({
        url: "http://localhost/smartTaskManagement/public/login",
        type: "POST",
        data: {
            email: $('#email').val(),
            password: $('#password').val()
        },
        success: function(res) {
            if (res.error) {
                toastr.error(res.error);
                return;
            }

            toastr.success(res.message);

            setTimeout(() => {
                window.location.href = "/smartTaskManagement/public/dashboard";
            }, 1000);
        }
        
    });
}
</script>

</body>
</html>