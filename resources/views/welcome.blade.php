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
<form action="/api/v1/" method="post" enctype="multipart/form-data">
    <input type="text" name="title" value="title "/>
    <input type="text" name="description" value="description "/>
    <input type="date" name="deadline" min="{{\Carbon\Carbon::now()}}" />
    <input type="file" name="file" />
    <input type="number" name="folder_id" value="2">
    <input type="submit" value="submit">
</form>
</body>
</html>
