<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="/admin/css/style.css">
    <title>Connexion Administrateur</title>
</head>
<body>
    <div class="form__connect d-flex justify-content-center">
        <form action="process_login.php" method="post">
            <h1 class="h1 mb-3">Connexion</h1>

            <div class="">
                <input type="text" class="form-control" name="username" placeholder="Nom d'utilisateur" required>
                <label></label>
            </div>
            <div class="">
                <input type="password" class="form-control" name="password" placeholder="Mot de passe">
                <label></label>
            </div>
            <button class="btn w-100 py-2" type="submit" name="login">Connexion</button>
            <p class="mt-5 mb-3 text-body-secondary">AzureWeb© 2024</p>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>