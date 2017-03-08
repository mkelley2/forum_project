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
        return $app['twig']->render('index.html.twig', array('all_categories'=>Category::getAll(), 'all_threads'=>Thread::getAll(), 'user'=>$_SESSION['user']));
    });

    $app->get("/categories", function() use ($app) {
        return $app['twig']->render('categories.html.twig', array('all_categories'=>Category::getAll(), 'user'=>$_SESSION['user']));
    });

    $app->post("/categories", function() use ($app) {
        $new_category = new Category(filter_var ($_POST['inputCategory'],FILTER_SANITIZE_MAGIC_QUOTES));
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
        return $app['twig']->render('thread.html.twig', array('all_categories'=>Category::getAll(), 'specific_category'=>$new_category, 'specific_thread'=>$new_thread, 'tags'=>$tags, 'user'=>$_SESSION['user'], 'comments'=>$new_thread->getComments()));

    });

    $app->get("/new-thread/{id}", function($id) use ($app) {
        $new_category = Category::findbyCategory($id);
        return $app['twig']->render('new-thread.html.twig', array('all_categories'=>Category::getAll(), 'specific_category'=>$new_category, 'user'=>$_SESSION['user']));
    });

    $app->post("/new-thread/{id}", function($id) use ($app) {
        $new_category = Category::findbyCategory($id);
        $new_thread = new Thread($_POST['inputPost'], $new_category->getId(), $_SESSION['user']->getId(), $_POST['inputTitle']);
        $new_thread->save();
        $thread_id = $new_thread->getId();
        return $app->redirect("/category/$id/$thread_id");
    });

    $app->post("/category/{id}/{thread_id}", function($id, $thread_id) use ($app) {
        $new_category = Category::findbyCategory($id);
        $new_thread = Thread::find($thread_id);
        $tags = $new_thread->getTags();
        $date = date("Y-m-d h:i:s");
        $text = nl2br($_POST['inputComment']);
        $text = preg_replace("/\r|\n/", "", $text);
        $new_comment = new Comment($_SESSION['user']->getId(), $text, $_POST['inputParent'], 1, $date, 1, $new_thread->getId());
        $new_comment->save();
        return $app->redirect("/category/$id/$thread_id");

    });

    $app->post("/register", function() use ($app) {
        $check = User::findbyName($_POST['inputUsername']);
        $date = date("Y-m-d h:i:s");
        if(!$check){
          $_SESSION['user'] = new User(filter_var($_POST['inputUsername'], FILTER_SANITIZE_MAGIC_QUOTES), password_hash($_POST['inputPassword'], CRYPT_BLOWFISH), "imgur.com", "normal", "", "", "", "", 0, $date);
          $_SESSION['user']->save();
          return $app->redirect('/');
        }else{
          return "User already exists";
        }
    });

    $app->post("/login", function() use ($app) {
      $check = User::findbyName($_POST['inputUsername']);
      if($check){
        $pass_login = User::logIn($_POST['inputUsername'], $_POST['inputPassword']);
        if($pass_login){
          $_SESSION['user'] = $pass_login;
          return $app->redirect('/');
        }else{
          return "Incorrect Login info";
        }
      }else{
        return "User does not exist, please register";
      }
    });

    $app->post("/logout", function() use ($app) {
      $_SESSION['user'] = array();
      return $app->redirect('/');
    });

    $app->patch('/score{id}', function($id) use ($app) {
      $comment = Commend::find($id);
      $comment->updateScore($_POST['inputScore']);
      $url = $_POST['currentUrl'];
      return $app->redirect($url);
    });
    
    $app->delete("/delete-thread/{id}", function($id) use ($app) {
        $thread = Thread::find($id);
        $thread->delete();
        $category = $_POST['categoryName'];
        return $app->redirect("/category/$category");
    });

    return $app;
?>
