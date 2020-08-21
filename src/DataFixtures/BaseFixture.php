<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Classe "moèle" pourr les fixtures
 * On ne peut pas instancier une abstraction
 */

abstract class BaseFixture extends Fixture
{
    /**
     * Undocumented variable
     *
     * @var [ObjectManager]
     */
    private $manager;
    /**
     * Undocumented variable
     *
     * @var [Generator]
     */
    protected $faker;
    /**@var array Liste des reférences connues */
    private $refences = [];

    /**
     * Méthode à implémenter par les classes qui héritent
     * pour générer les fausses données
     */
    abstract protected function loadData();

    /**
     * Méthode appelée par le système de fixtures
     */
    public function load(ObjectManager $manager)
    {
        // on enregistre le ObjectManager
        $this->manager = $manager;
        // On instancie Faker
        $this->faker = Factory::create('fr_FR');

        //On appelle loadData() pour avoir  les fausses données
        $this->loadData();
        // on execute l'enregistrement en base
        $this->manager->flush();
    }

    /**
     * Enregistrer plusieurs entités
     * @param int $count            nombre d'entités à générer
     * @param string $groupName     nom du groupe de références
     * @param callable $factory     fonction qui genère 1 entité
     */

     protected function createMany( int $count, string $groupName, callable $factory)
     {
         for($i =0; $i < $count; $i++)
         {
             //On exécute $factory qui doit retourner l'éntité générée
             $entity = $factory();

             // Vérifier que l'éntité ait été retournée
             if($entity == null)
             {
                 throw new \LogicException('L\'entité doit être retournée. Auriez-vous oublié un "return" ?');
             }

            // On prépare à l'enregistrement de l'entité
            $this->manager->persist($entity) ;

            //Enregistre une référence à l'entité
            $refences = sprintf('%s_%d', $groupName, $i);
            $this->addReference($refences, $entity);
         }
     }

     /**
      * Recuperer 1 entié par son nom de groupe de références
      * @param string $groupName nom de groupe de reférences
      */
    protected function getRandomReference(string $groupName)
    {
        //Vereifier si on a déja enregistré les réfences du groupe demandé
        if(!isset($this->reference[$groupName])) 
        {
            // si non , on va rechercher les références
            $this->refences[$groupName] = [];

            // On parcourt la liste de toutes les références (toutes classe confondues)
            foreach ($this->referenceRepository->getReferences() as $key => $ref)
            {
                // la clé $key correspond à nos réferences
                // si $key commence par $groupName , on le sauvegarde
                if(strpos($key, $groupName) === 0)
                {
                    $this->refences[$groupName][]= $key;
                }
            }
        }

        // Vérifier que l'on a récupèré des réfèrences
        if($this->refences[$groupName] === [])
        {
            throw new \Exception(sprintf('Aucune référence trouvée pour le groupe "%s"', $groupName));
        }

        // Retourner une entité correspondant à une référence aléatoire
        $randomReference = $this->faker->randomElement($this->refences[$groupName]);
        return $this->getReference($randomReference);
    } 
    
}

