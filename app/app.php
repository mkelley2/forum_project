<?php
    date_default_timezone_set('America/Los_Angeles');

    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Category.php";
    require_once __DIR__."/../src/Tag.php";
    require_once __DIR__."/../src/User.php";
    require_once __DIR__."/../src/Comment.php";
    require_once __DIR__."/../src/Thread.php";

    $app = new Silex\Application();

    $app['debug']=true;

    $server = 'mysql:host=localhost:8889;dbname=library';
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

    $app->get("/categories", function() use ($app) {
        return $app['twig']->render('categories.html.twig', array('all_categories'=>Category::getall()));
    });

    $app->post("/categories", function() use ($app) {
        $new_category = new Category($_POST['inputCategory']);
        $new_category->save();
        return $app['twig']->render('categories.html.twig', array('all_categories'=>Category::getall()));
    });

    $app->get("/category/{id}", function($id) use ($app) {
        $new_category = Category::findbyCategory($id);
        $threads = $new_category->getThreads();
        return $app['twig']->render('category.html.twig', array('all_categories'=>Category::getall(), 'specific_category'=>$new_category, 'threads'=>$threads));
    });
    
    $app->post("/category/{id}", function($id) use ($app) {
        $new_category = Category::findbyCategory($id);
        $threads = $new_category->getThreads();
        $new_thread = new Thread($_POST['inputPost'], $new_category->getId(), 1);
        $new_thread->save();
        return $app['twig']->render('category.html.twig', array('all_categories'=>Category::getall(), 'specific_category'=>$new_category, 'threads'=>$threads));
    });

    $app->delete("/delete-category/{id}", function($id) use ($app) {
        $category = Category::find($id);
        $category->delete();
        return $app->redirect("/categories");
    });
    
    $app->get("/category/{id}/{thread_id}", function($id, $thread_id) use ($app) {
        $new_category = Category::findbyCategory($id);
        $new_thread = Thread::find($thread_id);
        $tags = $new_thread->getTags();
        return $app['twig']->render('category.html.twig', array('all_categories'=>Category::getall(), 'specific_category'=>$new_category, 'specific_thread'=>$new_thread, 'tags'=>$tags));

    });

    return $app;
?>
