# Release Notes

## v1.0.0 (2017-02-21)

## v1.1.0 (2017-06-07)
### Added 
* Added ExceptionHandlerService Replace ExceptionHandler ([#6](https://github.com/tastphp/framework/pull/6))

### Fixed
* Refactor code for Console ([#7](https://github.com/tastphp/framework/pull/7))
* Fixed codeclimate issues ([#8](https://github.com/tastphp/framework/pull/8))([#9](https://github.com/tastphp/framework/pull/9))

### Removed
* ExceptionHandler ([#10](https://github.com/tastphp/framework/pull/10))

## v1.2.0 (2017-07-12)
* Added implement PSR-11: Container Interface.
* Fixed phpunit config

## v1.3.0 (2017-07-12)
* Added implement PSR-7: HTTP Message Interfaces

## v1.3.2 (2017-07-17)
* Removed RequestListener
* Modify ListenerRegisterService Logic

## v1.4.0 (2017-07-18)
* Add RequestServiceProvider and Compatible PSR-7

## v1.4.1 (2017-07-19)
* Add ResponseAdapter and Compatible PSR-7

## v1.5.0 (2017-07-19)
* Refactor Http component
* Added HttpAdapter

## v1.6.0 (2017-07-28)
* not support php56
* Fixed when config file not exits

## v1.6.1 (2017-10-12)
* Fixed console command bug when integrate to Tastphp

## v1.7.0 (2017-10-16)
* refactor kernel && optimize config service, enhance performance 10X!
* Fixed Router merge route array bug when route empty
* Fixed YamlService parse empty string warning
* clean code for kernel Listener && remove business logic Listeners (src/Framework/Listener/MailListener.php、src/Framework/Listener/MiddlewareListener.php)

## v1.7.1 (2017-10-17)
* add register kernel listeners&&ServiceProvider wrapper

## v1.7.2 (2017-10-20)
* optimize ConfigService
* add cache command for route&config

## v1.7.3 (2017-10-20)
* Merge pull request #15 from akiyamaSM/patch-2
* Fixed when no app.yml file error
* move register debugbar code to WhoopsExceptionsHandler