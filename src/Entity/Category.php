<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\Parameter;
use ApiPlatform\OpenApi\Model\Response;
use ApiPlatform\OpenApi\OpenApi;
use App\State\CategoryAlphabeticalProvider;
use App\State\CategoryBooksProvider;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/category/{id}', 
            requirements: ['id' => '\d+'],
        ),
		new GetCollection(
			uriTemplate: '/categories/alphabetical',
			provider:CategoryAlphabeticalProvider::class,
			normalizationContext:['groups' =>['category:list']],
			outputFormats: ['json' => ['application/json']],
			
		),
        new GetCollection(
            uriTemplate: '/categories'
        ),
        new Post(
            uriTemplate:'/category',
            status: 301
        ),
		new GetCollection(
			uriTemplate:'/categories/{id}/books',
			provider:CategoryBooksProvider::class,
			uriVariables:[
				'id' => new Link(
					fromClass: Category::class,
					toProperty: 'categories'
				)
			],
			normalizationContext:['groups' => ['book:list']],
			outputFormats: ['json' => ['application/json']],
			output: Book::class,
			openapi: new Operation(
				summary: 'Liste les livres d\'une catégorie',
				description: 'Retourne les livres associés à la catégorie dont l\'identifiant est donné. Si la catégorie existe mais ne contient aucun livre, la liste est vide.',
				parameters: [
					new Parameter(name: 'id', in: 'path', description: 'identifiant de la catégorie', required: true, schema: [
						'type' => 'integer'
					])
				],
				responses:[
					'200' => new Response(description: 'Liste des livres de la catégorie (tableau vide si elle n\'a aucun livre)'),
					'404' => new Response(description: 'Aucune catégorie ne correspond à cet identifiant')
				]
			)
		)

    ],
    order: ['id' => 'ASC', 'name' => 'ASC'],
    paginationEnabled: false
)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
	#[Groups(['category:list'])]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: false)]
	#[Groups(['category:list'])]
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
