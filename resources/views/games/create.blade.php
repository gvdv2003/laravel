<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<!-- resources/views/posts/create.blade.php -->
<form action="{{ route('games.store') }}" method="POST">


    <div>
        <label for="name">naam</label>
        <input type="text" id="name" name="name" required>
    </div>

    <div>
        <label for="description">description</label>
        <textarea type="text" id="description" name="description" required></textarea>
    </div>

    <div>
        <label for="year">year</label>
        <input type="number" id="year" name="year" required>
    </div>

    <button type="submit">Submit</button>
</form>




</body>
</html>
