<?php
    date_default_timezone_set('America/Los_Angeles');

    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Comment.php";
    require_once __DIR__."/../src/User.php";
    require_once __DIR__."/../src/Category.php";
    require_once __DIR__."/../src/Thread.php";

    $app = new Silex\Application();

    $app['debug']=true;

    $server = 'mysql:host=localhost:8889;dbname=forum';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    // for postgres
    // $dbopts = parse_url(getenv('DATABASE_URL'));
    // $app->register(new Herrera\Pdo\PdoServiceProvider(),
    // array(
    //   'pdo.dsn' => 'pgsql:dbname='.ltrim($dbopts["path"],'/').';host='.$dbopts["host"] . ';port=' . $dbopts["port"],
    //   'pdo.username' => $dbopts["user"],
    //   'pdo.password' => $dbopts["pass"]
    //   )
    // );
    // $DB = $app['pdo'];

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../web/views'
    ));

    $app->get("/", function() use ($app) {
        return $app['twig']->render('homeView.html.twig');
    });


    return $app;
?>

///Applications/MAMP/Library/bin/mysql --host=localhost -uroot -proot
//don't look
