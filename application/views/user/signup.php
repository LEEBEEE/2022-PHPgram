<!DOCTYPE html>
<html lang="en">
<?php include_once "application/views/template/head.php"; ?>
<body class="h-full container-center">
    <div>
        <h1>회원가입</h1>

        <form action="signup" method="post">
            <div><input type="email" name="email" placeholder="email" autofocus required></div>
            <div><input type="password" name="pw" placeholder="password" required></div>
            <div><input type="text" name="nm" placeholder="name" required></div>
            <div>
                <input type="submit" value="sign up">
            </div>
        </form>
    </div>
</body>
</html>