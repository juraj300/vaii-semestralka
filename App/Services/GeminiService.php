<?php

namespace App\Services;

use App\Configuration;
use Exception;

class GeminiService
{
    private string $apiKey;
    private string $baseUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent";

    public function __construct()
    {
        // We will look for GEMINI_API_KEY in environment or Configuration
        $this->apiKey = getenv('GEMINI_API_KEY') ?: (defined('App\Configuration::GEMINI_API_KEY') ? Configuration::GEMINI_API_KEY : '');
    }

    /**
     * Generate talking points based on lead data and script.
     * 
     * @param array $leadData [company, website, background_info]
     * @param string $scriptBody The current script text
     * @return string Generated talking points (Markdown)
     */
    public function generateTalkingPoints(array $leadData, string $scriptBody): string
    {
        if (empty($this->apiKey)) {
            throw new Exception("Gemini API Key is not configured. Please add GEMINI_API_KEY to your .env or Configuration.php");
        }

        $prompt = $this->buildPrompt($leadData, $scriptBody);

        $payload = [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $prompt]
                    ]
                ]
            ],
            "tools" => [
                ["google_search" => (object)[]]
            ]
        ];

        $ch = curl_init($this->baseUrl . "?key=" . $this->apiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            $error = json_decode($response, true);
            throw new Exception("Gemini API Error: " . ($error['error']['message'] ?? 'Unknown error'));
        }

        $result = json_decode($response, true);
        $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;
        
        if (!$text) {
            return "Nepodarilo sa vygenerovať body (AI). Skúste to prosím neskôr.";
        }
        
        return $text;
    }

    private function buildPrompt(array $leadData, string $scriptBody): string
    {
        $company = $leadData['company'] ?? 'this company';
        $website = $leadData['website'] ?? '';
        $context = $leadData['background_info'] ?? 'No additional context provided.';
        
        $prompt = "You are a professional sales assistant. I am about to call a lead from '{$company}'.\n";
        
        if (!empty($website)) {
            $prompt .= "Their website is: {$website}. Please use your search tool to find their recent news, what they do, and any specific pain points they might have.\n";
        }
        
        $prompt .= "Additional Context: {$context}\n\n";
        $prompt .= "Our Sales Script/Offer is:\n\"{$scriptBody}\"\n\n";
        $prompt .= "INSTRUCTIONS:\n";
        $prompt .= "1. Respond EXCLUSIVELY in Slovak (Slovenčina).\n";
        $prompt .= "2. Analyze the 'Sales Script/Offer' above to understand exactly WHAT we are selling. Do not assume we are selling CallAssistant unless the script says so.\n";
        $prompt .= "3. Give me 3 specific 'Talking Points' (v slovenčine) based on your research of the company and our specific product.\n";
        $prompt .= "4. Give me 1 'Pro Tip' (v slovenčine) on how to pitch OUR specific offer to this specific company.\n";
        $prompt .= "5. Keep the response short (max 4-5 sentences total).\n";
        $prompt .= "6. Use bullet points and a professional tone.";

        return $prompt;
    }
}
