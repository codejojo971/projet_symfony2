<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Label;
use App\Entity\Record;
use App\Repository\ArtistRepository;
use App\Repository\LabelRepository;
use App\Repository\RecordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class RecordController extends AbstractController
{
    /**
     * Listes des artistes
     * @Route("/artist", name="artist_list")
     */
    public function index(ArtistRepository $repository)
    {
        return $this->render('record/artist_list.html.twig', [
            'artist_list' => $repository->findAll(),
        ]);
    }
    /**
    * Page d'un Artiste
    * @Route("/artist/{id}", name="artist_page")
    */
    public function artistPage(Artist $artist)
    {
        return $this->render('record/artist_page.html.twig', [
            'artist' => $artist
        ]);
    }
    /**
     * Page d'un album
     * @Route("/record/{id}", name="record_page")
     */
    public function recordPage(Record $record)
    {
        return $this->render('record/record_page.html.twig',[
            'record' => $record
        ]);
    }
/**
 * Nouveaux albums
 * @Route("/news", name="record_news")
 *
 */
    public function recordNews(RecordRepository $repository)
    {
        /**
         * 
         * Albums sortis il y a moins d'un mois
         * $ilyya1mois
         * 
         * Select *
         * From record
         * WHERE releasedAt >= $date
         */

         return $this->render('record/record_news.html.twig', [
             'record_news' => $repository->findNews(),
         ]);
    }

    /**
     * page d'un label
     * @Route("/label/{id}", name="label_page")
     */
    public function labelPage(label $label)
    {
        return $this->render('record/label_page.html.twig', [
            'record_label' => $label
        ]);
    }
}

