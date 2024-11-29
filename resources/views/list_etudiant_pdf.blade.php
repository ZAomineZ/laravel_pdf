<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <style type="text/css">
        .box {
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<br/>
<div class="container">
    <h3 align="center">Generate a PDF file with laravel DomPDF</h3>
    <br/>
    <div class="row">
        <div class="col-md-7" align="right">
            <h4>Etudiant Data</h4>
        </div>
        <div class="col-md-5" align="right">
            <a href="{{ url('list_etudiant_pdf/pdf') }}">Download PDF</a>
        </div>
    </div>
    <br/>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Email</th>
                <th>User</th>
                <th>Telephone</th>
                <th>Birthday</th>
                <th>Gender</th>
            </tr>
            </thead>
            <tbody>
                @foreach($etudiantData as $etudiant)
                    <tr>
                        <td>{{ $etudiant->id }}</td>
                        <td>{{ $etudiant->firstname }}</td>
                        <td>{{ $etudiant->lastname }}</td>
                        <td>{{ $etudiant->email }}</td>
                        <td>{{ $etudiant->name_util }}</td>
                        <td>{{ $etudiant->telephone }}</td>
                        <td>{{ $etudiant->birthday }}</td>
                        <td>{{ $etudiant->gender }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
