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
    
    session_start();
    
    if (empty($_SESSION['user'])) {
        $_SESSION['user'] = array();
    }

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../web/views'
    ));

    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig', array('all_categories'=>Category::getAll(), 'user'=>$_SESSION['user']));
    });

    $app->get("/categories", function() use ($app) {
        return $app['twig']->render('categories.html.twig', array('all_categories'=>Category::getAll(), 'user'=>$_SESSION['user']));
    });

    $app->post("/categories", function() use ($app) {
        $new_category = new Category($_POST['inputCategory']);
        $new_category->save();
        return $app['twig']->render('categories.html.twig', array('all_categories'=>Category::getAll(), 'user'=>$_SESSION['user']));
    });

    $app->get("/category/{id}", function($id) use ($app) {
        $new_category = Category::findbyCategory($id);
        $threads = $new_category->getThreads();
        return $app['twig']->render('category.html.twig', array('all_categories'=>Category::getAll(), 'specific_category'=>$new_category, 'threads'=>$threads, 'user'=>$_SESSION['user']));
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
        return $app['twig']->render('category.html.twig', array('all_categories'=>Category::getAll(), 'specific_category'=>$new_category, 'specific_thread'=>$new_thread, 'tags'=>$tags, 'user'=>$_SESSION['user']));

    });
    
    $app->get("/new-thread/{id}", function($id) use ($app) {
        $new_category = Category::findbyCategory($id);
        return $app['twig']->render('new-thread.html.twig', array('all_categories'=>Category::getAll(), 'specific_category'=>$new_category, 'user'=>$_SESSION['user']));
    });
    
    $app->post("/new-thread/{id}", function($id) use ($app) {
        $new_category = Category::findbyCategory($id);
        $new_thread = new Thread($_POST['inputPost'], $new_category->getId(), 1, $_POST['inputTitle']);
        $new_thread->save();
        $thread_id = $new_thread->getId();
        return $app->redirect("/category/$id/$thread_id");
    });
    
    $app->post("/category/{id}/{thread_id}", function($id, $thread_id) use ($app) {
        $new_category = Category::findbyCategory($id);
        $new_thread = Thread::find($thread_id);
        $tags = $new_thread->getTags();
        $date = date("Y-m-d h:i:s");
        $new_comment = new Comment(1, $_POST['inputComment'], $_POST['inputParent'], 1, $date, 1, $new_thread->getId());
        return $app['twig']->render('category.html.twig', array('all_categories'=>Category::getAll(), 'specific_category'=>$new_category, 'specific_thread'=>$new_thread, 'tags'=>$tags, 'user'=>$_SESSION['user']));

    });
    
    $app->post("/register", function() use ($app) {
        $check = User::findbyName($_POST['inputUsername']);
        $date = date("Y-m-d h:i:s");
        if(!$check){
          $_SESSION['user'] = new User($_POST['inputUsername'], password_hash($_POST['inputPassword'], CRYPT_BLOWFISH), "imgur.com", "normal", "", "", "", 0, $date);
          $_SESSION['user']->save();
          return $app['twig']->render('index.html.twig', array('all_categories'=>Category::getAll(), 'user'=>$_SESSION['user']));
        }else{
          return "User already exists";
        }
    });
    
    $app->post("/login", function() use ($app) {
      $check = User::findbyName($_POST['inputUsername']);
      $date = date("Y-m-d h:i:s");
      if($check){
        $pass_login = User::logIn($_POST['inputUsername'], $_POST['inputPassword']);
        if($pass_login){
          $_SESSION['user'] = $pass_login;
          return $app['twig']->render('index.html.twig', array('all_categories'=>Category::getAll(), 'user'=>$_SESSION['user']));
        }else{
          return "Incorrect Login info";
          
        }
      }else{
        return "User does not exist, please register";
      }
    });
    
    return $app;
?>
