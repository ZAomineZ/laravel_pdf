# Generate a PDF File with Laravel DomPDF

## Introduction

This tutorial demonstrates how to use **Laravel** and the **DomPDF** package to generate PDF files, using a sample list of students. We will create a Laravel application capable of displaying and downloading a dynamically generated PDF containing student information.

---

## Prerequisites

- **PHP 8.x** or higher
- **Composer** installed
- **Laravel 10** or higher
- A configured database (e.g., MySQL)
- **DomPDF** installed via Composer

---

## Implementation Steps

### 1. Install the DomPDF Package

Run the following command to install DomPDF:

```bash
composer require barryvdh/laravel-dompdf
```

Add the service provider (optional if Laravel auto-discovers it):

```php
// config/app.php
'providers' => [
    Barryvdh\DomPDF\ServiceProvider::class,
```

Add an alias for convenience (optional):

```php
// config/app.php
'aliases' => [
    "PDF" => \Barryvdh\DomPDF\Facade\Pdf::class
];
```

---

### 2. Create the Model and Migrations for Students

Generate a model with a migration:

```php
php artisan make:model Etudiant -m
```

In the migration file database/migrations/<timestamp>_create_etudiants_table.php, define the columns:

```php
public function up()
{
    Schema::create('etudiants', function (Blueprint $table) {
        $table->id();
        $table->string('firstname');
        $table->string('lastname');
        $table->string('email')->unique();
        $table->string('name_util');
        $table->string('telephone');
        $table->date('birthday');
        $table->string('gender');
        $table->timestamps();
    });
}
```

Run the migration:

```bash
php artisan migrate
```

---

### 3. Create a Seeder for Sample Data

Add a seeder to generate sample students. Create a factory with:

```bash
php artisan make:factory EtudiantFactory --model=Etudiant
```

In `database/factories/EtudiantFactory.php`:

```php
use Faker\Generator as Faker;

public function definition()
{
    return [
        'firstname' => $this->faker->firstName(),
        'lastname' => $this->faker->lastName(),
        'email' => $this->faker->unique()->safeEmail(),
        'name_util' => $this->faker->userName(),
        'telephone' => $this->faker->phoneNumber(),
        'birthday' => $this->faker->date(),
        'gender' => $this->faker->randomElement(['Male', 'Female']),
    ];
}
```

Add the seeder logic in `DatabaseSeeder.php`:

```php
Etudiant::factory(100)->create();
```

Run the seeders:

```php
php artisan db:seed
```

---

### 4. Create the Controller to Display and Generate PDFs

Create a controller with:

```bash
php artisan make:controller EtudiantPDFController
```

Add methods to display data in a view and generate a PDF file in `EtudiantPDFController.php`:

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Etudiant;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;

final class EtudiantPDFController extends Controller
{
    public function index(): Factory|View|Application
    {
        $etudiantData = $this->getEtudiantData();
        return view('list_etudiant_pdf', compact('etudiantData'));
    }

    public function pdf()
    {
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($this->convertEtudiantDataToHtml());
        $pdf->getDomPDF()->set_option('enable_php', true);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream();
    }

    protected function convertEtudiantDataToHtml(): string
    {
        $etudiantData = $this->getEtudiantData();

        $output = '
            <h3 align="center">Liste des étudiants</h3>
            <table width="100%" style="border-collapse: collapse; border: 0px;">
                <tr>
                    <th style="border: 1px solid; padding: 6px; width: 10%">ID</th>
                    <th style="border: 1px solid; padding: 6px; width: 20%">Firstname</th>
                    <th style="border: 1px solid; padding: 6px; width: 20%">Lastname</th>
                    <th style="border: 1px solid; padding: 6px; width: 20%">User</th>
                    <th style="border: 1px solid; padding: 6px; width: 10%">Téléphone</th>
                    <th style="border: 1px solid; padding: 6px; width: 10%">Birthday</th>
                    <th style="border: 1px solid; padding: 6px; width: 10%">Gender</th>
                </tr>
        ';
        foreach ($etudiantData as $etudiant) {
            $output .= '
                 <tr>
                    <td style="border: 1px solid; padding: 6px">'. $etudiant->id .'</td>
                    <td style="border: 1px solid; padding: 6px">'. $etudiant->firstname .'</td>
                    <td style="border: 1px solid; padding: 6px">'. $etudiant->lastname .'</td>
                    <td style="border: 1px solid; padding: 6px">'. $etudiant->name_util .'</td>
                    <td style="border: 1px solid; padding: 6px">'. $etudiant->telephone .'</td>
                    <td style="border: 1px solid; padding: 6px">'. $etudiant->birthday .'</td>
                    <td style="border: 1px solid; padding: 6px">'. $etudiant->gender .'</td>
                </tr>
            ';
        }
        $output .= "</table>";
        $output .= '<script type="text/PHP">
            if (isset($pdf)) {
                $text = "Page : {PAGE_NUM}/{PAGE_COUNT}";
                $size = 10;
                $font = $fontMetrics->getFont("Verdana");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                $x = ($pdf->get_width() - $width) / 2;
                $y = $pdf->get_height() - 35;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script>';

        return $output;
    }

    protected function getEtudiantData()
    {
        return Etudiant::get();
    }
}
```

---

### 5. Create the View to Display the Data

In `resources/views/list_etudiant_pdf.blade.php`:

```bladehtml
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
```

---

### 6. Add Routes

In `routes/web.php`:

```php
Route::get('/list_etudiant_pdf', [EtudiantPDFController::class, 'index']);
Route::get('/list_etudiant_pdf/pdf', [EtudiantPDFController::class, 'pdf']);
```

---

### 7. Test the Application

- Visit `/list_etudiant_pdf `to view the student list.
- Click "Download PDF" to generate and display the PDF.
