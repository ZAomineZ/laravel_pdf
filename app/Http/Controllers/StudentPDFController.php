<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;

final class StudentPDFController extends Controller
{
    public function index(): Factory|View|Application
    {
        $studentData = $this->getStudentData();
        return view('list_students_pdf', compact('studentData'));
    }

    public function pdf()
    {
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($this->convertStudentDataToHtml());
        $pdf->getDomPDF()->set_option('enable_php', true);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream();
    }

    protected function convertStudentDataToHtml(): string
    {
        $studentData = $this->getStudentData();

        $output = '
            <h3 align="center">List of students</h3>
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
        foreach ($studentData as $student) {
            $output .= '
                 <tr>
                    <td style="border: 1px solid; padding: 6px">'. $student->id .'</td>
                    <td style="border: 1px solid; padding: 6px">'. $student->firstname .'</td>
                    <td style="border: 1px solid; padding: 6px">'. $student->lastname .'</td>
                    <td style="border: 1px solid; padding: 6px">'. $student->name_util .'</td>
                    <td style="border: 1px solid; padding: 6px">'. $student->telephone .'</td>
                    <td style="border: 1px solid; padding: 6px">'. $student->birthday .'</td>
                    <td style="border: 1px solid; padding: 6px">'. $student->gender .'</td>
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

    protected function getStudentData()
    {
        return Student::get();
    }
}
