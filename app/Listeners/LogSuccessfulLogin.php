<?php

namespace App\Listeners;

use App\Models\IpLoginLog;
use App\Support\ActivityLogger;
use App\Support\UserAgentParser;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;

class LogSuccessfulLogin
{
    public function __construct(private Request $request) {}

    public function handle(Login $event): void
    {
        $ua = $this->request->userAgent() ?? '';

        IpLoginLog::create([
            'user_id'    => $event->user->id,
            'ip_address' => $this->request->ip(),
            'user_agent' => mb_substr($ua, 0, 255),
            'session_id' => $this->request->session()->getId(),
            'browser'    => UserAgentParser::browser($ua),
            'device'     => UserAgentParser::device($ua),
            'platform'   => UserAgentParser::platform($ua),
        ]);

        ActivityLogger::log('user.login', $event->user, [
            'ip'      => $this->request->ip(),
            'browser' => UserAgentParser::browser($ua),
            'device'  => UserAgentParser::device($ua),
        ]);
    }
}
