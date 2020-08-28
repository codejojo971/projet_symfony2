<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModifyController extends AbstractController
{
    
    public function index()
    {
        return $this->render('modification/modify.html.twig', [
            'UserModifyLogin' => 'ModifyController',
        ]);
    }
}