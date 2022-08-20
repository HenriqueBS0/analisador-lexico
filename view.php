<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Análisador Léxico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
</head>

<body class="bg-body">

    <h1 class="text-center">Analisador Léxico</h1>

    <div class="container text-center">
        <div class="row g-2 row-cols-1">
            <div class="col">
                <div class="d-flex flex-column align-items-start">
                    <label for="entrada" class="form-label">Entrada</label>
                    <textarea class="form-control" id="entrada" rows="15"></textarea>
                </div>
            </div>
            <div class="col">
                <div class="alert alert-warning" role="alert">
                    A simple warning alert—check it out!
                </div>
            </div>
            <div class="col">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Token</th>
                            <th>Lexema</th>
                            <th>Linha</th>
                            <th>Posição Inicial</th>
                            <th>Posição Final</th>
                        </tr>
                    </thead>
                    <tbody id='tbody-tokens'>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script type="module" src="main.js"></script>
</body>

</html>