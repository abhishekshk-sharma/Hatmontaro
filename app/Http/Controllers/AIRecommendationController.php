<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\AIRecommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIRecommendationController extends Controller
{
    public function getAdvancedRecommendations(Request $request)
    {
        $userQuery = $request->input('query');
        $userPreferences = auth()->check() ? auth()->user()->preferences : null;
        
        // Integrate with OpenAI API
        $recommendations = $this->callOpenAI($userQuery, $userPreferences);
        
        return response()->json([
            'products' => $recommendations,
            'style_tips' => $this->generateStyleTips($userQuery),
            'outfit_suggestions' => $this->generateOutfitSuggestions($recommendations)
        ]);
    }
    
    private function callOpenAI($query, $preferences)
    {
        // Example using OpenAI API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "You are a fashion AI assistant. Analyze the user's query and preferences to recommend clothing items."
                ],
                [
                    'role' => 'user',
                    'content' => "Query: {$query}\nPreferences: " . json_encode($preferences)
                ]
            ]
        ]);
        
        // Process response and return product recommendations
        return $this->processAIResponse($response->json());
    }
    
    private function generateStyleTips($query)
    {
        // Generate AI-powered style tips
        return [
            "Based on your query about '{$query}', here are some style tips:",
            "1. Consider layering for versatility",
            "2. Choose colors that complement your skin tone",
            "3. Accessorize to complete the look"
        ];
    }
}