<!DOCTYPE HTML>

<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>found</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="/css/app.css">
    </head>
    <body>
        <h1>FOUND</h1>
          <div>
               <h2 class='name'>{{ $product->name }}</h2>
               <h4 class='photo'>{{ $product->photo}}</h4>
               <p class='body'>{{ $product->body }}</p>
               <p class='category'>{{ $product->category->name }}</p>
               <p class="edit">[<a href="/products/{{ $product->id }}/edit">edit</a>]</p>
          </div>
      <div class='back'>[<a href='/top'>back</a>]</div>
    </body>
</html>