<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="/static/js/jquery-3.2.1.min.js"></script>
    <title>Document</title>
</head>
<body>
    <button type="button" id="dj">点击</button>
</body>
</html>
<script>
$(function () {
    $("#dj").click(function () {
        $.ajax({
            method: "get",
            url: "{{url('index/asa')}}",
        }).done(function( msg ) {
            console.log(msg);
        });

    })
})    
</script>