
<html>
<head>
    <title>Login Page</title>
    <style>
       body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #ffffff;
}

.container {
    text-align: center;
}

.logo {
    font-size: 48px;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 40px;
}

.input-field {
    display: block;
    width: 300px;
    padding: 15px;
    margin: 10px auto;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #f5f5f5;
    font-size: 16px;
}

.button {
    width: 330px;
    padding: 15px;
    margin: 20px auto;
    border: none;
    border-radius: 5px;
    background-color: #4a4aff;
    color: white;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.links {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 10px;
}

.link {
    color: #4a4aff;
    text-decoration: none;
    font-size: 14px;
}

/* Responsive styles */
@media (max-width: 768px) {
    .input-field {
        width: 80%;
        font-size: 14px;
    }

    .button {
        width: 80%;
        font-size: 14px;
    }

    .links {
        gap: 15px;
    }

    .link {
        font-size: 12px;
    }
}

@media (max-width: 480px) {
    .logo {
        font-size: 36px;
        margin-bottom: 20px;
    }

    .input-field {
        width: 90%;
        padding: 12px;
        font-size: 14px;
    }

    .button {
        width: 90%;
        font-size: 14px;
    }

    .links {
        flex-direction: column;
        gap: 10px;
    }

    .link {
        font-size: 12px;
    }
}

    </style>
</head>
<body>
    <div class="container">
        <header>
        <img src="/capstone-main/logo/logo.png" alt="Logo">
        </header>
        <form action="verify_login.php" method="post">
            <input type="text" name="email" class="input-field" placeholder="Email">
            <input type="password" name="password" class="input-field" placeholder="Password">
            <button type="submit" class="button">Login</button>
            <div class="links">
                <a href="#" class="link">Forgot Password</a>
            </div>
        </form>
    </div>
</body>
</html>


