<?php

namespace App\Controllers;

use App\Models\Appointment;
use App\Models\Lead;
use App\Core\SecureController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;
use Framework\Http\Responses\JsonResponse;

class CalendarController extends SecureController
{
    public function authorize(Request $request, string $action): bool
    {
        return $this->user->isLoggedIn();
    }

    public function index(Request $request): Response
    {
        return $this->html();
    }

    public function events(Request $request): Response
    {
        $userId = $this->user->getIdentity()->id;
        $appointments = Appointment::getAll("user_id = ?", [$userId]);

        $events = [];
        foreach ($appointments as $app) {
            $events[] = [
                'id' => 'app_' . $app->id,
                'title' => $app->title,
                'start' => $app->start_at,
                'end' => $app->end_at,
                'description' => $app->description,
                'color' => '#3b82f6',
                'allDay' => false
            ];
        }

        // Add Public Holidays (External API)
        $holidays = $this->getPublicHolidays();
        foreach ($holidays as $holiday) {
            $events[] = [
                'title' => '🎉 ' . $holiday['localName'],
                'start' => $holiday['date'],
                'allDay' => true,
                'color' => '#10b981',
                'display' => 'block'
            ];
        }

        return $this->json($events);
    }

    public function save(Request $request): Response
    {
        if (!$request->isPost()) {
            return $this->json(['error' => 'Method not allowed'], 405);
        }

        $app = new Appointment();
        $app->user_id = $this->user->getIdentity()->id;
        $app->lead_id = $request->value('lead_id') ?: null;
        $app->title = $request->value('title') ?: 'Follow-up';
        $app->start_at = $request->value('start_at');
        // If end_at is not provided, default to 30 mins after start
        $app->end_at = $request->value('end_at') ?: date('Y-m-d H:i:s', strtotime($app->start_at . ' +30 minutes'));
        $app->description = $request->value('description');

        if (empty($app->start_at)) {
            return $this->json(['error' => 'Start date is required'], 400);
        }

        $app->save();

        return $this->json(['success' => true, 'id' => $app->id]);
    }

    private function getPublicHolidays(): array
    {
        $year = date('Y');
        $countryCode = 'SK';
        $apiUrl = "https://date.nager.at/api/v3/PublicHolidays/{$year}/{$countryCode}";

        try {
            // Simple file_get_contents for the demo, in real life we'd use cURL or Guzzle
            $ctx = stream_context_create([
                "http" => ["timeout" => 2] // fast timeout to not block UI
            ]);
            $response = @file_get_contents($apiUrl, false, $ctx);
            if ($response === false) return [];
            
            return json_decode($response, true) ?: [];
        } catch (\Exception $e) {
            return [];
        }
    }
}
