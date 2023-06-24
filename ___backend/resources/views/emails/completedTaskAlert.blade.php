<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SFS</title>
    <style>
        html {
            line-height: 1.15;
            -webkit-text-size-adjust: 100%
        }

        body {
            margin: 20px
        }

        .info {
            margin-bottom: 20px;
        }
    </style>


</head>

<body>
    <section class="info">
        <div>TASK WAITING FOR APPROVAL</div>
    </section>

    <section class="info">
        TASK NAME: {{$task_name}}
    </section>
</body>

</html>
