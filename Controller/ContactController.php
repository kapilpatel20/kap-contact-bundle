<?php
namespace Kap\ContactBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends Controller
{
    public function indexAction(Request $request){
        
        // CREATE CONTACT US FORM
        $form = $this->createFormBuilder()
            ->add('name', 'text')
            ->add('email', 'text')
            ->add('subject', 'text')
            ->add('message', 'textarea')
            ->add('save', 'submit', array('label' => 'Send'))
            ->getForm(); 
        
      if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                
                // SEND EMAIL TO AUTHORIZE PERSON
                $formData = $form->getData();
               
                $contactUsEmail = \Swift_Message::newInstance()
                        ->setSubject("Contact Us: ".$formData['subject'])
                        ->setFrom($formData['email'])
                        ->setTo($this->container->getParameter('contact_us_email'))
                        ->setBody($formData['message'])
                        ->setContentType('text/html');
                $this->container->get('mailer')->send($contactUsEmail);
                
                // REDIRECT TO CONTACT US PAGE
                $this->get('session')->getFlashBag()->add('success', 'Your request has been successfully sent.');
                return $this->redirect($this->generateUrl('kap_contact'));
            }
      }
       return $this->render('KapContactBundle:Contact:index.html.twig', array(
           'form' => $form->createView()
       ));
    }
}
?>
