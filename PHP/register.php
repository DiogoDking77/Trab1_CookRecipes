<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LetHimCook - Register</title>
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
                    <h2 class="text-uppercase text-center mb-2 text-white">Create an account</h2>

                    <?php
                    include('config.php');

                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $name = $_POST["form3Example1cg"];
                        $email = $_POST["form3Example3cg"];
                        $password = $_POST["form3Example4cg"];

                        $sql = "INSERT INTO users (User_Name, User_Email, User_Password) VALUES ('$name', '$email', '$password')";

                        if ($conn->query($sql) === TRUE) {
                            log ("Registro inserido com sucesso!");
                        } else {
                            error_log ("Erro ao inserir registro: " . $conn->error);
                        }
                    }

                    $conn->close();
                    ?>

                        <form id="signupForm" method="post" onsubmit="return validateForm();">

                        <div class="form-outline mb-2">
                            <label class="form-label text-white" for="form3Example1cg">Your Name</label>
                            <input type="text" id="form3Example1cg" name="form3Example1cg" class="form-control form-control-lg custom-shadow"/>
                        </div>

                        <div class="form-outline mb-2">
                            <label class="form-label text-white" for="form3Example3cg">Your Email</label>
                            <input type="email" id="form3Example3cg" name="form3Example3cg" class="form-control form-control-lg custom-shadow" />
                        </div>

                        <div class="form-outline mb-2">
                            <label class="form-label text-white" for="form3Example4cg">Password</label>
                            <input type="password" id="form3Example4cg" name="form3Example4cg" class="form-control form-control-lg custom-shadow" />
                        </div>

                        <div class="form-outline mb-2">
                            <label class="form-label text-white" for="form3Example4cdg">Repeat your password</label>
                            <input type="password" id="form3Example4cdg" name="form3Example4cdg" class="form-control form-control-lg custom-shadow" />
                        </div>

                        <div class="form-check d-flex justify-content-center mb-2">
                            <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3cg" />
                            <label class="form-check-label text-white" for="form2Example3g">
                                I agree all statements in <a href="#!" class="text-white"><u>Terms of service</u></a>
                            </label>
                        </div>

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-block btn-lg gradient-custom-4 text-body">Register</button>
                        </div>

                        <p class="text-center mt-2 mb-0 text-white">Have already an account? <a href="login.php" class="fw-bold text-white"><u>Login here</u></a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function validateForm() {
        var name = document.getElementById('form3Example1cg').value;
        var email = document.getElementById('form3Example3cg').value;
        var password = document.getElementById('form3Example4cg').value;
        var repeatPassword = document.getElementById('form3Example4cdg').value;

        if (name === '' || email === '' || password === '' || repeatPassword === '') {
            alert('Por favor, preencha todos os campos.');
            return false;
        }

        if (password !== repeatPassword) {
            alert('As senhas não coincidem.');
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
