<?php
namespace Kap\ContactBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class ContactController extends Controller
{
    public function indexAction(Request $request){
        
        // CREATE CONTACT US FORM
        $form = $this->createFormBuilder()
            ->add('name', 'text',array(
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter name')),
                    new Length(array('min' => 2,'minMessage' => 'Name should contain atleast 2 characters'))
                ), 
            ))
            ->add('email', 'text',array(
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter email')),
                    new Email(array('message' => 'Please enter valid email'))
                )
            ))
            ->add('subject', 'text', array(
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter subject')),
                ),
            ))
            ->add('message', 'textarea',array(
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter message')),
                )
            ))
            ->add('save', 'submit', array('label' => 'Send'))
            ->getForm(); 
        
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
      
       return $this->render('KapContactBundle:Contact:index.html.twig', array(
           'form' => $form->createView()
       ));
    }
}
?>
