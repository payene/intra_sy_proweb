<?php

namespace Loonins\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\BrowserKit\Response;

class ExceptionController extends Controller
{
    /**
     * @Route("/showException")
     */
    public function showExceptionAction(Request $request)
    {
        
  //   	// dump($request); exit;
  // //   	$uri = $_SERVER['REQUEST_URI'];
		// // $foo = isset($_GET['foo']) ? $_GET['foo']:'foo';

		// // header('Content-Type: text/html');
		// // echo 'The URI requested is: '.$uri;
		// // echo 'The value of the "foo" parameter is: '.$foo;
		// // exit();
		// // dump($code = http_response_code());
  //       return $this->render('TwigBundle:Exception:exception_full.html.twig', array(
  //       	'status_text' => ""
  //       ));


  //       $response = new Response();

		// $response->setContent('<html><body><h1>Hello world!</h1></body></html>');
		// $response->setStatusCode(Response::HTTP_OK);

		// // sets a HTTP response header
		// $response->headers->set('Content-Type', 'text/html');

		// // prints the HTTP headers followed by the content
		// $response->send();
    }

}
