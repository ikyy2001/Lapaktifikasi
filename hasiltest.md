PS C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web> php artisan test

   PASS  Tests\Unit\ExampleTest
  ✓ that true is true

   PASS  Tests\Unit\PaymentGatewayFactoryTest
  ✓ it creates midtrans gateway                            0.24s  
  ✓ it creates pakasir gateway                             0.02s  
  ✓ it creates tripay gateway                              0.04s  
  ✓ it defaults to midtrans gateway                        0.03s  

   PASS  Tests\Unit\TriPayGatewayTest
  ✓ signature generation                                   0.03s  
  ✓ get payment channels successful                        0.03s  
  ✓ create transaction valid closed payment                0.09s  
  ✓ create transaction handles api failure                 0.08s  
  ✓ verify status paid maps to success                     0.06s  
  ✓ verify status expired maps to expired                  0.03s  
  ✓ verify status failed maps to failed                    0.05s  

   FAIL  Tests\Feature\ExampleTest
  ⨯ the application returns a successful response          2.11s  
  ✓ health check endpoint                                  0.04s  

   FAIL  Tests\Feature\PaymentGatewaySettingTest
  ⨯ admin can update payment gateway settings              1.65s  
  ✓ admin cannot disable all payment gateways              1.32s  
  ⨯ checkout dropdown only renders active gateways         2.04s  
  ✓ generate transaksi rejects disabled gateway            1.12s  

   FAIL  Tests\Feature\PaymentStatusReturnTest
  ⨯ status page renders without undefined variable error   2.17s  
  ⨯ riwayat renders transaksi dibatalkan when order expir… 1.41s  

   PASS  Tests\Feature\ProductVariantSelectorTest
  ✓ livewire component mounts and selects first available… 2.34s  
  ✓ switching variant updates price stock and dispatches…  0.61s  
  ✓ multi attribute variant switching validates combinati… 0.57s  
  ✓ out of stock variant disables actions and shows badge  0.52s  
  ✓ quantity stepper increments and decrements within sto… 0.75s  

   PASS  Tests\Feature\TriPayCallbackTest
  ✓ callback rejected when signature missing               0.61s  
  ✓ callback rejected when signature invalid               0.67s  
  ✓ callback ignores non payment status event              0.40s  
  ✓ callback rejected when order not found                 0.58s  
  ✓ callback paid marks order as success and is idempoten… 1.09s  
  ✓ callback expired marks order as failed                 0.82s  
  ──────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\ExampleTest > the application returns…   
  Expected response status code [200] but received 500.
Failed asserting that 500 is identical to 200.

The following exception occurred during the last request:

