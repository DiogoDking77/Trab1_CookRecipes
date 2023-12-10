<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LetHimCook - Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IM+Fell+English&family=Pixelify+Sans&family=Raleway:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/register.css">
</head>
<body>
        
<div class="container">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-9 col-lg-7 col-xl-6">
            <div class="card" style="border-radius: 15px; background: rgba(59, 59, 59, 0.95); border: 3px solid rgba(180, 124, 20, 1);">
                <div class="card-body p-5">
                
                    <h2 class="text-uppercase text-center mb-2 text-white">
                    <div class="d-flex justify-content-center">
                        <img src="./../Images/Logo.png" alt="logo" class="w-50 h-50" >
                    </div>
                        Login
                    </h2>

                    <?php
                    include('config.php');

                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $email = $_POST["form3Example3cg"];
                        $password = $_POST["form3Example4cg"];
                    
                        $sql = "SELECT * FROM users WHERE User_Email='$email' AND User_Password='$password'";
                        $result = $conn->query($sql);
                    
                        if ($result->num_rows > 0) {
                            // Usuário autenticado com sucesso
                            header("Location: dashboard.php"); // Redirecionar para a página de dashboard ou outra página após o login
                            exit();
                        } else {
                            echo "Credenciais inválidas. Por favor, tente novamente.";
                        }
                    }
                    
                    $conn->close();
                    ?>

                        <form id="signupForm" method="post">

                        <div class="form-outline mb-2">
                            <label class="form-label text-white" for="form3Example3cg">Your Email</label>
                            <input type="email" id="form3Example3cg" name="form3Example3cg" class="form-control form-control-lg custom-shadow" />
                        </div>

                        <div class="form-outline mb-2">
                            <label class="form-label text-white" for="form3Example4cg">Password</label>
                            <input type="password" id="form3Example4cg" name="form3Example4cg" class="form-control form-control-lg custom-shadow" />
                        </div>

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-block btn-lg gradient-custom-4 text-body">Enter</button>
                        </div>

                        <p class="text-center mt-2 mb-0 text-white">Don't have an account? <a href="register.php" class="fw-bold text-white"><u>Register here</u></a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function validateForm() {
        var email = document.getElementById('form3Example3cg').value;
        var password = document.getElementById('form3Example4cg').value;

        if (email === '' || password === '') {
            alert('Por favor, preencha todos os campos.');
            return false;
        }

        return true;
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

</body>
</html>
