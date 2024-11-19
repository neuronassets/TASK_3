<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albero delle categorie</title>
</head>
<body>
    <h1>Albero delle categorie</h1>
    <ul>
        @foreach ($nodiPrimoLivello as $nodo)
            @include('categorie.nodo', ['nodo' => $nodo])
        @endforeach
    </ul>
</body>
</html>