ParseError: syntax error, unexpected end of file, expecting "elseif" or "else" or "endif" in C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\storage\framework\views\e7bd1a446b58df150bb30cda8d26c569.php:706
Stack trace:
#0 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Filesystem\Filesystem.php(128): Illuminate\Filesystem\Filesystem::{closure:Illuminate\Filesystem\Filesystem::getRequire():124}()
#1 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\View\Engines\PhpEngine.php(57): Illuminate\Filesystem\Filesystem->getRequire('C:\\Users\\iki\\Do...', Array)
#2 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\livewire\livewire\src\Mechanisms\ExtendBlade\ExtendedCompilerEngine.php(22): Illuminate\View\Engines\PhpEngine->evaluatePath('C:\\Users\\iki\\Do...', Array)
#3 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\View\Engines\CompilerEngine.php(76): Livewire\Mechanisms\ExtendBlade\ExtendedCompilerEngine->evaluatePath('C:\\Users\\iki\\Do...', Array)
#4 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\livewire\livewire\src\Mechanisms\ExtendBlade\ExtendedCompilerEngine.php(10): Illuminate\View\Engines\CompilerEngine->get('C:\\Users\\iki\\Do...', Array)
#5 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\View\View.php(208): Livewire\Mechanisms\ExtendBlade\ExtendedCompilerEngine->get('C:\\Users\\iki\\Do...', Array)
#6 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\View\View.php(191): Illuminate\View\View->getContents()
#7 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\View\View.php(160): Illuminate\View\View->renderContents()
#8 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Http\Response.php(82): Illuminate\View\View->render()
#9 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Http\Response.php(40): Illuminate\Http\Response->setContent(Object(Illuminate\View\View))
#10 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Routing\Router.php(954): Illuminate\Http\Response->__construct(Object(Illuminate\View\View), 200, Array)
#11 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Routing\Router.php(921): Illuminate\Routing\Router::toResponse(Object(Illuminate\Http\Request), Object(Illuminate\View\View))
#12 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Routing\Router.php(834): Illuminate\Routing\Router->prepareResponse(Object(Illuminate\Http\Request), Object(Illuminate\View\View))
#13 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(180): Illuminate\Routing\Router->{closure:Illuminate\Routing\Router::runRouteWithinStack():834}(Object(Illuminate\Http\Request))  
#14 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\app\Http\Middleware\CheckBannedUser.php(27): Illuminate\Pipeline\Pipeline->{closure:Illuminate\Pipeline\Pipeline::prepareDestination():178}(Object(Illuminate\Http\Request))
#15 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): App\Http\Middleware\CheckBannedUser->handle(Object(Illuminate\Http\Request), Object(Closure))
#16 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\app\Http\Middleware\CheckMaintenanceMode.php(75): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#17 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): App\Http\Middleware\CheckMaintenanceMode->handle(Object(Illuminate\Http\Request), Object(Closure))
#18 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\app\Http\Middleware\RedirectIfMustChangePassword.php(26): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))      
#19 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): App\Http\Middleware\RedirectIfMustChangePassword->handle(Object(Illuminate\Http\Request), Object(Closure))
#20 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Routing\Middleware\SubstituteBindings.php(52): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#21 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Illuminate\Routing\Middleware\SubstituteBindings->handle(Object(Illuminate\Http\Request), Object(Closure))
#22 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\PreventRequestForgery.php(104): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#23 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Illuminate\Foundation\Http\Middleware\PreventRequestForgery->handle(Object(Illuminate\Http\Request), Object(Closure))       
#24 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\View\Middleware\ShareErrorsFromSession.php(48): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#25 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Illuminate\View\Middleware\ShareErrorsFromSession->handle(Object(Illuminate\Http\Request), Object(Closure))
#26 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php(120): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#27 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php(63): Illuminate\Session\Middleware\StartSession->handleStatefulRequest(Object(Illuminate\Http\Request), Object(Illuminate\Session\Store), Object(Closure))
#28 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Illuminate\Session\Middleware\StartSession->handle(Object(Illuminate\Http\Request), Object(Closure))
#29 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse.php(36): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#30 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse->handle(Object(Illuminate\Http\Request), Object(Closure))
#31 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Cookie\Middleware\EncryptCookies.php(74): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#32 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Illuminate\Cookie\Middleware\EncryptCookies->handle(Object(Illuminate\Http\Request), Object(Closure))
#33 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(137): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#34 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Routing\Router.php(834): Illuminate\Pipeline\Pipeline->then(Object(Closure))
#35 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Routing\Router.php(813): Illuminate\Routing\Router->runRouteWithinStack(Object(Illuminate\Routing\Route), Object(Illuminate\Http\Request))
#36 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Routing\Router.php(777): Illuminate\Routing\Router->runRoute(Object(Illuminate\Http\Request), Object(Illuminate\Routing\Route))
#37 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Routing\Router.php(766): Illuminate\Routing\Router->dispatchToRoute(Object(Illuminate\Http\Request))
#38 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php(200): Illuminate\Routing\Router->dispatch(Object(Illuminate\Http\Request))
#39 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(180): Illuminate\Foundation\Http\Kernel->{closure:Illuminate\Foundation\Http\Kernel::dispatchToRouter():197}(Object(Illuminate\Http\Request))
#40 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\livewire\livewire\src\Features\SupportDisablingBackButtonCache\DisableBackButtonCacheMiddleware.php(19): Illuminate\Pipeline\Pipeline->{closure:Illuminate\Pipeline\Pipeline::prepareDestination():178}(Object(Illuminate\Http\Request))
#41 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Livewire\Features\SupportDisablingBackButtonCache\DisableBackButtonCacheMiddleware->handle(Object(Illuminate\Http\Request), Object(Closure))
#42 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php(38): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#43 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull.php(31): Illuminate\Foundation\Http\Middleware\TransformsRequest->handle(Object(Illuminate\Http\Request), Object(Closure))
#44 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull->handle(Object(Illuminate\Http\Request), Object(Closure))   
#45 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php(38): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#46 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TrimStrings.php(51): Illuminate\Foundation\Http\Middleware\TransformsRequest->handle(Object(Illuminate\Http\Request), Object(Closure))
#47 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Illuminate\Foundation\Http\Middleware\TrimStrings->handle(Object(Illuminate\Http\Request), Object(Closure))
#48 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePostSize.php(27): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#49 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Illuminate\Http\Middleware\ValidatePostSize->handle(Object(Illuminate\Http\Request), Object(Closure))
#50 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance.php(110): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#51 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance->handle(Object(Illuminate\Http\Request), Object(Closure))
#52 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Http\Middleware\HandleCors.php(61): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#53 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Illuminate\Http\Middleware\HandleCors->handle(Object(Illuminate\Http\Request), Object(Closure))
#54 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Http\Middleware\TrustProxies.php(58): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#55 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Illuminate\Http\Middleware\TrustProxies->handle(Object(Illuminate\Http\Request), Object(Closure))
#56 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks.php(22): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#57 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks->handle(Object(Illuminate\Http\Request), Object(Closure))     
#58 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePathEncoding.php(28): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#59 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Illuminate\Http\Middleware\ValidatePathEncoding->handle(Object(Illuminate\Http\Request), Object(Closure))
#60 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(137): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#61 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php(175): Illuminate\Pipeline\Pipeline->then(Object(Closure))  
#62 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php(144): Illuminate\Foundation\Http\Kernel->sendRequestThroughRouter(Object(Illuminate\Http\Request))
#63 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Foundation\Testing\Concerns\MakesHttpRequests.php(638): Illuminate\Foundation\Http\Kernel->handle(Object(Illuminate\Http\Request))
#64 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Foundation\Testing\Concerns\MakesHttpRequests.php(368): Illuminate\Foundation\Testing\TestCase->call('GET', '/', Array, Array, Array, Array)
#65 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\tests\Feature\ExampleTest.php(14): Illuminate\Foundation\Testing\TestCase->get('/')
#66 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\phpunit\phpunit\src\Framework\TestCase.php(1667): Tests\Feature\ExampleTest->test_the_application_returns_a_successful_response()
#67 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\phpunit\phpunit\src\Framework\TestCase.php(519): PHPUnit\Framework\TestCase->runTest()
#68 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\phpunit\phpunit\src\Framework\TestRunner\TestRunner.php(87): PHPUnit\Framework\TestCase->runBare()
#69 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\phpunit\phpunit\src\Framework\TestCase.php(365): PHPUnit\Framework\TestRunner->run(Object(Tests\Feature\ExampleTest))  
#70 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\phpunit\phpunit\src\Framework\TestSuite.php(369): PHPUnit\Framework\TestCase->run()
#71 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\phpunit\phpunit\src\Framework\TestSuite.php(369): PHPUnit\Framework\TestSuite->run()
#72 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\phpunit\phpunit\src\Framework\TestSuite.php(369): PHPUnit\Framework\TestSuite->run()
#73 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\phpunit\phpunit\src\TextUI\TestRunner.php(64): PHPUnit\Framework\TestSuite->run()
#74 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\phpunit\phpunit\src\TextUI\Application.php(211): PHPUnit\TextUI\TestRunner->run(Object(PHPUnit\TextUI\Configuration\Configuration), Object(PHPUnit\Runner\ResultCache\DefaultResultCache), Object(PHPUnit\Framework\TestSuite))
#75 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\phpunit\phpunit\phpunit(104): PHPUnit\TextUI\Application->run(Array)
#76 {main}

