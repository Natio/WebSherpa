<?

define('__LIBRARY__', __ROOT__ . '/library');
define('__MODEL__',__ROOT__.'/model');
define('__EXTERNAL_LIBRARIES__', __ROOT__ . '/external');

require (__LIBRARY__ . '/autoloader/PCAutoloader.php');

PCCache::setDefaultCacheProvider(PCCache::getTestCacheProvider());

$rootDomain = PCConfigManager::sharedManager()->getValue('DOMAIN_NAME');

ini_set('session.cookie_domain', ".$rootDomain");

$apiSubdomain = "api.".$rootDomain;
$ajaxSubdomain = "ajax.".$rootDomain;


$router = PCRouter::sharedRouter();
$router->setControllersBaseDirectory(__ROOT__."/controllers");
$router->addSubdomain("/^$apiSubdomain/", PCRequest::TYPE_API);
$router->addSubdomain("/^$ajaxSubdomain/", PCRequest::TYPE_AJAX);
$router->addSubdomain("/^$rootDomain/", PCRequest::TYPE_WEB);


$pages = new PCRouterRoute("/web/PCPageController.php", "page", "PCPageController");
$pages->addSubroute(new PCRouterSubroute("me","meAction"));
$pages->setType(PCRequest::TYPE_AJAX);
$router->addRoute($pages,  PCRequest::TYPE_AJAX);


//Aggiungo home
$homeRoute = new PCRouterRoute("/web/PCHomeController.php", "home", "PCHomeController");
$homeRoute->addSubroute(new PCRouterSubroute("home",  "homeAction"));
$router->addRoute($homeRoute, "web");

//Aggiungo pagine

$pagesRoute = new PCRouterRoute("/web/PCPageController.php", "page", "PCPageController");
$pagesRoute->addSubroute(new PCRouterSubroute("about",  "aboutAction"));
$pagesRoute->addSubroute(new PCRouterSubroute("welcome",  "welcomeAction"));
$pagesRoute->addSubroute(new PCRouterSubroute("faq",  "faqAction"));
$pagesRoute->addSubroute(new PCRouterSubroute("downloads",  "downloadAction"));
$pagesRoute->addSubroute(new PCRouterSubroute("instructions",  "instructionsAction"));
$pagesRoute->addSubroute(new PCRouterSubroute("logout", "logoutAction"));
$pagesRoute->addSubroute(new PCRouterSubroute("repass", "repassAction"));
$pagesRoute->addSubroute(new PCRouterSubroute("version", "versionAction"));
$pagesRoute->addSubroute(new PCRouterSubroute("tos", "tosAction"));
$pagesRoute->addSubroute(new PCRouterSubroute("register", "registerPageAction"));
$router->addRoute($pagesRoute, PCRequest::TYPE_WEB);


$sitesWeb = new PCRouterRoute("/web/PCWebSitesController.php",'sites','PCWebSitesController');
$sitesWeb->addSubroute(new PCRouterSubroute('site', 'siteAction'));
$sitesWeb->addSubroute(new PCRouterSubroute('user', 'userAction'));
$sitesWeb->addSubroute(new PCRouterSubroute('search', 'searchAction'));
$sitesWeb->addSubroute(new PCRouterSubroute('add','addSiteAction'));
$router->addRoute($sitesWeb, PCRequest::TYPE_WEB);

$userWeb = new PCRouterRoute("/web/PCWebUserController.php",'user','PCWebUserController');
$userWeb->addSubroute(new PCRouterSubroute('profile', 'profileAction'));
$router->addRoute($userWeb, PCRequest::TYPE_WEB);

addCompatibilityRoutes($router);

/**
 * 
 * @param PCRouter $router
 */
function addCompatibilityRoutes($router) {
    //aggiungo ajax

    $categoriesRoute = new PCRouterRoute("/ajax/PCAjaxCategoriesController.php", "categories", "PCAjaxCategoriesController");
    $categoriesRoute->addSubroute(new PCRouterSubroute("all", "allAction"));
    $categoriesRoute->setType(PCRequest::TYPE_AJAX);
    $router->addRoute($categoriesRoute, PCRequest::TYPE_WEB);

//aggiungo user-ajax

    $userAjaxRoute = new PCRouterRoute("/ajax/PCAjaxUsersController.php", "usersajax", "PCAjaxUsersController");
    $userAjaxRoute->addSubroute(new PCRouterSubroute("login", "loginAction"));
    $userAjaxRoute->addSubroute(new PCRouterSubroute("repass", "handleRepassAction"));
    $userAjaxRoute->addSubroute(new PCRouterSubroute("register", "registerAction", PCRequest::HTTP_METHOD_POST));
    $userAjaxRoute->addSubroute(new PCRouterSubroute("contact", "contactAction", PCRequest::HTTP_METHOD_POST));
    $userAjaxRoute->addSubroute(new PCRouterSubroute("changepwd", "changePasswordAction", PCRequest::HTTP_METHOD_POST));
    $userAjaxRoute->setType(PCRequest::TYPE_AJAX);
    $router->addRoute($userAjaxRoute, PCRequest::TYPE_WEB);


    $sitesAjax = new PCRouterRoute("/ajax/PCAjaxSitesController.php", "sitesajax", "PCAjaxSitesController");
    $sitesAjax->addSubroute(new PCRouterSubroute('url', 'getSiteByURLAction'));
    $sitesAjax->addSubroute(new PCRouterSubroute('review', 'getSiteReviewsAction'));
    $sitesAjax->addSubroute(new PCRouterSubroute('add', 'addReviewAction', PCRequest::HTTP_METHOD_POST));
    $sitesAjax->addSubroute(new PCRouterSubroute('reportspam', 'reportSpamAction', PCRequest::HTTP_METHOD_POST));
    $sitesAjax->setType(PCRequest::TYPE_AJAX);
    $router->addRoute($sitesAjax, PCRequest::TYPE_WEB);
}

//aggiungo social

$social = new PCRouterRoute("/web/PCSocialController.php", "social", "PCSocialController", "PCAuthSocial");
$social->addSubroute(new PCRouterSubroute("twittercallback", "twitterCallbackAction"));
$social->addSubroute(new PCRouterSubroute("tw_login", "twitterLoginAction"));
$social->addSubroute(new PCRouterSubroute("fb_login", "facebookLoginAction"));
$social->addSubroute(new PCRouterSubroute("facebookcallback", "facebookCallbackAction"));
$router->addRoute($social, PCRequest::TYPE_WEB);


