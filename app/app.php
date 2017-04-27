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

    if($_SERVER['SERVER_NAME'] == 'localhost') {
      $server = 'mysql:host=localhost:8889;dbname=forum';
      $username = 'root';
      $password = 'root';
      $DB = new PDO($server, $username, $password);

    } else {
      // for postgres
      $dbopts = parse_url(getenv('DATABASE_URL'));
      $app->register(new Herrera\Pdo\PdoServiceProvider(),
      array(
        'pdo.dsn' => 'pgsql:dbname='.ltrim($dbopts["path"],'/').';host='.$dbopts["host"] . ';port=' . $dbopts["port"],
        'pdo.username' => $dbopts["user"],
        'pdo.password' => $dbopts["pass"]
        )
      );
      $DB = $app['pdo'];
    }


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

        return $app['twig']->render('index.html.twig', array('alert'=>null, 'all_categories'=>Category::getAll(), 'all_threads'=>Thread::getAll(), 'user'=>$_SESSION['user']));
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
        $post = $new_thread->getPost();
        $title = $new_thread->getPostTitle();
        return $app['twig']->render('thread.html.twig',
            array('all_categories'=>Category::getAll(),
            'specific_category'=>$new_category,
            'specific_thread'=>$new_thread,
            'post'=>$post,
            'title'=>$title,
            'tags'=>$tags,
            'user'=>$_SESSION['user'],
            'comments'=>$new_thread->getComments()));

    });

    $app->get("/new-thread/{id}", function($id) use ($app) {
        $new_category = Category::findbyCategory($id);
        return $app['twig']->render('new-thread.html.twig', array('all_categories'=>Category::getAll(), 'specific_category'=>$new_category, 'user'=>$_SESSION['user']));
    });

    $app->post("/new-thread/{id}", function($id) use ($app) {
        $new_category = Category::findbyCategory($id);
        $new_thread = new Thread(filter_var($_POST['inputPost'],FILTER_SANITIZE_MAGIC_QUOTES), $new_category->getId(), $_SESSION['user']->getId(), filter_var($_POST['inputTitle'],FILTER_SANITIZE_MAGIC_QUOTES), $id);
        $new_thread->save();
        $thread_id = $new_thread->getId();
        return $app->redirect("/category/$id/$thread_id");
    });

    $app->post("/category/{id}/{thread_id}", function($id, $thread_id) use ($app) {
        $new_category = Category::findbyCategory($id);
        $new_thread = Thread::find($thread_id);
        $tags = $new_thread->getTags();
        $date = date("Y-m-d H:i:s");
        $text = nl2br(filter_var($_POST['inputComment'],FILTER_SANITIZE_MAGIC_QUOTES));
        $text = preg_replace("/\r|\n/", "", $text);
        $new_comment = new Comment($_SESSION['user']->getId(), $text, $_POST['inputParent'], 1, $date, 1, $new_thread->getId());
        $new_comment->save();
        $new_comment->createMultiTags($_POST['tag']);
        // $new_comment->addMultiTags($_POST['tag']);
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
          return $app['twig']->render('index.html.twig', array('alert'=>'User already exists', 'all_categories'=>Category::getAll(), 'all_threads'=>Thread::getAll(), 'user'=>$_SESSION['user']));
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
          return $app['twig']->render('index.html.twig', array('alert'=>'Incorrect login info', 'all_categories'=>Category::getAll(), 'all_threads'=>Thread::getAll(), 'user'=>$_SESSION['user']));
        }
      }else{
        return $app['twig']->render('index.html.twig', array('alert'=>'Account does not exist, please register', 'all_categories'=>Category::getAll(), 'all_threads'=>Thread::getAll(), 'user'=>$_SESSION['user']));
      }
    });

    $app->post("/logout", function() use ($app) {
      $_SESSION['user'] = array();
      return $app->redirect('/');
    });

    $app->patch('/score/{id}', function($id) use ($app) {
      $comment = Comment::find($id);
      $comment->updateScore($_POST['inputScore']);
      $thread_id = $comment->getThreadId();
      $thread = Thread::find($thread_id);
      $cat_id = $thread->getCategoryId();
      $category = Category::find($cat_id);
      $cat = $category->getCategory();
      return $app->redirect("/category/$cat/$thread_id");

    });

    $app->delete("/delete-thread/{id}", function($id) use ($app) {
        $thread = Thread::find($id);
        $thread->delete();
        $category = $_POST['categoryName'];
        return $app->redirect("/category/$category");
    });

    $app->patch("/edit-thread/{id}", function($id) use ($app) {
        $thread = Thread::find($id);
        $thread->update($_POST['inputPost']);
        $category = $_POST['categoryName'];
        return $app->redirect("/category/$category/$id");
    });

    $app->get("/user/{id}", function($id) use ($app) {
        $user = User::find($id);
        $userComments = $user->getLinkInfoComments();
        $userThreads = $user->getThreads();
        return $app['twig']->render('users.html.twig', array('all_categories'=>Category::getAll(), 'userpage'=>$user, 'user'=>$_SESSION['user'], 'user_threads'=>$userThreads, 'user_comments'=> $userComments));
    });

    $app->patch("/user-bio/{id}", function($id) use ($app) {
        $user = User::find($id);
        $bio = nl2br($_POST['new-bio']);
        $city = $_POST['city'];
        $state = $_POST['state'];
        $country = $_POST['country'];
        $new_bio = $user->update($city, $state, $country, $bio);
        return $app->redirect("/user/$id");
    });

    $app->get("/search", function() use ($app) {
        $thread_results = Thread::searchFor(filter_var($_GET['search_term'],FILTER_SANITIZE_MAGIC_QUOTES));
        $comment_results = Comment::searchFor(filter_var($_GET['search_term'],FILTER_SANITIZE_MAGIC_QUOTES));
        $user_results = User::searchFor(filter_var($_GET['search_term'],FILTER_SANITIZE_MAGIC_QUOTES));
        return $app['twig']->render('search-results.html.twig', array('thread_results'=>$thread_results, 'comment_results'=> $comment_results, 'user_results'=> $user_results, 'all_categories'=>Category::getAll(), 'user'=>$_SESSION['user']));
    });

    $app->get("/tag", function() use ($app) {
        $thread_results = Thread::searchFor(filter_var($_GET['tag_search'],FILTER_SANITIZE_MAGIC_QUOTES));
        $comment_results = Comment::searchFor(filter_var($_GET['tag_search'],FILTER_SANITIZE_MAGIC_QUOTES));
        return $app['twig']->render('search-results.html.twig', array('thread_results'=>$thread_results, 'comment_results'=> $comment_results, 'all_categories'=>Category::getAll(), 'user'=>$_SESSION['user']));
    });
    return $app;

?>
