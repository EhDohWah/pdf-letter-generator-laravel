<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class LetterController extends Controller
{
    public function generateLetter(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'employee_name' => 'required|string|max:255',
                'position_title' => 'required|string|max:255',
                'date' => 'required|date',
            ]);

            // Prepare HTML content for the PDF
            $htmlContent = "
                <html>
                    <body>
                        <p>Date: {$validated['date']}</p>
                        <p>To: {$validated['employee_name']}</p>
                        <p>Position: {$validated['position_title']}</p>
                        <p>Dear {$validated['employee_name']},</p>
                        <p>
                            We are pleased to offer you the position of <b>{$validated['position_title']}</b>.
                            Your start date will be <b>{$validated['date']}</b>.
                            We look forward to working with you!
                        </p>
                        <p>Best regards,</p>
                        <p>The HR Team</p>
                    </body>
                </html>
            ";

            // Generate PDF
            $pdf = Pdf::loadHTML($htmlContent);

            // Return PDF response with appropriate headers
            return response($pdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="appointment_letter.pdf"');

        } catch (\Exception $e) {
            // Handle errors and return JSON response
            return response()->json([
                'message' => 'Error generating the letter.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
