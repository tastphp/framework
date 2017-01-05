<?php

namespace TastPHP\Framework\SwiftMailer;

use TastPHP\Framework\Service\ServiceProvider;

class SwiftMailerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $host = $this->app['swift.mail.host'];
        $port = $this->app['swift.mail.port'];
        $username = $this->app['swift.mail.username'];
        $password = $this->app['swift.mail.password'];

        $this->app->singleton('swiftMailer', function () use ($host, $port, $username, $password) {
            $transport = \Swift_SmtpTransport::newInstance($host, $port)
                ->setUsername($username)
                ->setPassword($password);
            return \Swift_Mailer::newInstance($transport);
        });

        $this->app->singleton('swiftMessage', function () {
            return \Swift_Message::newInstance();
        });

    }
}