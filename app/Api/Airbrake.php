<?php


namespace App\Api;

class Airbrake
{
    private $isActive = false;

    public function init()
    {
        if (env('AIRBRAKE_API_KEY') && env('AIRBRAKE_PROJECT_ID')) {
            $this->registerAirbrake(env('AIRBRAKE_PROJECT_ID'), env('AIRBRAKE_API_KEY'));
            \Log::info('Airbrake registered');
        }
    }

    public function notify($e)
    {
        if (!$this->isActive) {
            return;
        }
        \Airbrake\Instance::notify($e);
    }

    private function registerAirbrake($projectId, $projectKey)
    {
        // Create new Notifier instance.
        $notifier = new \Airbrake\Notifier(array(
            'projectId' => $projectId,
            'projectKey' => $projectKey,
        ));

        \Airbrake\Instance::set($notifier);

        $handler = new \Airbrake\ErrorHandler($notifier);
        $handler->register();

        $this->isActive = true;
    }

}