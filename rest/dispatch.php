<?php
if (!@include(__DIR__.'/../vendor/autoload.php')) { // use Composer autoloader
    die('Could not find autoloader');
}
Propel::init("../build/conf/evolv-conf.php");
set_include_path("../build/classes" . PATH_SEPARATOR . get_include_path());
$config = array(
    'load' => array(
        __DIR__.'/services/*.php'
    ),
);

$app = new Tonic\Application($config);
//$p = UserQuery::create();

//die;
//echo $app; die;

$request = new Tonic\Request();

#echo $request; die;

try {

    $resource = $app->getResource($request);

    #echo $resource; die;

    $response = $resource->exec();

} catch (Tonic\NotFoundException $e) {
    $response = new Tonic\Response(404, $e->getMessage());

} catch (Tonic\UnauthorizedException $e) {
    $response = new Tonic\Response(401, $e->getMessage());
    $response->wwwAuthenticate = 'Basic realm="My Realm"';

} catch (Tonic\Exception $e) {
    $response = new Tonic\Response($e->getCode(), $e->getMessage());
}

#echo $response;

$response->output();