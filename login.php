<?php

    require_once './classes/Session.php';

    $session = Session::getInstance();

    if($session->get('loggedIn')) header('Location: index.php');

    if(isset($_POST['Login'])) {
        require_once './classes/User.php';

        $username = $_POST['Login']['username'];
        $password = $_POST['Login']['password'];

        $db = Database::getInstance();

        /** @var User $user */
        $user = User::findOne(['username' => $username]);

        if($user) {
            if($user->verifyPassword($password)) {
                $user->login();
            }
        }

        $error = 'Invalid credentials';
    }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!--  Bootstrap 5 CSS  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!--  Bootstrap 5 JS  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="main.css">

    <title>T-COM | Log in</title>
</head>
<body>
    <section class="vh-100">
        <div class="container py-5 h-100">
            <div class="row d-flex align-items-center justify-content-center h-100">
                <div class="col-md-8 col-lg-7 col-xl-6">
                    <img src="./images/telekom.png"
                         class="img-fluid" alt="Phone image">
                </div>
                <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                    <form method="POST">
                        <div class="form-outline mb-4">
                            <input type="text" id="username" class="form-control form-control-lg" name="Login[username]"/>
                            <label class="form-label" for="username">Username</label>
                        </div>

                        <div class="form-outline mb-4">
                            <input type="password" id="password" class="form-control form-control-lg" name="Login[password]" />
                            <label class="form-label" for="password">Password</label>
                        </div>

                        <?php if(isset($error)): ?>
                            <div class="text-danger mb-5">
                                <?= $error ?>
                            </div>
                        <?php endif; ?>

                        <button type="submit" class="btn btn-primary btn-lg btn-block">Sign in</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>