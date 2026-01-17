<?php

namespace App\Controllers;

use App\Core\SecureController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

/**
 * Class AdminController
 *
 * This controller manages admin-related actions within the application.It extends the base controller functionality
 * provided by BaseController.
 *
 * @package App\Controllers
 */
class AdminController extends SecureController
{
    /**
     * Authorizes actions in this controller.
     *
     * This method checks if the user is logged in, allowing or denying access to specific actions based
     * on the authentication state.
     *
     * @param string $action The name of the action to authorize.
     * @return bool Returns true if the user is logged in; false otherwise.
     */
    public function authorize(Request $request, string $action): bool
    {
        return $this->user->isLoggedIn();
    }

    /**
     * Displays the index page of the admin panel.
     *
     * This action requires authorization. It returns an HTML response for the admin dashboard or main page.
     *
     * @return Response Returns a response object containing the rendered HTML.
     */
    public function index(Request $request): Response
    {
        // Simple Dashboard Statistics
        $totalLeads = \App\Models\Lead::getCount();
        $totalCalls = \App\Models\Call::getCount();
        
        // Calculate conversion rate (Closed Won / Total Leads)
        $wonLeads = \App\Models\Lead::getCount("status = 'closed_won'");
        $conversionRate = $totalLeads > 0 ? round(($wonLeads / $totalLeads) * 100, 1) : 0;
        
        // Leads by status
        $newLeads = \App\Models\Lead::getCount("status = 'new'");
        $interestedLeads = \App\Models\Lead::getCount("status = 'interested'");


        return $this->html(compact('totalLeads', 'totalCalls', 'conversionRate', 'newLeads', 'interestedLeads'));
    }
}