Next Illuminate\View\ViewException: syntax error, unexpected end of file, expecting "elseif" or "else" or "endif" (View: C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\resources\views\welcome.blade.php) in C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\storage\framework\views\e7bd1a446b58df150bb30cda8d26c569.php:706
Stack trace:
#0 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\livewire\livewire\src\Mechanisms\ExtendBlade\ExtendedCompilerEngine.php(58): Illuminate\View\Engines\CompilerEngine->handleViewException(Object(ParseError), 1)
#1 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\View\Engines\PhpEngine.php(59): Livewire\Mechanisms\ExtendBlade\ExtendedCompilerEngine->handleViewException(Object(ParseError), 1)
#2 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\livewire\livewire\src\Mechanisms\ExtendBlade\ExtendedCompilerEngine.php(22): Illuminate\View\Engines\PhpEngine->evaluatePath('C:\\Users\\iki\\Do...', Array)
#3 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\View\Engines\CompilerEngine.php(76): Livewire\Mechanisms\ExtendBlade\ExtendedCompilerEngine->evaluatePath('C:\\Users\\iki\\Do...', Array)
#4 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\livewire\livewire\src\Mechanisms\ExtendBlade\ExtendedCompilerEngine.php(10): Illuminate\View\Engines\CompilerEngine->get('C:\\Users\\iki\\Do...', Array)
#5 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\View\View.php(208): Livewire\Mechanisms\ExtendBlade\ExtendedCompilerEngine->get('C:\\Users\\iki\\Do...', Array)
#6 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\View\View.php(191): Illuminate\View\View->getContents()
#7 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\View\View.php(160): Illuminate\View\View->renderContents()
#8 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Http\Response.php(82): Illuminate\View\View->render()
#9 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Http\Response.php(40): Illuminate\Http\Response->setContent(Object(Illuminate\View\View))
#10 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Routing\Router.php(954): Illuminate\Http\Response->__construct(Object(Illuminate\View\View), 200, Array)
#11 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Routing\Router.php(921): Illuminate\Routing\Router::toResponse(Object(Illuminate\Http\Request), Object(Illuminate\View\View))
#12 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Routing\Router.php(834): Illuminate\Routing\Router->prepareResponse(Object(Illuminate\Http\Request), Object(Illuminate\View\View))
#13 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(180): Illuminate\Routing\Router->{closure:Illuminate\Routing\Router::runRouteWithinStack():834}(Object(Illuminate\Http\Request))  
#14 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\app\Http\Middleware\CheckBannedUser.php(27): Illuminate\Pipeline\Pipeline->{closure:Illuminate\Pipeline\Pipeline::prepareDestination():178}(Object(Illuminate\Http\Request))
#15 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): App\Http\Middleware\CheckBannedUser->handle(Object(Illuminate\Http\Request), Object(Closure))
#16 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\app\Http\Middleware\CheckMaintenanceMode.php(75): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#17 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): App\Http\Middleware\CheckMaintenanceMode->handle(Object(Illuminate\Http\Request), Object(Closure))
#18 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\app\Http\Middleware\RedirectIfMustChangePassword.php(26): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))      
#19 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): App\Http\Middleware\RedirectIfMustChangePassword->handle(Object(Illuminate\Http\Request), Object(Closure))
#20 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Routing\Middleware\SubstituteBindings.php(52): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#21 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Illuminate\Routing\Middleware\SubstituteBindings->handle(Object(Illuminate\Http\Request), Object(Closure))
#22 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\PreventRequestForgery.php(104): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#23 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Illuminate\Foundation\Http\Middleware\PreventRequestForgery->handle(Object(Illuminate\Http\Request), Object(Closure))       
#24 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\View\Middleware\ShareErrorsFromSession.php(48): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#25 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Illuminate\View\Middleware\ShareErrorsFromSession->handle(Object(Illuminate\Http\Request), Object(Closure))
#26 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php(120): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#27 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php(63): Illuminate\Session\Middleware\StartSession->handleStatefulRequest(Object(Illuminate\Http\Request), Object(Illuminate\Session\Store), Object(Closure))
#28 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Illuminate\Session\Middleware\StartSession->handle(Object(Illuminate\Http\Request), Object(Closure))
#29 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse.php(36): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#30 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse->handle(Object(Illuminate\Http\Request), Object(Closure))
#31 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Cookie\Middleware\EncryptCookies.php(74): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#32 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Illuminate\Cookie\Middleware\EncryptCookies->handle(Object(Illuminate\Http\Request), Object(Closure))
#33 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(137): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#34 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Routing\Router.php(834): Illuminate\Pipeline\Pipeline->then(Object(Closure))
#35 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Routing\Router.php(813): Illuminate\Routing\Router->runRouteWithinStack(Object(Illuminate\Routing\Route), Object(Illuminate\Http\Request))
#36 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Routing\Router.php(777): Illuminate\Routing\Router->runRoute(Object(Illuminate\Http\Request), Object(Illuminate\Routing\Route))
#37 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Routing\Router.php(766): Illuminate\Routing\Router->dispatchToRoute(Object(Illuminate\Http\Request))
#38 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php(200): Illuminate\Routing\Router->dispatch(Object(Illuminate\Http\Request))
#39 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(180): Illuminate\Foundation\Http\Kernel->{closure:Illuminate\Foundation\Http\Kernel::dispatchToRouter():197}(Object(Illuminate\Http\Request))
#40 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\livewire\livewire\src\Features\SupportDisablingBackButtonCache\DisableBackButtonCacheMiddleware.php(19): Illuminate\Pipeline\Pipeline->{closure:Illuminate\Pipeline\Pipeline::prepareDestination():178}(Object(Illuminate\Http\Request))
#41 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Livewire\Features\SupportDisablingBackButtonCache\DisableBackButtonCacheMiddleware->handle(Object(Illuminate\Http\Request), Object(Closure))
#42 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php(38): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#43 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull.php(31): Illuminate\Foundation\Http\Middleware\TransformsRequest->handle(Object(Illuminate\Http\Request), Object(Closure))
#44 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull->handle(Object(Illuminate\Http\Request), Object(Closure))   
#45 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php(38): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#46 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TrimStrings.php(51): Illuminate\Foundation\Http\Middleware\TransformsRequest->handle(Object(Illuminate\Http\Request), Object(Closure))
#47 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Illuminate\Foundation\Http\Middleware\TrimStrings->handle(Object(Illuminate\Http\Request), Object(Closure))
#48 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePostSize.php(27): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#49 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Illuminate\Http\Middleware\ValidatePostSize->handle(Object(Illuminate\Http\Request), Object(Closure))
#50 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance.php(110): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#51 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance->handle(Object(Illuminate\Http\Request), Object(Closure))
#52 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Http\Middleware\HandleCors.php(61): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#53 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Illuminate\Http\Middleware\HandleCors->handle(Object(Illuminate\Http\Request), Object(Closure))
#54 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Http\Middleware\TrustProxies.php(58): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#55 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Illuminate\Http\Middleware\TrustProxies->handle(Object(Illuminate\Http\Request), Object(Closure))
#56 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks.php(22): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#57 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks->handle(Object(Illuminate\Http\Request), Object(Closure))     
#58 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePathEncoding.php(28): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#59 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(219): Illuminate\Http\Middleware\ValidatePathEncoding->handle(Object(Illuminate\Http\Request), Object(Closure))
#60 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php(137): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))
#61 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php(175): Illuminate\Pipeline\Pipeline->then(Object(Closure))  
#62 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php(144): Illuminate\Foundation\Http\Kernel->sendRequestThroughRouter(Object(Illuminate\Http\Request))
#63 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Foundation\Testing\Concerns\MakesHttpRequests.php(638): Illuminate\Foundation\Http\Kernel->handle(Object(Illuminate\Http\Request))
#64 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\laravel\framework\src\Illuminate\Foundation\Testing\Concerns\MakesHttpRequests.php(368): Illuminate\Foundation\Testing\TestCase->call('GET', '/', Array, Array, Array, Array)
#65 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\tests\Feature\ExampleTest.php(14): Illuminate\Foundation\Testing\TestCase->get('/')
#66 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\phpunit\phpunit\src\Framework\TestCase.php(1667): Tests\Feature\ExampleTest->test_the_application_returns_a_successful_response()
#67 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\phpunit\phpunit\src\Framework\TestCase.php(519): PHPUnit\Framework\TestCase->runTest()
#68 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\phpunit\phpunit\src\Framework\TestRunner\TestRunner.php(87): PHPUnit\Framework\TestCase->runBare()
#69 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\phpunit\phpunit\src\Framework\TestCase.php(365): PHPUnit\Framework\TestRunner->run(Object(Tests\Feature\ExampleTest))  
#70 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\phpunit\phpunit\src\Framework\TestSuite.php(369): PHPUnit\Framework\TestCase->run()
#71 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\phpunit\phpunit\src\Framework\TestSuite.php(369): PHPUnit\Framework\TestSuite->run()
#72 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\phpunit\phpunit\src\Framework\TestSuite.php(369): PHPUnit\Framework\TestSuite->run()
#73 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\phpunit\phpunit\src\TextUI\TestRunner.php(64): PHPUnit\Framework\TestSuite->run()
#74 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\phpunit\phpunit\src\TextUI\Application.php(211): PHPUnit\TextUI\TestRunner->run(Object(PHPUnit\TextUI\Configuration\Configuration), Object(PHPUnit\Runner\ResultCache\DefaultResultCache), Object(PHPUnit\Framework\TestSuite))
#75 C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\vendor\phpunit\phpunit\phpunit(104): PHPUnit\TextUI\Application->run(Array)
#76 {main}

