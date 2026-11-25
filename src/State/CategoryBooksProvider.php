<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

final class CategoryBooksProvider implements ProviderInterface
{
	public function __construct(
		private readonly BookRepository $booksRepository,
		private readonly CategoryRepository $categoryRepository
	)
	{}

	public function provide(
		Operation $operation,
		array $uriVariables = [],
		array $context = []
	): array{
		$id = $uriVariables['id'];
		$category = $this->categoryRepository->find($id);
		if( $category === null ){
			throw new NotFoundHttpException('Erreur 404, categories non trouvée');
		}
		$bookByCat = $this->booksRepository->findByCategoryId($id);

		return $bookByCat;

	}
}