<?php

namespace App\Http\Controllers;

use App\Services\GeminiDashboardService;
use Illuminate\Http\Request;

class AiDashboardController extends Controller
{
    public function __construct(protected GeminiDashboardService $gemini) {}

    /**
     * Halaman AI Dashboard Builder.
     */
    public function index()
    {
        return view('ai.dashboard');
    }

    /**
     * Generate dashboard config dari prompt.
     * POST /ai/dashboard/generate
     */
    public function generate(Request $request)
    {
        $request->validate([
            'prompt' => ['required', 'string', 'min:5', 'max:500'],
        ]);

        try {
            $config = $this->gemini->generate($request->input('prompt'));
            return response()->json(['success' => true, 'dashboard' => $config]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
