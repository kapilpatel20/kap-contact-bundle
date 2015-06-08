<?php
namespace Kap\ContactBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContactController extends Controller
{
    public function indexAction(){
       return $this->render('KapContactBundle:Contact:index.html.twig', array());
    }
}
?>
