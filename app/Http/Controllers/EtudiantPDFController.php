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