----------------------------------------------------------------------------------

syntax error, unexpected end of file, expecting "elseif" or "else" or "endif" (View: C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\resources\views\welcome.blade.php)

  at tests\Feature\ExampleTest.php:16
     12▕     public function test_the_application_returns_a_successful_response(): void
     13▕     {
     14▕         $response = $this->get('/');
     15▕
  ➜  16▕         $response->assertStatus(200);
     17▕     }
     18▕
     19▕     public function test_health_check_endpoint(): void   
     20▕     {

  ──────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\PaymentGatewaySetting…  QueryException   
  SQLSTATE[42S22]: Column not found: 1054 Unknown column 'maps_embed_url' in 'field list' (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: trivelops_db, SQL: update `setting_websites` set `site_name` = Lapaktifikasi New, `is_pakasir_active` = 0, `maps_embed_url` = ?, `setting_websites`.`updated_at` = 2026-10-29 09:02:01 where `id` = 1)

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:864
    860▕             $exceptionType = ($isUniqueConstraintError = $this->isUniqueConstraintError($e))
    861▕                 ? UniqueConstraintViolationException::class
    862▕                 : QueryException::class;
    863▕
  ➜ 864▕             $exception = new $exceptionType(
    865▕                 $this->getNameWithReadWriteType(),       
    866▕                 $query,
    867▕                 $this->prepareBindings($bindings),       
    868▕                 $e,

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:864

  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:629
      NunoMaduro\Collision\Exceptions\TestException::("SQLSTATE[42S22]: Column not found: 1054 Unknown column 'maps_embed_url' in 'field list'")

  ──────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\PaymentGatewaySettingT…  ViewException   
  syntax error, unexpected end of file, expecting "elseif" or "else" or "endif" (View: C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\resources\views\partials\seo.blade.php) (View: C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\resources\views\partials\seo.blade.php) (View: C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\resources\views\partials\seo.blade.php) (View: C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\resources\views\partials\seo.blade.php)

  at storage\framework\views\9a4d2ee500ad5b321e8dfc4e71918ce5.php:124
    120▕ </script>
    121▕
    122▕ <!-- Custom Stack for Page Specific JSON-LD (Product, FAQ, Breadcrumb, etc.) -->
    123▕ <?php echo $__env->yieldPushContent('schema'); ?>        
  ➜ 124▕ <?php /**PATH C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\resources\views/partials/seo.blade.php ENDPATH**/ ?>

  1   storage\framework\views\9a4d2ee500ad5b321e8dfc4e71918ce5.php:124
  2   vendor\livewire\livewire\src\Mechanisms\ExtendBlade\ExtendedCompilerEngine.php:58

  ──────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\PaymentStatusReturnTes…  ViewException   
  syntax error, unexpected end of file, expecting "elseif" or "else" or "endif" (View: C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\resources\views\partials\seo.blade.php) (View: C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\resources\views\partials\seo.blade.php) (View: C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\resources\views\partials\seo.blade.php) (View: C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\resources\views\partials\seo.blade.php)

  at storage\framework\views\9a4d2ee500ad5b321e8dfc4e71918ce5.php:124
    120▕ </script>
    121▕
    122▕ <!-- Custom Stack for Page Specific JSON-LD (Product, FAQ, Breadcrumb, etc.) -->
    123▕ <?php echo $__env->yieldPushContent('schema'); ?>        
  ➜ 124▕ <?php /**PATH C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\resources\views/partials/seo.blade.php ENDPATH**/ ?>

  1   storage\framework\views\9a4d2ee500ad5b321e8dfc4e71918ce5.php:124
  2   vendor\livewire\livewire\src\Mechanisms\ExtendBlade\ExtendedCompilerEngine.php:58

  ──────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\PaymentStatusReturnTes…  ViewException   
  syntax error, unexpected end of file, expecting "elseif" or "else" or "endif" (View: C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\resources\views\partials\seo.blade.php) (View: C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\resources\views\partials\seo.blade.php) (View: C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\resources\views\partials\seo.blade.php) (View: C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\resources\views\partials\seo.blade.php)

  at storage\framework\views\9a4d2ee500ad5b321e8dfc4e71918ce5.php:124
    120▕ </script>
    121▕
    122▕ <!-- Custom Stack for Page Specific JSON-LD (Product, FAQ, Breadcrumb, etc.) -->
    123▕ <?php echo $__env->yieldPushContent('schema'); ?>        
  ➜ 124▕ <?php /**PATH C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web\resources\views/partials/seo.blade.php ENDPATH**/ ?>

  1   storage\framework\views\9a4d2ee500ad5b321e8dfc4e71918ce5.php:124
  2   vendor\livewire\livewire\src\Mechanisms\ExtendBlade\ExtendedCompilerEngine.php:58


  Tests:    5 failed, 26 passed (78 assertions)
  Duration: 21.75s

PS C:\Users\iki\Documents\CODING KERJA\LAPAKTIFIKASI\lapaktifikasi_web>